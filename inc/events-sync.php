<?php
/**
 * BLOND:ISH — Seated Sync
 * inc/events-sync.php
 *
 * Fetches event data from the public Seated artist page and syncs it
 * into the blondish_event CPT. Falls back gracefully if the page
 * structure changes — events can always be managed manually.
 *
 * Called by WP-Cron (every 6 hours) or manually via Settings > Seated Sync.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main sync function.
 *
 * Fetches the Seated artist page, parses events, and creates/updates
 * blondish_event posts. Returns a status message string on success,
 * or false on failure.
 *
 * @return string|false
 */
function blondish_sync_seated_events() {
	$artist_slug = get_option( 'blondish_seated_artist_slug', '' );

	if ( empty( $artist_slug ) ) {
		error_log( 'BLOND:ISH Seated Sync: No artist slug configured.' );
		return false;
	}

	$url = 'https://tours.seated.com/' . sanitize_title( $artist_slug );

	$response = wp_remote_get( $url, [
		'timeout'    => 30,
		'user-agent' => 'Mozilla/5.0 (compatible; BlondishWP/1.0; +https://blondish.world)',
	] );

	if ( is_wp_error( $response ) ) {
		error_log( 'BLOND:ISH Seated Sync: Fetch failed — ' . $response->get_error_message() );
		return false;
	}

	$status_code = wp_remote_retrieve_response_code( $response );
	if ( 200 !== $status_code ) {
		error_log( 'BLOND:ISH Seated Sync: HTTP ' . $status_code . ' from ' . $url );
		return false;
	}

	$html = wp_remote_retrieve_body( $response );
	if ( empty( $html ) ) {
		error_log( 'BLOND:ISH Seated Sync: Empty response body.' );
		return false;
	}

	$events = blondish_parse_seated_html( $html );

	if ( empty( $events ) ) {
		// Try JSON-LD fallback — some pages embed structured data
		$events = blondish_parse_seated_jsonld( $html );
	}

	if ( empty( $events ) ) {
		error_log( 'BLOND:ISH Seated Sync: No events found in page. Page structure may have changed.' );
		update_option( 'blondish_seated_last_sync', current_time( 'mysql' ) );
		return __( 'Sync complete — no events found on page.', 'blondish' );
	}

	$created = 0;
	$updated = 0;

	foreach ( $events as $event ) {
		$result = blondish_upsert_event( $event );
		if ( 'created' === $result ) {
			$created++;
		} elseif ( 'updated' === $result ) {
			$updated++;
		}
	}

	update_option( 'blondish_seated_last_sync', current_time( 'mysql' ) );

	$message = sprintf(
		__( 'Sync complete: %d events found, %d created, %d updated.', 'blondish' ),
		count( $events ),
		$created,
		$updated
	);

	error_log( 'BLOND:ISH Seated Sync: ' . $message );
	return $message;
}


/**
 * Parse the Seated artist page HTML for event data.
 *
 * Seated pages typically render event listings with structured markup
 * containing dates, venue names, cities, and ticket links. This parser
 * looks for common patterns in the rendered HTML.
 *
 * @param string $html Raw HTML from the Seated page.
 * @return array Array of event arrays with keys: seated_id, date, venue, city, country, ticket_url, title.
 */
function blondish_parse_seated_html( $html ) {
	$events = [];

	// Suppress libxml errors for malformed HTML
	$prev_errors = libxml_use_internal_errors( true );
	$doc         = new DOMDocument();
	$doc->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_NOERROR );
	libxml_clear_errors();
	libxml_use_internal_errors( $prev_errors );

	$xpath = new DOMXPath( $doc );

	// Strategy 1: Look for common Seated widget event containers
	// Seated typically uses data attributes or specific class patterns
	$event_nodes = $xpath->query( '//*[contains(@class, "event-row") or contains(@class, "event-listing") or contains(@class, "event-item") or contains(@class, "seated-event")]' );

	if ( $event_nodes->length > 0 ) {
		foreach ( $event_nodes as $node ) {
			$event = blondish_extract_event_from_node( $xpath, $node );
			if ( $event ) {
				$events[] = $event;
			}
		}
	}

	// Strategy 2: Look for links containing ticket/RSVP text alongside date patterns
	if ( empty( $events ) ) {
		// Try broader selectors — look for structured list items or article elements
		$containers = $xpath->query( '//article | //li[.//a[contains(@href, "ticket") or contains(@href, "seated") or contains(@href, "rsvp")]] | //div[contains(@class, "show") or contains(@class, "date")]' );

		foreach ( $containers as $node ) {
			$event = blondish_extract_event_from_node( $xpath, $node );
			if ( $event ) {
				$events[] = $event;
			}
		}
	}

	// Strategy 3: Look for embedded JavaScript data (common in React/Next.js apps)
	if ( empty( $events ) ) {
		$events = blondish_parse_seated_js_data( $html );
	}

	return $events;
}


/**
 * Extract event data from a DOM node.
 *
 * @param DOMXPath $xpath XPath object for the document.
 * @param DOMNode  $node  The event container node.
 * @return array|null Event data array or null if insufficient data found.
 */
function blondish_extract_event_from_node( $xpath, $node ) {
	$text = $node->textContent;

	// Try to find a date — look for common patterns
	$date = null;
	$date_nodes = $xpath->query( './/time[@datetime] | .//*[contains(@class, "date")]', $node );
	if ( $date_nodes->length > 0 ) {
		$date_node = $date_nodes->item( 0 );
		$date      = $date_node->getAttribute( 'datetime' ) ?: $date_node->textContent;
	}

	// Fallback: Try to parse a date from the text content
	if ( ! $date ) {
		// Match patterns like "Mar 28, 2026", "2026-03-28", "March 28, 2026"
		if ( preg_match( '/(\d{4}-\d{2}-\d{2})|(\w+ \d{1,2},?\s*\d{4})|(\d{1,2}\s+\w+\s+\d{4})/', $text, $m ) ) {
			$date = $m[0];
		}
	}

	if ( ! $date ) {
		return null; // Can't create an event without a date
	}

	// Parse the date to a standard format
	$timestamp = strtotime( $date );
	if ( ! $timestamp ) {
		return null;
	}

	// Find venue
	$venue      = '';
	$venue_nodes = $xpath->query( './/*[contains(@class, "venue")] | .//*[contains(@class, "location")]', $node );
	if ( $venue_nodes->length > 0 ) {
		$venue = trim( $venue_nodes->item( 0 )->textContent );
	}

	// Find city
	$city       = '';
	$city_nodes = $xpath->query( './/*[contains(@class, "city")] | .//*[contains(@class, "region")]', $node );
	if ( $city_nodes->length > 0 ) {
		$city = trim( $city_nodes->item( 0 )->textContent );
	}

	// If venue/city not found in specific elements, try splitting the location text
	if ( ! $venue && ! $city ) {
		$loc_nodes = $xpath->query( './/*[contains(@class, "loc")] | .//*[contains(@class, "place")]', $node );
		if ( $loc_nodes->length > 0 ) {
			$loc_text = trim( $loc_nodes->item( 0 )->textContent );
			$parts    = array_map( 'trim', explode( ',', $loc_text, 2 ) );
			$venue    = $parts[0] ?? '';
			$city     = $parts[1] ?? '';
		}
	}

	// Find ticket URL
	$ticket_url = '';
	$link_nodes = $xpath->query( './/a[contains(@href, "ticket") or contains(@href, "seated") or contains(@href, "rsvp") or contains(@href, "buy") or contains(translate(@class, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "ticket") or contains(translate(@class, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "cta") or contains(translate(@class, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "buy")]', $node );
	if ( $link_nodes->length > 0 ) {
		$ticket_url = $link_nodes->item( 0 )->getAttribute( 'href' );
	}

	// Fallback: grab any external link
	if ( ! $ticket_url ) {
		$any_links = $xpath->query( './/a[@href]', $node );
		foreach ( $any_links as $link ) {
			$href = $link->getAttribute( 'href' );
			if ( $href && false === strpos( $href, '#' ) && 0 !== strpos( $href, '/' ) ) {
				$ticket_url = $href;
				break;
			}
		}
	}

	// Generate a unique ID based on date + venue
	$seated_id = md5( date( 'Y-m-d', $timestamp ) . '|' . $venue . '|' . $city );

	return [
		'seated_id'  => $seated_id,
		'date'       => date( 'Y-m-d H:i:s', $timestamp ),
		'venue'      => $venue,
		'city'       => $city,
		'country'    => '',
		'ticket_url' => $ticket_url,
		'title'      => $venue ? $venue . ', ' . $city : $city,
	];
}


/**
 * Parse JSON-LD structured data from the Seated page.
 *
 * Some Seated pages embed MusicEvent or Event schema in JSON-LD format,
 * which is the easiest and most reliable source of structured data.
 *
 * @param string $html Raw HTML.
 * @return array Array of event data.
 */
function blondish_parse_seated_jsonld( $html ) {
	$events = [];

	// Find all JSON-LD script tags
	if ( ! preg_match_all( '/<script\s+type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches ) ) {
		return $events;
	}

	foreach ( $matches[1] as $json_string ) {
		$data = json_decode( trim( $json_string ), true );
		if ( ! $data ) {
			continue;
		}

		// Handle @graph arrays
		$items = isset( $data['@graph'] ) ? $data['@graph'] : [ $data ];

		foreach ( $items as $item ) {
			$type = $item['@type'] ?? '';
			if ( ! in_array( $type, [ 'MusicEvent', 'Event', 'DanceEvent' ], true ) ) {
				continue;
			}

			$date = $item['startDate'] ?? '';
			if ( ! $date ) {
				continue;
			}

			$venue   = '';
			$city    = '';
			$country = '';

			if ( isset( $item['location'] ) ) {
				$loc   = $item['location'];
				$venue = $loc['name'] ?? '';
				if ( isset( $loc['address'] ) ) {
					$addr    = $loc['address'];
					$city    = $addr['addressLocality'] ?? '';
					$country = $addr['addressCountry'] ?? '';
				}
			}

			$ticket_url = '';
			if ( isset( $item['offers'] ) ) {
				$offers     = is_array( $item['offers'] ) ? $item['offers'] : [ $item['offers'] ];
				$offer      = $offers[0] ?? [];
				$ticket_url = $offer['url'] ?? '';
			}

			$timestamp = strtotime( $date );
			if ( ! $timestamp ) {
				continue;
			}

			$events[] = [
				'seated_id'  => md5( date( 'Y-m-d', $timestamp ) . '|' . $venue . '|' . $city ),
				'date'       => date( 'Y-m-d H:i:s', $timestamp ),
				'venue'      => $venue,
				'city'       => $city,
				'country'    => $country,
				'ticket_url' => $ticket_url,
				'title'      => $venue ? $venue . ', ' . $city : $city,
			];
		}
	}

	return $events;
}


/**
 * Parse embedded JavaScript data objects for event information.
 *
 * Many modern sites (React, Next.js) embed serialized JSON in script tags
 * as __NEXT_DATA__, window.__data, or similar globals.
 *
 * @param string $html Raw HTML.
 * @return array Array of event data.
 */
function blondish_parse_seated_js_data( $html ) {
	$events = [];

	// Look for __NEXT_DATA__ or similar JSON blobs
	$patterns = [
		'/__NEXT_DATA__\s*=\s*({.+?})\s*;?\s*<\/script>/s',
		'/window\.__data\s*=\s*({.+?})\s*;?\s*<\/script>/s',
		'/window\.__PRELOADED_STATE__\s*=\s*({.+?})\s*;?\s*<\/script>/s',
	];

	foreach ( $patterns as $pattern ) {
		if ( preg_match( $pattern, $html, $m ) ) {
			$data = json_decode( $m[1], true );
			if ( ! $data ) {
				continue;
			}

			// Recursively search for event-like objects
			$found = blondish_find_events_in_data( $data );
			if ( $found ) {
				$events = array_merge( $events, $found );
			}
		}
	}

	return $events;
}


/**
 * Recursively search a data structure for event-like objects.
 *
 * Looks for arrays containing objects with date-like keys and
 * venue/location-like keys.
 *
 * @param mixed $data Data structure to search.
 * @param int   $depth Current recursion depth.
 * @return array Found events.
 */
function blondish_find_events_in_data( $data, $depth = 0 ) {
	if ( $depth > 10 || ! is_array( $data ) ) {
		return [];
	}

	$events = [];

	// Check if this looks like an event object
	$date_keys   = [ 'date', 'startDate', 'start_date', 'event_date', 'datetime', 'starts_at' ];
	$venue_keys  = [ 'venue', 'venue_name', 'location', 'place', 'venueName' ];
	$found_date  = null;
	$found_venue = null;
	$found_city  = null;
	$found_url   = null;

	foreach ( $date_keys as $key ) {
		if ( isset( $data[ $key ] ) && is_string( $data[ $key ] ) ) {
			$ts = strtotime( $data[ $key ] );
			if ( $ts ) {
				$found_date = date( 'Y-m-d H:i:s', $ts );
				break;
			}
		}
	}

	foreach ( $venue_keys as $key ) {
		if ( isset( $data[ $key ] ) ) {
			$val = $data[ $key ];
			if ( is_string( $val ) ) {
				$found_venue = $val;
			} elseif ( is_array( $val ) ) {
				$found_venue = $val['name'] ?? ( $val['venue_name'] ?? '' );
				$found_city  = $val['city'] ?? ( $val['addressLocality'] ?? '' );
			}
			break;
		}
	}

	$city_keys = [ 'city', 'addressLocality', 'location_city', 'cityName' ];
	if ( ! $found_city ) {
		foreach ( $city_keys as $key ) {
			if ( isset( $data[ $key ] ) && is_string( $data[ $key ] ) ) {
				$found_city = $data[ $key ];
				break;
			}
		}
	}

	$url_keys = [ 'ticket_url', 'ticketUrl', 'tickets_url', 'url', 'link', 'buy_url' ];
	foreach ( $url_keys as $key ) {
		if ( isset( $data[ $key ] ) && is_string( $data[ $key ] ) && filter_var( $data[ $key ], FILTER_VALIDATE_URL ) ) {
			$found_url = $data[ $key ];
			break;
		}
	}

	if ( $found_date && ( $found_venue || $found_city ) ) {
		$events[] = [
			'seated_id'  => md5( substr( $found_date, 0, 10 ) . '|' . $found_venue . '|' . $found_city ),
			'date'       => $found_date,
			'venue'      => $found_venue ?: '',
			'city'       => $found_city ?: '',
			'country'    => $data['country'] ?? ( $data['addressCountry'] ?? '' ),
			'ticket_url' => $found_url ?: '',
			'title'      => $found_venue ? $found_venue . ', ' . $found_city : $found_city,
		];
		return $events;
	}

	// Recurse into child arrays
	foreach ( $data as $value ) {
		if ( is_array( $value ) ) {
			$found = blondish_find_events_in_data( $value, $depth + 1 );
			$events = array_merge( $events, $found );
		}
	}

	return $events;
}


/**
 * Create or update a blondish_event post from parsed event data.
 *
 * Uses the seated_id for deduplication — if a post already exists with
 * the same seated_id, it updates the meta fields instead of creating
 * a duplicate.
 *
 * @param array $event Event data array.
 * @return string 'created', 'updated', or 'skipped'.
 */
function blondish_upsert_event( $event ) {
	// Check for existing event by seated_id
	$existing = get_posts( [
		'post_type'      => 'blondish_event',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'meta_query'     => [
			[
				'key'   => '_blondish_event_seated_id',
				'value' => $event['seated_id'],
			],
		],
	] );

	$meta = [
		'_blondish_event_date'       => $event['date'],
		'_blondish_event_venue'      => $event['venue'],
		'_blondish_event_city'       => $event['city'],
		'_blondish_event_country'    => $event['country'],
		'_blondish_event_ticket_url' => $event['ticket_url'],
		'_blondish_event_seated_id'  => $event['seated_id'],
	];

	if ( ! empty( $existing ) ) {
		$post_id = $existing[0]->ID;

		// Update meta fields
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		return 'updated';
	}

	// Create new event post
	$title = 'BLOND:ISH';
	if ( $event['venue'] && $event['city'] ) {
		$title .= ' — ' . $event['venue'] . ', ' . $event['city'];
	} elseif ( $event['venue'] ) {
		$title .= ' — ' . $event['venue'];
	} elseif ( $event['city'] ) {
		$title .= ' — ' . $event['city'];
	}

	// Generate a URL-friendly slug from the date and venue
	$date_str = date( 'Y-m-d', strtotime( $event['date'] ) );
	$slug     = sanitize_title( $date_str . '-' . ( $event['venue'] ?: $event['city'] ) );

	$post_id = wp_insert_post( [
		'post_type'   => 'blondish_event',
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
		'post_content' => '', // Keep empty — meta fields carry the data
	] );

	if ( is_wp_error( $post_id ) ) {
		error_log( 'BLOND:ISH Seated Sync: Failed to create event — ' . $post_id->get_error_message() );
		return 'skipped';
	}

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	return 'created';
}

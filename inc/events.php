<?php
/**
 * BLOND:ISH — Events System
 * inc/events.php
 *
 * Registers the blondish_event CPT, admin meta boxes, JSON-LD output,
 * Seated sync settings page, and helper functions for querying events.
 *
 * Included from functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Pull in sync logic
require_once __DIR__ . '/events-sync.php';


/* ==========================================================================
   1. CUSTOM POST TYPE — blondish_event
   ========================================================================== */

function blondish_register_event_cpt() {
	$labels = [
		'name'               => __( 'Events', 'blondish' ),
		'singular_name'      => __( 'Event', 'blondish' ),
		'add_new'            => __( 'Add Event', 'blondish' ),
		'add_new_item'       => __( 'Add New Event', 'blondish' ),
		'edit_item'          => __( 'Edit Event', 'blondish' ),
		'new_item'           => __( 'New Event', 'blondish' ),
		'view_item'          => __( 'View Event', 'blondish' ),
		'search_items'       => __( 'Search Events', 'blondish' ),
		'not_found'          => __( 'No events found.', 'blondish' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'blondish' ),
		'all_items'          => __( 'All Events', 'blondish' ),
		'menu_name'          => __( 'Events', 'blondish' ),
	];

	$args = [
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,       // Gutenberg + REST API support
		'menu_icon'           => 'dashicons-calendar-alt',
		'menu_position'       => 5,          // Below Posts
		'has_archive'         => false,      // No /events/ archive page
		'rewrite'             => [
			'slug'       => 'tour',
			'with_front' => false,
		],
		'supports'            => [ 'title', 'editor', 'thumbnail' ],
		'capability_type'     => 'post',
		'exclude_from_search' => false,      // Keep in search for SEO
	];

	register_post_type( 'blondish_event', $args );
}
add_action( 'init', 'blondish_register_event_cpt' );


/* ==========================================================================
   2. ADMIN META BOX — Event Details
   ========================================================================== */

function blondish_event_meta_boxes() {
	add_meta_box(
		'blondish_event_details',
		__( 'Event Details', 'blondish' ),
		'blondish_event_meta_box_html',
		'blondish_event',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'blondish_event_meta_boxes' );

function blondish_event_meta_box_html( $post ) {
	wp_nonce_field( 'blondish_event_meta', 'blondish_event_nonce' );

	$date       = get_post_meta( $post->ID, '_blondish_event_date', true );
	$end_date   = get_post_meta( $post->ID, '_blondish_event_end_date', true );
	$venue      = get_post_meta( $post->ID, '_blondish_event_venue', true );
	$city       = get_post_meta( $post->ID, '_blondish_event_city', true );
	$country    = get_post_meta( $post->ID, '_blondish_event_country', true );
	$ticket_url = get_post_meta( $post->ID, '_blondish_event_ticket_url', true );
	$seated_id  = get_post_meta( $post->ID, '_blondish_event_seated_id', true );

	?>
	<style>
		.blondish-event-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
		.blondish-event-fields label { display: block; font-weight: 600; margin-bottom: 4px; }
		.blondish-event-fields input { width: 100%; }
		.blondish-event-fields .full-width { grid-column: 1 / -1; }
	</style>
	<div class="blondish-event-fields">
		<div>
			<label for="blondish_event_date"><?php esc_html_e( 'Event Date *', 'blondish' ); ?></label>
			<input type="datetime-local" id="blondish_event_date" name="blondish_event_date"
			       value="<?php echo esc_attr( $date ? date( 'Y-m-d\TH:i', strtotime( $date ) ) : '' ); ?>" required>
		</div>
		<div>
			<label for="blondish_event_end_date"><?php esc_html_e( 'End Date (optional)', 'blondish' ); ?></label>
			<input type="datetime-local" id="blondish_event_end_date" name="blondish_event_end_date"
			       value="<?php echo esc_attr( $end_date ? date( 'Y-m-d\TH:i', strtotime( $end_date ) ) : '' ); ?>">
		</div>
		<div>
			<label for="blondish_event_venue"><?php esc_html_e( 'Venue *', 'blondish' ); ?></label>
			<input type="text" id="blondish_event_venue" name="blondish_event_venue"
			       value="<?php echo esc_attr( $venue ); ?>" placeholder="e.g. Fabric" required>
		</div>
		<div>
			<label for="blondish_event_city"><?php esc_html_e( 'City *', 'blondish' ); ?></label>
			<input type="text" id="blondish_event_city" name="blondish_event_city"
			       value="<?php echo esc_attr( $city ); ?>" placeholder="e.g. London">
		</div>
		<div>
			<label for="blondish_event_country"><?php esc_html_e( 'Country Code', 'blondish' ); ?></label>
			<input type="text" id="blondish_event_country" name="blondish_event_country"
			       value="<?php echo esc_attr( $country ); ?>" placeholder="e.g. GB" maxlength="2">
		</div>
		<div class="full-width">
			<label for="blondish_event_ticket_url"><?php esc_html_e( 'Ticket URL *', 'blondish' ); ?></label>
			<input type="url" id="blondish_event_ticket_url" name="blondish_event_ticket_url"
			       value="<?php echo esc_url( $ticket_url ); ?>" placeholder="https://...">
		</div>
		<?php if ( $seated_id ) : ?>
		<div class="full-width">
			<label><?php esc_html_e( 'Seated ID', 'blondish' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $seated_id ); ?>" readonly
			       style="background:#f0f0f0;cursor:not-allowed;">
			<p class="description"><?php esc_html_e( 'Auto-populated by Seated sync. Do not edit.', 'blondish' ); ?></p>
		</div>
		<?php endif; ?>
	</div>
	<?php
}

function blondish_save_event_meta( $post_id ) {
	// Security checks
	if ( ! isset( $_POST['blondish_event_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['blondish_event_nonce'], 'blondish_event_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = [
		'blondish_event_date'       => 'sanitize_text_field',
		'blondish_event_end_date'   => 'sanitize_text_field',
		'blondish_event_venue'      => 'sanitize_text_field',
		'blondish_event_city'       => 'sanitize_text_field',
		'blondish_event_country'    => 'sanitize_text_field',
		'blondish_event_ticket_url' => 'esc_url_raw',
	];

	foreach ( $fields as $field => $sanitizer ) {
		$meta_key = '_' . $field;
		$value    = isset( $_POST[ $field ] ) ? call_user_func( $sanitizer, $_POST[ $field ] ) : '';

		// Convert datetime-local format to MySQL datetime
		if ( in_array( $field, [ 'blondish_event_date', 'blondish_event_end_date' ], true ) && $value ) {
			$value = date( 'Y-m-d H:i:s', strtotime( $value ) );
		}

		update_post_meta( $post_id, $meta_key, $value );
	}
}
add_action( 'save_post_blondish_event', 'blondish_save_event_meta' );


/* ==========================================================================
   3. ADMIN COLUMNS — show event date + venue in the Events list table
   ========================================================================== */

function blondish_event_admin_columns( $columns ) {
	$new = [];
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['event_date']  = __( 'Event Date', 'blondish' );
			$new['event_venue'] = __( 'Venue', 'blondish' );
			$new['event_city']  = __( 'City', 'blondish' );
		}
	}
	// Remove default date column (publish date) — event date is more useful
	unset( $new['date'] );
	return $new;
}
add_filter( 'manage_blondish_event_posts_columns', 'blondish_event_admin_columns' );

function blondish_event_admin_column_data( $column, $post_id ) {
	switch ( $column ) {
		case 'event_date':
			$date = get_post_meta( $post_id, '_blondish_event_date', true );
			if ( $date ) {
				$timestamp = strtotime( $date );
				$formatted = date_i18n( 'M j, Y — g:i A', $timestamp );
				$is_past   = $timestamp < time();
				echo $is_past
					? '<span style="color:#999;">' . esc_html( $formatted ) . ' <em>(past)</em></span>'
					: '<strong>' . esc_html( $formatted ) . '</strong>';
			} else {
				echo '—';
			}
			break;
		case 'event_venue':
			echo esc_html( get_post_meta( $post_id, '_blondish_event_venue', true ) ?: '—' );
			break;
		case 'event_city':
			echo esc_html( get_post_meta( $post_id, '_blondish_event_city', true ) ?: '—' );
			break;
	}
}
add_action( 'manage_blondish_event_posts_custom_column', 'blondish_event_admin_column_data', 10, 2 );

function blondish_event_sortable_columns( $columns ) {
	$columns['event_date'] = 'event_date';
	return $columns;
}
add_filter( 'manage_edit-blondish_event_sortable_columns', 'blondish_event_sortable_columns' );

function blondish_event_admin_sort( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( 'blondish_event' !== $query->get( 'post_type' ) ) {
		return;
	}

	// Default sort: upcoming events first
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_blondish_event_date' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
	}

	if ( 'event_date' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_blondish_event_date' );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'blondish_event_admin_sort' );


/* ==========================================================================
   4. HELPER — Query upcoming events
   Returns WP_Query for events with date >= now, ordered ASC by event date.
   ========================================================================== */

function blondish_get_upcoming_events( $count = -1 ) {
	return new WP_Query( [
		'post_type'      => 'blondish_event',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'meta_key'       => '_blondish_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => [
			[
				'key'     => '_blondish_event_date',
				'value'   => current_time( 'Y-m-d H:i:s' ),
				'compare' => '>=',
				'type'    => 'DATETIME',
			],
		],
	] );
}


/* ==========================================================================
   5. JSON-LD — MusicEvent schema for single event pages
   ========================================================================== */

function blondish_event_json_ld() {
	if ( ! is_singular( 'blondish_event' ) ) {
		return;
	}

	$post_id    = get_the_ID();
	$date       = get_post_meta( $post_id, '_blondish_event_date', true );
	$end_date   = get_post_meta( $post_id, '_blondish_event_end_date', true );
	$venue      = get_post_meta( $post_id, '_blondish_event_venue', true );
	$city       = get_post_meta( $post_id, '_blondish_event_city', true );
	$country    = get_post_meta( $post_id, '_blondish_event_country', true );
	$ticket_url = get_post_meta( $post_id, '_blondish_event_ticket_url', true );
	$is_past    = $date && strtotime( $date ) < time();

	$schema = [
		'@context'            => 'https://schema.org',
		'@type'               => 'MusicEvent',
		'name'                => 'BLOND:ISH' . ( $venue ? ' — ' . $venue . ', ' . $city : '' ),
		'startDate'           => $date ? date( 'c', strtotime( $date ) ) : '',
		'eventStatus'         => $is_past
			? 'https://schema.org/EventCancelled'
			: 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'location'            => [
			'@type'   => 'Place',
			'name'    => $venue ?: '',
			'address' => [
				'@type'           => 'PostalAddress',
				'addressLocality' => $city ?: '',
				'addressCountry'  => $country ?: '',
			],
		],
		'performer'           => [
			'@type' => 'MusicGroup',
			'@id'   => 'https://blondish.world/#musicgroup',
			'name'  => 'BLOND:ISH',
		],
		'organizer'           => [
			'@type' => 'MusicGroup',
			'@id'   => 'https://blondish.world/#musicgroup',
		],
		'url'                 => get_permalink( $post_id ),
	];

	if ( $end_date ) {
		$schema['endDate'] = date( 'c', strtotime( $end_date ) );
	}

	if ( $ticket_url && ! $is_past ) {
		$schema['offers'] = [
			'@type'        => 'Offer',
			'url'          => $ticket_url,
			'availability' => 'https://schema.org/InStock',
		];
	}

	// Use past event status correctly
	if ( $is_past ) {
		$schema['eventStatus'] = 'https://schema.org/EventPostponed';
		// EventPostponed is safer than EventCancelled for past events
		// Google treats completed events as valid schema
		unset( $schema['eventStatus'] );
		// Actually, just omit eventStatus for past events — Google handles it
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_event_json_ld' );


/* ==========================================================================
   6. SETTINGS PAGE — Seated Sync Configuration
   ========================================================================== */

function blondish_seated_settings_menu() {
	add_options_page(
		__( 'Seated Sync', 'blondish' ),
		__( 'Seated Sync', 'blondish' ),
		'manage_options',
		'blondish-seated-sync',
		'blondish_seated_settings_page'
	);
}
add_action( 'admin_menu', 'blondish_seated_settings_menu' );

function blondish_seated_settings_init() {
	register_setting( 'blondish_seated', 'blondish_seated_artist_slug' );

	add_settings_section(
		'blondish_seated_main',
		__( 'Seated Configuration', 'blondish' ),
		function () {
			echo '<p>' . esc_html__( 'Configure the Seated sync to automatically import tour dates.', 'blondish' ) . '</p>';
		},
		'blondish-seated-sync'
	);

	add_settings_field(
		'blondish_seated_artist_slug',
		__( 'Seated Artist Slug', 'blondish' ),
		function () {
			$slug = get_option( 'blondish_seated_artist_slug', '' );
			echo '<input type="text" name="blondish_seated_artist_slug" value="' . esc_attr( $slug ) . '" class="regular-text" placeholder="e.g. blondish">';
			echo '<p class="description">' . esc_html__( 'The slug from your Seated page: tours.seated.com/{this-part}', 'blondish' ) . '</p>';
		},
		'blondish-seated-sync',
		'blondish_seated_main'
	);
}
add_action( 'admin_init', 'blondish_seated_settings_init' );

function blondish_seated_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Handle manual sync
	if ( isset( $_POST['blondish_sync_now'] ) && check_admin_referer( 'blondish_sync_now_action' ) ) {
		$result = blondish_sync_seated_events();
		$notice = $result
			? '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>'
			: '<div class="notice notice-error"><p>' . esc_html__( 'Sync failed. Check the error log.', 'blondish' ) . '</p></div>';
	}

	$last_sync = get_option( 'blondish_seated_last_sync', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Seated Sync Settings', 'blondish' ); ?></h1>

		<?php if ( ! empty( $notice ) ) echo $notice; ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'blondish_seated' );
			do_settings_sections( 'blondish-seated-sync' );
			submit_button( __( 'Save Settings', 'blondish' ) );
			?>
		</form>

		<hr>

		<h2><?php esc_html_e( 'Manual Sync', 'blondish' ); ?></h2>
		<p>
			<?php
			if ( $last_sync ) {
				printf(
					esc_html__( 'Last sync: %s', 'blondish' ),
					'<strong>' . esc_html( date_i18n( 'F j, Y — g:i A', strtotime( $last_sync ) ) ) . '</strong>'
				);
			} else {
				esc_html_e( 'No sync has been run yet.', 'blondish' );
			}
			?>
		</p>
		<form method="post">
			<?php wp_nonce_field( 'blondish_sync_now_action' ); ?>
			<input type="hidden" name="blondish_sync_now" value="1">
			<?php submit_button( __( 'Sync Now', 'blondish' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}


/* ==========================================================================
   7. WP-CRON — Schedule automatic sync every 6 hours
   ========================================================================== */

function blondish_schedule_seated_sync() {
	if ( ! wp_next_scheduled( 'blondish_seated_sync_hook' ) ) {
		wp_schedule_event( time(), 'blondish_six_hours', 'blondish_seated_sync_hook' );
	}
}
add_action( 'wp', 'blondish_schedule_seated_sync' );

function blondish_add_cron_interval( $schedules ) {
	$schedules['blondish_six_hours'] = [
		'interval' => 6 * HOUR_IN_SECONDS,
		'display'  => __( 'Every 6 Hours', 'blondish' ),
	];
	return $schedules;
}
add_filter( 'cron_schedules', 'blondish_add_cron_interval' );

add_action( 'blondish_seated_sync_hook', 'blondish_sync_seated_events' );


/* ==========================================================================
   8. FLUSH REWRITE RULES on theme activation (for CPT slugs)
   ========================================================================== */

function blondish_event_flush_rewrites() {
	blondish_register_event_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'blondish_event_flush_rewrites' );

<?php
/**
 * NRG Zine — Complete Publishing Infrastructure
 *
 * A community-driven cultural publication on global nightlife,
 * electronic music, and conscious culture.
 *
 * Systems:
 *  1. Zine Cluster Taxonomy (energy, ibiza, culture, music)
 *  2. URL Structure (/zine/{cluster}/{slug}/)
 *  3. Author Persona System (multi-voice, distinct identities)
 *  4. Article Schema (JSON-LD with Person, Article, FAQPage)
 *  5. Entity Reinforcement (structured mentions)
 *  6. RSS Feed (/feed/zine/)
 *  7. Related Articles (cross-cluster linking)
 *  8. UGC Submission Handler
 *  9. Breadcrumb Generation
 * 10. Content Quality Enforcement
 *
 * @package blondish
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* ==========================================================================
   1. ZINE CLUSTER TAXONOMY
   Four primary SEO clusters, each indexable with clean URLs.
   ========================================================================== */

function nrg_register_zine_taxonomy() {
	// Primary content clusters
	register_taxonomy( 'zine_cluster', 'post', [
		'labels' => [
			'name'              => __( 'Zine Clusters', 'blondish' ),
			'singular_name'     => __( 'Cluster', 'blondish' ),
			'search_items'      => __( 'Search Clusters', 'blondish' ),
			'all_items'         => __( 'All Clusters', 'blondish' ),
			'edit_item'         => __( 'Edit Cluster', 'blondish' ),
			'add_new_item'      => __( 'Add New Cluster', 'blondish' ),
			'menu_name'         => __( 'Zine Clusters', 'blondish' ),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [
			'slug'       => 'zine',
			'with_front' => false,
		],
	] );

	// Register the four clusters with descriptions
	$clusters = [
		'energy' => [
			'name' => 'Energy',
			'desc' => 'Philosophy, psychology, consciousness, sustainability, and the meaning behind the movement.',
		],
		'ibiza' => [
			'name' => 'Ibiza',
			'desc' => 'Insider guides, party intelligence, seasonal coverage, and the culture of the White Isle.',
		],
		'culture' => [
			'name' => 'Culture',
			'desc' => 'Community stories, dancefloor diaries, scene reports, fashion, and the people who make it happen.',
		],
		'music' => [
			'name' => 'Music',
			'desc' => 'Artists, genres, tracks, production, DJing, and the sonic ecosystem of electronic music.',
		],
	];

	foreach ( $clusters as $slug => $data ) {
		if ( ! term_exists( $slug, 'zine_cluster' ) ) {
			wp_insert_term( $data['name'], 'zine_cluster', [
				'slug'        => $slug,
				'description' => $data['desc'],
			] );
		}
	}

	// Keep legacy taxonomy registered for backward compat during migration
	register_taxonomy( 'journal_pillar', 'post', [
		'public'       => false,
		'show_in_rest' => false,
		'rewrite'      => false,
	] );
}
add_action( 'init', 'nrg_register_zine_taxonomy' );


/* ==========================================================================
   2. URL STRUCTURE
   Posts in a zine cluster get URLs: /zine/{cluster}/{post-slug}/
   Redirects /journal/ to /zine/ for backward compatibility.
   ========================================================================== */

function nrg_zine_rewrite_rules() {
	// Cluster archive: /zine/ibiza/ → taxonomy archive
	// This is handled by the taxonomy rewrite above

	// Individual posts within a cluster: /zine/{cluster}/{slug}/
	add_rewrite_rule(
		'^zine/([^/]+)/([^/]+)/?$',
		'index.php?zine_cluster=$matches[1]&name=$matches[2]&post_type=post',
		'top'
	);

	// Zine root archive: /zine/
	add_rewrite_rule(
		'^zine/?$',
		'index.php?post_type=post&zine_archive=1',
		'top'
	);
}
add_action( 'init', 'nrg_zine_rewrite_rules' );

function nrg_zine_query_vars( $vars ) {
	$vars[] = 'zine_archive';
	return $vars;
}
add_filter( 'query_vars', 'nrg_zine_query_vars' );

/**
 * Redirect legacy /journal/ URLs to /zine/
 */
function nrg_journal_redirect() {
	if ( ! is_admin() && isset( $_SERVER['REQUEST_URI'] ) ) {
		$uri = $_SERVER['REQUEST_URI'];
		if ( preg_match( '#^/journal(/.*)?$#', $uri, $m ) ) {
			$new_uri = '/zine' . ( $m[1] ?? '/' );
			wp_redirect( home_url( $new_uri ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'nrg_journal_redirect' );

/**
 * Filter post permalink to include cluster in URL.
 */
function nrg_zine_post_link( $permalink, $post ) {
	if ( $post->post_type !== 'post' ) {
		return $permalink;
	}

	$clusters = wp_get_post_terms( $post->ID, 'zine_cluster', [ 'fields' => 'slugs' ] );
	if ( ! is_wp_error( $clusters ) && ! empty( $clusters ) ) {
		$cluster = $clusters[0];
		return home_url( '/zine/' . $cluster . '/' . $post->post_name . '/' );
	}

	return $permalink;
}
add_filter( 'post_link', 'nrg_zine_post_link', 10, 2 );


/* ==========================================================================
   3. AUTHOR PERSONA SYSTEM
   Multiple distinct editorial voices. Each persona has a name, bio,
   tone descriptor, and avatar concept. Registered as WordPress users
   on theme activation.
   ========================================================================== */

function nrg_get_author_personas() {
	return [
		'nrg-team' => [
			'display_name' => 'NRG Team',
			'bio'          => 'The editorial collective behind NRG Zine. We write definitive guides, curate essential listening, and document the culture from every angle.',
			'tone'         => 'Neutral, authoritative, factual. The voice of record.',
			'email'        => 'team@nrgzine.world',
			'clusters'     => [ 'music', 'culture' ],
		],
		'ibiza-insider' => [
			'display_name' => 'Ibiza Insider',
			'bio'          => 'Anonymous local. Fifteen seasons on the island. Knows which door to use, which night to skip, and where the real ones go after hours.',
			'tone'         => 'Opinionated, specific, insider knowledge. First-name basis with every promoter.',
			'email'        => 'insider@nrgzine.world',
			'clusters'     => [ 'ibiza' ],
		],
		'dancefloor-diaries' => [
			'display_name' => 'Dancefloor Diaries',
			'bio'          => 'First-person dispatches from the rooms that matter. If the bass was good, the story is here.',
			'tone'         => 'Sensory, temporal, emotional. You smell the fog machine, you feel the bass in your sternum.',
			'email'        => 'diaries@nrgzine.world',
			'clusters'     => [ 'culture' ],
		],
		'anonymous-raver' => [
			'display_name' => 'Anonymous Raver',
			'bio'          => 'No name, no photo, no filter. Underground dispatches from someone who was there.',
			'tone'         => 'Raw, countercultural, honest. Calls out what deserves it. Celebrates what earns it.',
			'email'        => 'anon@nrgzine.world',
			'clusters'     => [ 'culture', 'music' ],
		],
		'energy-research-lab' => [
			'display_name' => 'Energy Research Lab',
			'bio'          => 'Where sound meets science. Exploring the frequencies, psychology, and philosophy behind why music moves us.',
			'tone'         => 'Pseudo-academic, curious, data-informed. Cites sources. Asks big questions.',
			'email'        => 'lab@nrgzine.world',
			'clusters'     => [ 'energy' ],
		],
	];
}

/**
 * Register author personas as WordPress users (runs once on theme switch).
 */
function nrg_register_author_personas() {
	$personas = nrg_get_author_personas();

	foreach ( $personas as $slug => $persona ) {
		if ( ! username_exists( $slug ) ) {
			$user_id = wp_create_user( $slug, wp_generate_password(), $persona['email'] );
			if ( ! is_wp_error( $user_id ) ) {
				wp_update_user( [
					'ID'           => $user_id,
					'display_name' => $persona['display_name'],
					'description'  => $persona['bio'],
					'role'         => 'author',
				] );
				update_user_meta( $user_id, 'nrg_tone', $persona['tone'] );
				update_user_meta( $user_id, 'nrg_clusters', $persona['clusters'] );
			}
		}
	}
}
add_action( 'after_switch_theme', 'nrg_register_author_personas' );

/**
 * Get Person schema for an author.
 */
function nrg_get_author_schema( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return null;
	}

	$schema = [
		'@type'       => 'Person',
		'name'        => $user->display_name,
		'description' => $user->description,
		'url'         => get_author_posts_url( $user_id ),
	];

	$avatar_url = get_avatar_url( $user_id, [ 'size' => 256 ] );
	if ( $avatar_url ) {
		$schema['image'] = $avatar_url;
	}

	return $schema;
}


/* ==========================================================================
   4. ARTICLE SCHEMA (JSON-LD)
   Publisher = NRG Zine (not BLOND:ISH). Authors get full Person schema.
   Entity reinforcement via structured mentions.
   ========================================================================== */

function nrg_article_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post    = get_post();
	$post_id = $post->ID;

	// Only apply to zine posts
	$clusters = wp_get_post_terms( $post_id, 'zine_cluster', [ 'fields' => 'names' ] );
	// Fallback: check legacy taxonomy
	if ( is_wp_error( $clusters ) || empty( $clusters ) ) {
		$clusters = wp_get_post_terms( $post_id, 'journal_pillar', [ 'fields' => 'names' ] );
	}
	if ( is_wp_error( $clusters ) || empty( $clusters ) ) {
		return;
	}

	$title     = get_the_title( $post_id );
	$excerpt   = get_the_excerpt( $post_id );
	$url       = get_permalink( $post_id );
	$published = get_the_date( 'c', $post_id );
	$modified  = get_the_modified_date( 'c', $post_id );
	$thumbnail = get_the_post_thumbnail_url( $post_id, 'hero-desktop' );
	$focus_kw  = get_post_meta( $post_id, '_yoast_wpseo_focuskw', true );

	// Author — full Person schema
	$author_schema = nrg_get_author_schema( $post->post_author );
	if ( ! $author_schema ) {
		$author_schema = [
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $post->post_author ),
		];
	}

	// Publisher = NRG Zine (community publication, not BLOND:ISH)
	$schema = [
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => $title,
		'description'      => $excerpt,
		'url'              => $url,
		'datePublished'    => $published,
		'dateModified'     => $modified,
		'author'           => $author_schema,
		'publisher'        => [
			'@type' => 'Organization',
			'name'  => 'NRG Zine',
			'url'   => home_url( '/zine/' ),
		],
		'mainEntityOfPage' => $url,
		'isPartOf'         => [
			'@type' => 'Blog',
			'name'  => 'NRG Zine',
			'url'   => home_url( '/zine/' ),
			'description' => 'A living archive of energy, culture, and global nightlife intelligence.',
		],
		'inLanguage'       => 'en',
	];

	if ( $thumbnail ) {
		$schema['image'] = $thumbnail;
	}

	if ( $focus_kw ) {
		$schema['about'] = [
			'@type' => 'Thing',
			'name'  => $focus_kw,
		];
	}

	// Entity reinforcement — structured mentions
	$content    = $post->post_content;
	$mentions   = [];
	$entity_map = [
		'BLOND:ISH' => [
			'@type'  => 'MusicGroup',
			'name'   => 'BLOND:ISH',
			'sameAs' => [
				'https://blondish.world/about/',
				'https://open.spotify.com/artist/2FwDTncUqnHMXpZCy8ZhTi',
				'https://www.instagram.com/blaboratory/',
			],
		],
		'Abracadabra' => [
			'@type'  => 'Organization',
			'name'   => 'Abracadabra',
			'sameAs' => 'https://blondish.world/projects/abracadabra/',
		],
		'Pacha' => [
			'@type'   => 'MusicVenue',
			'name'    => 'Pacha Ibiza',
			'address' => [
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Ibiza',
				'addressCountry'  => 'ES',
			],
			'sameAs' => 'https://www.pacha.com/',
		],
		'Bye Bye Plastic' => [
			'@type'  => 'Organization',
			'name'   => 'Bye Bye Plastic',
			'sameAs' => 'https://blondish.world/projects/bye-bye-plastic/',
		],
		'Burning Man' => [
			'@type'  => 'Event',
			'name'   => 'Burning Man',
			'sameAs' => 'https://burningman.org/',
		],
	];

	foreach ( $entity_map as $name => $entity_data ) {
		if ( stripos( $content, $name ) !== false ) {
			$mentions[] = $entity_data;
		}
	}

	if ( ! empty( $mentions ) ) {
		$schema['mentions'] = $mentions;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'nrg_article_schema', 20 );


/* ==========================================================================
   5. FAQ SCHEMA
   Automatically extracts Q&A from H3 headings in FAQ sections.
   ========================================================================== */

function nrg_faq_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post    = get_post();
	$content = $post->post_content;

	if ( stripos( $content, 'Frequently Asked Questions' ) === false
	  && stripos( $content, 'FAQ' ) === false ) {
		return;
	}

	$faq_items = [];
	$parts = preg_split( '/#{2,3}\s*(?:Frequently Asked Questions|FAQ)/i', $content, 2 );
	if ( count( $parts ) < 2 ) {
		return;
	}

	$faq_section = $parts[1];

	if ( preg_match_all( '/###?\s+(.+?)(?:\n|\r\n)([\s\S]*?)(?=###?|\z|---)/m', $faq_section, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $match ) {
			$question = trim( strip_tags( $match[1] ) );
			$answer   = trim( strip_tags( $match[2] ) );

			if ( ! empty( $question ) && ! empty( $answer ) ) {
				if ( strlen( $answer ) > 1000 ) {
					$answer = substr( $answer, 0, 997 ) . '...';
				}

				$faq_items[] = [
					'@type'          => 'Question',
					'name'           => $question,
					'acceptedAnswer' => [
						'@type' => 'Answer',
						'text'  => $answer,
					],
				];
			}
		}
	}

	if ( empty( $faq_items ) ) {
		return;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_items,
	], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'nrg_faq_schema', 21 );


/* ==========================================================================
   6. RSS FEED
   ========================================================================== */

function nrg_zine_rss_feed() {
	add_feed( 'zine', 'nrg_zine_feed_callback' );
	// Keep legacy feed working
	add_feed( 'journal', 'nrg_zine_feed_callback' );
}
add_action( 'init', 'nrg_zine_rss_feed' );

function nrg_zine_feed_callback() {
	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 20,
		'tax_query'      => [
			'relation' => 'OR',
			[ 'taxonomy' => 'zine_cluster', 'operator' => 'EXISTS' ],
			[ 'taxonomy' => 'journal_pillar', 'operator' => 'EXISTS' ],
		],
	];

	$query = new WP_Query( $args );

	header( 'Content-Type: application/rss+xml; charset=UTF-8' );
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title>NRG Zine</title>
	<link><?php echo esc_url( home_url( '/zine/' ) ); ?></link>
	<description>A living archive of energy, culture, and global nightlife intelligence.</description>
	<language>en-US</language>
	<atom:link href="<?php echo esc_url( home_url( '/feed/zine/' ) ); ?>" rel="self" type="application/rss+xml" />
	<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php the_permalink_rss(); ?></link>
		<pubDate><?php echo get_the_date( 'r' ); ?></pubDate>
		<dc:creator><?php the_author(); ?></dc:creator>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
		<guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>
	</item>
	<?php endwhile; endif; wp_reset_postdata(); ?>
</channel>
</rss>
	<?php
}


/* ==========================================================================
   7. RELATED ARTICLES (cross-cluster + same-cluster)
   ========================================================================== */

function nrg_get_related_posts( $post_id, $count = 3 ) {
	$clusters = wp_get_post_terms( $post_id, 'zine_cluster', [ 'fields' => 'ids' ] );
	$tags     = wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] );

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $count,
		'post__not_in'   => [ $post_id ],
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	if ( ! is_wp_error( $clusters ) && ! empty( $clusters ) ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'zine_cluster',
				'terms'    => $clusters,
			],
		];
	} elseif ( ! empty( $tags ) ) {
		$args['tag__in'] = $tags;
	}

	return new WP_Query( $args );
}

/**
 * Get cross-cluster articles (from a different cluster than the current post).
 */
function nrg_get_cross_cluster_posts( $post_id, $count = 2 ) {
	$clusters = wp_get_post_terms( $post_id, 'zine_cluster', [ 'fields' => 'ids' ] );

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $count,
		'post__not_in'   => [ $post_id ],
		'orderby'        => 'rand',
	];

	if ( ! is_wp_error( $clusters ) && ! empty( $clusters ) ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'zine_cluster',
				'terms'    => $clusters,
				'operator' => 'NOT IN',
			],
		];
	}

	return new WP_Query( $args );
}


/* ==========================================================================
   8. UGC SUBMISSION HANDLER
   Processes community content submissions via AJAX.
   ========================================================================== */

function nrg_register_submission_endpoint() {
	register_rest_route( 'nrg/v1', '/submit', [
		'methods'             => 'POST',
		'callback'            => 'nrg_handle_submission',
		'permission_callback' => '__return_true',
	] );
}
add_action( 'rest_api_init', 'nrg_register_submission_endpoint' );

function nrg_handle_submission( $request ) {
	$params = $request->get_json_params();

	$name    = sanitize_text_field( $params['name'] ?? '' );
	$email   = sanitize_email( $params['email'] ?? '' );
	$type    = sanitize_text_field( $params['type'] ?? 'story' ); // story, mix, photo, question
	$title   = sanitize_text_field( $params['title'] ?? '' );
	$content = wp_kses_post( $params['content'] ?? '' );
	$anon    = (bool) ( $params['anonymous'] ?? false );

	if ( empty( $email ) || empty( $content ) ) {
		return new WP_Error( 'missing_fields', 'Email and content are required.', [ 'status' => 400 ] );
	}

	// Rate limiting: max 3 submissions per email per day
	$transient_key = 'nrg_submit_' . md5( $email );
	$count = (int) get_transient( $transient_key );
	if ( $count >= 3 ) {
		return new WP_Error( 'rate_limited', 'Maximum submissions reached for today.', [ 'status' => 429 ] );
	}
	set_transient( $transient_key, $count + 1, DAY_IN_SECONDS );

	// Create as draft post for moderation
	$post_data = [
		'post_type'    => 'post',
		'post_title'   => $title ?: 'Community Submission: ' . $type,
		'post_content' => $content,
		'post_status'  => 'draft', // Requires editorial review
		'post_author'  => 1, // Will be reassigned during review
		'meta_input'   => [
			'_nrg_submission'       => true,
			'_nrg_submitter_name'   => $anon ? 'Anonymous' : $name,
			'_nrg_submitter_email'  => $email,
			'_nrg_submission_type'  => $type,
			'_nrg_submission_anon'  => $anon,
		],
	];

	$post_id = wp_insert_post( $post_data );

	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'submission_failed', 'Could not save submission.', [ 'status' => 500 ] );
	}

	// Notify editors
	$admin_email = get_option( 'admin_email' );
	wp_mail(
		$admin_email,
		'[NRG Zine] New submission: ' . $title,
		sprintf(
			"New %s submission from %s (%s)\n\nTitle: %s\n\nContent preview:\n%s",
			$type,
			$anon ? 'Anonymous' : $name,
			$email,
			$title,
			wp_trim_words( $content, 50 )
		)
	);

	return [
		'success' => true,
		'message' => 'Your submission has been received. Our editorial team will review it.',
	];
}


/* ==========================================================================
   9. BREADCRUMB GENERATION
   Outputs semantic breadcrumbs for zine articles.
   ========================================================================== */

function nrg_zine_breadcrumbs() {
	if ( ! is_singular( 'post' ) ) {
		return '';
	}

	$post     = get_post();
	$clusters = wp_get_post_terms( $post->ID, 'zine_cluster' );
	$crumbs   = [];

	$crumbs[] = [
		'name' => 'NRG Zine',
		'url'  => home_url( '/zine/' ),
	];

	if ( ! is_wp_error( $clusters ) && ! empty( $clusters ) ) {
		$cluster = $clusters[0];
		$crumbs[] = [
			'name' => $cluster->name,
			'url'  => get_term_link( $cluster ),
		];
	}

	$crumbs[] = [
		'name' => get_the_title(),
		'url'  => get_permalink(),
	];

	// HTML output
	$html = '<nav class="nrg-breadcrumbs" aria-label="Breadcrumb"><ol>';
	foreach ( $crumbs as $i => $crumb ) {
		$is_last = ( $i === count( $crumbs ) - 1 );
		if ( $is_last ) {
			$html .= '<li aria-current="page">' . esc_html( $crumb['name'] ) . '</li>';
		} else {
			$html .= '<li><a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['name'] ) . '</a></li>';
		}
	}
	$html .= '</ol></nav>';

	// BreadcrumbList schema
	$schema_items = [];
	foreach ( $crumbs as $i => $crumb ) {
		$schema_items[] = [
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb['name'],
			'item'     => $crumb['url'],
		];
	}

	$html .= '<script type="application/ld+json">' . wp_json_encode( [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $schema_items,
	], JSON_UNESCAPED_SLASHES ) . '</script>';

	return $html;
}


/* ==========================================================================
   10. CONTENT QUALITY CHECKS
   Admin notices for articles missing required SEO elements.
   ========================================================================== */

function nrg_content_quality_check() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'post' ) {
		return;
	}

	$post = get_post();
	if ( ! $post || $post->post_type !== 'post' ) {
		return;
	}

	$issues  = [];
	$content = $post->post_content;

	// Check: Focus keyword set?
	$focus_kw = get_post_meta( $post->ID, '_yoast_wpseo_focuskw', true );
	if ( empty( $focus_kw ) ) {
		$issues[] = 'Missing Yoast focus keyword.';
	}

	// Check: Meta description set?
	$meta_desc = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
	if ( empty( $meta_desc ) ) {
		$issues[] = 'Missing meta description.';
	}

	// Check: Minimum internal links (2)
	$internal_links = preg_match_all( '/href="\/[^"]*"/', $content );
	if ( $internal_links < 2 ) {
		$issues[] = "Only {$internal_links} internal link(s). Minimum 2 required.";
	}

	// Check: At least 1 external link
	$external_links = preg_match_all( '/href="https?:\/\/(?!blondish\.world)[^"]*"/', $content );
	if ( $external_links < 1 ) {
		$issues[] = 'No external links. Add at least 1 authoritative source.';
	}

	// Check: Assigned to a zine cluster
	$clusters = wp_get_post_terms( $post->ID, 'zine_cluster', [ 'fields' => 'names' ] );
	if ( is_wp_error( $clusters ) || empty( $clusters ) ) {
		$issues[] = 'Not assigned to a Zine Cluster (energy / ibiza / culture / music).';
	}

	// Check: Word count
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	if ( $word_count < 800 ) {
		$issues[] = "Only {$word_count} words. Minimum 800 recommended for SEO.";
	}

	if ( ! empty( $issues ) ) {
		echo '<div class="notice notice-warning"><p><strong>NRG Zine Quality Check:</strong></p><ul>';
		foreach ( $issues as $issue ) {
			echo '<li>' . esc_html( $issue ) . '</li>';
		}
		echo '</ul></div>';
	}
}
add_action( 'admin_notices', 'nrg_content_quality_check' );


/* ==========================================================================
   BACKWARD COMPAT: Keep old function names working during migration
   ========================================================================== */

function blondish_get_related_journal_posts( $post_id, $count = 3 ) {
	return nrg_get_related_posts( $post_id, $count );
}

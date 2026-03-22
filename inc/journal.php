<?php
/**
 * NRG Zine (Journal) — Taxonomy, Schema & LLMO Support
 *
 * Registers:
 *  - journal_pillar taxonomy (content pillars for the NRG Zine)
 *  - Article + FAQPage JSON-LD schema for journal posts
 *  - Open Graph meta for journal articles
 *  - RSS feed for journal specifically
 *
 * @package blondish
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   1. JOURNAL PILLAR TAXONOMY
   ========================================================================== */

function blondish_register_journal_taxonomy() {
	register_taxonomy( 'journal_pillar', 'post', [
		'labels' => [
			'name'          => __( 'Zine Pillars', 'blondish' ),
			'singular_name' => __( 'Zine Pillar', 'blondish' ),
			'search_items'  => __( 'Search Pillars', 'blondish' ),
			'all_items'     => __( 'All Pillars', 'blondish' ),
			'edit_item'     => __( 'Edit Pillar', 'blondish' ),
			'add_new_item'  => __( 'Add New Pillar', 'blondish' ),
		],
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => [ 'slug' => 'journal/category', 'with_front' => false ],
		'show_admin_column' => true,
	] );

	// Register default pillar terms
	$pillars = [
		'sound-lab'            => 'Sound Lab',
		'conscious-frequencies' => 'Conscious Frequencies',
		'inner-groove'         => 'Inner Groove',
		'scene-reports'        => 'Scene Reports',
		'community-voices'     => 'Community Voices',
	];

	foreach ( $pillars as $slug => $name ) {
		if ( ! term_exists( $slug, 'journal_pillar' ) ) {
			wp_insert_term( $name, 'journal_pillar', [ 'slug' => $slug ] );
		}
	}
}
add_action( 'init', 'blondish_register_journal_taxonomy' );


/* ==========================================================================
   2. JOURNAL ARTICLE SCHEMA (JSON-LD)
   ========================================================================== */

function blondish_journal_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post    = get_post();
	$post_id = $post->ID;

	// Only apply to posts in the journal (check if it has journal_pillar terms or is in journal category)
	$pillars = wp_get_post_terms( $post_id, 'journal_pillar', [ 'fields' => 'names' ] );
	if ( is_wp_error( $pillars ) || empty( $pillars ) ) {
		// Also check if slug starts with journal-adjacent content
		$categories = wp_get_post_categories( $post_id, [ 'fields' => 'slugs' ] );
		$journal_cats = [ 'sound-lab', 'conscious-frequencies', 'inner-groove', 'scene-reports', 'community-voices', 'journal', 'nrg-zine' ];
		if ( empty( array_intersect( $categories, $journal_cats ) ) ) {
			return;
		}
	}

	$title        = get_the_title( $post_id );
	$description  = get_the_excerpt( $post_id );
	$url          = get_permalink( $post_id );
	$published    = get_the_date( 'c', $post_id );
	$modified     = get_the_modified_date( 'c', $post_id );
	$author_name  = get_the_author_meta( 'display_name', $post->post_author );
	$thumbnail    = get_the_post_thumbnail_url( $post_id, 'hero-desktop' );

	// Yoast focus keyword as article "about"
	$focus_kw = get_post_meta( $post_id, '_yoast_wpseo_focuskw', true );

	// Build Article schema
	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'Article',
		'headline'        => $title,
		'description'     => $description,
		'url'             => $url,
		'datePublished'   => $published,
		'dateModified'    => $modified,
		'author'          => [
			'@type' => 'Person',
			'name'  => $author_name,
		],
		'publisher'       => [
			'@type' => 'Person',
			'name'  => 'BLOND:ISH',
			'url'   => home_url( '/about/' ),
			'sameAs' => [
				'https://www.instagram.com/blaboratory/',
				'https://www.facebook.com/blaboratory',
				'https://soundcloud.com/blondish',
				'https://open.spotify.com/artist/2FwDTncUqnHMXpZCy8ZhTi',
			],
		],
		'mainEntityOfPage' => $url,
		'isPartOf'         => [
			'@type' => 'Blog',
			'name'  => 'NRG Zine',
			'url'   => home_url( '/journal/' ),
		],
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

	// Add mentions for key entities
	$mentions = [];
	$content  = $post->post_content;

	$entity_map = [
		'BLOND:ISH'      => home_url( '/about/' ),
		'Abracadabra'    => home_url( '/projects/abracadabra/' ),
		'Bye Bye Plastic' => home_url( '/projects/bye-bye-plastic/' ),
	];

	foreach ( $entity_map as $name => $same_as ) {
		if ( stripos( $content, $name ) !== false ) {
			$mentions[] = [
				'@type'  => 'Thing',
				'name'   => $name,
				'sameAs' => $same_as,
			];
		}
	}

	if ( ! empty( $mentions ) ) {
		$schema['mentions'] = $mentions;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_journal_schema', 20 );


/* ==========================================================================
   3. FAQ SCHEMA EXTRACTION
   Automatically generates FAQPage schema from H3 headings in FAQ sections
   ========================================================================== */

function blondish_journal_faq_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post    = get_post();
	$content = $post->post_content;

	// Check if content has a FAQ section
	if ( stripos( $content, 'Frequently Asked Questions' ) === false &&
	     stripos( $content, 'FAQ' ) === false ) {
		return;
	}

	// Extract Q&A pairs from content
	// Pattern: ### Question\n\nAnswer paragraph(s) until next ### or ---
	$faq_items = [];

	// Split content at FAQ section
	$parts = preg_split( '/#{2,3}\s*(?:Frequently Asked Questions|FAQ)/i', $content, 2 );
	if ( count( $parts ) < 2 ) {
		return;
	}

	$faq_section = $parts[1];

	// Match ### headings and their following content
	if ( preg_match_all( '/###\s+(.+?)(?:\n|\r\n)([\s\S]*?)(?=###|\z|---)/m', $faq_section, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $match ) {
			$question = trim( strip_tags( $match[1] ) );
			$answer   = trim( strip_tags( $match[2] ) );

			if ( ! empty( $question ) && ! empty( $answer ) ) {
				// Truncate extremely long answers
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

	$faq_schema = [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_items,
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_journal_faq_schema', 21 );


/* ==========================================================================
   4. JOURNAL RSS FEED
   ========================================================================== */

function blondish_journal_rss_feed() {
	add_feed( 'journal', 'blondish_journal_feed_callback' );
}
add_action( 'init', 'blondish_journal_rss_feed' );

function blondish_journal_feed_callback() {
	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 20,
		'tax_query'      => [
			[
				'taxonomy' => 'journal_pillar',
				'operator' => 'EXISTS',
			],
		],
	];

	$query = new WP_Query( $args );

	header( 'Content-Type: application/rss+xml; charset=UTF-8' );
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title>NRG Zine — BLOND:ISH</title>
	<link><?php echo esc_url( home_url( '/journal/' ) ); ?></link>
	<description>Electronic music culture, sustainability, and conscious living from BLOND:ISH</description>
	<language>en-US</language>
	<atom:link href="<?php echo esc_url( home_url( '/feed/journal/' ) ); ?>" rel="self" type="application/rss+xml" />
	<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php the_permalink_rss(); ?></link>
		<pubDate><?php echo get_the_date( 'r' ); ?></pubDate>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
		<guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>
	</item>
	<?php endwhile; endif; wp_reset_postdata(); ?>
</channel>
</rss>
	<?php
}


/* ==========================================================================
   5. RELATED ARTICLES (for bottom of journal posts)
   ========================================================================== */

function blondish_get_related_journal_posts( $post_id, $count = 3 ) {
	$pillars = wp_get_post_terms( $post_id, 'journal_pillar', [ 'fields' => 'ids' ] );
	$tags    = wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] );

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $count,
		'post__not_in'   => [ $post_id ],
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	// Prefer same pillar, then same tags
	if ( ! is_wp_error( $pillars ) && ! empty( $pillars ) ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'journal_pillar',
				'terms'    => $pillars,
			],
		];
	} elseif ( ! empty( $tags ) ) {
		$args['tag__in'] = $tags;
	}

	return new WP_Query( $args );
}

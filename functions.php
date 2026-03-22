<?php
/**
 * BLOND:ISH Theme — functions.php
 * WordPress Full Site Editing (FSE) Block Theme
 * Site: blondish.world
 *
 * Sections:
 *  1. Theme Setup
 *  2. Custom Image Sizes
 *  3. Enqueue Scripts & Styles
 *  4. Hero Image Preloading (LCP optimisation)
 *  5. Block Patterns (disable core defaults)
 *  6. Excerpt Length (Journal Query Loop)
 *  7. WebP MIME Type Support
 *  8. Custom Image Sizes in Media Library
 *  9. Yoast SEO Breadcrumb Helper
 * 10. WP Rocket Compatibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

// Events system (CPT, Seated sync, JSON-LD)
require_once get_template_directory() . '/inc/events.php';

// Releases system (CPT, meta fields, JSON-LD)
require_once get_template_directory() . '/inc/releases.php';

// Features / Media system (CPT, taxonomy, JSON-LD, transcripts)
require_once get_template_directory() . '/inc/media.php';

// SEO & LLMO optimizations (venue/city/festival taxonomies, misspelling handling, CollectionPage schema)
require_once get_template_directory() . '/inc/seo-optimizations.php';

// NRG Zine — cluster taxonomy, author personas, schema, UGC, breadcrumbs
require_once get_template_directory() . '/inc/journal.php';

/* ==========================================================================
   1. THEME SETUP
   ========================================================================== */

function blondish_setup() {
	// Block theme essentials
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	// HTML5 markup
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'script',
		'style',
	] );

	// Navigation menus
	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'blondish' ),
		'footer'  => __( 'Footer Navigation', 'blondish' ),
		'social'  => __( 'Social Links', 'blondish' ),
	] );

	// Content width (used by embeds)
	$GLOBALS['content_width'] = 1400;
}
add_action( 'after_setup_theme', 'blondish_setup' );


/* ==========================================================================
   2. CUSTOM IMAGE SIZES
   ========================================================================== */

// Hero images — 16:9 aspect ratio, hard crop
add_image_size( 'hero-desktop', 1920, 1080, true );
add_image_size( 'hero-medium',  1280, 720,  true );
add_image_size( 'hero-mobile',  768,  432,  true );

// Portrait / square card (for Journal grid, press thumbnails)
add_image_size( 'card-square',  600,  600,  true );

// Wide landscape card (for Music / discography grid)
add_image_size( 'card-wide',    800,  450,  true );


/* ==========================================================================
   3. ENQUEUE SCRIPTS & STYLES
   ========================================================================== */

function blondish_scripts() {
	$theme_ver = wp_get_theme()->get( 'Version' );

	// -------------------------------------------------------------------------
	// Roboto Mono — monospace font from Google Fonts
	// -------------------------------------------------------------------------
	wp_enqueue_style(
		'google-fonts-roboto-mono',
		'https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,400;0,500;0,700;1,400;1,700&display=swap',
		[],
		null
	);

	// -------------------------------------------------------------------------
	// lite-youtube-embed (Paul Irish) — YouTube facade
	// https://github.com/paulirish/lite-youtube-embed
	// Download lite-yt-embed.css + lite-yt-embed.js and place in assets/
	// -------------------------------------------------------------------------
	wp_enqueue_style(
		'lite-youtube',
		get_template_directory_uri() . '/assets/css/lite-yt-embed.css',
		[],
		'0.3.2'
	);
	wp_enqueue_script(
		'lite-youtube',
		get_template_directory_uri() . '/assets/js/lite-yt-embed.js',
		[],
		'0.3.2',
		true // load in footer
	);

	// -------------------------------------------------------------------------
	// Custom embeds — IntersectionObserver for Seated.com + Shopify Buy Button
	// -------------------------------------------------------------------------
	wp_enqueue_script(
		'blondish-embeds',
		get_template_directory_uri() . '/assets/js/embeds.js',
		[ 'lite-youtube' ],
		$theme_ver,
		true // load in footer
	);

	// -------------------------------------------------------------------------
	// Theme stylesheet (already loaded by FSE, but kept for non-block overrides)
	// -------------------------------------------------------------------------
	wp_enqueue_style(
		'blondish-style',
		get_stylesheet_uri(),
		[],
		$theme_ver
	);

	// -------------------------------------------------------------------------
	// Events — styles and hero scroll indicators
	// -------------------------------------------------------------------------
	if ( is_front_page() || is_singular( 'blondish_event' ) || is_post_type_archive( 'blondish_event' ) ) {
		wp_enqueue_style(
			'blondish-events',
			get_template_directory_uri() . '/assets/css/events.css',
			[ 'blondish-style' ],
			$theme_ver
		);
	}

	// Press page
	if ( is_page( 'press' ) ) {
		wp_enqueue_style(
			'blondish-press',
			get_template_directory_uri() . '/assets/css/press.css',
			[ 'blondish-style' ],
			$theme_ver
		);
	}

	// Release pages — single + archive
	if ( is_singular( 'blondish_release' ) || is_post_type_archive( 'blondish_release' ) || is_tax( 'release_type' ) ) {
		wp_enqueue_style(
			'blondish-releases',
			get_template_directory_uri() . '/assets/css/releases.css',
			[ 'blondish-style' ],
			$theme_ver
		);
	}

	// Feature / Media pages — single + archive
	if ( is_singular( 'blondish_media' ) || is_post_type_archive( 'blondish_media' ) || is_tax( 'media_type' ) ) {
		wp_enqueue_style(
			'blondish-media',
			get_template_directory_uri() . '/assets/css/media.css',
			[ 'blondish-style' ],
			$theme_ver
		);
	}

	if ( is_front_page() ) {
		wp_enqueue_script(
			'blondish-events-hero',
			get_template_directory_uri() . '/assets/js/events-hero.js',
			[],
			$theme_ver,
			true
		);
		wp_enqueue_script(
			'blondish-countdown',
			get_template_directory_uri() . '/assets/js/countdown.js',
			[],
			$theme_ver,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'blondish_scripts' );


/* ==========================================================================
   4. HERO IMAGE PRELOADING (LCP OPTIMISATION)
   Preloads the largest above-the-fold image so the browser fetches it
   immediately, before the CSS/JS render chain fires.
   ========================================================================== */

/**
 * Add .js class to <html> and lazy-image load handler.
 * Printed early so the .js class is available before CSS paints.
 */
function blondish_inline_js_class() {
	echo "<script>document.documentElement.classList.add('js');"
		. "document.addEventListener('DOMContentLoaded',function(){"
		. "document.querySelectorAll('img[loading=\"lazy\"]').forEach(function(img){"
		. "function reveal(){img.classList.add('loaded')}"
		. "if(img.complete){reveal()}else{img.addEventListener('load',reveal)}"
		. "})});</script>\n";
}
add_action( 'wp_head', 'blondish_inline_js_class', 0 );

function blondish_preload_hero() {

	// Homepage hero
	if ( is_front_page() ) {
		$hero_desktop = get_template_directory_uri() . '/assets/images/hero-homepage-1920.webp';
		$hero_mobile  = get_template_directory_uri() . '/assets/images/hero-homepage-768.webp';

		// NOTE: If you use a media-library image as the hero, replace the URI
		// above with wp_get_attachment_image_src( ATTACHMENT_ID, 'hero-desktop' )[0]

		echo '<link rel="preload" as="image" href="' . esc_url( $hero_desktop ) . '" media="(min-width: 1024px)">' . "\n";
		echo '<link rel="preload" as="image" href="' . esc_url( $hero_mobile )  . '" media="(max-width: 1023px)">' . "\n";
	}

	// Abracadabra project page hero
	if ( is_page( 'abracadabra' ) ) {
		$hero = get_template_directory_uri() . '/assets/images/abra-pacha-ibiza-2026.jpg';
		echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '" media="(min-width: 1024px)">' . "\n";
	}
}
add_action( 'wp_head', 'blondish_preload_hero', 1 );


/* ==========================================================================
   5. BLOCK PATTERNS — DISABLE CORE DEFAULTS
   Keeps the inserter clean; only custom patterns will appear.
   ========================================================================== */

function blondish_remove_core_patterns() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'blondish_remove_core_patterns' );

function blondish_register_pattern_categories() {
	register_block_pattern_category( 'blondish-heroes', [
		'label' => __( 'BLOND:ISH — Heroes', 'blondish' ),
	] );
	register_block_pattern_category( 'blondish-sections', [
		'label' => __( 'BLOND:ISH — Sections', 'blondish' ),
	] );
}
add_action( 'init', 'blondish_register_pattern_categories' );

/**
 * Manually register the Music Releases Grid pattern.
 * Auto-discovery can be unreliable in containerised environments (wp-env),
 * so we register it explicitly to guarantee availability in templates.
 */
function blondish_register_custom_patterns() {
	$patterns = [
		'hero-next-event' => [
			'title'       => __( 'Hero — Next Event', 'blondish' ),
			'description' => __( 'Dynamic hero showing next upcoming event with countdown and ticket CTA.', 'blondish' ),
		],
		'music-releases-grid' => [
			'title'       => __( 'Music Releases Grid', 'blondish' ),
			'description' => __( 'Homepage section showing 4 latest Music category posts as square clickable cover images.', 'blondish' ),
		],
		'discography-seo-intro' => [
			'title'       => __( 'Discography SEO Intro', 'blondish' ),
			'description' => __( 'SEO-optimized introductory content for the discography archive.', 'blondish' ),
		],
		'discography-faq' => [
			'title'       => __( 'Discography FAQ', 'blondish' ),
			'description' => __( 'FAQ section for the discography archive optimized for AI search and featured snippets.', 'blondish' ),
		],
		'abracadabra-hub' => [
			'title'       => __( 'Abracadabra Hub', 'blondish' ),
			'description' => __( 'Hub page for Abracadabra events — targets high-value keywords like "abracadabra new york".', 'blondish' ),
		],
		'abracadabra-homepage-banner' => [
			'title'       => __( 'Abracadabra Homepage Banner', 'blondish' ),
			'description' => __( 'Full-width Abracadabra 2026 season banner for the homepage.', 'blondish' ),
		],
	];

	foreach ( $patterns as $slug => $pattern_info ) {
		$file = get_template_directory() . '/patterns/' . $slug . '.php';
		if ( ! file_exists( $file ) ) {
			continue;
		}

		// Capture title/description before include (which may clobber local vars)
		$pattern_title = $pattern_info['title'];
		$pattern_desc  = $pattern_info['description'];

		ob_start();
		include $file;
		$content = ob_get_clean();

		register_block_pattern( 'blondish/' . $slug, [
			'title'       => $pattern_title,
			'description' => $pattern_desc,
			'categories'  => [ 'blondish-sections' ],
			'content'     => $content,
		] );
	}
}
add_action( 'init', 'blondish_register_custom_patterns' );


/* ==========================================================================
   6. EXCERPT LENGTH — JOURNAL QUERY LOOP
   Controls the word count for auto-generated post excerpts in the
   Journal archive grid. Adjust the number to suit your card design.
   ========================================================================== */

function blondish_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	return 20; // words — approx 2 lines of card text
}
add_filter( 'excerpt_length', 'blondish_excerpt_length', 999 );

function blondish_excerpt_more( $more ) {
	return '&hellip;'; // replaces the default "[...]"
}
add_filter( 'excerpt_more', 'blondish_excerpt_more' );


/* ==========================================================================
   7. WEBP MIME TYPE SUPPORT
   WordPress < 5.8 may block WebP uploads. This filter ensures WebP is
   always allowed regardless of WordPress version.
   ========================================================================== */

function blondish_webp_upload_mimes( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}
add_filter( 'mime_types',     'blondish_webp_upload_mimes' );
add_filter( 'upload_mimes',   'blondish_webp_upload_mimes' );


/* ==========================================================================
   8. CUSTOM IMAGE SIZES IN MEDIA LIBRARY SELECTOR
   Makes the custom sizes appear in the "Image Size" dropdown when you
   insert an image block in the editor.
   ========================================================================== */

function blondish_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, [
		'hero-desktop' => __( 'Hero Desktop (1920×1080)', 'blondish' ),
		'hero-medium'  => __( 'Hero Medium (1280×720)',   'blondish' ),
		'hero-mobile'  => __( 'Hero Mobile (768×432)',    'blondish' ),
		'card-square'  => __( 'Card Square (600×600)',    'blondish' ),
		'card-wide'    => __( 'Card Wide (800×450)',      'blondish' ),
	] );
}
add_filter( 'image_size_names_choose', 'blondish_custom_image_sizes' );


/* ==========================================================================
   9. YOAST SEO — BREADCRUMB HELPER
   Call <?php blondish_breadcrumb(); ?> inside any template part to
   output Yoast's accessible breadcrumb trail.
   ========================================================================== */

function blondish_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb(
			'<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'blondish' ) . '">',
			'</nav>'
		);
	}
}


/* ==========================================================================
  10. WP ROCKET COMPATIBILITY
   Ensures WP Rocket's "Delay JavaScript" feature doesn't interfere
   with scripts that must fire immediately (e.g. GTM, consent banners).
   Add any script handles that should NOT be delayed to the array below.
   ========================================================================== */

function blondish_rocket_exclude_delay_js( $excluded ) {
	// Scripts to exclude from WP Rocket's JS delay feature
	$excluded[] = '/assets/js/embeds.js';   // Our IntersectionObserver — self-managed
	$excluded[] = 'gtm';                     // Google Tag Manager
	return $excluded;
}
add_filter( 'rocket_delay_js_exclusions', 'blondish_rocket_exclude_delay_js' );


/* ==========================================================================
  11. GLOBAL STRUCTURED DATA — Person (Artist) + WebSite JSON-LD
   ========================================================================== */

function blondish_global_json_ld() {
	$site_url = home_url();
	$hero_img = get_template_directory_uri() . '/assets/images/blondish-hero-website.png';

	$music_group = [
		'@context'      => 'https://schema.org',
		'@type'         => 'Person',
		'@id'           => $site_url . '/#artist',
		'name'          => 'BLOND:ISH',
		'alternateName' => [ 'Blondish', 'Blondeish', 'Blond:ish', 'Vivie-Ann Bakos', 'DJ Blondish', 'Bondish', 'Blonde ish' ],
		'givenName'     => 'Vivie-Ann',
		'familyName'    => 'Bakos',
		'jobTitle'      => [ 'DJ', 'Music Producer', 'Event Creator' ],
		'url'           => $site_url,
		'birthPlace'    => [
			'@type' => 'Place',
			'name'  => 'Montreal, Canada',
		],
		'nationality'   => [
			'@type' => 'Country',
			'name'  => 'Canada',
		],
		'description'   => 'BLOND:ISH (Vivie-Ann Bakos) is a Montreal-born DJ and producer known for melodic house, Afro house, and melodic techno. Creator of Abracadabra events. Ibiza resident with Coachella, Burning Man, and Tomorrowland appearances.',
		'knowsAbout'    => [ 'DJing', 'Music Production', 'Melodic House', 'Afro House', 'Melodic Techno', 'Electronic Music', 'Abracadabra Events' ],
		'genre'         => [ 'Melodic House', 'Afro House', 'Melodic Techno', 'Organic House', 'Deep House' ],
		'sameAs'        => [
			'https://www.instagram.com/blondish/',
			'https://open.spotify.com/artist/6zsJjoCtL1WByG0VsuFWzR',
			'https://soundcloud.com/blondish',
			'https://www.youtube.com/@blondish',
			'https://www.facebook.com/blondish',
			'https://ra.co/dj/blondish',
			'https://www.beatport.com/artist/blondish/178964',
			'https://www.discogs.com/artist/1618498-Blondish',
			'https://en.wikipedia.org/wiki/Blond:ish',
			'https://www.tiktok.com/@blondish',
		],
		'image'         => $hero_img,
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $music_group, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";

	$website = [
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => 'BLOND:ISH',
		'url'             => $site_url,
		'potentialAction' => [
			'@type'       => 'SearchAction',
			'target'      => $site_url . '/?s={search_term_string}',
			'query-input' => 'required name=search_term_string',
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $website, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_global_json_ld', 2 );


/* ==========================================================================
  12. OPEN GRAPH & TWITTER CARDS
   Only output if Yoast/RankMath is NOT active.
   ========================================================================== */

function blondish_og_twitter_meta() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	$site_url   = home_url();
	$hero_img   = get_template_directory_uri() . '/assets/images/blondish-hero-website.png';

	// Determine og:type
	$og_type = 'website';
	if ( is_front_page() ) {
		$og_type = 'music.musician';
	} elseif ( is_singular( 'blondish_release' ) ) {
		$og_type = 'music.song';
	} elseif ( is_singular( 'blondish_event' ) ) {
		$og_type = 'event';
	}

	// Title
	$og_title = wp_get_document_title();

	// Description
	$og_desc = '';
	if ( is_front_page() ) {
		$og_desc = 'BLOND:ISH — DJ, producer & creator of Abracadabra. Melodic house, Afro house & melodic techno. Tour dates, music, and more.';
	} elseif ( is_singular() ) {
		$og_desc = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
	} elseif ( is_post_type_archive( 'blondish_event' ) ) {
		$og_desc = 'See all upcoming BLOND:ISH tour dates and buy tickets. Live shows across Ibiza, Europe, North America and beyond.';
	} elseif ( is_post_type_archive( 'blondish_release' ) ) {
		$og_desc = 'Explore BLOND:ISH\'s full discography — albums, EPs, singles, and remixes in melodic house, Afro house, and melodic techno.';
	}
	$og_desc = mb_substr( $og_desc, 0, 200 );

	// Image
	$og_image = $hero_img;
	if ( is_singular() && has_post_thumbnail() ) {
		$og_image = get_the_post_thumbnail_url( null, 'hero-desktop' );
	}

	// URL
	$og_url = is_singular() ? get_permalink() : ( is_front_page() ? $site_url . '/' : '' );
	if ( ! $og_url && is_post_type_archive() ) {
		$og_url = get_post_type_archive_link( get_queried_object()->name ?? '' );
	}

	echo '<meta property="og:site_name" content="BLOND:ISH">' . "\n";
	echo '<meta property="og:locale" content="en_US">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
	if ( $og_desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $og_desc ) . '">' . "\n";
	}
	if ( $og_image ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
	}
	if ( $og_url ) {
		echo '<meta property="og:url" content="' . esc_url( $og_url ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:site" content="@blondaboratory_ish">' . "\n";
}
add_action( 'wp_head', 'blondish_og_twitter_meta', 2 );


/* ==========================================================================
  13. META DESCRIPTIONS — Homepage + Music archive
   (Event/release/media singles + archives already handled in inc/ files)
   ========================================================================== */

function blondish_homepage_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	if ( is_front_page() ) {
		echo '<meta name="description" content="BLOND:ISH — DJ, producer &amp; creator of Abracadabra. Melodic house, Afro house &amp; melodic techno. Tour dates, music, and more.">' . "\n";
	}
}
add_action( 'wp_head', 'blondish_homepage_meta_description', 1 );


/* ==========================================================================
  14. XML SITEMAP ENHANCEMENT
   Include CPTs and add lastmod dates.
   ========================================================================== */

function blondish_sitemap_post_types( $post_types ) {
	// Ensure our CPTs are included (they should be by default since public = true,
	// but this makes it explicit)
	if ( ! isset( $post_types['blondish_event'] ) ) {
		$post_types['blondish_event'] = get_post_type_object( 'blondish_event' );
	}
	if ( ! isset( $post_types['blondish_release'] ) ) {
		$post_types['blondish_release'] = get_post_type_object( 'blondish_release' );
	}
	if ( ! isset( $post_types['blondish_media'] ) ) {
		$post_types['blondish_media'] = get_post_type_object( 'blondish_media' );
	}
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'blondish_sitemap_post_types' );

function blondish_sitemap_entry( $entry, $post, $post_type ) {
	$entry['lastmod'] = get_the_modified_date( 'Y-m-d\TH:i:sP', $post );
	return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'blondish_sitemap_entry', 10, 3 );

function blondish_sitemap_taxonomies( $taxonomies ) {
	if ( ! isset( $taxonomies['release_type'] ) ) {
		$taxonomies['release_type'] = get_taxonomy( 'release_type' );
	}
	if ( ! isset( $taxonomies['media_type'] ) ) {
		$taxonomies['media_type'] = get_taxonomy( 'media_type' );
	}
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'blondish_sitemap_taxonomies' );


/* ==========================================================================
  15. FONT PRELOADING
   ========================================================================== */

function blondish_preload_fonts() {
	echo '<link rel="preload" href="' . esc_url( get_theme_file_uri( 'assets/fonts/BabyDoll-Regular.woff2' ) ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url( get_theme_file_uri( 'assets/fonts/AcuminProCondensed-Regular.woff2' ) ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action( 'wp_head', 'blondish_preload_fonts', 1 );


/* ==========================================================================
  16. STICKY MOBILE CTA — "Buy Tickets" bar on mobile
   Shows on front page and single event pages.
   ========================================================================== */

function blondish_sticky_cta() {
	// On homepage, scroll to inline Seated widget; on other pages, link to /tour/
	$is_home    = is_front_page();
	$ticket_url = $is_home ? '#tour-dates' : home_url( '/tour/' );

	echo '<div class="sticky-cta-bar" aria-label="Buy tickets">';
	echo '<a href="' . esc_url( $ticket_url ) . '" class="sticky-cta-bar__button"' . ( $is_home ? ' data-scroll-to="tour-dates"' : '' ) . '>GET TICKETS</a>';
	echo '</div>';
}
add_action( 'wp_footer', 'blondish_sticky_cta' );

function blondish_enqueue_sticky_cta() {
	wp_enqueue_script(
		'blondish-sticky-cta',
		get_template_directory_uri() . '/assets/js/sticky-cta.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'blondish_enqueue_sticky_cta' );


/* ==========================================================================
  17. FAQ SCHEMA — FAQPage JSON-LD for key pages
   ========================================================================== */

function blondish_faq_schema() {
	$faqs = [];

	if ( is_front_page() ) {
		$faqs = [
			[
				'q' => 'Who is BLOND:ISH?',
				'a' => 'BLOND:ISH is the stage name of Vivie-Ann Bakos, a Montreal-born DJ and music producer. She is known for melodic house, Afro house, and melodic techno sets that blend spirituality with dancefloor energy. She holds Ibiza residencies and has performed at Coachella, Burning Man, Tomorrowland, and major festivals worldwide. Also commonly searched as Blondeish or Bondish.',
			],
			[
				'q' => 'What is BLOND:ISH\'s real name?',
				'a' => 'BLOND:ISH\'s real name is Vivie-Ann Bakos. She is a Canadian DJ and producer born in Montreal, Quebec, Canada. She performs solo under the name BLOND:ISH (also spelled Blondish or Blond:ish).',
			],
			[
				'q' => 'What genre is BLOND:ISH?',
				'a' => 'BLOND:ISH produces and performs melodic house, Afro house, melodic techno, organic house, and deep house. Her sound blends world music influences with electronic production, creating a distinctive spiritual dancefloor experience that spans from downtempo yoga sets to peak-time festival performances.',
			],
			[
				'q' => 'Where can I see BLOND:ISH live?',
				'a' => 'BLOND:ISH tours internationally with regular appearances in Ibiza, Miami, Los Angeles, New York, Berlin, London, and at major festivals. Visit the Tour page at blondish.world/tour for upcoming dates and ticket links.',
			],
			[
				'q' => 'What is Abracadabra?',
				'a' => 'Abracadabra is BLOND:ISH\'s event concept and community — a space where music, art, and consciousness converge. Born from the belief that the dancefloor can be a place of genuine transformation, Abracadabra events feature immersive experiences in cities like New York, Miami, and Ibiza. Abracadabra NYC is one of the most popular recurring events.',
			],
			[
				'q' => 'Is BLOND:ISH a DJ or a band?',
				'a' => 'BLOND:ISH is a solo DJ and music producer — not a band. The project is the work of Vivie-Ann Bakos, who performs, produces, and creates events as a solo artist. She was previously part of a duo but has been a solo act since 2022.',
			],
			[
				'q' => 'Where is BLOND:ISH from?',
				'a' => 'BLOND:ISH (Vivie-Ann Bakos) was born and raised in Montreal, Canada. She is now an international touring DJ based between Ibiza and various global locations, performing at venues and festivals across North America, Europe, South America, and beyond.',
			],
		];
	}

	if ( is_post_type_archive( 'blondish_event' ) ) {
		$faqs = [
			[
				'q' => 'How do I buy BLOND:ISH tickets?',
				'a' => 'BLOND:ISH tickets are available through the official tour page at blondish.world/tour. Each event listing includes a direct link to purchase tickets through the authorized ticket provider. Early purchase is recommended as shows frequently sell out.',
			],
			[
				'q' => 'Does BLOND:ISH have an Ibiza residency?',
				'a' => 'Yes, BLOND:ISH is a regular Ibiza resident performing at leading venues on the island including Pacha, Hï Ibiza, DC-10, and Amnesia during the summer season. Check the tour dates page for upcoming Ibiza appearances and ticket availability.',
			],
			[
				'q' => 'What festivals does BLOND:ISH play?',
				'a' => 'BLOND:ISH has performed at Coachella, Burning Man, Tomorrowland, BPM Festival, Zamna Tulum, Art Basel Miami, and many other major electronic music festivals worldwide. Festival appearances are listed on the tour dates page.',
			],
			[
				'q' => 'Where does BLOND:ISH play in New York?',
				'a' => 'BLOND:ISH regularly performs in New York City, including Abracadabra NYC events, Brooklyn Mirage, Avant Gardner, and other leading venues. See the tour page for upcoming NYC dates and Abracadabra New York events.',
			],
			[
				'q' => 'Does BLOND:ISH play in Miami?',
				'a' => 'Yes, BLOND:ISH frequently performs in Miami, especially during Miami Music Week and Art Basel. Past appearances include venues like Club Space, Mila, and Do Not Sit on the Furniture. Check tour dates for BLOND:ISH Miami shows.',
			],
			[
				'q' => 'Does BLOND:ISH play in Los Angeles?',
				'a' => 'Yes, BLOND:ISH regularly performs in Los Angeles at venues and festivals throughout the year. Check the tour dates page for upcoming BLOND:ISH LA shows and Abracadabra Los Angeles events.',
			],
		];
	}

	if ( is_post_type_archive( 'blondish_release' ) ) {
		$faqs = [
			[
				'q' => 'Where can I stream BLOND:ISH music?',
				'a' => 'BLOND:ISH music is available on all major streaming platforms including Spotify, Apple Music, SoundCloud, Beatport, and Bandcamp. Each release page on blondish.world includes direct streaming links to all platforms.',
			],
			[
				'q' => 'What label is BLOND:ISH on?',
				'a' => 'BLOND:ISH has released music on several respected labels in the electronic music world. Individual release pages on the discography show the specific label for each track, EP, and album.',
			],
		];
	}

	if ( empty( $faqs ) ) {
		return;
	}

	$schema = [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => [],
	];

	foreach ( $faqs as $faq ) {
		$schema['mainEntity'][] = [
			'@type' => 'Question',
			'name'  => $faq['q'],
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $faq['a'],
			],
		];
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_faq_schema', 5 );


/* ==========================================================================
  18. SPEAKABLE SCHEMA — Helps voice assistants cite key content
   ========================================================================== */

function blondish_speakable_schema() {
	if ( ! is_front_page() && ! is_singular() ) {
		return;
	}

	$schema = [
		'@context'  => 'https://schema.org',
		'@type'     => 'WebPage',
		'name'      => wp_get_document_title(),
		'url'       => is_front_page() ? home_url( '/' ) : get_permalink(),
		'speakable' => [
			'@type'    => 'SpeakableSpecification',
			'cssSelector' => [
				'.homepage-section h2',
				'.homepage-section p',
				'.event-meta',
				'.release-streaming__heading',
				'.wp-block-post-title',
				'.event-seo-summary',
			],
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_speakable_schema', 5 );


/* ==========================================================================
  19. BREADCRUMB SCHEMA — BreadcrumbList JSON-LD
   ========================================================================== */

function blondish_breadcrumb_json_ld() {
	$items = [];
	$position = 1;
	$site_url = home_url();

	$items[] = [
		'@type'    => 'ListItem',
		'position' => $position++,
		'name'     => 'Home',
		'item'     => $site_url . '/',
	];

	if ( is_singular( 'blondish_event' ) ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Tour',
			'item'     => $site_url . '/tour/',
		];
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
		];
	} elseif ( is_singular( 'blondish_release' ) ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Music',
			'item'     => $site_url . '/music/',
		];
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
		];
	} elseif ( is_singular( 'blondish_media' ) ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Features',
			'item'     => $site_url . '/features/',
		];
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
		];
	} elseif ( is_singular( 'post' ) ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'NRG Zine',
			'item'     => $site_url . '/journal/',
		];
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => get_the_title(),
		];
	} else {
		return; // No breadcrumbs for other pages
	}

	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'blondish_breadcrumb_json_ld', 5 );

/* ==========================================================================
   11. YOAST SEO META — EXPOSE VIA REST API
   Registers Yoast's meta fields so the Social SEO agent can read/write them.
   ========================================================================== */

function blondish_register_yoast_meta_rest() {
	$yoast_keys = [
		'_yoast_wpseo_title',
		'_yoast_wpseo_metadesc',
		'_yoast_wpseo_focuskw',
		'_yoast_wpseo_linkdex',
		'_yoast_wpseo_content_score',
	];
	foreach ( [ 'page', 'post', 'blondish_release', 'blondish_event', 'blondish_media' ] as $type ) {
		foreach ( $yoast_keys as $key ) {
			register_post_meta( $type, $key, [
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
			] );
		}
	}
}
add_action( 'init', 'blondish_register_yoast_meta_rest' );

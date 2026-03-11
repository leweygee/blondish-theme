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
	$GLOBALS['content_width'] = 1200;
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
	if ( is_front_page() || is_singular( 'blondish_event' ) ) {
		wp_enqueue_style(
			'blondish-events',
			get_template_directory_uri() . '/assets/css/events.css',
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
	}
}
add_action( 'wp_enqueue_scripts', 'blondish_scripts' );


/* ==========================================================================
   4. HERO IMAGE PRELOADING (LCP OPTIMISATION)
   Preloads the largest above-the-fold image so the browser fetches it
   immediately, before the CSS/JS render chain fires.
   ========================================================================== */

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
		$hero = get_template_directory_uri() . '/assets/images/hero-abracadabra-1920.webp';
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

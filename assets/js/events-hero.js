/**
 * BLOND:ISH — Events Hero
 * assets/js/events-hero.js
 *
 * Adds scroll shadow indicators to the event list in the hero section.
 * Classes .has-scroll-top / .has-scroll-bottom drive CSS mask-image fades.
 */

( function () {
	'use strict';

	function initScrollIndicators() {
		var list = document.querySelector( '.events-hero__list' );
		if ( ! list ) return;

		function updateIndicators() {
			var scrollTop    = list.scrollTop;
			var scrollHeight = list.scrollHeight;
			var clientHeight = list.clientHeight;
			var threshold    = 8; // px of slack

			list.classList.toggle( 'has-scroll-top', scrollTop > threshold );
			list.classList.toggle( 'has-scroll-bottom', scrollTop + clientHeight < scrollHeight - threshold );
		}

		// Initial check
		updateIndicators();

		// Update on scroll (passive for performance)
		list.addEventListener( 'scroll', updateIndicators, { passive: true } );

		// Recheck on window resize
		window.addEventListener( 'resize', updateIndicators, { passive: true } );
	}

	// Run on DOMContentLoaded
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initScrollIndicators );
	} else {
		initScrollIndicators();
	}
} )();

/**
 * BLOND:ISH — Lazy Embed Loader
 * assets/js/embeds.js
 *
 * Uses IntersectionObserver to defer loading Seated.com and Shopify Buy Button
 * scripts until their container scrolls into the viewport.
 *
 * Why: Both scripts are heavy and trigger long tasks that hurt INP and TBT.
 * Loading them only when visible means they never block the initial page load.
 *
 * ─── SETUP REQUIRED ──────────────────────────────────────────────────────────
 *
 * SEATED:
 *   1. Log into your Seated account → Settings → Get your Artist ID
 *   2. Replace REPLACE_SEATED_ARTIST_ID below with your real ID (e.g. "abc123")
 *
 * SHOPIFY:
 *   1. Shopify Admin → Sales Channels → Buy Button
 *   2. Create a collection buy button for your merch collection
 *   3. Copy the generated JS snippet — it will look like:
 *        ShopifyBuy.UI.onReady(client).then(function(ui) { ui.createComponent(...) });
 *   4. Paste the full snippet inside the initShopifyBuyButton() function below,
 *      replacing the console.warn placeholder.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 */

( function () {
	'use strict';

	/* =========================================================================
	   HELPER — create an IntersectionObserver that fires a callback once,
	   when the target element enters the viewport (with rootMargin buffer).
	   ========================================================================= */
	function onVisible( element, callback, rootMargin ) {
		if ( ! element ) return;

		var observer = new IntersectionObserver(
			function ( entries, obs ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						obs.disconnect();
						callback();
					}
				} );
			},
			{ rootMargin: rootMargin || '200px 0px' } // start loading 200px before visible
		);

		observer.observe( element );
	}

	/* =========================================================================
	   SEATED.COM — Tour page embed
	   The placeholder div must have id="seated-placeholder" in your
	   Custom HTML block on the Tour page.

	   Block HTML to paste in WordPress:
	   <div id="seated-placeholder" style="min-height:400px;"></div>
	   ========================================================================= */
	( function () {
		var el = document.getElementById( 'seated-placeholder' );
		if ( ! el ) return; // not on Tour page — bail immediately

		onVisible( el, function () {
			var script = document.createElement( 'script' );
			script.src = 'https://cdn.seated.com/app/seated.js';

			// ⚠️  REPLACE THIS with your real Seated Artist ID
			script.setAttribute( 'data-artist-id', 'REPLACE_SEATED_ARTIST_ID' );
			script.setAttribute( 'data-css-version', '1' );

			document.head.appendChild( script );
		} );
	} )();

	/* =========================================================================
	   SHOPIFY BUY BUTTON — Store page embed
	   The placeholder div must have id="shopify-placeholder" in your
	   Custom HTML block on the Store page.

	   Block HTML to paste in WordPress:
	   <div id="shopify-placeholder" style="min-height:400px;"></div>
	   ========================================================================= */
	( function () {
		var el = document.getElementById( 'shopify-placeholder' );
		if ( ! el ) return; // not on Store page — bail immediately

		onVisible( el, function () {
			// Load the Shopify Buy Button SDK first, then init
			var sdkScript = document.createElement( 'script' );
			sdkScript.src = 'https://sdks.shopifycdn.com/buy-button/latest/buy-button-storefront.min.js';

			sdkScript.onload = function () {
				initShopifyBuyButton();
			};

			document.head.appendChild( sdkScript );
		} );
	} )();

	/* =========================================================================
	   SHOPIFY INIT FUNCTION
	   Paste your generated Shopify Buy Button code here.
	   ========================================================================= */
	function initShopifyBuyButton() {
		/*
		 * ⚠️  REPLACE THIS ENTIRE BLOCK with your Shopify Buy Button snippet.
		 *
		 * How to get it:
		 *   1. Shopify Admin → Sales Channels → Buy Button
		 *   2. Create button → choose your Collection
		 *   3. Copy the JS embed code Shopify generates
		 *   4. Paste it here, replacing this comment
		 *
		 * Example structure (yours will have real IDs):
		 *
		 *   var client = ShopifyBuy.buildClient({
		 *     domain: 'your-shop.myshopify.com',
		 *     storefrontAccessToken: 'YOUR_STOREFRONT_TOKEN',
		 *   });
		 *   ShopifyBuy.UI.onReady(client).then(function(ui) {
		 *     ui.createComponent('collection', {
		 *       id: 'YOUR_COLLECTION_ID',
		 *       node: document.getElementById('shopify-placeholder'),
		 *       ...
		 *     });
		 *   });
		 */
		console.warn( 'BLOND:ISH Shopify: paste your Buy Button init code into initShopifyBuyButton() in embeds.js' );
	}

} )();

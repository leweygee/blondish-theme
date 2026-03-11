<?php
/**
 * Title: Hero Cover
 * Slug: blondish/hero-cover
 * Categories: blondish-heroes
 * Description: Full-viewport hero with background image, heading, subtitle and CTAs.
 */
?>

<!-- wp:cover {"url":"REPLACE_IMAGE_URL","dimRatio":40,"minHeight":95,"minHeightUnit":"vh","isDark":true,"align":"full","className":"is-homepage-hero","style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|2xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"}}},"layout":{"type":"constrained","contentSize":"900px","justifyContent":"center"}} -->
<div class="wp-block-cover alignfull is-dark is-homepage-hero" style="min-height:95vh;padding-top:0;padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--2xl);padding-left:var(--wp--preset--spacing--md)">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-40 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="REPLACE_IMAGE_URL" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em"}},"fontSize":"display","fontFamily":"heading"} -->
		<h1 class="wp-block-heading has-text-align-center has-display-font-size has-heading-font-family">Heading</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.15em"}},"textColor":"light-grey","fontSize":"md","fontFamily":"heading"} -->
		<p class="has-text-align-center has-light-grey-color has-text-color has-md-font-size has-heading-font-family">Subtitle text</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|sm","margin":{"top":"var:preset|spacing|lg"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--lg)">
			<!-- wp:button {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
			<div class="wp-block-button has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="#">Primary CTA</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-outline","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
			<div class="wp-block-button is-style-outline has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="#">Secondary CTA</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
</div>
<!-- /wp:cover -->

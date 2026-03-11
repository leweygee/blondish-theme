<?php
/**
 * Title: Embed Section
 * Slug: blondish/embed-section
 * Categories: blondish-sections
 * Description: Full-width section with heading and placeholder for third-party embed (Seated, Shopify, etc).
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2xl","bottom":"var:preset|spacing|2xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"blockGap":"var:preset|spacing|lg"},"border":{"top":{"color":"var:preset|color|dark-grey","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="border-top-color:var(--wp--preset--color--dark-grey);border-top-width:1px;padding-top:var(--wp--preset--spacing--2xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--2xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"xl","fontFamily":"heading"} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family">Section Heading</h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div id="embed-placeholder" aria-label="Loading external content">
		<!-- Third-party embed loads here via JavaScript -->
	</div>
	<!-- /wp:html -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--lg)">
		<!-- wp:button {"className":"is-style-outline","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
		<div class="wp-block-button is-style-outline has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="#">View All</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->

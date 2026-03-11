<?php
/**
 * Title: Text Section with CTA
 * Slug: blondish/section-text-cta
 * Categories: blondish-sections
 * Description: Centered text section with heading, paragraph, and outline CTA button.
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2xl","bottom":"var:preset|spacing|2xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"blockGap":"var:preset|spacing|md"},"border":{"top":{"color":"var:preset|color|dark-grey","width":"1px"}}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group alignfull" style="border-top-color:var(--wp--preset--color--dark-grey);border-top-width:1px;padding-top:var(--wp--preset--spacing--2xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--2xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"xl","fontFamily":"heading"} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family">Section Heading</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"light-grey","fontSize":"md"} -->
	<p class="has-light-grey-color has-text-color has-md-font-size">Add your section text here. This pattern works for about teasers, project descriptions, or any text-heavy section that needs a call to action.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"className":"is-style-outline","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
		<div class="wp-block-button is-style-outline has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="#">Learn More</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->

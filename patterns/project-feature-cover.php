<?php
/**
 * Title: Project Feature Cover
 * Slug: blondish/project-feature-cover
 * Categories: blondish-sections
 * Description: Full-width cover block for featuring a project with image background, heading, description and CTA.
 */
?>

<!-- wp:cover {"url":"REPLACE_PROJECT_IMAGE_URL","dimRatio":60,"minHeight":70,"minHeightUnit":"vh","isDark":true,"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|3xl","bottom":"var:preset|spacing|3xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"}},"border":{"top":{"color":"var:preset|color|dark-grey","width":"1px"}}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-cover alignfull is-dark" style="border-top-color:var(--wp--preset--color--dark-grey);border-top-width:1px;min-height:70vh;padding-top:var(--wp--preset--spacing--3xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--3xl);padding-left:var(--wp--preset--spacing--md)">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="REPLACE_PROJECT_IMAGE_URL" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}},"fontSize":"2xl","fontFamily":"heading"} -->
		<h2 class="wp-block-heading has-text-align-center has-2xl-font-size has-heading-font-family">Project Name</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"light-grey","fontSize":"md"} -->
		<p class="has-text-align-center has-light-grey-color has-text-color has-md-font-size">Brief project description goes here.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
			<!-- wp:button {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
			<div class="wp-block-button has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="#">Explore Project</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
</div>
<!-- /wp:cover -->

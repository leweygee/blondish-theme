<?php
/**
 * Title: Journal Grid
 * Slug: blondish/journal-grid
 * Categories: blondish-sections
 * Description: 3-column grid of latest posts with featured image, date, title and excerpt.
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2xl","bottom":"var:preset|spacing|2xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"blockGap":"var:preset|spacing|lg"},"border":{"top":{"color":"var:preset|color|dark-grey","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="border-top-color:var(--wp--preset--color--dark-grey);border-top-width:1px;padding-top:var(--wp--preset--spacing--2xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--2xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"xl","fontFamily":"heading"} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family">From the Journal</h2>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":10,"query":{"perPage":3,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|sm"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","style":{"border":{"radius":"0px"}}} /-->
				<!-- wp:post-date {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"textColor":"light-grey","fontSize":"xs","fontFamily":"heading"} /-->
				<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.04em"}},"fontSize":"md","fontFamily":"heading"} /-->
				<!-- wp:post-excerpt {"moreText":"","excerptLength":20,"textColor":"light-grey","fontSize":"sm"} /-->
			</div>
			<!-- /wp:group -->

		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"className":"is-style-outline","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}},"fontSize":"sm","fontFamily":"heading"} -->
		<div class="wp-block-button is-style-outline has-custom-font-size has-sm-font-size has-heading-font-family"><a class="wp-block-button__link wp-element-button" href="/journal/">Read the Journal</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->

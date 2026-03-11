<?php
/**
 * Title: Single Event Meta
 * Slug: blondish/single-event-meta
 * Categories: blondish-sections
 * Description: Displays event date, venue, city, and ticket button on single event pages.
 * Inserter: no
 */

if ( ! is_singular( 'blondish_event' ) ) {
	return;
}

$post_id    = get_the_ID();
$date       = get_post_meta( $post_id, '_blondish_event_date', true );
$end_date   = get_post_meta( $post_id, '_blondish_event_end_date', true );
$venue      = get_post_meta( $post_id, '_blondish_event_venue', true );
$city       = get_post_meta( $post_id, '_blondish_event_city', true );
$country    = get_post_meta( $post_id, '_blondish_event_country', true );
$ticket_url = get_post_meta( $post_id, '_blondish_event_ticket_url', true );
$is_past    = $date && strtotime( $date ) < time();
?>

<!-- wp:html -->
<div class="event-meta">
	<?php if ( $date ) : ?>
	<div class="event-meta__row">
		<span class="event-meta__icon" aria-hidden="true">📅</span>
		<div class="event-meta__detail">
			<time datetime="<?php echo esc_attr( date( 'c', strtotime( $date ) ) ); ?>">
				<?php echo esc_html( date_i18n( 'l, F j, Y — g:i A', strtotime( $date ) ) ); ?>
			</time>
			<?php if ( $end_date ) : ?>
				<span class="event-meta__end-date">
					— <?php echo esc_html( date_i18n( 'F j, Y — g:i A', strtotime( $end_date ) ) ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $venue ) : ?>
	<div class="event-meta__row">
		<span class="event-meta__icon" aria-hidden="true">📍</span>
		<div class="event-meta__detail">
			<strong><?php echo esc_html( $venue ); ?></strong>
			<?php if ( $city ) : ?>
				<br>
				<?php
				echo esc_html( $city );
				if ( $country ) {
					echo ', ' . esc_html( strtoupper( $country ) );
				}
				?>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $is_past ) : ?>
	<div class="event-meta__past-notice">
		This event has passed.
	</div>
	<?php elseif ( $ticket_url ) : ?>
	<div class="event-meta__cta">
		<a href="<?php echo esc_url( $ticket_url ); ?>"
		   class="wp-block-button__link wp-element-button"
		   target="_blank"
		   rel="noopener noreferrer">
			Get Tickets
		</a>
	</div>
	<?php endif; ?>
</div>
<!-- /wp:html -->

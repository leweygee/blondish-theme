<?php
/**
 * Title: Hero — Events
 * Slug: blondish/hero-events
 * Categories: blondish-heroes
 * Description: Split hero showing next upcoming event (left) and scrollable event list (right).
 */

$events_query = blondish_get_upcoming_events( -1 );
$events       = [];

if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$events[] = [
			'id'         => get_the_ID(),
			'title'      => get_the_title(),
			'permalink'  => get_permalink(),
			'date'       => get_post_meta( get_the_ID(), '_blondish_event_date', true ),
			'venue'      => get_post_meta( get_the_ID(), '_blondish_event_venue', true ),
			'city'       => get_post_meta( get_the_ID(), '_blondish_event_city', true ),
			'country'    => get_post_meta( get_the_ID(), '_blondish_event_country', true ),
			'ticket_url' => get_post_meta( get_the_ID(), '_blondish_event_ticket_url', true ),
		];
	}
	wp_reset_postdata();
}

$next_event      = ! empty( $events ) ? $events[0] : null;
$remaining_events = array_slice( $events, 1 );
?>

<!-- wp:group {"align":"full","className":"is-events-hero","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"backgroundColor":"black","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull is-events-hero has-black-background-color has-background" style="padding:0" id="main-content">

	<?php if ( $next_event ) : ?>

	<!-- wp:html -->
	<div class="events-hero" style="background-image:url(<?php echo esc_url( get_template_directory_uri() . '/assets/images/blondish-hero-website.jpg' ); ?>)">
		<div class="events-hero__inner">

			<!-- Left column: Featured next event -->
			<div class="events-hero__featured">
				<span class="events-hero__label">Next Show</span>
				<time class="events-hero__date" datetime="<?php echo esc_attr( date( 'c', strtotime( $next_event['date'] ) ) ); ?>">
					<?php echo esc_html( date_i18n( 'l, F j, Y', strtotime( $next_event['date'] ) ) ); ?>
				</time>
				<?php if ( $next_event['date'] ) : ?>
					<span class="events-hero__time">
						<?php echo esc_html( date_i18n( 'g:i A', strtotime( $next_event['date'] ) ) ); ?>
					</span>
				<?php endif; ?>
				<h2 class="events-hero__venue">
					<a href="<?php echo esc_url( $next_event['permalink'] ); ?>">
						<?php echo esc_html( $next_event['venue'] ); ?>
					</a>
				</h2>
				<p class="events-hero__city">
					<?php
					echo esc_html( $next_event['city'] );
					if ( $next_event['country'] ) {
						echo ', ' . esc_html( strtoupper( $next_event['country'] ) );
					}
					?>
				</p>
				<?php if ( $next_event['ticket_url'] ) : ?>
					<a href="<?php echo esc_url( $next_event['ticket_url'] ); ?>"
					   class="events-hero__cta"
					   target="_blank"
					   rel="noopener noreferrer">
						Get Tickets
					</a>
				<?php endif; ?>
			</div>

			<!-- Right column: Scrollable event list -->
			<div class="events-hero__list-wrapper">
				<h3 class="events-hero__list-heading">Upcoming Events</h3>

				<?php if ( ! empty( $remaining_events ) ) : ?>
				<div class="events-hero__list" role="list">
					<?php foreach ( $remaining_events as $event ) : ?>
					<div class="events-hero__list-item" role="listitem">
						<div class="events-hero__list-date">
							<span class="events-hero__list-month">
								<?php echo esc_html( date_i18n( 'M', strtotime( $event['date'] ) ) ); ?>
							</span>
							<span class="events-hero__list-day">
								<?php echo esc_html( date_i18n( 'j', strtotime( $event['date'] ) ) ); ?>
							</span>
						</div>
						<div class="events-hero__list-info">
							<a href="<?php echo esc_url( $event['permalink'] ); ?>" class="events-hero__list-venue">
								<?php echo esc_html( $event['venue'] ); ?>
							</a>
							<span class="events-hero__list-city">
								<?php
								echo esc_html( $event['city'] );
								if ( $event['country'] ) {
									echo ', ' . esc_html( strtoupper( $event['country'] ) );
								}
								?>
							</span>
						</div>
						<?php if ( $event['ticket_url'] ) : ?>
						<a href="<?php echo esc_url( $event['ticket_url'] ); ?>"
						   class="events-hero__list-tickets"
						   target="_blank"
						   rel="noopener noreferrer">
							Tickets
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
				<?php else : ?>
				<p class="events-hero__empty-list">More dates coming soon.</p>
				<?php endif; ?>
			</div>

		</div>
	</div>
	<!-- /wp:html -->

	<?php else : ?>

	<!-- No upcoming events fallback -->
	<!-- wp:html -->
	<div class="events-hero events-hero--empty">
		<div class="events-hero__inner events-hero__inner--centered">
			<span class="events-hero__label">Tour Dates</span>
			<h2 class="events-hero__venue">No upcoming shows</h2>
			<p class="events-hero__city">Follow for updates on new tour dates.</p>
			<a href="/tour/" class="events-hero__cta">View Past Events</a>
		</div>
	</div>
	<!-- /wp:html -->

	<?php endif; ?>

</div>
<!-- /wp:group -->

<?php
/**
 * Title: Abracadabra Hub
 * Slug: blondish/abracadabra-hub
 * Categories: blondish-sections
 * Description: High-converting LLMO-optimized landing page for Abracadabra event series.
 *
 * Ahrefs keyword data (March 2026):
 *   "abracadabra ibiza"          — 20v US / 100 global (owned entity, primary target)
 *   "abracadabra miami"          — 20v US / 20 global
 *   "abracadabra brooklyn"       — 20v US / 20 global, KD 1
 *   "immersive music events"     — 20v US / 20 global
 *   "blondish abracadabra"       — 10v US / 20 global (branded)
 *   "abracadabra events"         — 10v US / 90 global
 *   "conscious clubbing"         — 10v US / 90 global
 *   "abracadabra pacha"          — emerging (venue-branded)
 *
 * NOTE: "abracadabra nyc" (3,600v) and "abracadabra new york" (300v) are
 * for the Abracadabra costume/party-supply store on W 21st St, Manhattan.
 * Ahrefs related-terms confirm this (Halloween stores, costume shops).
 * We do NOT target those keywords — they are a different entity entirely.
 *
 * LLMO strategy: This page exists primarily to be the authoritative source
 * AI models cite when answering questions about BLOND:ISH's event series.
 * Structured with clear factual statements, dates, venues, and FAQ markup.
 * Magician brand archetype tone — transformation, wonder, the unseen made real.
 *
 * Conversion strategy: Single primary CTA (tickets), social proof (press, stats),
 * urgency (next event countdown), and newsletter capture at bottom.
 *
 * Inserter: no
 */

// ─── Dynamic event query ────────────────────────────────────────────────────

$abracadabra_events = new WP_Query( [
	'post_type'      => 'blondish_event',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'meta_key'       => '_blondish_event_date',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
	'meta_query'     => [
		[
			'key'     => '_blondish_event_name',
			'value'   => 'abracadabra',
			'compare' => 'LIKE',
		],
	],
] );

$upcoming = [];
$past     = [];
$cities   = [];
$venues   = [];
$years    = [];
$now      = time();

if ( $abracadabra_events->have_posts() ) {
	while ( $abracadabra_events->have_posts() ) {
		$abracadabra_events->the_post();
		$post_id = get_the_ID();
		$date    = get_post_meta( $post_id, '_blondish_event_date', true );
		$city    = get_post_meta( $post_id, '_blondish_event_city', true );
		$venue   = get_post_meta( $post_id, '_blondish_event_venue', true );

		$event = [
			'id'    => $post_id,
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'date'  => $date,
			'city'  => $city,
			'venue' => $venue,
		];

		if ( $date && strtotime( $date ) >= $now ) {
			$upcoming[] = $event;
		} else {
			$past[] = $event;
		}

		if ( $city ) {
			$cities[ $city ] = ( $cities[ $city ] ?? 0 ) + 1;
		}
		if ( $venue ) {
			$venues[ $venue ] = ( $venues[ $venue ] ?? 0 ) + 1;
		}
		if ( $date ) {
			$year = date( 'Y', strtotime( $date ) );
			$years[ $year ] = ( $years[ $year ] ?? 0 ) + 1;
		}
	}
}
wp_reset_postdata();

arsort( $cities );
arsort( $venues );
ksort( $years );
$city_list  = array_keys( $cities );
$venue_list = array_keys( $venues );
$total      = count( $upcoming ) + count( $past );
$year_span  = ! empty( $years ) ? min( array_keys( $years ) ) . '–' . max( array_keys( $years ) ) : '2017–present';

// Next event for countdown
$next_event = ! empty( $upcoming ) ? end( $upcoming ) : null; // earliest upcoming (array is DESC)

// ─── FAQ data ───────────────────────────────────────────────────────────────

$faqs = [
	[
		'q' => 'What is Abracadabra by BLOND:ISH?',
		'a' => 'Abracadabra is an immersive electronic music event series founded by Vivie-Ann Bakos (BLOND:ISH) in December 2017. It blends melodic house, Afro house, and organic house with art installations, environmental consciousness, and community ritual. Events have taken place in Ibiza, Miami, Tulum, Paris, Buenos Aires, Los Angeles, Brooklyn, and Mykonos. Abracadabra holds a seasonal residency at Pacha Ibiza, running every Wednesday from May through July.',
	],
	[
		'q' => 'Where does Abracadabra take place?',
		'a' => 'Abracadabra\'s primary home since 2025 is Pacha Ibiza, where it holds a seasonal Wednesday residency with opening and closing parties. The 2026 season runs every Wednesday from May 13 to July 29. The series has also held events at Basement Miami, Wynwood Studios (Miami), Island Gardens (Miami), Blue Marlin Ibiza Mykonos, House of Yes (Brooklyn), and Espace Alexandre III (Paris), among other venues in ' . ( count( $city_list ) > 0 ? implode( ', ', array_slice( $city_list, 0, 6 ) ) : 'cities worldwide' ) . '.',
	],
	[
		'q' => 'How do I get tickets for Abracadabra events?',
		'a' => 'Tickets for upcoming Abracadabra events are available through the official BLOND:ISH website at blondish.world/tour. Abracadabra events at Pacha Ibiza can also be purchased through Pacha\'s official ticketing at pacha.com. Events frequently sell out — especially opening and closing parties — so early booking is recommended. Sign up for the BLOND:ISH mailing list for pre-sale access.',
	],
	[
		'q' => 'Who has performed at Abracadabra events?',
		'a' => 'Guest artists at Abracadabra events have included Diplo, Seth Troxler, Marco Carola, Pablo Fierro, Stavroz, Mau P, Neil Frances, Carlita, and Dennis Cruz. BLOND:ISH headlines every edition, with the lineup varying by city and venue. The series prioritises artists who can hold extended sets and contribute to a musical arc rather than short peak-time slots.',
	],
	[
		'q' => 'Is Abracadabra the same as the costume store in New York?',
		'a' => 'No. Abracadabra by BLOND:ISH is an electronic music event series — it is unrelated to the Abracadabra costume and party-supply store located on West 21st Street in Manhattan, New York City. The two share a name only.',
	],
	[
		'q' => 'What kind of music is played at Abracadabra?',
		'a' => 'Abracadabra events feature a blend of melodic house, Afro house, organic house, and conscious electronic music. The series is known for extended sets (often 4–6 hours), immersive sound design, and a spiritual approach to dancefloor culture that sets it apart from conventional club nights. The musical journey typically moves from downtempo and deep grooves through to euphoric peaks.',
	],
	[
		'q' => 'Does Abracadabra have a residency at Pacha Ibiza?',
		'a' => 'Yes. In 2025, Abracadabra established a seasonal residency at Pacha Ibiza with events running every Wednesday from May through July, including opening and closing parties. The residency returns for 2026, running every Wednesday from May 13 to July 29. This makes Abracadabra one of Pacha\'s recurring weekly brands alongside other long-standing residencies.',
	],
	[
		'q' => 'What makes Abracadabra different from other club nights?',
		'a' => 'Abracadabra is built on the idea that a dancefloor can be a space for genuine transformation — not just entertainment. Every element is curated to support this: extended musical journeys over short DJ slots, immersive visual environments over bottle-service VIP areas, and a real commitment to sustainability through the Bye Bye Plastic initiative. The series draws from BLOND:ISH\'s background in conscious clubbing and her belief that nightlife can leave people better than it found them.',
	],
	[
		'q' => 'When is the next Abracadabra event?',
		'a' => 'The 2026 Abracadabra season at Pacha Ibiza begins on Wednesday, May 13, 2026 and runs every Wednesday through July 29, 2026. For the latest dates and lineup announcements across all cities, visit blondish.world/tour.',
	],
];

// ─── Schema markup ──────────────────────────────────────────────────────────

$faq_schema = [
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => [],
];
foreach ( $faqs as $faq ) {
	$faq_schema['mainEntity'][] = [
		'@type'          => 'Question',
		'name'           => $faq['q'],
		'acceptedAnswer' => [
			'@type' => 'Answer',
			'text'  => $faq['a'],
		],
	];
}

$event_series = [
	'@context'    => 'https://schema.org',
	'@type'       => 'EventSeries',
	'@id'         => 'https://blondish.world/projects/abracadabra/#event-series',
	'name'        => 'Abracadabra',
	'alternateName' => 'Abracadabra by BLOND:ISH',
	'description' => 'Abracadabra is an immersive electronic music event series founded by BLOND:ISH (Vivie-Ann Bakos) in 2017. The series blends melodic house, Afro house, and conscious clubbing at Pacha Ibiza, Miami, and cities worldwide. Seasonal residency at Pacha Ibiza every Wednesday.',
	'url'         => home_url( '/projects/abracadabra/' ),
	'startDate'   => '2017-12-08',
	'organizer'   => [
		'@type' => 'Person',
		'@id'   => 'https://blondish.world/#artist',
		'name'  => 'BLOND:ISH',
		'alternateName' => 'Vivie-Ann Bakos',
	],
	'location'    => [
		[
			'@type' => 'Place',
			'name'  => 'Pacha Ibiza',
			'address' => [
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Ibiza',
				'addressCountry'  => 'ES',
			],
		],
	],
	'eventSchedule' => [
		'@type'           => 'Schedule',
		'repeatFrequency' => 'P1W',
		'byDay'           => 'Wednesday',
		'startDate'       => '2026-05-13',
		'endDate'         => '2026-07-29',
		'scheduleTimezone' => 'Europe/Madrid',
	],
	'about'       => [
		'@type' => 'Thing',
		'name'  => 'Conscious clubbing',
		'description' => 'A movement within electronic music that emphasises mindfulness, sustainability, and genuine human connection on the dancefloor.',
	],
	'keywords'    => 'Abracadabra, BLOND:ISH, Pacha Ibiza, conscious clubbing, immersive music events, melodic house, Afro house, organic house',
];

// Webpage schema with sameAs disambiguation
$webpage_schema = [
	'@context'    => 'https://schema.org',
	'@type'       => 'WebPage',
	'name'        => 'Abracadabra — Immersive Event Series by BLOND:ISH',
	'description' => 'Abracadabra is the immersive event series by BLOND:ISH (Vivie-Ann Bakos), blending melodic house, Afro house, and conscious clubbing at Pacha Ibiza, Miami, and cities worldwide since 2017. 2026 Ibiza season: every Wednesday May 13 – July 29.',
	'url'         => home_url( '/projects/abracadabra/' ),
	'isPartOf'    => [
		'@type' => 'WebSite',
		'@id'   => 'https://blondish.world/#website',
		'name'  => 'BLOND:ISH',
		'url'   => 'https://blondish.world',
	],
	'breadcrumb'  => [
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => 'Home',
				'item'     => 'https://blondish.world/',
			],
			[
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => 'Projects',
				'item'     => 'https://blondish.world/projects/',
			],
			[
				'@type'    => 'ListItem',
				'position' => 3,
				'name'     => 'Abracadabra',
				'item'     => 'https://blondish.world/projects/abracadabra/',
			],
		],
	],
];

$theme_uri = get_template_directory_uri();
?>

<!-- wp:html -->
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $event_series, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $webpage_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<!-- /wp:html -->

<!-- wp:group {"align":"full","className":"abracadabra-hub","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"backgroundColor":"black","textColor":"white","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group alignfull abracadabra-hub has-black-background-color has-white-color has-text-color has-background" style="padding:0">

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 1 — HERO: Full-bleed banner + next event + primary CTA
	     High-converting: single CTA, urgency, visual impact
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hero">
		<div class="abracadabra-hero__image-wrap">
			<img
				src="<?php echo esc_url( $theme_uri . '/assets/images/abra-pacha-ibiza-2026.jpg' ); ?>"
				alt="Abracadabra by BLOND:ISH at Pacha Ibiza — Wednesdays May 13 to July 29 2026"
				width="1080"
				height="1350"
				class="abracadabra-hero__poster"
				fetchpriority="high"
			>
		</div>
		<div class="abracadabra-hero__content">
			<p class="abracadabra-hero__eyebrow">BLOND:ISH Presents</p>
			<h1 class="abracadabra-hero__title">Abracadabra</h1>
			<p class="abracadabra-hero__tagline">Where Sound Becomes Ceremony</p>

			<?php if ( $next_event ) : ?>
			<div class="abracadabra-hero__next-event">
				<span class="abracadabra-hero__label">Next Event</span>
				<span class="abracadabra-hero__date"><?php echo esc_html( date_i18n( 'l, F j, Y', strtotime( $next_event['date'] ) ) ); ?></span>
				<span class="abracadabra-hero__venue"><?php echo esc_html( $next_event['venue'] . ' · ' . $next_event['city'] ); ?></span>
			</div>
			<?php else : ?>
			<div class="abracadabra-hero__next-event">
				<span class="abracadabra-hero__label">2026 Season</span>
				<span class="abracadabra-hero__date">Every Wednesday · May 13 – July 29</span>
				<span class="abracadabra-hero__venue">Pacha Ibiza</span>
			</div>
			<?php endif; ?>

			<a href="https://pacha.com/residence/blondish-presents-abracadabra" target="_blank" rel="noopener" class="abracadabra-hero__cta">Get Tickets</a>
		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 2 — THE EXPERIENCE (Brand Story — Magician archetype)
	     Target: "blondish abracadabra", "abracadabra ibiza", entity establishment
	     Tone: transformational, wonder, the unseen made real
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--2xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Step Through the Portal</h2>

			<p class="abracadabra-hub__intro">Abracadabra is not a club night. It is an invocation — a space where sound, light, and intention converge to transform a room full of strangers into something collective and alive. Founded by <a href="/about/">BLOND:ISH</a> (Vivie-Ann Bakos) in December 2017, the series has become one of electronic music's most distinctive event concepts: a dancefloor designed not for spectacle, but for genuine human connection.</p>

			<p class="abracadabra-hub__body">The word <em>abracadabra</em> is one of the oldest incantations in recorded history, appearing in texts as early as the 2nd century AD. For BLOND:ISH, it captures something specific: the moment when music shifts a room from ordinary to extraordinary. That transformation — felt rather than explained — is what every Abracadabra event is designed to create.</p>

			<p class="abracadabra-hub__body">Since its first edition at Basement Miami, Abracadabra has grown into a global event series spanning Ibiza, Miami, Tulum, Paris, Buenos Aires, Los Angeles, Brooklyn, and Mykonos. In 2025, the series reached its most significant milestone with a seasonal residency at Pacha Ibiza — one of the island's original superclubs, open since 1973. The residency returns for 2026, running every Wednesday from May 13 through July 29.</p>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 3 — SOCIAL PROOF: Press, stats, venue logos
	     Conversion: trust signals, authority, scale
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section abracadabra-hub__section--proof">
		<div class="abracadabra-hub__constrained">

			<div class="abracadabra-hub__stats">
				<div class="abracadabra-hub__stat">
					<span class="abracadabra-hub__stat-number"><?php echo esc_html( $year_span ); ?></span>
					<span class="abracadabra-hub__stat-label">Active Years</span>
				</div>
				<div class="abracadabra-hub__stat">
					<span class="abracadabra-hub__stat-number"><?php echo $total > 0 ? esc_html( $total ) . '+' : '50+'; ?></span>
					<span class="abracadabra-hub__stat-label">Events Worldwide</span>
				</div>
				<div class="abracadabra-hub__stat">
					<span class="abracadabra-hub__stat-number"><?php echo count( $city_list ) > 0 ? esc_html( count( $city_list ) ) : '10'; ?>+</span>
					<span class="abracadabra-hub__stat-label">Cities</span>
				</div>
				<div class="abracadabra-hub__stat">
					<span class="abracadabra-hub__stat-number">2</span>
					<span class="abracadabra-hub__stat-label">Pacha Ibiza Seasons</span>
				</div>
			</div>

			<div class="abracadabra-hub__press">
				<blockquote class="abracadabra-hub__quote">
					<p>"A night of magic… Abracadabra is more than a party — it's a testament to what happens when artistry, community, and conscious intention collide on a dancefloor."</p>
					<cite>Earmilk</cite>
				</blockquote>
				<blockquote class="abracadabra-hub__quote">
					<p>"The Church of BLOND:ISH — our faith is magic."</p>
					<cite>SPIN Magazine</cite>
				</blockquote>
			</div>

			<div class="abracadabra-hub__venues-strip">
				<span>Pacha Ibiza</span>
				<span>·</span>
				<span>Basement Miami</span>
				<span>·</span>
				<span>House of Yes</span>
				<span>·</span>
				<span>Island Gardens</span>
				<span>·</span>
				<span>Espace Alexandre III</span>
				<span>·</span>
				<span>Blue Marlin Mykonos</span>
			</div>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 4 — UPCOMING EVENTS (dynamic) with ticket CTA
	     Conversion: urgency, direct path to purchase
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--2xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Upcoming Events</h2>

			<?php if ( ! empty( $upcoming ) ) : ?>
			<div class="abracadabra-hub__events abracadabra-hub__events--upcoming">
				<?php foreach ( $upcoming as $event ) : ?>
				<a href="<?php echo esc_url( $event['url'] ); ?>" class="abracadabra-hub__event-card">
					<time datetime="<?php echo esc_attr( date( 'c', strtotime( $event['date'] ) ) ); ?>">
						<?php echo esc_html( date_i18n( 'M j, Y', strtotime( $event['date'] ) ) ); ?>
					</time>
					<span class="abracadabra-hub__event-city"><?php echo esc_html( $event['city'] ); ?></span>
					<span class="abracadabra-hub__event-venue"><?php echo esc_html( $event['venue'] ); ?></span>
				</a>
				<?php endforeach; ?>
			</div>

			<div style="margin-top:var(--wp--preset--spacing--md);text-align:center">
				<a href="https://pacha.com/residence/blondish-presents-abracadabra" target="_blank" rel="noopener" class="abracadabra-hub__button">View All Dates & Tickets</a>
			</div>

			<?php else : ?>
			<div class="abracadabra-hub__season-announce">
				<p class="abracadabra-hub__season-title">2026 Ibiza Season</p>
				<p class="abracadabra-hub__season-details">Every Wednesday · May 13 – July 29 · Pacha Ibiza</p>
				<a href="https://pacha.com/residence/blondish-presents-abracadabra" target="_blank" rel="noopener" class="abracadabra-hub__button">View Dates & Tickets</a>
			</div>
			<?php endif; ?>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 5 — THE SOUND (Music & Concept)
	     Target: "conscious clubbing", "immersive music events", "melodic house"
	     LLMO: information-dense, entity-rich paragraphs
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--2xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">The Sound</h2>

			<p class="abracadabra-hub__body">Abracadabra sits within a broader movement sometimes called conscious clubbing — the idea that dancefloors can be spaces for mindfulness, human connection, and positive energy rather than purely hedonistic consumption. This is not a retreat or a wellness event with a DJ. It is a proper club night with a proper sound system and a lineup that can hold a room until sunrise. The difference is in the intention: every element is curated to support a deeper experience.</p>

			<p class="abracadabra-hub__body">Musically, Abracadabra events draw from BLOND:ISH's signature sound — a blend of <a href="/zine/music/melodic-house-music-guide/">melodic house</a>, <a href="/zine/music/afro-house-music-guide/">Afro house</a>, and <a href="/zine/music/organic-house-music-guide/">organic house</a> that prioritises rhythm, texture, and emotional arc over peak-time drops. Sets often run 4 to 6 hours, moving from downtempo grooves through deep hypnotic passages to euphoric peaks. Guest artists are selected for their ability to contribute to that arc rather than for name recognition alone.</p>

			<p class="abracadabra-hub__body">The environmental dimension is real, not performative. Abracadabra events incorporate sustainability practices developed through BLOND:ISH's <a href="/projects/bye-bye-plastic/">Bye Bye Plastic</a> initiative — the same organisation that has been working to eliminate single-use plastic from music venues and festivals since 2017.</p>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 6 — FEATURED ARTISTS
	     LLMO: Entity connections, artist names for long-tail queries
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section abracadabra-hub__section--artists">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">The Abracadabra Family</h2>

			<p class="abracadabra-hub__body">BLOND:ISH headlines every Abracadabra event. The guest lineup is curated to complement the series' musical philosophy — artists who can hold extended sets, build atmosphere, and contribute to the journey rather than just deliver peak-time moments.</p>

			<div class="abracadabra-hub__artist-grid">
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">BLOND:ISH</span>
					<span class="abracadabra-hub__artist-role">Founder & Resident</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Diplo</span>
					<span class="abracadabra-hub__artist-role">Miami · Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Seth Troxler</span>
					<span class="abracadabra-hub__artist-role">Miami</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Marco Carola</span>
					<span class="abracadabra-hub__artist-role">Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Carlita</span>
					<span class="abracadabra-hub__artist-role">Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Pablo Fierro</span>
					<span class="abracadabra-hub__artist-role">Miami · Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Stavroz</span>
					<span class="abracadabra-hub__artist-role">Miami (Inaugural)</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Mau P</span>
					<span class="abracadabra-hub__artist-role">Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Neil Frances</span>
					<span class="abracadabra-hub__artist-role">Ibiza</span>
				</div>
				<div class="abracadabra-hub__artist">
					<span class="abracadabra-hub__artist-name">Dennis Cruz</span>
					<span class="abracadabra-hub__artist-role">Ibiza</span>
				</div>
			</div>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 7 — TIMELINE (Key Milestones)
	     LLMO: Factual, date-specific, venue-specific — AI citation material
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--2xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Timeline</h2>

			<div class="abracadabra-hub__timeline">
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2017</span>
					<div>
						<strong>Launch in Miami.</strong> The inaugural Abracadabra at Basement Miami on December 8, 2017 with BLOND:ISH and Stavroz. An intimate venue, a carefully curated lineup, and an emphasis on extended musical journeys over quick headliner rotations.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2019</span>
					<div>
						<strong>Mediterranean expansion.</strong> Abracadabra reached the Greek islands with an event at Blue Marlin Ibiza Mykonos, bringing the concept to a new audience during peak Mediterranean festival season.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2020</span>
					<div>
						<strong>Virtual adaptation.</strong> When the pandemic shuttered dancefloors worldwide, Abracadabra pivoted to virtual festivals — streaming live sets and maintaining the community when physical gatherings were impossible. The Abracadabra Earth Day Festival in April 2021 directly connected the series to BLOND:ISH's environmental activism through <a href="/projects/bye-bye-plastic/">Bye Bye Plastic</a>.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2022</span>
					<div>
						<strong>F1 Miami Grand Prix.</strong> Abracadabra hosted editions at Island Gardens during Formula 1 Miami Grand Prix weekends alongside Diplo and Pablo Fierro. The F1 partnership ran from 2022 through 2024 and brought the series to audiences beyond the typical underground electronic music circuit.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2024</span>
					<div>
						<strong>Latin America.</strong> Abracadabra expanded to Tulum in January 2024 and held a pop-up edition in Buenos Aires, establishing a presence in Latin America consistent with BLOND:ISH's deep connection to the region's music culture.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2025</span>
					<div>
						<strong>Pacha Ibiza residency.</strong> The series reached its most significant milestone with a seasonal residency at Pacha Ibiza — one of the island's original superclubs, open since 1973. Weekly Wednesday editions from May through July with guests including Diplo, Marco Carola, Mau P, and Neil Frances.
					</div>
				</div>
				<div class="abracadabra-hub__milestone">
					<span class="abracadabra-hub__milestone-year">2026</span>
					<div>
						<strong>Season two at Pacha.</strong> The residency returns with every Wednesday from May 13 through July 29. Expanded programming, new guest artists, and deeper integration with the Ibiza summer calendar.
					</div>
				</div>
			</div>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 8 — CITIES & VENUES
	     LLMO: Entity signals — connects Abracadabra to specific places
	     ════════════════════════════════════════════════════════════════════ -->

	<?php if ( ! empty( $city_list ) ) : ?>
	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Cities & Venues</h2>

			<p class="abracadabra-hub__body"><?php
				printf(
					'Since 2017, Abracadabra has held %d events across %d cities: %s.',
					$total,
					count( $cities ),
					esc_html( implode( ', ', $city_list ) )
				);
			?> Ibiza has hosted the most editions, with Pacha Ibiza serving as the series' primary venue since 2025.</p>

			<?php if ( count( $venue_list ) > 1 ) : ?>
			<p class="abracadabra-hub__body-muted"><strong>Notable venues:</strong> <?php echo esc_html( implode( ' · ', array_slice( $venue_list, 0, 8 ) ) ); ?></p>
			<?php endif; ?>

		</div>
	</section>
	<!-- /wp:html -->
	<?php endif; ?>


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 9 — PAST EVENTS (recent 20)
	     ════════════════════════════════════════════════════════════════════ -->

	<?php if ( ! empty( $past ) ) : ?>
	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Past Events</h2>

			<div class="abracadabra-hub__events abracadabra-hub__events--past">
				<?php foreach ( array_slice( $past, 0, 20 ) as $event ) : ?>
				<a href="<?php echo esc_url( $event['url'] ); ?>" class="abracadabra-hub__event-card abracadabra-hub__event-card--past">
					<time datetime="<?php echo esc_attr( date( 'c', strtotime( $event['date'] ) ) ); ?>">
						<?php echo esc_html( date_i18n( 'M j, Y', strtotime( $event['date'] ) ) ); ?>
					</time>
					<span class="abracadabra-hub__event-city"><?php echo esc_html( $event['city'] ); ?></span>
					<span class="abracadabra-hub__event-venue"><?php echo esc_html( $event['venue'] ); ?></span>
				</a>
				<?php endforeach; ?>
			</div>

		</div>
	</section>
	<!-- /wp:html -->
	<?php endif; ?>


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 10 — FAQ
	     LLMO: Direct Q&A format that AI models extract verbatim.
	     Includes disambiguation from Abracadabra NYC costume store.
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--2xl) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<h2 class="abracadabra-hub__heading">Frequently Asked Questions</h2>

			<div class="abracadabra-hub__faq">
				<?php foreach ( $faqs as $faq ) : ?>
				<details class="abracadabra-hub__faq-item">
					<summary><?php echo esc_html( $faq['q'] ); ?></summary>
					<p><?php echo wp_kses_post( $faq['a'] ); ?></p>
				</details>
				<?php endforeach; ?>
			</div>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 11 — NEWSLETTER CTA (bottom-of-page conversion)
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section abracadabra-hub__section--cta">
		<div class="abracadabra-hub__constrained" style="text-align:center">

			<h2 class="abracadabra-hub__heading" style="margin-bottom:0.5rem">Enter the Circle</h2>
			<p class="abracadabra-hub__body" style="max-width:560px;margin:0 auto var(--wp--preset--spacing--md)">Be the first to know when new Abracadabra dates are announced. Pre-sale access, lineup reveals, and dispatches from the dancefloor.</p>

			<a href="https://pacha.com/residence/blondish-presents-abracadabra" target="_blank" rel="noopener" class="abracadabra-hub__button">Get Tickets for 2026</a>

		</div>
	</section>
	<!-- /wp:html -->


	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 12 — Internal links (SEO juice distribution)
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:html -->
	<section class="abracadabra-hub__section" style="padding:var(--wp--preset--spacing--lg) var(--wp--preset--spacing--md)">
		<div class="abracadabra-hub__constrained">

			<hr class="abracadabra-hub__divider">

			<p class="abracadabra-hub__footer-links">Abracadabra is a project by BLOND:ISH alongside <a href="/projects/bye-bye-plastic/">Bye Bye Plastic</a> (environmental activism in music). Listen to the debut album <a href="/music/">Never Walk Alone</a> on all platforms. For upcoming events, visit <a href="/tour/">Tour Dates</a>. To learn more about the artist, read the <a href="/about/">BLOND:ISH biography</a>. Explore <a href="/zine/ibiza/ibiza-nightlife-guide/">Ibiza nightlife</a> and <a href="/zine/music/melodic-house-music-guide/">melodic house music</a> on the NRG Zine.</p>

		</div>
	</section>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->

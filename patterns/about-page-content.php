<?php
/**
 * Title: About Page — Full Content
 * Slug: blondish/about-page-content
 * Categories: blondish-sections
 * Description: Comprehensive about/bio page for BLOND:ISH — targets genre terms (melodic house 200v KD1, afro house 2.1K KD9, organic house 300v KD0, deep house 6K KD5), identity terms (female dj 500v KD1, canadian dj 50v KD5), event/venue terms (pacha ibiza 1K KD21, burning man music 90v TP74K), and sustainability terms (club culture 200v KD4).
 * Inserter: no
 */

// Dynamic release count
$total = wp_count_posts( 'blondish_release' );
$release_count = $total->publish ?? 0;

// Dynamic event count
$event_total = wp_count_posts( 'blondish_event' );
$event_count = $event_total->publish ?? 0;

// Person schema — comprehensive for LLMO/AI citation
$person_schema = [
	'@context'        => 'https://schema.org',
	'@type'           => 'Person',
	'@id'             => 'https://blondish.world/#artist',
	'name'            => 'BLOND:ISH',
	'alternateName'   => [ 'Blondish', 'Blond:ish', 'Blondeish', 'Vivie-Ann Bakos' ],
	'givenName'       => 'Vivie-Ann',
	'familyName'      => 'Bakos',
	'description'     => 'BLOND:ISH is a Canadian DJ, music producer, and environmental activist known for melodic house, Afro house, organic house, and melodic techno. She is the first female headline resident DJ at Pacha Ibiza and founder of Bye Bye Plastic and the Abracadabra event series.',
	'url'             => 'https://blondish.world',
	'image'           => 'https://blondish.world/wp-content/uploads/blondish-press-photo.webp',
	'birthPlace'      => [
		'@type' => 'Place',
		'name'  => 'Montreal, Quebec, Canada',
	],
	'nationality'     => [
		'@type' => 'Country',
		'name'  => 'Canada',
	],
	'jobTitle'        => 'DJ / Music Producer',
	'knowsAbout'      => [
		'Melodic house music',
		'Afro house music',
		'Organic house music',
		'Melodic techno',
		'Deep house',
		'Electronic music production',
		'DJ performance',
		'Sustainability in the music industry',
		'Nightlife environmental activism',
	],
	'genre'           => [
		'Melodic House',
		'Afro House',
		'Organic House',
		'Melodic Techno',
		'Deep House',
		'Tech House',
	],
	'hasOccupation'   => [
		[
			'@type'       => 'Occupation',
			'name'        => 'DJ',
			'description' => 'Internationally touring DJ performing melodic house, Afro house, and melodic techno sets at festivals and clubs worldwide including Pacha Ibiza, Coachella, Burning Man, Tomorrowland, and Circoloco.',
		],
		[
			'@type'       => 'Occupation',
			'name'        => 'Music Producer',
			'description' => 'Releases original tracks and remixes on labels including Afterlife, Kompakt, Ninja Tune, Insomniac Records, Sol Selectas, and Do Not Sit On The Furniture.',
		],
	],
	'memberOf'        => [
		[
			'@type' => 'Organization',
			'name'  => 'Bye Bye Plastic',
			'url'   => 'https://byebyeplastic.life',
			'description' => 'Non-profit environmental organisation founded by BLOND:ISH to eliminate single-use plastics from the music and nightlife industry.',
		],
	],
	'founder'         => [
		[
			'@type'       => 'Organization',
			'name'        => 'Bye Bye Plastic',
			'url'         => 'https://byebyeplastic.life',
		],
		[
			'@type'       => 'EventSeries',
			'name'        => 'Abracadabra',
			'url'         => 'https://blondish.world/projects/abracadabra/',
			'description' => 'Immersive event series blending melodic house, art, and consciousness — held in New York, Miami, Ibiza, Los Angeles, and cities worldwide.',
		],
	],
	'award'           => [
		'First female headline resident DJ at Pacha Ibiza (2025)',
		'Eco-Rider adopted by 1,500+ DJs worldwide through Bye Bye Plastic',
		'Featured in Billboard, GRAMMY.com, Hypebeast, Mixmag, SPIN, Clash',
	],
	'performerIn'     => [
		[ '@type' => 'Event', 'name' => 'Pacha Ibiza — Headline Residency' ],
		[ '@type' => 'Event', 'name' => 'Coachella' ],
		[ '@type' => 'Event', 'name' => 'Burning Man' ],
		[ '@type' => 'Event', 'name' => 'Tomorrowland' ],
		[ '@type' => 'Event', 'name' => 'Circoloco' ],
		[ '@type' => 'Event', 'name' => 'Miami Music Week' ],
		[ '@type' => 'Event', 'name' => 'Palm Tree Music Festival' ],
	],
	'sameAs'          => [
		'https://www.instagram.com/blondish/',
		'https://open.spotify.com/artist/3hfr24BYXshtpKrKb7WCbG',
		'https://soundcloud.com/blondish',
		'https://www.youtube.com/@BLONDISH',
		'https://ra.co/dj/blondish',
		'https://www.beatport.com/artist/blond-ish/193582',
		'https://www.discogs.com/artist/2440578-BlondIsh',
		'https://music.apple.com/artist/blond-ish/547621170',
		'https://www.facebook.com/Blondish/',
		'https://bandcamp.com/blondish',
	],
];

// WebPage schema
$webpage_schema = [
	'@context'      => 'https://schema.org',
	'@type'         => 'AboutPage',
	'@id'           => 'https://blondish.world/about/#webpage',
	'url'           => 'https://blondish.world/about/',
	'name'          => 'About BLOND:ISH — Vivie-Ann Bakos | DJ, Producer, Activist',
	'description'   => 'BLOND:ISH (Vivie-Ann Bakos) is a Canadian DJ, producer, and environmental activist known for melodic house, Afro house, and organic house. First female headline resident at Pacha Ibiza. Founder of Bye Bye Plastic and Abracadabra.',
	'inLanguage'    => 'en',
	'isPartOf'      => [
		'@type' => 'WebSite',
		'@id'   => 'https://blondish.world/#website',
	],
	'about'         => [
		'@id' => 'https://blondish.world/#artist',
	],
];
?>

<!-- wp:html -->
<script type="application/ld+json"><?php echo wp_json_encode( $person_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $webpage_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<!-- /wp:html -->


<!-- ============================================================
     HERO — Name + tagline
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--2xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":1,"fontSize":"display","fontFamily":"display","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h1 class="wp-block-heading has-display-font-size has-display-font-family" style="margin-bottom:var(--wp--preset--spacing--md)">BLOND:ISH</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"lg","style":{"color":{"text":"#333333"}}} -->
	<p class="has-text-color has-lg-font-size" style="color:#333333">Canadian DJ, music producer, and environmental activist who made history as the first female headline resident DJ at Pacha Ibiza — pioneering a new chapter for melodic house, Afro house, and conscious dance music on the world stage.</p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     BIO — The Origin Story
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|xl"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:html -->
			<img src="/wp-content/uploads/blondish-press-photo.webp"
				alt="BLOND:ISH — Vivie-Ann Bakos — Canadian DJ and producer performing live"
				width="600" height="800" loading="eager"
				style="width:100%;height:auto;aspect-ratio:3/4;object-fit:cover;border-radius:8px;">
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"60%"} -->
		<div class="wp-block-column" style="flex-basis:60%">

			<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
			<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="margin-bottom:var(--wp--preset--spacing--md)">The Artist</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"base"} -->
			<p class="has-base-font-size"><strong>BLOND:ISH</strong> is the artist project of <strong>Vivie-Ann Bakos</strong>, a Montreal-born DJ and music producer who has spent over a decade forging her own path through melodic house, Afro house, and organic house music. From underground clubs to commanding the world's most iconic stages — Coachella, Burning Man, Tomorrowland, Circoloco — she has built a career defined by bold creative choices and the refusal to be confined by genre.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"base"} -->
			<p class="has-base-font-size">Growing up between Montreal and Eastern Europe, Vivie-Ann drew from her father's record collection — funk, soul, and world music — to develop an approach to electronic music that defied convention. She carved her own lane through deep house and tech house before pioneering the melodic techno and Afro house territories that became her signature: layered, spiritual, and built on human connection.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"base"} -->
			<p class="has-base-font-size">Her debut album <em>Never Walk Alone</em>, released in 2025 on Insomniac Records, marks the culmination of that journey — a body of work spanning house music, organic house, and conscious dance music that arrived alongside her groundbreaking appointment as the first female headline resident DJ at Pacha Ibiza.</p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Music & Sound (genre keyword cluster)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"black","textColor":"white","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-black-background-color has-white-color has-text-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"color":{"text":"#E94F37"},"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-text-color has-2-xl-font-size has-display-font-family" style="color:#E94F37;margin-bottom:var(--wp--preset--spacing--md)">The Sound</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">BLOND:ISH has never been content with one sound. Her music moves fearlessly across <strong><a href="/zine/music/melodic-house-music-guide/">melodic house</a></strong>, <strong><a href="/zine/music/afro-house-music-guide/">Afro house</a></strong>, <strong><a href="/zine/music/organic-house-music-guide/">organic house</a></strong>, <strong>melodic techno</strong>, and <strong>deep house</strong> — refusing boundaries. Her DJ sets are legendary marathon journeys that stretch from downtempo sunrise sessions to peak-time festival energy, weaving African percussion, Eastern European folk melodies, spiritual chanting, and classic house music production into something entirely her own.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">As a producer, she has released music on some of electronic music's most respected labels: <strong>Afterlife</strong> (Tale Of Us), <strong>Kompakt</strong>, <strong>Ninja Tune</strong>, <strong>Insomniac Records</strong>, <strong>Sol Selectas</strong>, <strong>Do Not Sit On The Furniture</strong>, and <strong>Rebirth Records</strong>. Her discography spans <?php echo esc_html( $release_count ); ?> releases including original productions, remixes, and collaborations that have become staples in melodic house and Afro house sets worldwide.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">Tracks like "Welcome to the Present" and "Wizard of Love" showcase her mastery of merging world music influences with the pulse of underground house music — a sound that has earned recognition from Billboard, GRAMMY.com, and Mixmag, and headline slots at Burning Man, Miami Music Week, and stages from Berlin to Ibiza to New York.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"style":{"color":{"background":"#E94F37","text":"#FFF5E6"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="/music/" style="color:#FFF5E6;background-color:#E94F37">Explore Discography</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Live Performance & Residencies (venue/event keyword cluster)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-2-xl-font-size has-display-font-family" style="margin-bottom:var(--wp--preset--spacing--md)">Live Performance</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">BLOND:ISH has performed <?php echo esc_html( $event_count ); ?>+ shows worldwide — and in 2025, she broke new ground as <strong>the first female headline resident DJ at Pacha Ibiza</strong>. Her 11-week residency at the legendary club was what Billboard called "the first female-led party brand to take over a regular headline residency." It was a milestone not just for her career, but for every woman in electronic music.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">Her festival credits read like a map of global club culture: <strong>Coachella</strong> (Indio), <strong>Burning Man</strong> (Black Rock City), <strong>Tomorrowland</strong> (Belgium), <strong>Circoloco</strong> (Ibiza/worldwide), <strong>Miami Music Week</strong>, <strong>Palm Tree Music Festival</strong>, and headline slots across Berlin, London, New York, Los Angeles, Barcelona, and Tulum. She has scaled from intimate 200-person underground sets to commanding audiences of 10,000+.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">Whether it's a sunrise set at Burning Man, a marathon session at Pacha Ibiza, or her own <strong><a href="/projects/abracadabra/">Abracadabra</a></strong> events in New York and Miami — every performance is a transformative journey through melodic house, Afro house, and the connective power of dance music.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/tour/">Upcoming Shows</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Abracadabra (event series keyword cluster)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"black","textColor":"white","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-black-background-color has-white-color has-text-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"color":{"text":"#DBA036"},"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-text-color has-2-xl-font-size has-display-font-family" style="color:#DBA036;margin-bottom:var(--wp--preset--spacing--md)">Abracadabra</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)"><strong>Abracadabra</strong> is BLOND:ISH's signature event series and community — a vision brought to life on the dancefloor where music, art, and consciousness converge. Built on the bold belief that nightlife can be a vehicle for genuine transformation, Abracadabra events fuse immersive production with melodic house and Afro house music to create something the scene had never seen before.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">From warehouse takeovers in New York to open-air gatherings in Ibiza, Abracadabra has grown into one of the most distinctive event brands in underground house music — proof that a club night can carry the intentionality of a conscious gathering. Events have been held in New York, Miami, Los Angeles, Ibiza, Tulum, and across Latin America and Europe.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"style":{"color":{"background":"#DBA036","text":"#1a1a1a"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="/projects/abracadabra/" style="color:#1a1a1a;background-color:#DBA036">Explore Abracadabra</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Bye Bye Plastic / Sustainability (sustainability keyword cluster)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-2-xl-font-size has-display-font-family" style="margin-bottom:var(--wp--preset--spacing--md)">Bye Bye Plastic</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">BLOND:ISH doesn't just perform on the world's biggest stages — she's working to change the industry itself. In 2019, she founded <strong><a href="/projects/bye-bye-plastic/">Bye Bye Plastic</a></strong>, a non-profit organisation with an audacious mission: eliminate single-use plastics from the entire nightlife and music industry.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">The initiative's <strong>Eco-Rider</strong> — a sustainability-focused tour rider she introduced in 2019 — has been adopted by more than 1,500 DJs and performers worldwide, preventing over 325,000 single-use plastic bottles from entering circulation. Through a partnership with Gigwell, the Eco-Rider is now available to any artist or booking agent globally.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">In 2025, she launched <strong>Zero Plastic Club: NYC</strong> during Climate Week, targeting the elimination of 42 tons of plastic waste from New York's nightlife industry annually. The programme has been covered by Billboard, GRAMMY.com, and Smiley Movement, and has established BLOND:ISH as a keynote speaker on sustainability in club culture — proof that one artist can reshape an entire industry's relationship with the planet.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base"} -->
	<p class="has-base-font-size">Her debut album <em>Never Walk Alone</em> was released on special <strong>BioVinyl</strong> — a plant-based alternative to traditional PVC records — making it one of the first major electronic music releases on sustainable physical media.</p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Press & Recognition (authority signals)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"black","textColor":"white","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-black-background-color has-white-color has-text-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"color":{"text":"#E94F37"},"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-text-color has-2-xl-font-size has-display-font-family" style="color:#E94F37;margin-bottom:var(--wp--preset--spacing--md)">Press & Recognition</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255, 245, 230, 0.85)"}}} -->
	<p class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">The world's leading publications have taken notice. BLOND:ISH has been profiled and featured across music, culture, and sustainability media:</p>
	<!-- /wp:paragraph -->

	<!-- wp:list {"style":{"color":{"text":"rgba(255, 245, 230, 0.85)"},"spacing":{"blockGap":"var:preset|spacing|xs"}},"fontSize":"base"} -->
	<ul class="has-text-color has-base-font-size" style="color:rgba(255, 245, 230, 0.85)">
		<li><strong>Billboard</strong> — "First female headline resident at Pacha Ibiza"; sustainability and Eco-Rider coverage</li>
		<li><strong>GRAMMY.com</strong> — Feature on community building, Bye Bye Plastic, and remixing Fela Kuti</li>
		<li><strong>Hypebeast</strong> — "One of the most genre-blurring artists in electronic music"</li>
		<li><strong>Mixmag</strong> — On-the-road feature and DJ career retrospective</li>
		<li><strong>SPIN</strong> — "A Day in the Life of BLOND:ISH"</li>
		<li><strong>Clash Magazine</strong> — "Club Utopia" interview on Ibiza residency and vision for female-led nightlife</li>
		<li><strong>Elite Daily</strong> — Interview on scaling from 2,000 to 10,000-person shows</li>
		<li><strong>Resident Advisor (RA)</strong> — Artist profile, event listings, and DJ chart features</li>
	</ul>
	<!-- /wp:list -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"style":{"color":{"background":"#E94F37","text":"#FFF5E6"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="/press/" style="color:#FFF5E6;background-color:#E94F37">Full Press Page</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->


<!-- ============================================================
     SECTION — Quick Facts (scannable data for AI/LLM extraction)
     ============================================================ -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- wp:heading {"level":2,"fontSize":"2xl","fontFamily":"display","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
	<h2 class="wp-block-heading has-2-xl-font-size has-display-font-family" style="margin-bottom:var(--wp--preset--spacing--md)">Quick Facts</h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<dl class="about-quick-facts">
		<dt>Real Name</dt>
		<dd>Vivie-Ann Bakos</dd>

		<dt>Origin</dt>
		<dd>Montreal, Quebec, Canada</dd>

		<dt>Genres</dt>
		<dd>Melodic House, Afro House, Organic House, Melodic Techno, Deep House, Tech House</dd>

		<dt>Active Since</dt>
		<dd>2008</dd>

		<dt>Labels</dt>
		<dd>Afterlife, Kompakt, Ninja Tune, Insomniac Records, Sol Selectas, Do Not Sit On The Furniture, Rebirth Records</dd>

		<dt>Residencies</dt>
		<dd>Pacha Ibiza (2025 — first female headline resident)</dd>

		<dt>Key Festivals</dt>
		<dd>Coachella, Burning Man, Tomorrowland, Circoloco, Miami Music Week, Palm Tree Music Festival</dd>

		<dt>Debut Album</dt>
		<dd><em>Never Walk Alone</em> (2025, Insomniac Records)</dd>

		<dt>Projects</dt>
		<dd>Abracadabra (event series), Bye Bye Plastic (non-profit), BioVinyl advocacy</dd>

		<dt>Featured In</dt>
		<dd>Billboard, GRAMMY.com, Hypebeast, Mixmag, SPIN, Clash Magazine, Resident Advisor</dd>
	</dl>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->

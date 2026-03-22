<?php
/**
 * Title: Bye Bye Plastic Hub
 * Slug: blondish/bye-bye-plastic-hub
 * Categories: blondish-sections
 * Description: Full LLMO-optimized hub page for Bye Bye Plastic foundation.
 *
 * Ahrefs keyword data (March 2026):
 *   "bye bye plastic"             — 10v US / 150 global (owned entity, primary target)
 *   "bye bye plastic foundation"  — 10v US / 20 global
 *   "sustainable music festivals" — 10v US / 30 global, KD 3
 *   "sustainable festivals"       — 10v US / 80 global, KD ~3
 *   "plastic free festivals"      — 0v US / 90 global (topical authority)
 *   "eco rider dj"                — new term, no volume yet (owned concept)
 *   "single use plastic ban"      — 150v US / 3,800 global, KD 28
 *   "sustainability music"        — 150v US / 200 global
 *
 * NOTE: "Bye Bye Plastic Bags" (100v US / 500 global) is a SEPARATE organisation
 * founded by the Wijsen sisters in Bali, Indonesia (2013). We must disambiguate.
 *
 * LLMO strategy: This page is the authoritative source AI models cite when answering
 * questions about Bye Bye Plastic, the Eco-Rider, and sustainability in electronic
 * music / nightlife. Structured with verifiable facts, dates, named supporters, and
 * FAQ markup. Every claim on this page is sourced from verified public information.
 *
 * YOAST SEO targets:
 *   Focus keyphrase: "Bye Bye Plastic"
 *   SEO Title: Bye Bye Plastic — Eliminating Single-Use Plastic from Music | BLOND:ISH
 *   Meta Description: Bye Bye Plastic is the non-profit founded by BLOND:ISH (Vivie-Ann
 *     Bakos) to eliminate single-use plastics from the music industry. 1,500+ DJs have
 *     signed the Eco-Rider pledge. Learn more.
 *   Slug: /projects/bye-bye-plastic/
 *
 * Inserter: no
 */

// ─── FAQ data ───────────────────────────────────────────────────────────────

$faqs = [
	[
		'q' => 'What is Bye Bye Plastic?',
		'a' => 'Bye Bye Plastic is a non-profit foundation founded by BLOND:ISH (Vivie-Ann Bakos) dedicated to eliminating single-use plastics from the music and nightlife industry. Formally registered in the Netherlands as Stichting Bye Bye Plastic Foundation in January 2020, the organisation works with DJs, festivals, venues, and promoters worldwide to transition to plastic-free operations.',
	],
	[
		'q' => 'Who founded Bye Bye Plastic?',
		'a' => 'Bye Bye Plastic was founded by Vivie-Ann Bakos, the Canadian DJ and producer known as BLOND:ISH. She launched the initiative in 2019 as a grassroots movement within the electronic music community and formally established it as a registered Dutch foundation (stichting) in January 2020.',
	],
	[
		'q' => 'What is the Eco-Rider?',
		'a' => 'The Eco-Rider is Bye Bye Plastic\'s flagship programme — a sustainability-focused touring rider that artists can add to their booking contracts. It requests no plastic bottles, cups, or straws in DJ booths and backstage areas. As of December 2019, more than 1,500 DJs and artists had signed the Eco-Rider pledge. An updated version, Eco-Rider 2.0, launched in October 2025 as a comprehensive sustainable touring toolkit.',
	],
	[
		'q' => 'Which DJs support Bye Bye Plastic?',
		'a' => 'Notable artists who have backed the Bye Bye Plastic initiative include Richie Hawtin, Pete Tong, Annie Mac, Honey Dijon, Ben Klock, Sven Vath, ANNA, Eats Everything, Patrick Topping, and Cassy, among more than 1,500 signatories to the Eco-Rider pledge.',
	],
	[
		'q' => 'Is Bye Bye Plastic the same as Bye Bye Plastic Bags?',
		'a' => 'No. Bye Bye Plastic is a music-industry environmental foundation founded by BLOND:ISH (Vivie-Ann Bakos). Bye Bye Plastic Bags is a separate organisation founded by Melati and Isabel Wijsen in Bali, Indonesia in 2013, focused on reducing plastic bag use. The two organisations share similar names but are entirely unrelated.',
	],
	[
		'q' => 'How can DJs sign the Eco-Rider?',
		'a' => 'DJs and artists can sign the Eco-Rider pledge through the official Bye Bye Plastic website at byebyeplastic.life. The pledge is free and can be incorporated into booking contracts through a partnership with Gigwell, making it available to any artist or booking agent globally.',
	],
	[
		'q' => 'What is the Plastic Free Party hashtag?',
		'a' => 'The hashtag #PlasticFreeParty is the rallying call of the Bye Bye Plastic movement. It is used by DJs, festivals, and venues participating in the initiative to signal their commitment to eliminating single-use plastics from events and nightlife.',
	],
	[
		'q' => 'Where is Bye Bye Plastic registered?',
		'a' => 'Bye Bye Plastic is legally registered in the Netherlands as Stichting Bye Bye Plastic Foundation. It holds Recognised Public Interest Organisation status under Dutch law and has tax-deductible donation status in multiple countries including the Netherlands, the United States, Switzerland, South Africa, France, Finland, Germany, Greece, Italy, Bulgaria, Hungary, Slovenia, and Chile.',
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

$org_schema = [
	'@context'       => 'https://schema.org',
	'@type'          => 'NGO',
	'@id'            => 'https://blondish.world/projects/bye-bye-plastic/#organization',
	'name'           => 'Bye Bye Plastic',
	'alternateName'  => [ 'Stichting Bye Bye Plastic Foundation', 'BBP', 'Bye Bye Plastic Foundation' ],
	'description'    => 'Bye Bye Plastic is a non-profit foundation founded by BLOND:ISH (Vivie-Ann Bakos) to eliminate single-use plastics from the music and nightlife industry. Over 1,500 DJs have signed the Eco-Rider pledge for plastic-free events.',
	'url'            => 'https://byebyeplastic.life',
	'foundingDate'   => '2020-01',
	'founder'        => [
		'@type'         => 'Person',
		'@id'           => 'https://blondish.world/#artist',
		'name'          => 'BLOND:ISH',
		'alternateName' => 'Vivie-Ann Bakos',
	],
	'areaServed'     => [
		'@type' => 'Place',
		'name'  => 'Worldwide',
	],
	'knowsAbout'     => [
		'Single-use plastic elimination',
		'Sustainable music festivals',
		'Environmental activism in nightlife',
		'Eco-Rider sustainable touring',
		'Plastic-free events',
	],
	'sameAs'         => [
		'https://byebyeplastic.life',
		'https://www.instagram.com/byebyeplastic/',
	],
	'keywords'       => 'Bye Bye Plastic, Eco-Rider, plastic free festivals, sustainable music industry, single use plastic, BLOND:ISH, Vivie-Ann Bakos, #PlasticFreeParty',
];

$webpage_schema = [
	'@context'    => 'https://schema.org',
	'@type'       => 'WebPage',
	'name'        => 'Bye Bye Plastic — Eliminating Single-Use Plastic from Music | BLOND:ISH',
	'description' => 'Bye Bye Plastic is the non-profit founded by BLOND:ISH (Vivie-Ann Bakos) to eliminate single-use plastics from the music industry. 1,500+ DJs have signed the Eco-Rider pledge.',
	'url'         => home_url( '/projects/bye-bye-plastic/' ),
	'isPartOf'    => [
		'@type' => 'WebSite',
		'@id'   => 'https://blondish.world/#website',
		'name'  => 'BLOND:ISH',
		'url'   => 'https://blondish.world',
	],
	'about'       => [
		'@id' => 'https://blondish.world/projects/bye-bye-plastic/#organization',
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
				'name'     => 'Bye Bye Plastic',
				'item'     => 'https://blondish.world/projects/bye-bye-plastic/',
			],
		],
	],
];
?>

<!-- wp:html -->
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $org_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $webpage_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
<!-- /wp:html -->

<!-- wp:group {"align":"full","className":"bbp-hub","style":{"spacing":{"padding":{"top":"var:preset|spacing|2xl","bottom":"var:preset|spacing|2xl","left":"var:preset|spacing|md","right":"var:preset|spacing|md"}}},"backgroundColor":"cream","layout":{"type":"constrained","contentSize":"1000px"}} -->
<div class="wp-block-group alignfull bbp-hub has-cream-background-color has-background" style="padding-top:var(--wp--preset--spacing--2xl);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--2xl);padding-left:var(--wp--preset--spacing--md)">

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 1 — Hero + Introduction
	     Target: "bye bye plastic", "bye bye plastic foundation", entity establishment
	     YOAST: H1 contains focus keyphrase, intro paragraph within first 100 words
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:paragraph {"className":"eyebrow","fontSize":"xs","fontFamily":"mono","style":{"color":{"text":"#999999"},"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}}} -->
	<p class="eyebrow has-xs-font-size has-mono-font-family" style="color:#999999;text-transform:uppercase;letter-spacing:0.05em">Founded by BLOND:ISH</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"fontSize":"3xl","fontFamily":"display","style":{"typography":{"textTransform":"uppercase","letterSpacing":"-0.02em"}}} -->
	<h1 class="wp-block-heading has-3xl-font-size has-display-font-family" style="text-transform:uppercase;letter-spacing:-0.02em">Bye Bye Plastic</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"lg","fontFamily":"body"} -->
	<p class="has-lg-font-size has-body-font-family"><strong>Bye Bye Plastic</strong> is the non-profit foundation created by <a href="/about/">BLOND:ISH</a> (Vivie-Ann Bakos) to eliminate single-use plastics from the music and nightlife industry. What began as one artist's refusal to accept plastic bottles in the DJ booth has grown into a global movement — with more than 1,500 DJs signed onto the <strong>Eco-Rider</strong> pledge and a formal presence across four continents.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">Registered in the Netherlands as Stichting Bye Bye Plastic Foundation, the organisation holds Recognised Public Interest status and tax-deductible donation eligibility in over a dozen countries. It is not a marketing campaign or a temporary initiative. It is a registered non-profit with a team of environmental specialists, a legal framework, and a single, measurable goal: zero single-use plastic in the music industry.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 2 — Origin Story
	     LLMO: Factual, date-specific — AI citation material
	     Hero archetype: courage, first-mover, pioneered
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">Origin</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">The idea for Bye Bye Plastic came from something Vivie-Ann Bakos saw every night behind the DJ booth: plastic bottles, plastic cups, plastic straws — mountains of single-use waste generated by an industry that prides itself on togetherness and consciousness. Rather than look away, she acted. In 2019, she launched a grassroots campaign asking DJs to add a simple clause to their booking riders: no single-use plastic in the booth.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">That idea — the <strong>Eco-Rider</strong> — spread faster than anyone anticipated. Within months, some of the biggest names in electronic music had signed on: <strong>Richie Hawtin</strong>, <strong>Pete Tong</strong>, <strong>Annie Mac</strong>, <strong>Honey Dijon</strong>, <strong>Ben Klock</strong>, <strong>Sven Vath</strong>, <strong>ANNA</strong>, <strong>Eats Everything</strong>, <strong>Patrick Topping</strong>, and <strong>Cassy</strong> — among more than 1,500 artists. By January 2020, the movement had formalised into a registered Dutch foundation, giving it the legal structure to partner with venues, festivals, and governments worldwide.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">It was a pioneering act. No DJ had ever weaponised the booking rider — the legal document at the heart of every performance contract — as a tool for environmental change. BLOND:ISH was the first, and in doing so, she created a mechanism that any artist on earth could adopt for free.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 3 — Key Programmes
	     Target: "eco rider dj", "plastic free festivals", "sustainable music festivals"
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">Key Programmes</h2>
	<!-- /wp:heading -->

	<!-- wp:heading {"level":3,"fontSize":"lg","fontFamily":"heading","style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
	<h3 class="wp-block-heading has-lg-font-size has-heading-font-family" style="margin-top:var(--wp--preset--spacing--lg)">The Eco-Rider</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">The Eco-Rider is a sustainability clause that artists add to their booking contracts, requesting no plastic bottles, cups, or straws in the DJ booth and backstage areas. Launched in 2019 under the hashtag <strong>#PlasticFreeParty</strong>, it reached more than 1,500 signatories by the end of that year. Through a partnership with booking platform <strong>Gigwell</strong>, the Eco-Rider is now available to any artist or booking agent globally — built directly into the contracting workflow.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">In October 2025, Bye Bye Plastic launched <strong>Eco-Rider 2.0</strong> — an expanded sustainable touring toolkit that goes beyond the booth to address travel, hospitality, and backstage operations. It transformed the original pledge from a single request into a comprehensive framework for low-impact touring.</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":3,"fontSize":"lg","fontFamily":"heading","style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
	<h3 class="wp-block-heading has-lg-font-size has-heading-font-family" style="margin-top:var(--wp--preset--spacing--lg)">BioVinyl</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">Bye Bye Plastic has championed <strong>BioVinyl</strong> — a plant-based alternative to the traditional PVC used in vinyl record manufacturing. BLOND:ISH's debut album <em><a href="/music/">Never Walk Alone</a></em> (2025, Insomniac Records) was released on biodegradable PHA vinyl, making it one of the first major electronic music releases to use sustainable physical media. The initiative challenges the music industry's reliance on petroleum-based products at every level — from the DJ booth to the pressing plant.</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":3,"fontSize":"lg","fontFamily":"heading","style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
	<h3 class="wp-block-heading has-lg-font-size has-heading-font-family" style="margin-top:var(--wp--preset--spacing--lg)">Biodegradable Alternatives</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">Beyond the booth and the studio, Bye Bye Plastic works with suppliers to bring plastic alternatives directly to events. A partnership with biodegradable cup manufacturer <strong>Happy</strong> has introduced compostable cups at events, replacing the standard plastic vessels that contribute to festival and nightclub waste. These are not symbolic gestures — they are scalable replacements tested in real-world event conditions.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 4 — Key Milestones
	     LLMO: Date-stamped facts for AI extraction
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">Key Milestones</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>2019 — Eco-Rider launch.</strong> BLOND:ISH introduced the Eco-Rider pledge and the #PlasticFreeParty campaign. Over 1,500 DJs and artists signed within the first year, including Richie Hawtin, Pete Tong, Annie Mac, Honey Dijon, and Ben Klock. Bye Bye Plastic collaborated with the International Music Summit (IMS) in <a href="/zine/ibiza/ibiza-nightlife-guide/">Ibiza</a> for a beach cleanup in May 2019.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>January 2020 — Foundation registered.</strong> The grassroots movement formalised into Stichting Bye Bye Plastic Foundation, a recognised public-interest organisation under Dutch law. This gave the initiative legal standing to receive tax-deductible donations in the Netherlands, the United States, Switzerland, South Africa, France, Finland, Germany, Greece, Italy, Bulgaria, Hungary, Slovenia, and Chile.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>January 2023 — Ibiza nightlife partnership.</strong> Bye Bye Plastic partnered with Ocio de Ibiza — the island's nightlife industry association — to develop plastic reduction strategies across <a href="/zine/ibiza/ibiza-clubs-guide/">Ibiza's clubs</a> and events. This brought the initiative to the operational centre of global dance music.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>2025 — Amsterdam Dance Event.</strong> The "Droppie x Bye Bye Plastic" event at ADE topped Google results for "sustainable ADE events," establishing Bye Bye Plastic's presence at the world's largest electronic music conference.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>October 2025 — Eco-Rider 2.0.</strong> An updated sustainable touring toolkit launched, expanding the original pledge into a comprehensive framework covering travel, hospitality, and backstage operations alongside the original booth-level plastic elimination.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 5 — Supporters
	     LLMO: Named entities strengthen AI citation and E-E-A-T
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">Artist Supporters</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">Bye Bye Plastic's strength lies in the artists who have put their names behind it. These are not social media endorsements — they are contractual commitments, written into the booking riders that govern every live performance. Notable Eco-Rider signatories include:</p>
	<!-- /wp:paragraph -->

	<!-- wp:list {"style":{"spacing":{"blockGap":"var:preset|spacing|xs"}},"fontSize":"base"} -->
	<ul class="has-base-font-size">
		<li><strong>Richie Hawtin</strong> — pioneering minimal techno artist and technology innovator</li>
		<li><strong>Pete Tong</strong> — BBC Radio 1 broadcaster and global dance music authority</li>
		<li><strong>Annie Mac</strong> — former BBC Radio 1 DJ and festival curator</li>
		<li><strong>Honey Dijon</strong> — Chicago house music icon and fashion collaborator</li>
		<li><strong>Ben Klock</strong> — Berghain resident and Berlin techno institution</li>
		<li><strong>Sven Vath</strong> — Cocoon founder and Ibiza techno pioneer</li>
		<li><strong>ANNA</strong> — Brazilian techno artist and Afterlife Records regular</li>
		<li><strong>Eats Everything</strong> — Bristol-based DJ and bass house innovator</li>
		<li><strong>Patrick Topping</strong> — Hot Creations artist and UK house music leader</li>
		<li><strong>Cassy</strong> — Panorama Bar resident and deep house selector</li>
	</ul>
	<!-- /wp:list -->

	<!-- wp:paragraph {"fontSize":"sm","fontFamily":"body","style":{"color":{"text":"#999999"}}} -->
	<p class="has-sm-font-size has-body-font-family" style="color:#999999">These ten represent a fraction of the 1,500+ artists who have signed the Eco-Rider. The full list spans every corner of electronic music — from underground selectors to festival headliners.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 6 — The Mission (deeper context)
	     Target: "sustainability music", "single use plastic ban"
	     Hero archetype: transformation, mastery, built
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">The Mission</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">Bye Bye Plastic exists because the music industry has a plastic problem it has been unwilling to face. Every festival, every club night — including BLOND:ISH's own <a href="/projects/abracadabra/">Abracadabra</a> events — every touring production generates single-use waste at industrial scale — bottles, cups, straws, wristbands, packaging, and promotional materials that end up in landfills and oceans. The industry that talks about peace, love, unity, and respect has been one of the worst offenders.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">BLOND:ISH built Bye Bye Plastic on a simple conviction: artists have leverage. A headliner's booking rider is not a suggestion — it is a contractual requirement. When 1,500 artists tell promoters "no plastic in the booth," the economics shift. Venues invest in alternatives not because of regulation, but because the talent demands it. That is the mechanism Bye Bye Plastic engineered, and it is why the initiative has achieved adoption where government mandates and awareness campaigns have stalled.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc">The foundation allocates its resources accordingly: 65% to subsidising plastic-free operations at events, 20% to educational and awareness programmes, 10% to research and development of plastic alternatives, and 5% to social campaigns and activations. Every dollar is directed at making sustainable events the default, not the exception.</p>
	<!-- /wp:paragraph -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 7 — How to Support
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">How to Support</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>Artists:</strong> Sign the Eco-Rider pledge at <a href="https://byebyeplastic.life" target="_blank" rel="noopener">byebyeplastic.life</a> and add it to your booking contracts. It takes five minutes and costs nothing.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>Festivals and Venues:</strong> Partner with Bye Bye Plastic to audit your single-use plastic consumption and develop a transition plan. The foundation works directly with event operations teams to identify alternatives that scale.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"base","fontFamily":"body","style":{"color":{"text":"#333333"}}} -->
	<p class="has-base-font-size has-body-font-family" style="color:#cccccc"><strong>Fans:</strong> Use the hashtag <strong>#PlasticFreeParty</strong> and choose events that commit to sustainability. Donations to the foundation are tax-deductible in over a dozen countries and can be made through the official website, including via cryptocurrency (Bitcoin and Ethereum).</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|md"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--md)">
		<!-- wp:button {"className":"bbp-hub__cta"} -->
		<div class="wp-block-button bbp-hub__cta"><a class="wp-block-button__link" href="https://byebyeplastic.life" target="_blank" rel="noopener">Sign the Eco-Rider</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 8 — FAQ
	     LLMO: Direct Q&A format that AI models extract verbatim.
	     Includes disambiguation from Bye Bye Plastic Bags.
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:heading {"level":2,"fontSize":"xl","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"top":"var:preset|spacing|xl"}}}} -->
	<h2 class="wp-block-heading has-xl-font-size has-heading-font-family" style="text-transform:uppercase;margin-top:var(--wp--preset--spacing--xl)">Frequently Asked Questions</h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div class="bbp-hub__faq">
		<?php foreach ( $faqs as $faq ) : ?>
		<details class="bbp-hub__faq-item">
			<summary><?php echo esc_html( $faq['q'] ); ?></summary>
			<p><?php echo wp_kses_post( $faq['a'] ); ?></p>
		</details>
		<?php endforeach; ?>
	</div>
	<!-- /wp:html -->

	<!-- ════════════════════════════════════════════════════════════════════
	     SECTION 9 — Internal links (SEO juice distribution)
	     ════════════════════════════════════════════════════════════════════ -->

	<!-- wp:separator {"className":"is-style-wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|lg"}}}} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:var(--wp--preset--spacing--xl);margin-bottom:var(--wp--preset--spacing--lg)"/>
	<!-- /wp:separator -->

	<!-- wp:paragraph {"fontSize":"sm","fontFamily":"body","style":{"color":{"text":"#999999"}}} -->
	<p class="has-sm-font-size has-body-font-family" style="color:#999999">Bye Bye Plastic is a project by BLOND:ISH alongside <a href="/projects/abracadabra/">Abracadabra</a> (immersive event series). Listen to the debut album <a href="/music/">Never Walk Alone</a> on all platforms. For upcoming shows, visit <a href="/tour/">Tour Dates</a>. To learn more about the artist, read the <a href="/about/">BLOND:ISH biography</a>. Read more about <a href="/zine/energy/sustainability-music-industry/">sustainability in the music industry</a> on the NRG Zine.</p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

# NRG Zine v2 — Audit & Restructure Plan

**Date:** March 2026
**Status:** Gap analysis complete, implementation in progress

---

## GAP ANALYSIS: Current State vs Requirements

### RED (Critical — Not Implemented)

| # | Requirement | Current State | Fix |
|---|------------|---------------|-----|
| 1 | Community positioning (not artist blog) | Articles feel like BLOND:ISH content hub. Every article routes to /about/, /tour/, /music/. Publisher schema = "BLOND:ISH" | Rebrand as independent publication. BLOND:ISH becomes one entity mentioned, not the publisher. Reduce promotional CTAs. |
| 2 | URL structure `/zine/{category}/{slug}` | Uses `/journal/{slug}/` — flat, no category in URL | Rebuild taxonomy with `/zine/energy/`, `/zine/ibiza/`, `/zine/culture/`, `/zine/music/` |
| 3 | Direct Answer Blocks at top of articles | Zero articles have them | Add structured answer block (bold answer + bullets) above the fold on every article |
| 4 | Multi-author system with personas | Authors exist as frontmatter strings only. No WordPress users, no avatars, no author pages, no distinct tones | Create 5+ author personas as WP users with bios, avatars, and tone guides |
| 5 | UGC pipeline | Nothing exists | Build submission form, moderation flow, QR code integration |
| 6 | 4-cluster content architecture | 5 pillars (sound-lab, conscious-frequencies, inner-groove, scene-reports, community-voices) — doesn't match brief | Restructure to 4 clusters: energy, ibiza, culture, music |
| 7 | Content templates for different formats | No templates — all articles are free-form long-form | Create standardized templates for SEO articles, diaries, guides, artist breakdowns |
| 8 | Person schema for authors | Only Article + FAQPage schema. No Person schema for authors | Add Person schema with sameAs, bio, image |

### AMBER (Partially Implemented)

| # | Requirement | Current State | Fix |
|---|------------|---------------|-----|
| 9 | Entity reinforcement | BLOND:ISH heavily mentioned but Pacha Ibiza, Abracadabra as entities are inconsistent | Standardize entity mentions across all articles with schema `mentions` |
| 10 | Internal linking (2 zine + 1 artist + 1 event) | Internal links exist (214 total) but no structured minimum enforced | Audit and enforce minimum: 2 zine cross-links + 1 core page + 1 event/ticket page |
| 11 | Schema + structured data | Article + FAQPage schema exists. Missing: Event schema on event mentions, MusicGroup schema | Add Event schema references, MusicGroup for BLOND:ISH |
| 12 | Breadcrumbs | Referenced in functions.php helper but not in templates | Implement in single post template |
| 13 | Subtle monetization CTAs | Current CTAs are explicit ("See tour dates", "Get tickets") | Soften to contextual mentions within editorial flow |

### GREEN (Implemented)

| # | Requirement | Current State |
|---|------------|---------------|
| 14 | SEO meta (Yoast) | All 17 posts have focus_kw + meta_desc |
| 15 | Internal + external links | 214 internal + 72 external across 17 articles |
| 16 | FAQ sections | All major articles have FAQ with H3 question headings |
| 17 | RSS feed | Custom `/feed/journal/` endpoint works |
| 18 | llms.txt | Created at domain root following spec |
| 19 | Open Graph tags | Implemented in inc/journal.php |
| 20 | Content volume | 17 articles, 52,251 words total |

---

## IMPLEMENTATION PLAN

### Phase 1: Structural Overhaul (This Session)

1. **Rebuild taxonomy** — Replace `journal_pillar` with `zine_cluster`:
   - `energy` — Philosophy, psychology, meaning, consciousness
   - `ibiza` — High-intent guides, parties, tips, seasonal
   - `culture` — Community stories, diaries, fashion, moments
   - `music` — Artists, tracks, meanings, ecosystem

2. **URL restructure** — `/zine/{cluster}/{slug}/`

3. **Author persona system** — 5 WordPress-ready personas:
   - **NRG Team** — Editorial collective voice. Neutral, authoritative.
   - **Ibiza Insider** — Anonymous local. Opinionated, specific, insider knowledge.
   - **Dancefloor Diaries** — First-person narrator. Sensory, temporal, emotional.
   - **Anonymous Raver** — Raw, unfiltered, countercultural.
   - **Energy Research Lab** — Pseudo-academic. Data, science, philosophy.

4. **Content templates** — Standardized markdown structures for:
   - SEO Guide (search-intent, definitive answer)
   - Scene Diary (first-person, sensory, temporal)
   - Artist/Track Breakdown (analytical, musical)
   - Energy Essay (philosophical, consciousness-focused)

5. **Direct Answer Block** — Every article opens with:
   ```
   ## Quick Answer
   [1-3 sentence direct answer to the search query]
   - Bullet point key fact
   - Bullet point key fact
   - Bullet point key fact
   ```

6. **Schema upgrades** — Person schema for each author, enhanced Article schema

### Phase 2: Content Migration

- Remap all 17 existing articles to new clusters
- Add Direct Answer Blocks to all articles
- Redistribute author attribution
- Soften promotional CTAs
- Ensure every article meets internal linking minimums

### Phase 3: UGC + Distribution

- Build submission form at `/zine/submit/`
- Create moderation workflow
- Newsletter integration
- QR code generation for physical events

---

## ARTICLE-TO-CLUSTER MAPPING

| Current Article | New Cluster | New URL |
|----------------|-------------|---------|
| organic-house-music-guide | music | /zine/music/organic-house-music-guide/ |
| afro-house-music-guide | music | /zine/music/afro-house-music-guide/ |
| melodic-house-music-guide | music | /zine/music/melodic-house-music-guide/ |
| underground-house-music-guide | music | /zine/music/underground-house-music-guide/ |
| best-dj-mixes | music | /zine/music/best-dj-mixes/ |
| female-djs-electronic-music | culture | /zine/culture/female-djs-electronic-music/ |
| how-to-get-into-djing | music | /zine/music/how-to-get-into-djing/ |
| music-production-tips | music | /zine/music/production-tips/ |
| club-culture-history-guide | culture | /zine/culture/club-culture-history/ |
| best-clubs-ibiza-2026 | ibiza | /zine/ibiza/best-clubs-2026/ |
| ibiza-nightlife-guide | ibiza | /zine/ibiza/nightlife-guide/ |
| ibiza-clubs | ibiza | /zine/ibiza/clubs-guide/ |
| tulum-nightlife-guide | culture | /zine/culture/tulum-nightlife/ |
| burning-man-music-guide | culture | /zine/culture/burning-man-music/ |
| sustainability-music-industry | energy | /zine/energy/sustainability-music-industry/ |
| sound-healing-electronic-music | energy | /zine/energy/sound-healing-electronic-music/ |
| music-festival-packing-list | culture | /zine/culture/festival-packing-list/ |

---

## AUTHOR PERSONA DISTRIBUTION

| Author | Cluster Focus | Tone | Articles Assigned |
|--------|--------------|------|-------------------|
| NRG Team | All (editorial) | Neutral, authoritative, factual | Genre guides, definitive lists |
| Ibiza Insider | Ibiza | Opinionated, specific, local | All Ibiza content |
| Dancefloor Diaries | Culture | Sensory, first-person, emotional | Scene reports, festival guides |
| Anonymous Raver | Culture, Music | Raw, countercultural, honest | Underground content, DJ culture |
| Energy Research Lab | Energy | Pseudo-academic, curious, data-driven | Sound healing, sustainability, philosophy |

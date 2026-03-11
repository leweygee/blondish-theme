# BLOND:ISH WordPress FSE Block Theme

Custom WordPress Full Site Editing (FSE) block theme for **blondish.world** — the official website of electronic music duo BLOND:ISH.

No page builders. Pure Gutenberg blocks, theme.json, and HTML templates.

**WordPress minimum:** 6.4 | **PHP minimum:** 8.0

---

## File Structure

```
blondish/                              ← Theme root (upload to wp-content/themes/)
├── style.css                          ← Theme header + CSS overrides
├── theme.json                         ← Design tokens: colors, fonts, spacing, layout
├── functions.php                      ← Theme setup, enqueues, helpers
├── json-ld-schemas.html               ← Structured data templates
│
├── templates/                         ← FSE page templates
│   ├── front-page.html                ← Homepage (hero, tour, music, store, journal)
│   ├── index.html                     ← Blog fallback / Journal archive
│   ├── page.html                      ← Generic page (720px constrained)
│   ├── single.html                    ← Single blog post
│   ├── archive.html                   ← Category / tag archive
│   ├── 404.html                       ← Error page ("Lost in the mix")
│   ├── full-bleed-landing.html        ← Project landing pages (Abracadabra, etc.)
│   ├── standard-content.html          ← About / Press pages
│   └── embed-page.html                ← Tour / Store pages (1200px for embeds)
│
├── parts/                             ← Reusable template parts
│   ├── header.html                    ← Sticky header with navigation
│   └── footer.html                    ← 4-column footer
│
├── patterns/                          ← Block patterns
│   ├── hero-cover.php                 ← Full-viewport hero with CTAs
│   ├── section-text-cta.php           ← Text section with heading + button
│   ├── project-feature-cover.php      ← 70vh cover for featuring a project
│   ├── journal-grid.php               ← 3-column latest posts grid
│   └── embed-section.php              ← Embed container with heading + CTA
│
└── assets/
    ├── fonts/                         ← Self-hosted .woff2 font files (add these)
    ├── css/
    │   └── lite-yt-embed.css          ← lite-youtube-embed styles (add this)
    ├── js/
    │   ├── lite-yt-embed.js           ← lite-youtube-embed (add this)
    │   └── embeds.js                  ← IntersectionObserver for Seated/Shopify
    └── images/                        ← Hero images in WebP (add these)
```

---

## Quick Start

### 1. Install the Theme

**Option A — ZIP upload:**
Rename the folder to `blondish/`, zip it, then upload via Appearance > Themes > Add New > Upload.

**Option B — FTP/SFTP:**
Upload the folder to `/wp-content/themes/blondish/` and activate.

### 2. Add Font Files

Download [Montserrat](https://fonts.google.com/specimen/Montserrat) and [Inter](https://fonts.google.com/specimen/Inter). Convert to `.woff2` via [google-webfonts-helper](https://gwfh.mranftl.com/). Place in `assets/fonts/`:

- `Montserrat-Regular.woff2`
- `Montserrat-Bold.woff2`
- `Inter-Regular.woff2`
- `Inter-SemiBold.woff2`

### 3. Add Hero Images

Place WebP hero images in `assets/images/`:

- `hero-homepage-1920.webp` (1920x1080)
- `hero-homepage-768.webp` (768x432)
- `hero-abracadabra-1920.webp` (1920x1080)

### 4. Add lite-youtube-embed

Download from [paulirish/lite-youtube-embed](https://github.com/paulirish/lite-youtube-embed):

- `assets/css/lite-yt-embed.css`
- `assets/js/lite-yt-embed.js`

### 5. Configure WordPress

- **Settings > Permalinks** — Set to "Post name"
- **Settings > Reading** — Set homepage to a static page named "Home"
- **Appearance > Editor** — Verify the Site Editor loads your templates

---

## Page Setup

Create these pages in WordPress and assign the correct template:

| Page              | Slug                  | Template            |
|-------------------|-----------------------|---------------------|
| Home              | `/`                   | Front Page (auto)   |
| About             | `/about/`             | Standard Content    |
| Music             | `/music/`             | Standard Content    |
| Tour              | `/tour/`              | Embed Page          |
| Store             | `/store/`             | Embed Page          |
| Abracadabra       | `/abracadabra/`       | Full-Bleed Landing  |
| Bye Bye Plastic   | `/bye-bye-plastic/`   | Full-Bleed Landing  |
| Abraca-Dahlia     | `/abraca-dahlia/`     | Full-Bleed Landing  |
| Press             | `/press/`             | Standard Content    |
| Journal           | `/journal/`           | (set as Posts page) |

---

## Customisation

### Brand Colors

Edit `theme.json` → `settings.color.palette`:

| Slug           | Default   | Usage                              |
|----------------|-----------|------------------------------------|
| brand-primary  | `#D4A853` | Gold — primary accent              |
| brand-accent   | `#E8C547` | Bright gold — hover states         |
| dark-grey      | `#333333` | Borders, separators                |
| light-grey     | `#999999` | Secondary text, dates, captions    |
| black          | `#000000` | Page backgrounds                   |
| white          | `#FFFFFF` | Primary text, button fills         |

### Fonts

Edit `theme.json` → `settings.typography.fontFamilies`:

| Slot    | Default     | Weights   | Usage                  |
|---------|-------------|-----------|------------------------|
| Heading | Montserrat  | 400, 700  | Headings, nav, buttons |
| Body    | Inter       | 400, 600  | Body text, paragraphs  |

To swap fonts: update `fontFamily`, `fontFace` entries, and add matching `.woff2` files.

### Custom Image Sizes

Registered in `functions.php`:

| Name         | Size      | Crop | Use Case                 |
|--------------|-----------|------|--------------------------|
| hero-desktop | 1920x1080 | Hard | Desktop hero images      |
| hero-medium  | 1280x720  | Hard | Tablet hero images       |
| hero-mobile  | 768x432   | Hard | Mobile hero images       |
| card-square  | 600x600   | Hard | Journal grid, press      |
| card-wide    | 800x450   | Hard | Music / discography grid |

---

## Third-Party Embeds

The theme uses IntersectionObserver (`embeds.js`) to lazy-load third-party scripts only when the user scrolls near them.

### Seated.com (Tour Dates)

Add a Custom HTML block to the Tour page:

```html
<div id="seated-container" data-seated-id="YOUR_SEATED_WIDGET_ID"></div>
```

### Shopify Buy Button (Store)

Add a Custom HTML block to the Store page:

```html
<div id="shopify-container"
     data-shopify-domain="YOUR_STORE.myshopify.com"
     data-shopify-token="YOUR_STOREFRONT_TOKEN">
</div>
```

### YouTube

Use `<lite-youtube>` anywhere:

```html
<lite-youtube videoid="YOUTUBE_VIDEO_ID" playlabel="Play video"></lite-youtube>
```

---

## JSON-LD Structured Data

Copy schemas from `json-ld-schemas.html` into Custom HTML blocks on the relevant pages. Replace all `REPLACE_` / `YOUR_` placeholders.

| Schema               | Page              |
|----------------------|-------------------|
| MusicGroup + Person  | Homepage or About |
| WebSite + Search     | Homepage          |
| MusicEvent / Event   | Tour              |
| Blog + Article       | Journal           |

Validate at: https://search.google.com/test/rich-results

---

## Recommended Plugins

| Plugin                 | Purpose                                   |
|------------------------|-------------------------------------------|
| Yoast SEO              | Meta, sitemaps, breadcrumbs, schema       |
| WP Rocket (paid)       | Caching, critical CSS, JS delay           |
| Imagify                | WebP conversion, image compression        |
| Regenerate Thumbnails  | Apply custom sizes to existing media      |

---

## Performance Targets

| Metric | Target  | Strategy                                 |
|--------|---------|------------------------------------------|
| LCP    | < 2.5s  | Hero preload, WebP, self-hosted fonts    |
| CLS    | < 0.1   | Explicit image sizes, no layout shift    |
| INP    | < 200ms | Deferred JS, IntersectionObserver embeds |

---

## Accessibility

- Skip-to-content link in header
- `aria-label` on all navigation landmarks
- `focus-visible` outlines (2px white)
- 44px minimum touch targets on mobile
- Semantic HTML5 (`<main>`, `<nav>`, `<header>`, `<footer>`)
- WCAG 2.1 AA colour contrast

---

## Pre-Launch Checklist

- [ ] Font files placed in `assets/fonts/`
- [ ] Hero images placed in `assets/images/`
- [ ] lite-youtube-embed files added
- [ ] All 10 pages created with correct templates assigned
- [ ] Seated widget ID configured on Tour page
- [ ] Shopify Buy Button configured on Store page
- [ ] JSON-LD schemas pasted and validated
- [ ] Yoast SEO configured (org name, logo, social profiles)
- [ ] PageSpeed Insights: LCP < 2.5s on homepage
- [ ] Rich Results Test: valid schemas on homepage + tour
- [ ] Mobile check: hamburger nav, hero sizing, grid at 375px
- [ ] Keyboard navigation: skip-to-content, focus rings visible
- [ ] No console errors in DevTools

---

*BLOND:ISH Theme — generated March 2026*

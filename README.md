# Entire RCM — WordPress block theme

A child theme of **Twenty Twenty-Five** that carries the Entire RCM design system.
It exists so the brand tokens live in one editable place and the pages can be
built entirely from native Gutenberg blocks.

## What is in here

| Path | Purpose |
| --- | --- |
| `theme.json` | The whole design system: brand palette, Plus Jakarta Sans + Inter (self-hosted), the type scale from the design spec, component radii and shadows, and per-block styles. Editable in **Appearance → Editor → Styles**. |
| `parts/header.html` | Announcement bar + logo/navigation/CTA header. Editable in **Appearance → Editor → Patterns → Header**. |
| `parts/footer.html` | Footer CTA bar, link columns, brand block, legal bar. |
| `templates/page-no-title.html` | Full-bleed page template (no theme title) used by the landing page. |
| `templates/home.html`, `templates/index.html` | Blog index with a card grid. |
| `templates/single.html` | Article layout. |
| `templates/page.html` | Standard page layout for Privacy / Terms. |
| `assets/css/entire-rcm.css` | Block-style variants (`is-style-ercm-card`, `-card`, `-tint-panel`, `-dark-panel`, `-pill`, `-badge`, `-eyebrow`, `-check`), the accordion chevron, form chrome and the sticky header. No layouts. |
| `assets/js/entire-rcm.js` | The revenue-recovery calculator and the sticky-header shadow. Progressive: the page is complete without it. |
| `functions.php` | Registers the block styles (so they appear in the editor's Styles panel), the calculator shortcode `[entire_rcm_calculator]`, and enqueues the two asset files. |

## Design tokens

* Navy `#122056`, Indigo `#5B65DC`, Tint `#EEEEFD`, Canvas `#FAFAFD`, White `#FFFFFF`
* Headlines: Plus Jakarta Sans (700, tight tracking) · Body and data: Inter
* Radii: `0.5rem` controls, `0.75rem`–`1rem` cards, `9999px` pills
* Shadows: card rest, hover lift, float layers — all tinted with the navy, per the spec

## Content

Pages are native blocks (groups, columns, grid, headings, buttons, details,
shortcode), so every section is edited in the block editor exactly like any other
WordPress content. Nothing is rendered from hard-coded HTML.

/* eslint-disable */
/**
 * Tailwind configuration taken verbatim from the exported Stitch design
 * (the inline <script id="tailwind-config"> in code.html), so the compiled
 * utilities are identical to the ones the design was authored against.
 *
 * `content` covers the block-theme parts/templates, the generated page markup
 * in content/ (the WordPress page bodies, kept here so Tailwind can see every
 * class that lives in the database) and the front-end script, which toggles a
 * handful of utilities at runtime.
 */
module.exports = {
  darkMode: "class",
  content: {
    files: [
      "./parts/**/*.html",
      "./templates/**/*.html",
      "./content/**/*.html",
      "./assets/js/**/*.js",
      "./functions.php",
      // The page bodies live in the WordPress database, so their classes are
      // scanned from the spec that generates them plus the form templates.
      "../spec_shell.py",
      "../spec_body_a.py",
      "../spec_body_b.py",
      "../ddl.py",
      "../forms_design.py",
    ],
    extract: {
      // Contact Form 7 spells utility classes as `class:py-3.5`; strip the
      // prefix so the utilities are still compiled. Split on whitespace and
      // quotes only — arbitrary values like `max-w-[1440px]` and
      // `shadow-[0_1px_8px_rgba(0,0,0,0.04)]` contain brackets and commas.
      py: ( content ) => content
        .replace( /class:/g, " " )
        .split( /[\s"']+/ )
        .filter( Boolean ),
    },
  },
  safelist: [
    "hidden", "rotate-45", "font-bold", "shadow-sm", "text-primary",
    "text-on-surface-variant", "bg-surface-container-lowest",
  ],
  theme: {
    extend: {
      "colors": {
        "on-primary-fixed": "#06164d",
        "surface": "#f8f9ff",
        "surface-variant": "#d9e3f4",
        "surface-container-low": "#eef4ff",
        "on-surface": "#121c28",
        "on-secondary-fixed": "#00036b",
        "surface-tint": "#4f5b94",
        "surface-container": "#e5eeff",
        "secondary": "#4750c7",
        "tertiary-container": "#002b1b",
        "secondary-fixed": "#e0e0ff",
        "primary-fixed": "#dde1ff",
        "primary-fixed-dim": "#b8c3ff",
        "surface-container-highest": "#d9e3f4",
        "inverse-surface": "#27313e",
        "surface-container-high": "#dfe9fa",
        "on-surface-variant": "#45464f",
        "on-secondary": "#ffffff",
        "on-tertiary-fixed-variant": "#005236",
        "surface-container-lowest": "#ffffff",
        "secondary-fixed-dim": "#bec2ff",
        "on-primary": "#ffffff",
        "on-tertiary-container": "#009f6e",
        "on-error-container": "#93000a",
        "tertiary": "#00130a",
        "on-background": "#121c28",
        "inverse-primary": "#b8c3ff",
        "tertiary-fixed-dim": "#4edea3",
        "surface-bright": "#f8f9ff",
        "on-primary-fixed-variant": "#37437a",
        "secondary-container": "#7b85fe",
        "background": "#f8f9ff",
        "inverse-on-surface": "#eaf1ff",
        "on-tertiary-fixed": "#002113",
        "error": "#ba1a1a",
        "tertiary-fixed": "#6ffbbe",
        "outline-variant": "#c6c5d1",
        "error-container": "#ffdad6",
        "outline": "#767680",
        "on-error": "#ffffff",
        "on-primary-container": "#7c89c5",
        "surface-dim": "#d1dbec",
        "on-tertiary": "#ffffff",
        "on-secondary-container": "#000794",
        "primary": "#000a39",
        "on-secondary-fixed-variant": "#2d36ae",
        "primary-container": "#122056"
      },
      "borderRadius": {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "full": "9999px"
      },
      "spacing": {
        "space-xs": "0.25rem",
        "margin-mobile": "1rem",
        "space-sm": "0.5rem",
        "space-md": "1rem",
        "gutter-mobile": "1rem",
        "gutter": "1.5rem",
        "margin-desktop-wide": "3rem",
        "margin": "2rem",
        "space-lg": "1.5rem",
        "space-xl": "2.5rem"
      },
      "fontFamily": {
        "headline-xl-mobile": ["Plus Jakarta Sans"],
        "body-md": ["Inter"],
        "body-sm": ["Inter"],
        "label-md": ["Inter"],
        "label-sm": ["Inter"],
        "headline-md": ["Plus Jakarta Sans"],
        "data-metric": ["Plus Jakarta Sans"],
        "label-lg": ["Inter"],
        "headline-xl": ["Plus Jakarta Sans"],
        "display-lg-mobile": ["Plus Jakarta Sans"],
        "display-lg": ["Plus Jakarta Sans"],
        "headline-lg": ["Plus Jakarta Sans"],
        "body-lg": ["Inter"],
        "headline-sm": ["Plus Jakarta Sans"]
      },
      "fontSize": {
        "headline-xl-mobile": ["26px", { "lineHeight": "34px", "letterSpacing": "-0.015em", "fontWeight": "700" }],
        "body-md": ["14px", { "lineHeight": "22px", "letterSpacing": "0em", "fontWeight": "400" }],
        "body-sm": ["13px", { "lineHeight": "18px", "letterSpacing": "0em", "fontWeight": "400" }],
        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.01em", "fontWeight": "600" }],
        "label-sm": ["11px", { "lineHeight": "14px", "letterSpacing": "0.02em", "fontWeight": "600" }],
        "headline-md": ["22px", { "lineHeight": "30px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
        "data-metric": ["30px", { "lineHeight": "36px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
        "label-lg": ["14px", { "lineHeight": "20px", "letterSpacing": "0.005em", "fontWeight": "600" }],
        "headline-xl": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
        "display-lg-mobile": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.025em", "fontWeight": "700" }],
        "headline-lg": ["28px", { "lineHeight": "36px", "letterSpacing": "-0.015em", "fontWeight": "600" }],
        "body-lg": ["16px", { "lineHeight": "26px", "letterSpacing": "-0.005em", "fontWeight": "400" }],
        "headline-sm": ["18px", { "lineHeight": "26px", "letterSpacing": "-0.005em", "fontWeight": "600" }]
      }
    }
  }
};

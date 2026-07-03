# Living Archive Bootstrap Landing Page

A responsive Bootstrap 5 PSD/Figma-to-HTML style conversion for `thomasalexanderthevoice.com/living-archive`.

## Files

- `index.html` — main landing page
- `assets/css/style.css` — custom responsive styling and lightweight animations
- `assets/js/main.js` — sticky header, reveal-on-scroll animation, active menu state
- `assets/img/` — SVG placeholders for crest, portrait, and social preview

## How to use

Open `index.html` in a browser, or upload the folder to hosting. Replace the SVG placeholders with the real Thomas Alexander/crest images when available.

## Performance notes

- Bootstrap CDN only; no jQuery, no heavy animation library
- CSS + IntersectionObserver animations only
- Responsive images and lazy loading used
- `prefers-reduced-motion` supported for accessibility

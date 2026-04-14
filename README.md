# wp-video-modal

Lightweight block-ready media modal for WordPress. Any `<a href>` that points at a supported media type (images, videos, YouTube, Vimeo) is automatically wrapped in a [Lity](https://sorgalla.com/lity/) lightbox trigger — no per-link configuration, no custom block, no build step.

Works with every core block (`core/button`, `core/image`, `core/paragraph`, `core/gallery`, …) and the Site Editor without the editor knowing the plugin exists.

## How it works

1. On the frontend, the plugin scans every `<a href>` in `document.body` after `DOMContentLoaded`.
2. Each href is matched against the enabled media-type patterns.
3. Matches get a `data-lity` attribute — Lity handles the lightbox from there.
4. A `MutationObserver` catches links added later (block interactivity updates, AJAX content).

Opting a single link *out* of the modal: add `data-video-modal-skip` to the `<a>`.

## Settings

`Settings → Video Modal`. Four checkboxes, all checked by default:

| Group   | Matches |
|---------|---------|
| Images  | `.jpg`, `.jpeg`, `.png`, `.gif`, `.webp`, `.svg`, `.avif` |
| Videos  | `.mp4`, `.webm`, `.ogv`, `.mov`, `.m4v` |
| YouTube | `youtube.com/watch`, `youtube.com/embed`, `youtube.com/shorts`, `youtu.be` |
| Vimeo   | `vimeo.com/{id}` |

If every group is unchecked, nothing enqueues — zero frontend cost.

## Dependencies

- WordPress 6.6+
- PHP 7.4+
- jQuery (shipped by WP core; Lity v2 depends on it)

## Vendored

- [Lity](https://github.com/jsor/lity) v2.4.1 — MIT © Jan Sorgalla. License at `assets/vendor/LICENSE.lity`.

## Structure

```
wp-video-modal/
├── wp-video-modal.php            # plugin header + inc/ auto-loader
├── inc/
│   ├── options/
│   │   └── options-defaults.php  # default options + accessor
│   ├── settings/
│   │   └── settings-page.php     # Settings → Video Modal
│   └── enqueues/
│       └── enqueue-assets.php    # conditional Lity + wrapper enqueue
└── assets/
    ├── js/
    │   └── video-modal.js        # scan <a>, apply data-lity
    └── vendor/
        ├── lity.min.css
        ├── lity.min.js
        └── LICENSE.lity
```

Every feature lives in a focused, single-responsibility file under `inc/<category>/` — the same auto-loader pattern as [diffyweb/wp-basetheme](https://github.com/diffyweb/wp-basetheme). Drop a new file in and it loads on the next request.

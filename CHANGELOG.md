# Changelog

## 0.1.0

- Initial scaffold
- `inc/` auto-loader modeled after diffyweb/wp-basetheme
- Settings page at `Settings → Video Modal` with checkboxes for images / videos / YouTube / Vimeo (all enabled by default)
- Frontend scanner that auto-applies `data-lity` to any `<a href>` whose target matches an enabled media type
- MutationObserver catches links added after initial load
- Vendored Lity v2.4.1 (MIT)

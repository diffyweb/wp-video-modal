/**
 * Video Modal wrapper.
 *
 * Scans every <a href> in the document body after DOMContentLoaded,
 * tests each href against the enabled media-type patterns emitted
 * by the PHP side in window.wpVideoModalConfig, and attaches the
 * data-lity attribute when it matches. Lity then picks up the
 * attribute and handles the lightbox lifecycle itself.
 *
 * Runs once at load plus a MutationObserver for nodes added later
 * (Gutenberg previews, AJAX content, block interactivity updates).
 */
(function () {
	'use strict';

	var config = window.wpVideoModalConfig || { enabled: [] };
	if (!config.enabled || !config.enabled.length) {
		return;
	}

	var patterns = {
		images: /\.(jpe?g|png|gif|webp|svg|avif)(\?.*)?$/i,
		videos: /\.(mp4|webm|ogv|mov|m4v)(\?.*)?$/i,
		youtube: /(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)[\w-]+/i,
		vimeo: /vimeo\.com\/(?:video\/)?\d+/i
	};

	function shouldWrap(href) {
		if (!href) return false;
		for (var i = 0; i < config.enabled.length; i++) {
			var type = config.enabled[i];
			if (patterns[type] && patterns[type].test(href)) {
				return true;
			}
		}
		return false;
	}

	function enhance(link) {
		if (!link || link.nodeType !== 1 || link.tagName !== 'A') return;
		if (link.hasAttribute('data-lity')) return;
		if (link.hasAttribute('data-video-modal-skip')) return;
		var href = link.getAttribute('href');
		if (!shouldWrap(href)) return;
		link.setAttribute('data-lity', '');
		link.classList.add('wp-video-modal-trigger');
	}

	function scan(root) {
		var links = (root || document).querySelectorAll('a[href]');
		for (var i = 0; i < links.length; i++) {
			enhance(links[i]);
		}
	}

	function init() {
		scan(document);

		if (!window.MutationObserver) return;
		var observer = new MutationObserver(function (mutations) {
			for (var i = 0; i < mutations.length; i++) {
				var added = mutations[i].addedNodes;
				for (var j = 0; j < added.length; j++) {
					var node = added[j];
					if (node.nodeType !== 1) continue;
					if (node.tagName === 'A') {
						enhance(node);
					} else if (node.querySelectorAll) {
						scan(node);
					}
				}
			}
		});
		observer.observe(document.body, { childList: true, subtree: true });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();

<?php
/**
 * Enqueue Lity + the wrapper script on the frontend.
 *
 * Skipped entirely if the admin disabled every media type in the
 * settings page — no point loading Lity if nothing will match.
 *
 * The wrapper script receives the enabled types via
 * wp_add_inline_script so the config travels with the main asset
 * and there's no extra HTTP request.
 *
 * @package wp-video-modal
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'wp_video_modal_enqueue_assets' );

function wp_video_modal_enqueue_assets() {
	$enabled = wp_video_modal_enabled_types();
	if ( empty( $enabled ) ) {
		return;
	}

	wp_enqueue_style(
		'lity',
		WP_VIDEO_MODAL_URL . 'assets/vendor/lity.min.css',
		array(),
		'2.4.1'
	);

	wp_enqueue_script(
		'lity',
		WP_VIDEO_MODAL_URL . 'assets/vendor/lity.min.js',
		array( 'jquery' ),
		'2.4.1',
		true
	);

	wp_enqueue_script(
		'wp-video-modal',
		WP_VIDEO_MODAL_URL . 'assets/js/video-modal.js',
		array( 'lity' ),
		WP_VIDEO_MODAL_VERSION,
		true
	);

	$config = array( 'enabled' => $enabled );

	wp_add_inline_script(
		'wp-video-modal',
		'window.wpVideoModalConfig = ' . wp_json_encode( $config ) . ';',
		'before'
	);
}

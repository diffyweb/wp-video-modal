<?php
/**
 * Option defaults + accessor.
 *
 * Single source of truth for the enabled media-type list. Every
 * other file reads from wp_video_modal_get_options() so defaults
 * and user overrides stay consistent.
 *
 * @package wp-video-modal
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default settings — every media type enabled.
 */
function wp_video_modal_default_options() {
	return array(
		'images'  => 1,
		'videos'  => 1,
		'youtube' => 1,
		'vimeo'   => 1,
	);
}

/**
 * Current options, merged over defaults so missing keys fall through.
 */
function wp_video_modal_get_options() {
	$stored = get_option( 'wp_video_modal_options', array() );
	return wp_parse_args( (array) $stored, wp_video_modal_default_options() );
}

/**
 * Return only the enabled media-type slugs so the frontend JS
 * receives a compact config.
 */
function wp_video_modal_enabled_types() {
	$opts    = wp_video_modal_get_options();
	$enabled = array();
	foreach ( wp_video_modal_default_options() as $key => $_default ) {
		if ( ! empty( $opts[ $key ] ) ) {
			$enabled[] = $key;
		}
	}
	return $enabled;
}

<?php
/**
 * Plugin Name:       Video Modal
 * Plugin URI:        https://github.com/diffyweb/wp-video-modal
 * Description:       Lightweight block-ready media modal. Auto-wraps any <a href> that points at a supported media type (images, videos, YouTube, Vimeo) with a Lity lightbox trigger. Works with every core block and the Site Editor without configuration.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Author:            Diffy Web
 * Author URI:        https://github.com/diffyweb
 * Text Domain:       wp-video-modal
 *
 * Bundles Lity (https://sorgalla.com/lity/) — MIT © 2015–2020 Jan Sorgalla.
 * Full license text at assets/vendor/LICENSE.lity.
 *
 * @package wp-video-modal
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_VIDEO_MODAL_VERSION', '0.1.0' );
define( 'WP_VIDEO_MODAL_FILE', __FILE__ );
define( 'WP_VIDEO_MODAL_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_VIDEO_MODAL_URL', plugin_dir_url( __FILE__ ) );

/**
 * Auto-load every PHP file in inc/<category>/ so plugin features live
 * in focused, single-responsibility files. Drop a new file into any
 * inc/<category>/ directory and it will be picked up on the next load.
 */
foreach ( (array) glob( WP_VIDEO_MODAL_DIR . 'inc/*/*.php' ) as $wp_video_modal_include ) {
	require_once $wp_video_modal_include;
}
unset( $wp_video_modal_include );

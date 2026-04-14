<?php
/**
 * Settings page at Settings → Video Modal.
 *
 * Renders four checkboxes for the supported media-type groups and
 * persists them as the single `wp_video_modal_options` array so the
 * option row count stays at one regardless of how many groups ship.
 *
 * @package wp-video-modal
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'wp_video_modal_register_settings_page' );

function wp_video_modal_register_settings_page() {
	add_options_page(
		__( 'Video Modal', 'wp-video-modal' ),
		__( 'Video Modal', 'wp-video-modal' ),
		'manage_options',
		'wp-video-modal',
		'wp_video_modal_render_settings_page'
	);
}

add_action( 'admin_init', 'wp_video_modal_register_settings' );

function wp_video_modal_register_settings() {
	register_setting(
		'wp_video_modal',
		'wp_video_modal_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'wp_video_modal_sanitize_options',
			'default'           => wp_video_modal_default_options(),
		)
	);

	add_settings_section(
		'wp_video_modal_section_main',
		__( 'Media types', 'wp-video-modal' ),
		'wp_video_modal_render_section_intro',
		'wp-video-modal'
	);

	$fields = array(
		'images'  => __( 'Images (.jpg, .jpeg, .png, .gif, .webp, .svg, .avif)', 'wp-video-modal' ),
		'videos'  => __( 'Videos (.mp4, .webm, .ogv, .mov, .m4v)', 'wp-video-modal' ),
		'youtube' => __( 'YouTube (youtube.com, youtu.be)', 'wp-video-modal' ),
		'vimeo'   => __( 'Vimeo (vimeo.com)', 'wp-video-modal' ),
	);

	foreach ( $fields as $key => $label ) {
		add_settings_field(
			'wp_video_modal_' . $key,
			$label,
			'wp_video_modal_render_checkbox_field',
			'wp-video-modal',
			'wp_video_modal_section_main',
			array( 'key' => $key, 'label_for' => 'wp_video_modal_' . $key )
		);
	}
}

function wp_video_modal_sanitize_options( $input ) {
	$clean = array();
	foreach ( array_keys( wp_video_modal_default_options() ) as $key ) {
		$clean[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
	}
	return $clean;
}

function wp_video_modal_render_section_intro() {
	echo '<p>';
	esc_html_e(
		'Any <a href> link whose target matches an enabled media type will automatically open in a Lity lightbox. Works with every core block and the Site Editor — no per-link configuration needed.',
		'wp-video-modal'
	);
	echo '</p>';
}

function wp_video_modal_render_checkbox_field( $args ) {
	$opts    = wp_video_modal_get_options();
	$key     = $args['key'];
	$checked = ! empty( $opts[ $key ] ) ? 'checked' : '';
	printf(
		'<input type="checkbox" id="wp_video_modal_%1$s" name="wp_video_modal_options[%1$s]" value="1" %2$s />',
		esc_attr( $key ),
		esc_attr( $checked )
	);
}

function wp_video_modal_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'wp_video_modal' );
			do_settings_sections( 'wp-video-modal' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Seed defaults on activation so the settings page shows every
 * media type already checked for a brand-new install.
 */
register_activation_hook( WP_VIDEO_MODAL_FILE, 'wp_video_modal_seed_defaults' );

function wp_video_modal_seed_defaults() {
	if ( false === get_option( 'wp_video_modal_options' ) ) {
		add_option( 'wp_video_modal_options', wp_video_modal_default_options() );
	}
}

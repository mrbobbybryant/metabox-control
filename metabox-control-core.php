<?php
/**
 * Plugin Name: Metabox Control
 * Plugin URI:  https://github.com/mrbobbybryant/metabox-control
 * Description: Provides a Javascript and PHP API to conditionally show metaboxes on the page post type based on the selected Page Template.
 * Version:     0.1.0
 * Author:      Bobby Bryant
 * Author URI:  https://twitter.com/mrbobbybryant
 * License:     GPLv2+
 * Text Domain: mb_control
 * Domain Path: /languages
 */

namespace metabox_control;

/**
 * Useful Global Constants
 */
define( 'MBCONTROL_VERSION', '0.1.0' );
define( 'MBCONTROL_URL',     plugin_dir_url( __FILE__ ) );
define( 'MBCONTROL_PATH',    dirname( __FILE__ ) . '/' );

/**
 * Load all internal PHP Files
 */
function mb_control_init() {
	require_once( MBCONTROL_PATH . '/includes/metabox-control-utils.php' );
	require_once( MBCONTROL_PATH . '/includes/metabox-control-api.php' );
	require_once( MBCONTROL_PATH . '/includes/metabox-control-internal-api.php' );
	require_once( MBCONTROL_PATH . '/includes/metabox-control-ajax.php' );
}

/**
 * Activate the plugin
 */
function mb_control_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	mb_control_init();

}
register_activation_hook( __FILE__, __NAMESPACE__ . '\mb_control_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function wp_autosearch_deactivate() {
	delete_option( 'metabox_control_current_templates' );
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\mb_control_deactivate' );

/**
 * Kicks Everything Off
 */
add_action( 'init', __NAMESPACE__ . '\mb_control_init' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_mb_control_scripts' );

/**
 * Load Required Javascript Files
 */
function load_mb_control_scripts() {
	$post_type = get_post_type();
	$type = apply_filters( 'set_metabox_control_post_type', 'page' );

	if ( $type === $post_type ) {
		wp_enqueue_script( 'mb-control', MBCONTROL_URL . '/js/metabox-control.js', array( 'underscore', 'lodash' ), '0.0.1', true );
		wp_enqueue_script( 'lodash', MBCONTROL_URL . "/vendor/lodash.min.js", array( 'underscore' ), '3.0.1' ,true );
		wp_localize_script( 'mb-control', 'mbControl', array(
				'security'  =>  wp_create_nonce( 'metabox-control-nonce' )
		) );
	}
}

<?php
namespace metabox_control;

/**
 * Useful Global Constants
 */
 if ( !defined( 'MBCONTROL_PATH' ) ) {
 	define( 'MBCONTROL_PATH', dirname( __FILE__ ) );
 }

 if ( !defined( 'MBCONTROL_URL' ) ) {
 	define( 'MBCONTROL_URL', get_stylesheet_directory_uri() . '/vendor' );
 }

 if ( !defined( 'MBCONTROL_VERSION' ) ) {
 	define( 'MBCONTROL_VERSION', '0.2.0' );
 }

 require_once( MBCONTROL_PATH . '/includes/metabox-control-utils.php' );
 require_once( MBCONTROL_PATH . '/includes/metabox-control-api.php' );
 require_once( MBCONTROL_PATH . '/includes/metabox-control-internal-api.php' );
 require_once( MBCONTROL_PATH . '/includes/metabox-control-ajax.php' );

 /**
  * Kicks Everything Off
  */
 add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_mb_control_scripts' );


/**
 * Load Required Javascript Files
 */
function load_mb_control_scripts() {
	$post_type = get_post_type();
	$type = apply_filters( 'set_metabox_control_post_type', 'page' );

	if ( $type === $post_type ) {
		wp_enqueue_script( 'mb-control', MBCONTROL_URL . '/developwithwp/metabox-control/js/metabox-control.js', array( 'underscore', 'lodash' ), MBCONTROL_VERSION, true );
		wp_enqueue_script( 'lodash', MBCONTROL_URL . "/developwithwp/metabox-control/vendor/lodash.min.js", array( 'underscore' ), '3.0.1' ,true );
		wp_localize_script( 'mb-control', 'mbControl', array(
				'security'  =>  wp_create_nonce( 'metabox-control-nonce' ),
                'baseURL'   =>  site_url()
		) );
	}
}

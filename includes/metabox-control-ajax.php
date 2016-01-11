<?php
/**
 * Summary (no period for file headers)
 *
 * Description. (use period)
 *
 * @link URL
 * @since x.x.x (if available)
 *
 * @package WordPress
 * @subpackage Component
 */

namespace metabox_control\ajax;

function get_registered_templates() {

	if ( ! check_ajax_referer( 'metabox-control-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid Nonce' );
	}

	$test = 0;

	wp_send_json_success('working');
}

add_action( 'wp_ajax_get_registered_templates', __NAMESPACE__ . '\get_registered_templates' );
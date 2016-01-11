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

	$registered_templates = get_option( 'metabox_control_current_templates' );

	wp_send_json_success($registered_templates);
}

add_action( 'wp_ajax_get_registered_templates', __NAMESPACE__ . '\get_registered_templates' );
<?php
/**
 * Admin Ajax Request
 *
 * Description. PUsed by metabox control to fetch templates registered via PHP..
 *
 * @since 0.0.1
 *
 * @package WordPress
 * @subpackage Component
 */

namespace metabox_control\ajax;

/**
 * Returns via Ajax the current templates saved into WordPress. Uses get_option.
 */
function get_registered_templates() {

	if ( ! check_ajax_referer( 'metabox-control-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid Nonce' );
	}

	$registered_templates = get_option( 'metabox_control_current_templates' );

	wp_send_json_success($registered_templates);
}

add_action( 'wp_ajax_get_registered_templates', __NAMESPACE__ . '\get_registered_templates' );
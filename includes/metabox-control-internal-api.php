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
namespace metabox_control\API\Internal;

function remove_existing_template( $collection, $template ) {
	return array_filter( $collection, function( $data ) use ($template) {
		return $data['template'] !== $template;
	} );
}

function return_existing_template( $collection, $template ) {
	return array_filter( $collection, function( $data ) use ($template) {
		return $data['template'] === $template;
	} );
}

function add_template( $collection, $template ) {
	return array_merge( $collection, array( $template ) );
}

function update_metabox_option( $template ) {
	return update_option( 'metabox_control_current_templates', $template );
}

function get_metabox_option() {
	return get_option( 'metabox_control_current_templates' );
}
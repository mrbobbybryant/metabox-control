<?php
/**
 * internal API
 *
 * Description. Primary methods used by the external facing APIs. This methods handle
 * the actual processing of metabox templates.
 *
 * @since 0.0.1
 *
 * @package WordPress
 * @subpackage Component
 */
namespace metabox_control\API\Internal;

/**
 * Removes an existing template from the current array of templates
 * @param $collection - array
 * @param $template - string
 *
 * @return array
 */
function remove_existing_template( $collection, $template ) {
	return array_filter( $collection, function( $data ) use ($template) {
		return $data['template'] !== $template;
	} );
}

/**
 * Returns an existing template, if it exists. Returns and empty array if the template
 * does not exist.
 * @param $collection - array
 * @param $template - string
 *
 * @return array
 */
function return_existing_template( $collection, $template ) {
	return array_filter( $collection, function( $data ) use ($template) {
		return $data['template'] === $template;
	} );
}

/**
 * Adds a new template to the array of existing templates
 * @param $collection - array
 * @param $template - string
 *
 * @return array
 */
function add_template( $collection, $template ) {
	return array_merge( $collection, array( $template ) );
}

/**
 * Calls update_option to save and update the current list of registered templates.
 * @param $template
 *
 * @return bool
 */
function update_metabox_option( $template ) {
	return update_option( 'metabox_control_current_templates', $template );
}

/**
 * Fetches the current array of templates via get_option.
 * @return mixed|void
 */
function get_metabox_option() {
	return get_option( 'metabox_control_current_templates' );
}
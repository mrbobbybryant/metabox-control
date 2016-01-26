<?php
/**
 * External API
 *
 * Description. Primary methods designed for external use by users.
 *
 * @since 0.0.1
 *
 * @package WordPress
 * @subpackage Component
 */
namespace metabox_control\API;
use metabox_control\Utils as Utils;
use metabox_control\API\Internal as Internal;

/**
 * Function ensures this is a new template, and if so, adds it to metabox control.
 * However if it determines that the template already exists, it call update_metabox_template internally.
 * @param $template - string - required
 * @param $metaboxes - array - required
 *
 * @return bool|\Exception
 * @throws \Exception
 */
function add_metabox_template( $template, $metaboxes ) {
	//TypeCheck
	Utils\error_check( 'is_string', 'This function expects a string', $template );
	Utils\error_check( 'is_array', 'This function expects a string', $metaboxes );

	//Page Template Exists
	$is_wp_template = locate_template( array( $template ) );
	if ( ! $is_wp_template ) {
		return new \Exception( 'A template with the name '. $template . ' does not exist in WordPress' );
	}

	//Add Template
	$new_template = array( 'template' => $template, 'metaboxes' => $metaboxes );
	$current_templates = Internal\get_metabox_option();

	if ( ! $current_templates ) {
		return Internal\update_metabox_option( array( $new_template ) );
	}

	$template_exist = Utils\check_existing_templates( $template, $current_templates );

	if ( $template_exist ) {
		return update_metabox_template( $template, $metaboxes, $exists = true );
	}

	$new_templates_array = Internal\add_template( $current_templates, $new_template );
	return Internal\update_metabox_option( $new_templates_array );
}

/**
 * Attempts to find the requested template, and removes it from metabox control if its found.
 * @param $template - string - required
 *
 * @return mixed
 * @throws \Exception
 */
function remove_metabox_template( $template ) {
	//typecheck
	Utils\error_check( 'is_string', 'This function expects a string', $template );
	//has template been registers?
	$current_templates = Internal\get_metabox_option();

	if ( ! $current_templates ) {
		throw new \Exception( 'No metabox templates have been registered with Metabox control' );
	}

	$template_exist = Utils\check_existing_templates( $template, $current_templates );

	if ( ! $template_exist ) {
		throw new \Exception( $template . ' has not be registered with Metabox Control.' );
	}
	//remove template
	$new_templates_array = Internal\remove_existing_template( $current_templates, $template );
	return Internal\update_metabox_option( $new_templates_array );
}

/**
 * This function is used to update a template and it's metaboxes which has already been registers.
 * Checks to see if the template does in fact exist. Once satisfied, update metabox option.
 * @param $template - string - required
 * @param $metaboxes - array - required
 * @param null $exists - bool - used internally by add_metabox_template
 *
 * @return bool
 * @throws \Exception
 */
function update_metabox_template( $template, $metaboxes, $exists = null ) {
	Utils\error_check( 'is_string', 'This function expects a string', $template );
	Utils\error_check( 'is_array', 'This function expects a string', $metaboxes );

	$current_templates = Internal\get_metabox_option();

	if ( ! $current_templates ) {
		throw new \Exception( 'No metabox templates have been registered with Metabox control' );
	}

	if ( null === $exists ) {
		$template_exist = Utils\check_existing_templates( $template, $current_templates );

		if ( ! $template_exist  ) {
			throw new \Exception( $template . ' has not be registered with Metabox Control.' );
		}
	}

	$new_template = array( 'template' => $template, 'metaboxes' => $metaboxes );

	/**
	 * Compare existing values to ensure we actually have new values.
	 */
	$existing_template = Internal\return_existing_template( $current_templates, $template );
	$existing_template = array_values( $existing_template );
	if ( $existing_template[0] === $new_template ) {
		return false;
	}

	$filtered_templates = Internal\remove_existing_template( $current_templates, $template );
	$new_templates_array = Internal\add_template( $filtered_templates, $new_template );
	return Internal\update_metabox_option( $new_templates_array );

}

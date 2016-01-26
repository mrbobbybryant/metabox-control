<?php
/**
 * Utility Functions
 *
 * Description. Useful Helper Functions uses Internally..
 *
 * @since 0.0.1
 *
 * @package WordPress
 * @subpackage Component
 */
namespace metabox_control\Utils;

/**
 * This function allows us to check for a number of possible issue like is_array or is_object
 * and throw preset errors if certain conditions exist.
 * @param $condition - function
 * @param $error - string - Error message to display
 * @param $value - mixed - the value to wish to check.
 *
 * @return \Exception
 */
function error_check( $condition, $error, $value ) {
	$bool = call_user_func( $condition, $value );
	if ( $bool ) {
		return $value;
	} else {
		return new \Exception( $error );
	}
}

/**
 * Checks to see if a certain template exists. Returns either true or false.
 * @param $template
 * @param $current_templates
 *
 * @return bool
 */
function check_existing_templates( $template, $current_templates ) {
	$check = array_reduce( $current_templates, function( $carry, $item ) use($template) {
		if ( $item['template'] === $template || true === $carry ) {
			$carry = true;
		} else {
			$carry = false;
		}
		return $carry;
	});
	return $check;
}

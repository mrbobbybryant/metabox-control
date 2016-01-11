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
namespace metabox_control\Utils;

function error_check( $condition, $error, $value ) {
	$bool = call_user_func( $condition, $value );
	if ( $bool ) {
		return $value;
	} else {
		return new \Exception( $error );
	}
}

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

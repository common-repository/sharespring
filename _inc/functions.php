<?php

/**
 * Sanitize any string into the format we're expecting
 *
 * @param  string $val User input
 * @return string      Formatted into key="value"
 */
function sharespring_sanitize_str($val) {
	// Lowercase & remove spaces
	$sanitized = strtolower(trim(str_replace(array(' ', 'data-', 'href=', 'class=', '[sharespring', ']'), '', $val)));

	// Find values that match the correct format
	preg_match_all('/[\w-]+="[\w#-]+"/', $sanitized, $matches);
	$matches = $matches[0];

	// Set input to the sanitized format
	return implode("\n", $matches);
}

/**
 * Sanitize callback
 *
 * @param  array $val Form array containing the keys we're looking to sanitize
 * @return array      Same array, with values sanitized
 */
function sharespring_sanitize($val) {
	if (array_key_exists('sharespring_defaults', $val)) {
		$val['sharespring_defaults'] = sharespring_sanitize_str($val['sharespring_defaults']);
	}

	return $val;
}

/**
 * Get an array of default key/value pairs
 *
 * @return array Defaults with format: key => value
 */
function sharespring_get_defaults() {
	$options = (array) get_option('sharespring_settings');
	$value = array_key_exists('sharespring_defaults', $options) ? $options['sharespring_defaults'] : '';

	// Find values that match the correct format
	preg_match_all('/[\w-]+="[\w#-]+"/', $value, $matches);
	$matches = $matches[0];

	$defaults = array();
	foreach ($matches as $match) {
		list($key, $val) = explode('=', $match);
		$defaults[$key] = str_replace('"', '', $val);
	}

	return $defaults;
}

/**
 * Format an array of attributes into an HTML string
 * @param  array $args Attributes with format: key => value
 * @return string      HTML string for embed code
 */
function sharespring_attributes ($args) {
	$attributes = sprintf('href="https://sharespring.com/%s/%s"', $args['username'], $args['stream']);
	unset($args['username']);
	unset($args['stream']);

	foreach ($args as $key => $val) {
		$attributes .= sprintf(' data-%s="%s"', $key, htmlspecialchars($val));
	}

	return $attributes;
}

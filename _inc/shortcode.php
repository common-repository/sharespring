<?php

/**
 * Parse shortcode into the embed code
 *
 * @param  array $args Attributes
 * @return string      Embed code
 */
function sharespring_shortcode($args) {
	$args = wp_parse_args($args, sharespring_get_defaults());

	// Make sure we have a username
	if (empty($args['username'])) {
		error_log('ShareSpring shortcode is missing the username attribute');
		return '';
	}

	// Make sure we have a stream
	if (empty($args['stream'])) {
		error_log('ShareSpring shortcode is missing the stream attribute');
		return '';
	}

	// Include the javascript in the footer
	wp_enqueue_script(
		'sharespring',
		'https://sharespring.com/sharespring-embed.min.js',
		null,
		null,
		true
	);

	return sprintf('<a class="sharespring-embed" %s>Check out my content and social media feed from ShareSpring</a>', sharespring_attributes($args));
}
add_shortcode('sharespring', 'sharespring_shortcode');

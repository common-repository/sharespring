<?php

/**
 * Add Dashboard & Settings links on the Plugin index page
 *
 * @param  array $actions      Existing links
 * @param  string $plugin_file The plugin file being processed
 * @return array               Links with Dashboard & Settings prepended
 */
function sharespring_plugin_action_links($actions, $plugin_file) {
	// Skip plugins that aren't ours
	if ($plugin_file !== 'sharespring/sharespring.php') {
		return $actions;
	}

	return array_merge(array(
		'<a href="https://sharespring.com/dashboard" target="_blank">' . __('Dashboard', 'sharespring') . '</a>',
		'<a href="' . admin_url('options-general.php?page=sharespring') . '">' . __('Settings', 'sharespring') . '</a>'
	), $actions);
}
add_filter('plugin_action_links', 'sharespring_plugin_action_links', null, 2);

/**
 * Add admin menu link to Settings > ShareSpring
 *
 * @return void
 */
function sharespring_add_admin_menu() {
	add_options_page('ShareSpring', 'ShareSpring', 'manage_options', 'sharespring', 'sharespring_options_page');
}
add_action('admin_menu', 'sharespring_add_admin_menu');

/**
 * Add settings page Settings > ShareSpring
 *
 * @return void
 */
function sharespring_settings_init() {
	register_setting('pluginPage', 'sharespring_settings', 'sharespring_sanitize');

	add_settings_section(
		'sharespring_pluginPage_section',
		'',
		'sharespring_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'sharespring_defaults',
		__('Default attributes', 'sharespring'),
		'sharespring_defaults_render',
		'pluginPage',
		'sharespring_pluginPage_section'
	);
}
add_action('admin_init', 'sharespring_settings_init');

/**
 * Settings page input field
 *
 * @return void
 */
function sharespring_defaults_render() {
	$options = (array) get_option('sharespring_settings');
	$value = array_key_exists('sharespring_defaults', $options) ? $options['sharespring_defaults'] : '';
	?>

	<fieldset>
		<legend class="screen-reader-text"><span>Default attributes</span> (optional)</legend>
		<p>
			If you embed the shortcode in many places throughout your site, use this field to store the default attributes.<br>
			This way, wherever you embed a shortcode, you can just use <kbd>[sharespring]</kbd> or <kbd>[sharespring rows="1"]</kbd> rather than including all attributes every time.
			<br><br>
			Enter any default attributes in the following format:
<pre>
username="johnnyappleseed"
stream="apples"
border="0"
</pre>
		</p>
		<p>
			<textarea name="sharespring_settings[sharespring_defaults]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($value); ?></textarea>
		</p>
	</fieldset>

	<?php
}

/**
 * Function that fills the Settings section with the desired content. The function should echo its output.
 *
 * @return void
 */
function sharespring_settings_section_callback() {}

/**
 * Settings page
 *
 * @return void
 */
function sharespring_options_page() {
	?>
	<div class="wrap">
		<h1>ShareSpring Settings</h1>

		<form action='options.php' method='post'>
			<?php
				settings_fields('pluginPage');
				do_settings_sections('pluginPage');
				submit_button();
			?>
		</form>
	<?php
}

<?php

/**
 * ShareSpring Widget
 */
class ShareSpringWidget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'sharespring_widget',
			'ShareSpring',
			array('description' => __('Embed ShareSpring', 'sharespring'),)
		);
	}

/**
 * Echo the embed code
 *
 * @param  array $args     Widget arguments
 * @param  array $instance Instance
 * @return void            Echoes the output HTML
 */
	function widget($args, $instance) {
		echo $args['before_widget'];
		echo do_shortcode(sprintf('[sharespring %s]', preg_replace('/\r|\n/', ' ', $instance['attributes'])));
		echo $args['after_widget'];
	}

/**
 * Widget form
 *
 * @param  array $instance Instance
 * @return void
 */
	function form($instance) {
		$value = array_key_exists('attributes', $instance) ? $instance['attributes'] : '';
		?>

		<fieldset>
			<p>
				Enter any attributes in the following format:
<pre>
username="johnnyappleseed"
stream="apples"
border="0"
</pre>
			</p>
			<p>
				<textarea id="<?php echo $this->get_field_id('attributes'); ?>" name="<?php echo $this->get_field_name('attributes'); ?>" rows="10" cols="20" class="widefat code"><?php echo esc_textarea($value); ?></textarea>
			</p>
		</fieldset>

		<?php
	}

/**
 * Sanitize widget form values as they are saved.
 *
 * @param  array $new_instance Values just sent to be saved.
 * @param  array $old_instance Previously saved values from database.
 * @return array               Updated safe values to be saved.
 */
	function update($new_instance, $old_instance) {
		return array(
			'attributes' => sharespring_sanitize_str($new_instance['attributes'])
		);
	}
}

/**
 * Register and load the widget
 *
 * @return void
 */
function sharespring_register_widget() {
	register_widget('ShareSpringWidget');
}
add_action('widgets_init', 'sharespring_register_widget');

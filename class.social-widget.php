<?php
define('SOCIALMEDIAPLUGIN__PLUGIN_DIR', plugin_dir_url(__FILE__));
class Socialmedia extends WP_Widget
{
	// Main constructor
	public function __construct()
	{
		parent::__construct(
			'social_widget', // Base ID
			'Socialmedia', // Name
			array('description' => __('Widget réseaux sociaux', 'text_domain'),) // Args
		);
	}

	// The widget form (for the backend )
	public function form($instance)
	{

		// Set widget defaults
		$defaults = array(
			'title'    => '',
			'checkbox[]' => '',
		);

		// Parse current settings with defaults
		extract(wp_parse_args((array) $instance, $defaults)); ?>

		<?php // Widget Title 
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget Title', 'text_domain'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<?php // Checkbox 
		global $wpdb;
		$table_name = $wpdb->prefix . "socialnetwork";
		$retrieve_data = $wpdb->get_results("SELECT * FROM $table_name");

		foreach ($retrieve_data as $data) :
		?>
			<input id="<?= $data->id ?>" name="<?php echo esc_attr($this->get_field_name('checkbox[]')); ?>" type="checkbox" value="<?= $data->id ?>" />
			<label for="<?= $data->id ?>"><?= $data->name ?></label>
			<?php endforeach;
	}

	// Update widget settings
	// public function update( $new_instance, $old_instance ) {
	// 	$instance = $old_instance;
	// 	$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
	// 	$instance['checkbox'] = isset( $new_instance['checkbox'] ) ? 1 : false;
	// 	return $instance;
	// }

	// Display the widget
	public function widget($args, $instance)
	{

		extract($args);

		// Check the widget options
		$title    = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
		$checkbox = !empty($instance['checkbox']) ? $instance['checkbox'] : false;

		global $wpdb;
		$table_name = $wpdb->prefix . "socialnetwork";
		$check = $instance['checkbox'];


		// WordPress  before_widget
		echo $before_widget;

		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';

		// Display widget title if defined
		if ($title) {
			echo $before_title . $title . $after_title;
		}

		if ($checkbox) {

			?> <p> <?php
			foreach ($instance['checkbox'] as $inst) {
				$AllData = $wpdb->get_results("SELECT * FROM $table_name WHERE `id` = $inst");

				foreach ($AllData as $d) : ?>

					<a href="<?= $d->url ?>"><img src="<?= SOCIALMEDIAPLUGIN__PLUGIN_DIR . 'social_medias/' . $d->imgUrl ?>" alt=""></a>
				<?php endforeach;
			}
			?></p><?php
		}


		echo '</div>';

		// WordPress after_widget hook 
		echo $after_widget;
	}
}

<?php

class Socialmedia extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'social_widget', // Base ID
            'Socialmedia', // Name
            array( 'description' => __( 'Widget réseaux sociaux', 'text_domain' ), ) // Args
        );
    }
    
    // The widget form (for the backend )
public function form( $instance ) {

	// Set widget defaults
	$defaults = array(
		'title'    => '',
		'text'     => '',
		'select'   => '',
	);
	
	// Parse current settings with defaults
	extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

	<?php // Widget Title ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>

	<?php // Text Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
	</p>

	<?php // Dropdown  
    
    global $wpdb;
// this adds the prefix which is set by the user upon instillation of wordpress
$table_name = $wpdb->prefix . "socialnetwork";
// this will get the data from your table
$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );
var_dump($retrieve_data);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Select', 'text_domain' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
		<?php
	
    

		// Loop through options and add each one to the select dropdown
		foreach ( $retrieve_data as $data ) {
			echo '<option value="' . esc_attr( $data->name ) . '" id="' . esc_attr( $data->id ) . '" '. selected( $select, false ) . '>'. $data->name . '</option>';

		} ?>
		</select>
	</p>

<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['text']     = isset( $new_instance['text'] ) ? wp_strip_all_tags( $new_instance['text'] ) : '';
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {

		extract( $args );

		// Check the widget options
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$text     = isset( $instance['text'] ) ? $instance['text'] : '';
		$select   = isset( $instance['select'] ) ? $instance['select'] : '';
		

		// WordPress core before_widget hook (always include )
		echo $before_widget;

		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';

			// Display widget title if defined
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			// Display text field
			if ( $text ) {
				echo '<p>' . $text . '</p>';
			}


			// Display select field
			if ( $select ) {
				echo '<p>' . $select . '</p>';
			}

		echo '</div>';

		// WordPress core after_widget hook (always include )
		echo $after_widget;
	}
    
}

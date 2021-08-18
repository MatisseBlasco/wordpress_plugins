<?php

Class Socialnetwork extends WP_Widget {

    function __construct() {
        parent::__construct(
        // widget ID
        'social_widget',
        // widget name
        __('Another Social Widget'),
        // widget description
        array( 'description' => __( 'le meilleur widget de lunivers'), )
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'Socialnetwork_widget', $instance['title'] );
        echo $args['before_widget'];
        //if title is present
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
        //output
        echo __('<img src="wp-content/plugins/wordpress_plugins/social_medias/linkedin.png" alt="logo" />');
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) )
        $title = $instance[ 'title' ];
        else
        $title = __( 'Titre par défaut');
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

}
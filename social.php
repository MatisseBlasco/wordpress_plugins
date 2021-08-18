<?php

/**
 *Plugin Name: SocialExtension
 *Description: Akecoucou
 *Version: 1.0
 *Author: Matisse Blasco
 **/

require_once('class.social-widget.php');



function Socialnetwork_register_widget()
{
    register_widget('Socialnetwork');
}
add_action('widgets_init', 'Socialnetwork_register_widget');

//création de table en bdd
function table_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "socialnetwork";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    url varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'table_install');


//FORMULAIRE
function form_html() { ?>
    <div class="wrap top-bar-wrapper">
        <form method="post" action="options.php">
            <?php settings_errors() ?>
            <?php settings_fields('topbar_option_group'); ?>
            <label for="topbar_field_eat">Nom du réseau:</label>
            <input name="topbar_field" id="topbar_field_eat" type="text" value=" <?php echo get_option('topbar_field'); ?> ">
            <?php submit_button(); ?>
        </form>
    </div>
<?php 
}
    
add_action('admin_head', 'form_html');

//afficher le formulaire dans le backofice
function topbar_plugin_page() {
    $page_title = 'Top Bar Options';
    $menu_title = 'Socialnetwork';
    $capatibily = 'manage_options';
    $slug = 'another-socialnetwork-plugin';
    $callback = 'form_html';
    $icon = 'dashicons-schedule';
    $position = 90;
    
    add_menu_page($page_title, $menu_title, $capatibily, $slug, $callback, $icon, $position);
}
    
add_action('admin_menu', 'topbar_plugin_page');
    
function topbar_register_settings() {
    register_setting('topbar_option_group', 'topbar_field');
}
    
add_action('admin_init', 'topbar_register_settings');
<?php
/**
*Plugin Name: SocialPlug
*Description: Akecoucou
*Version: 1.0
*Author: Matisse Blasco
**/

//Add social plugin after the opening body
add_action('wp_body_open', 'social_head');

function get_user_or_websitename()
{
    if(!is_user_logged_in())
    {
        return 'Welcome to ' . get_bloginfo('name');
    }
    else
    {
        $current_user = wp_get_current_user();
        return 'Welcome back ' . $current_user -> user_login;
    }
}

function social_head()
{
    echo '<h3 class="tb">' . get_user_or_websitename() . '</h3>';
}
//Add CSS to the social plugin
add_action('wp_print_styles', 'social_css');

function social_css()
{
    echo '
    <style>
    h3.tb {color: #fff; margin: 0; padding: 30px; text-align: center; background: blue}
    </style>
    ';
}

//Social plugin page

function social_plugin_page() {
    $page_title = 'Social Plugin Options';
    $menu_title = 'Social Plugin';
    $capatibily = 'manage_options';
    $slug = 'social-plugin';
    $position = 60;

    add_menu_page($page_title, $menu_title, $capatibily, $slug, $position);
}

add_action('admin_menu', 'social_plugin_page');
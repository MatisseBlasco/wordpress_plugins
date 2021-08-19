<?php

/**
 *Plugin Name: SocialExtension
 *Description: Akecoucou
 *Version: 1.0
 *Author: Matisse Blasco
 **/

require_once('class.social-widget.php');

function table_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "socialnetwork";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    url varchar(255) DEFAULT '' NOT NULL,
    imgUrl varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'table_install');


//register widget
add_action('widgets_init', 'register_socialnetwork');

function register_socialnetwork()
{
    register_widget('Socialmedia');
}

//page admin
function my_admin_menu()
{
    add_menu_page(
        __('Social Widget page', 'my-textdomain'),
        __('Social Medias', 'my-textdomain'),
        'manage_options',
        'social-widget-page',
        'my_admin_page_contents',
        'dashicons-schedule',
        3
    );
}

add_action('admin_menu', 'my_admin_menu');

function my_admin_page_contents()
{
    var_dump($_POST);
    ?>
        <h1>
            <?php esc_html_e('Social Medias Plugin.', 'my-plugin-textdomain'); ?>
        </h1>

        <form id="formulaire" action="" method="POST">
            <label for="title">Réseau</label>
            <input type="text" id="title" name="title">

            <label for="url">Url Réseau</label>
            <input type="text" id="url" name="url">

            <div id="btn">+</div>

            <input type="submit" name="submit" value="Enregistrer">
        </form>

        <?php 
            if ( isset( $_POST['submit'] ) ){
                echo "coucou";
                global $wpdb;
                $tablename = $wpdb->prefix.'socialnetwork';
                
               $wpdb->insert( $tablename, array(
                   'name' => $_POST['title'], 
                   'url' => $_POST['url'], 
                   ),
                   array( '%s', '%s', '%s') 
               );
               
            }
        ?>

        <script>
            const btn = document.getElementById('btn');
            const formulaire = document.getElementById('formulaire');

            btn.addEventListener('click', function(){
                console.log('coucou');
                var input = document.createElement("input");
                input.type = "text";
                formulaire.appendChild(input);
            })
        </script>
    <?php
}





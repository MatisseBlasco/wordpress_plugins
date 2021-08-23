<?php

/**
 *Plugin Name: SocialExtension
 *Description: Akecoucou
 *Version: 1.0
 *Author: Matisse Blasco
 **/

require_once('class.social-widget.php');
define('LOGOFILE', plugin_dir_path(__FILE__));

//----------------Create table
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

//----------------Drop table
function table_uninstall()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "socialnetwork";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

register_deactivation_hook(__FILE__, 'table_uninstall');


//register widget
add_action('widgets_init', 'register_socialnetwork');

function register_socialnetwork()
{
    register_widget('Socialmedia');
}

//page admin

add_action('admin_menu', 'my_admin_menu');
 
function my_admin_menu() {
   add_menu_page(__('Social Widget page', 'my-textdomain'), __('Social Medias', 'my-textdomain'), 'manage_options', 'social-widget-page', 'my_admin_page_contents', 'dashicons-schedule', 30);
   add_submenu_page('social-widget-page', 'Ajouter réseaux sociaux', 
        'Ajouter réseaux sociaux', 'manage_options', 'admin.php?page=social-widget-page','');
    add_submenu_page('social-widget-page', 'Tous les réseaux sociaux',
        'Tous les réseaux sociaux', 'manage_options', 'admin.php?page=social-all-medias-page.php','');
}

function my_admin_page_contents()
{
    var_dump($_POST);
?>
    <h1>
        <?php esc_html_e('Social Medias Plugin.', 'my-plugin-textdomain'); ?>
    </h1>

    <form id="formulaire" action="" method="POST" enctype="multipart/form-data">
        <label for="title">Réseau</label>
        <input type="text" id="title" name="title" required>

        <label for="url">Url Réseau</label>
        <input type="text" id="url" name="url" required>

        <input type="file" name="logo" required>

        <div id="btn">+</div>

        <input type="submit" name="submit" value="Enregistrer">
    </form>

    <?php
    if (!empty($_POST['url']) && !empty($_POST['title']) && !empty($_FILES['logo']) && isset($_POST['submit'])) {
        echo "coucou";
        global $wpdb;
        $tablename = $wpdb->prefix . 'socialnetwork';

        var_dump($_FILES);

        $tmpName = $_FILES['logo']['tmp_name'];
        $name = $_FILES['logo']['name'];
        $size = $_FILES['logo']['size'];
        $errors = $_FILES['logo']['error'];
        $getExtension = explode('.', $name);
        $extension = strtolower(end($getExtension));
        $allowed = array('jpg', 'png', 'jpeg', 'jfif', 'webp', 'svg');
        $maxsize = 20000000;

        if (in_array($extension, $allowed) && $size <= $maxsize && $errors == 0) {
            $uniq = uniqid('', true);
            $file = $uniq . "." . $extension;
            move_uploaded_file($tmpName, LOGOFILE . 'social_medias/' . $file);
        } else {
            echo "Veuillez choisir un bon format de fichier d'une taille de 2Mb maximum.";
        }

        var_dump($tmpName);

        var_dump($file);

        $wpdb->insert(
            $tablename,
            array(
                'name' => $_POST['title'],
                'url' => $_POST['url'],
                'imgUrl' => $file,
            ),
            array('%s', '%s', '%s')
        );
    }
    ?>

    <script>
        const btn = document.getElementById('btn');
        const formulaire = document.getElementById('formulaire');

        btn.addEventListener('click', function() {
            console.log('coucou');
            var input = document.createElement("input");
            var label = document.createElement("label");
            input.type = "text";
            formulaire.appendChild(input);
        })
    </script>
<?php
}

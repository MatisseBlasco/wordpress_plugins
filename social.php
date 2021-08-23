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
function my_admin_menu()
{
    add_menu_page(
        __('Social Widget page', 'my-textdomain'), //titre page
        __('Social Medias', 'my-textdomain'), //titre menu
        'manage_options', //capability
        'social-widget-page', //slug
        'my_admin_page_contents', //callbac
        'dashicons-schedule', //icon url
        3 //position
    );

    add_submenu_page(
        'social-widget-page',
        'Vos réseaux', //page title
        'Vos réseaux', //menu title
        'edit_themes', //capability,
        'vos-reseaux', //menu slug
        'my_submenu_content' //callback function
    );
}
add_action('admin_menu', 'my_admin_menu');


function my_submenu_content()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "socialnetwork";
    $retrieve_data = $wpdb->get_results("SELECT * FROM $table_name"); ?>

    <form action="" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Nom du réseaux</th>
                    <th>URL du réseaux</th>
                    <th>Logo du réseaux</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($retrieve_data as $data) : ?>
                    <tr>
                        <td><?= $data->name ?></td>
                        <td><?= $data->url ?></td>
                        <td><?= $data->imgUrl ?></td>
                        <td><a href="">Modifier</a></td>
                        <td><button name="delete" value="<?= $data->id ?>">Supprimer</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
<?php
}

if (isset($_POST['delete'])) {
    DeleteEntry();
}

function DeleteEntry() {
    $id = $_POST['delete'];
    global $wpdb;
    $table_name = $wpdb->prefix . "socialnetwork";
    $wpdb->delete( $table_name, array( 'id' => $id ) );
}


//PAGE ADMIN INSERT SOCIAL MEDIA
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

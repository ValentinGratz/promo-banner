<?php
/*
Plugin Name: Promo Banner
Description: Crée une bannière promo avec des options de personnalisation.
Version: 1.1.1
Author: Valentin Grtz
*/


// Ajouter la bannière en haut du site si l'utilisateur n'est pas connecté
function add_promo_banner_top() {
    if (!is_user_logged_in()) {
        add_action('wp_body_open', 'add_promo_banner'); // Utilisation de wp_body_open pour l'ajout de la bannière
    }
}
add_action('wp', 'add_promo_banner_top');

// Ajouter les options de personnalisation dans le tableau de bord WordPress
function promo_banner_settings() {
    add_menu_page('Paramètres de la bannière promo', 'Promo Banner', 'manage_options', 'promo-banner-settings', 'promo_banner_settings_page', 'dashicons-format-image');
}
add_action('admin_menu', 'promo_banner_settings');

function promo_banner_settings_page() {
    ?>
    <div class="wrap">
        <h2>Paramètres de la bannière promo</h2>
        <form method="post" action="options.php">
            <?php settings_fields('promo_banner_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Texte de la bannière</th>
                    <td><textarea name="promo_banner_text" cols="50" rows="3"><?php echo esc_html(get_option('promo_banner_text')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Style du texte</th>
                    <td>
                        <select name="promo_banner_style">
                            <option value="scrolling" <?php selected(get_option('promo_banner_style'), 'scrolling'); ?>>Texte défilant</option>
                            <option value="centered" <?php selected(get_option('promo_banner_style'), 'centered'); ?>>Centré</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Couleur du texte</th>
                    <td><input type="text" name="promo_banner_text_color" value="<?php echo esc_attr(get_option('promo_banner_text_color', '#000000')); ?>" class="my-color-field" data-default-color="#000000" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Couleur de fond</th>
                    <td><input type="text" name="promo_banner_background_color" value="<?php echo esc_attr(get_option('promo_banner_background_color', '#ffffff')); ?>" class="my-color-field" data-default-color="#ffffff" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Hauteur de la bannière (en pixels)</th>
                    <td><input type="number" name="promo_banner_height" value="<?php echo esc_attr(get_option('promo_banner_height', '50')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function promo_banner_register_settings() {
    register_setting('promo_banner_settings_group', 'promo_banner_text');
    register_setting('promo_banner_settings_group', 'promo_banner_style');
    register_setting('promo_banner_settings_group', 'promo_banner_text_color');
    register_setting('promo_banner_settings_group', 'promo_banner_background_color');
    register_setting('promo_banner_settings_group', 'promo_banner_height');
}
add_action('admin_init', 'promo_banner_register_settings');

function add_promo_banner() {
    $text = get_option('promo_banner_text');
    $style = get_option('promo_banner_style');
    $text_color = get_option('promo_banner_text_color');
    $background_color = get_option('promo_banner_background_color');
    $height = get_option('promo_banner_height');
    
    if (!empty($text)) {
        $style_attribute = '';
        if ($style === 'scrolling') {
            $style_attribute = 'style="animation: scrollLeft 10s linear infinite;"';
        } elseif ($style === 'centered') {
            $style_attribute = 'style="text-align: center;"';
        }

        echo '<div class="promo-banner" ' . $style_attribute . ' style="color: ' . $text_color . '; background-color: ' . $background_color . '; height: ' . $height . 'px;">' . $text . '</div>';
    }
}

// Enqueue scripts et styles pour le color picker
add_action('admin_enqueue_scripts', 'promo_banner_enqueue_color_picker');
function promo_banner_enqueue_color_picker($hook_suffix) {
    // Assurez-vous que les scripts et styles ne sont chargés que sur la page d'administration des paramètres de la bannière promo
    $allowed_pages = array('toplevel_page_promo-banner-settings');
    if (in_array($hook_suffix, $allowed_pages)) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('promo-banner-color-picker', plugins_url('promo-banner-color-picker.js', __FILE__), array('wp-color-picker'), false, true);
    }
}
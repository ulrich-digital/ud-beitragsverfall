<?php
/**
 * Plugin Name: Beitragsverfall
 * Description: Setzt Beiträge, Seiten und CPTs nach Ablauf eines festgelegten Verfallsdatums automatisch auf "Entwurf".
 * Version: 1.0
 * Author: ulrich.digital
 * Author URI: https://ulrich.digital/
 * Text Domain: beitragszerfall
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Metabox für Verfallsdatum hinzufügen
function beitragsverfall_add_meta_box() {
    $post_types = get_post_types(array('public' => true), 'names');
    foreach ($post_types as $post_type) {
        add_meta_box(
            'beitragsverfall_meta_box',
            'Verfallsdatum',
            'beitragsverfall_meta_box_callback',
            $post_type,
            'side'
        );
    }
}
add_action('add_meta_boxes', 'beitragsverfall_add_meta_box');

// Callback für die Metabox
function beitragsverfall_meta_box_callback($post) {
    wp_nonce_field('beitragsverfall_nonce_action', 'beitragsverfall_nonce_name');
    $expiry_date = get_post_meta($post->ID, '_beitragsverfall_expiry_date', true);
    echo '<label for="beitragsverfall_expiry_date">Verfallsdatum:</label>';
    echo '<input type="datetime-local" id="beitragsverfall_expiry_date" name="beitragsverfall_expiry_date" value="' . esc_attr($expiry_date) . '" style="width:100%;" />';
}

// Speichern des Verfallsdatums
function beitragsverfall_save_post($post_id) {
    if (!isset($_POST['beitragsverfall_nonce_name']) ||
        !wp_verify_nonce($_POST['beitragsverfall_nonce_name'], 'beitragsverfall_nonce_action')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['beitragsverfall_expiry_date'])) {
        $expiry_date = sanitize_text_field($_POST['beitragsverfall_expiry_date']);
        update_post_meta($post_id, '_beitragsverfall_expiry_date', $expiry_date);
    }
}
add_action('save_post', 'beitragsverfall_save_post');

// Prüfung beim Init: Alle Beiträge mit Verfallsdatum überprüfen
function beitragsverfall_check_expired_posts() {
    $args = array(
        'post_type' => get_post_types(array('public' => true), 'names'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_beitragsverfall_expiry_date',
                'compare' => 'EXISTS',
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $expiry_date = get_post_meta($post->ID, '_beitragsverfall_expiry_date', true);
            if (!empty($expiry_date) && strtotime($expiry_date) < current_time('timestamp')) {
                // Beitrag abgelaufen → auf Entwurf setzen
                wp_update_post(array(
                    'ID' => $post->ID,
                    'post_status' => 'draft',
                ));
            }
        }
    }
    wp_reset_postdata();
}
add_action('init', 'beitragsverfall_check_expired_posts');

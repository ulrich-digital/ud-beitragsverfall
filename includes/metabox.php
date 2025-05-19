<?php
/*
* Verfallsdatum-Feld + Speichern
*/

defined('ABSPATH') || exit;

// Metabox hinzufügen
add_action('add_meta_boxes', function () {
    $enabled_post_types = get_option('beitragsverfall_enabled_post_types', ['post']);
    
    foreach ($enabled_post_types as $post_type) {
        add_meta_box(
            'beitragsverfall_meta_box',
            'Verfallsdatum',
            'beitragsverfall_meta_box_callback',
            $post_type,
            'side'
        );
    }
});

function beitragsverfall_meta_box_callback($post) {
    wp_nonce_field('beitragsverfall_nonce_action', 'beitragsverfall_nonce_name');
    $expiry = get_post_meta($post->ID, '_beitragsverfall_expiry_date', true);
    echo '<label for="beitragsverfall_expiry_date">Verfallsdatum:</label>';
    echo '<input type="datetime-local" id="beitragsverfall_expiry_date" name="beitragsverfall_expiry_date" value="' . esc_attr($expiry) . '" style="width:100%;" />';
}

// Speichern
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['beitragsverfall_nonce_name']) ||
        !wp_verify_nonce($_POST['beitragsverfall_nonce_name'], 'beitragsverfall_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    ) return;

    if (isset($_POST['beitragsverfall_expiry_date'])) {
        $expiry = sanitize_text_field($_POST['beitragsverfall_expiry_date']);
        if (empty($expiry)) {
            delete_post_meta($post_id, '_beitragsverfall_expiry_date');
            if (get_post_status($post_id) === 'expired') {
                wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
            }
        } else {
            update_post_meta($post_id, '_beitragsverfall_expiry_date', $expiry);
            if (strtotime($expiry) > current_time('timestamp') && get_post_status($post_id) === 'expired') {
                wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
            }
        }
    }
});

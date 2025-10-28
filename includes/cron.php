<?php
/*
* Cron-Planung + Ablauf-Logik
*/


defined('ABSPATH') || exit;

// Eigene Intervalle registrieren
add_filter('cron_schedules', function($schedules) {
    $schedules['every_five_minutes'] = [
        'interval' => 300,
        'display'  => __('Alle 5 Minuten')
    ];
    $schedules['every_minute'] = [
        'interval' => 60,
        'display'  => __('Jede Minute')
    ];
    return $schedules;
});


// Aktivierung
register_activation_hook(__FILE__, function () {
    beitragsverfall_reschedule_cron();
});

// Deaktivierung
register_deactivation_hook(__FILE__, function () {
    $timestamp = wp_next_scheduled('beitragsverfall_cron_hook');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'beitragsverfall_cron_hook');
    }
});

// Umplanen bei Testmodus-Wechsel
function beitragsverfall_reschedule_cron() {
    $timestamp = wp_next_scheduled('beitragsverfall_cron_hook');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'beitragsverfall_cron_hook');
    }

    $interval = get_option('beitragsverfall_testmode') ? 'every_minute' : 'every_five_minutes';
    wp_schedule_event(time(), $interval, 'beitragsverfall_cron_hook');
}


// Ablaufprüfung
add_action('beitragsverfall_cron_hook', function () {
    $args = [
        'post_type' => get_option('beitragsverfall_enabled_post_types', ['post']),
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [[
            'key'     => '_beitragsverfall_expiry_date',
            'compare' => 'EXISTS',
        ]],
    ];

    $query = new WP_Query($args);

    foreach ($query->posts as $post) {
        $expiry = get_post_meta($post->ID, '_beitragsverfall_expiry_date', true);
        if ($expiry && strtotime($expiry) < current_time('timestamp')) {
            wp_update_post(['ID' => $post->ID, 'post_status' => 'expired']);
        }
    }

    wp_reset_postdata();
});

// Docker/localhost-Fix
add_filter('cron_request', function($request) {
    if (strpos($request['url'], 'localhost') !== false) {
        $request['url'] = str_replace('localhost', 'host.docker.internal', $request['url']);
    }
    return $request;
});

<?php
/*
* Post-Status + Anzeige im Admin
*/
defined('ABSPATH') || exit;

// Post-Status registrieren
add_action('init', function () {
    register_post_status('expired', [
        'label'                     => _x('Abgelaufen', 'post'),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Abgelaufen <span class="count">(%s)</span>', 'Abgelaufen <span class="count">(%s)</span>'),
    ]);
});

// Status in der Beitragsliste anzeigen
add_filter('display_post_states', function ($states, $post) {
    if ($post->post_status === 'expired') {
        $states[] = __('Abgelaufen', 'beitragsverfall');
    }
    return $states;
}, 10, 2);

// "expired" in der Beitragsübersicht anzeigen
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    $screen = get_current_screen();
    if (isset($screen->base) && $screen->base === 'edit') {
        $status = $query->get('post_status');
        if (empty($status) || $status === 'all') {
            $query->set('post_status', ['publish', 'draft', 'pending', 'future', 'private', 'expired']);
        }
    }
});

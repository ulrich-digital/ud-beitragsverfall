<?php
defined('ABSPATH') || exit;

// Admin-Menü und Settings registrieren
add_action('admin_menu', function () {
    add_options_page(
        'Beitragsverfall',
        'Beitragsverfall',
        'manage_options',
        'beitragsverfall',
        'beitragsverfall_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('beitragsverfall_settings', 'beitragsverfall_testmode');
    register_setting('beitragsverfall_settings', 'beitragsverfall_enabled_post_types', [
        'type' => 'array',
        'default' => ['post'],
    ]);

    // Testmodus-Umschalter verarbeiten
    if (isset($_POST['toggle_testmode']) && current_user_can('manage_options')) {
        $new_value = get_option('beitragsverfall_testmode') ? 0 : 1;
        update_option('beitragsverfall_testmode', $new_value);
        beitragsverfall_reschedule_cron();

        wp_redirect(admin_url('options-general.php?page=beitragsverfall&updated=1'));
        exit;
    }
});

// Settings-Seite
function beitragsverfall_settings_page() {
    $next_run = wp_next_scheduled('beitragsverfall_cron_hook');
    $interval = beitragsverfall_get_current_cron_interval();
    $testmode = get_option('beitragsverfall_testmode');
    $enabled = get_option('beitragsverfall_enabled_post_types', ['post']);
    $all_types = array_filter(
        get_post_types(['public' => true, 'show_ui' => true], 'objects'),
        fn($type) => $type->name !== 'attachment'
    );

    echo '<div class="wrap">';
    echo '<h1>Beitragsverfall Einstellungen</h1>';

    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success"><p>Einstellungen wurden aktualisiert.</p></div>';
    }

    echo '<form method="post" action="options.php">';
    settings_fields('beitragsverfall_settings');

    echo '<h2>Aktivierte Inhaltstypen</h2>';
    echo '<p>Für welche Post-Types soll der Verfall aktiviert sein?</p>';
    echo '<table class="form-table"><tbody>';

    foreach ($all_types as $type) {
        $checked = in_array($type->name, $enabled) ? 'checked' : '';
        echo '<tr><th scope="row">' . esc_html($type->labels->name) . '</th>';
        echo '<td><label class="switch">';
        echo '<input type="checkbox" name="beitragsverfall_enabled_post_types[]" value="' . esc_attr($type->name) . '" ' . $checked . '>';
        echo '<span class="slider"></span>';
        echo '</label></td></tr>';
    }

    echo '</tbody></table>';
    submit_button('Speichern');
    echo '</form>';

    // Testmodus separat (eigener POST)
    echo '<form method="post" style="margin-top:2em;">';
    echo '<h2>Testmodus</h2>';
    echo '<p>Aktiviert das Cron-Intervall alle 5 Minuten zur schnellen Überprüfung.</p>';
    echo '<table class="form-table"><tbody>';
    echo '<tr><th>Aktiver Cron-Intervall</th><td>' . esc_html($interval) . '</td></tr>';

    if ($next_run) {
        $datetime = new DateTime('@' . $next_run);
        $datetime->setTimezone(new DateTimeZone(beitragsverfall_get_timezone_string()));
        $abbr = $datetime->format('T');
        $long = beitragsverfall_timezone_longname($abbr);
        echo '<tr><th>Nächste Ausführung</th><td>' . $datetime->format('d.m.Y H:i:s') . ' (' . $long . ')</td></tr>';
    } else {
        echo '<tr><th>Nächste Ausführung</th><td>Nicht geplant</td></tr>';
    }

    echo '<tr><th>Testmodus</th><td>' . ($testmode ? '✅ Aktiv' : '❌ Deaktiviert') . '</td></tr>';
    echo '</tbody></table>';
    echo '<p><input type="submit" name="toggle_testmode" class="button" value="' . ($testmode ? 'Testmodus deaktivieren' : 'Testmodus aktivieren') . '"></p>';
    echo '</form>';
    echo '</div>';

    // Inline-CSS für "Switch"
    echo '<style>
    .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
              background-color: #ccc; transition: .4s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
                     background-color: white; transition: .4s; border-radius: 50%; }
    .switch input:checked + .slider { background-color: #2271b1; }
    .switch input:checked + .slider:before { transform: translateX(26px); }
    </style>';
}

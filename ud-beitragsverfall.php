<?php
/**
 * Plugin Name:     UD Block-Erweiterung: Beitragsverfall
 * Description:     Setzt Beiträge, Seiten und CPTs nach einem festgelegten Verfallsdatum automatisch auf den Status „Abgelaufen“. Aktivierte Inhaltstypen und Prüfintervall lassen sich in den Einstellungen anpassen.
 * Version:         1.2.0
 * Author:          ulrich.digital gmbh
 * Author URI:      https://ulrich.digital/
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     beitragsverfall-ud
 */




defined('ABSPATH') || exit;

// 1. Helper-Funktionen (Grundlage)
require_once __DIR__ . '/includes/helpers.php';

// 2. Beitragsstatus-Ausgabe (Backend-Badge)
require_once __DIR__ . '/includes/status.php';

// 3. Metabox im Editor (für Beitragsauszeichnung)
require_once __DIR__ . '/includes/metabox.php';

// 4. REST API-Einstellungen (optional, kann früher)
require_once __DIR__ . '/includes/settings.php';

// 5. Cron-Jobs
require_once __DIR__ . '/includes/cron.php';



// Direktlink zur Einstellungsseite im Plugin-Menü
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $url = admin_url('options-general.php?page=beitragsverfall');
    $settings_link = '<a href="' . esc_url($url) . '">Einstellungen</a>';
    array_unshift($links, $settings_link); // ganz vorne einfügen
    return $links;
});

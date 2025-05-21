<?php
/**
 * Plugin Name: Beitragsverfall
 * Description: Setzt Beiträge, Seiten und CPTs nach Ablauf eines festgelegten Verfallsdatums automatisch auf den Status "Abgelaufen". Die aktivierten Beitragstypen und der Prüfintervall lassen sich in den Einstellungen anpassen.
 * Version: 1.2.0
 * Author: ulrich.digital
 * Author URI: https://ulrich.digital/
 * Text Domain: beitragsverfall
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
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

<?php
/**
 * Plugin Name: Beitragsverfall
 * Description: Setzt Beiträge, Seiten und CPTs nach Ablauf eines festgelegten Verfallsdatums automatisch auf den Status "Abgelaufen". 
 * Version: 1.1.0
 * Author: ulrich.digital
 * Author URI: https://ulrich.digital/
 * Text Domain: beitragsverfall
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

foreach ([
    'status.php',
    'metabox.php',
    'cron.php',
    'settings.php',
    'helpers.php'
] as $file) {
    require_once __DIR__ . '/includes/' . $file;
}
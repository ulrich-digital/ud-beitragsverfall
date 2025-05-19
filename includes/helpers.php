<?php
/*
* Zeitzonen-Logik
*/

defined('ABSPATH') || exit;

function beitragsverfall_get_current_cron_interval() {
    $crons = _get_cron_array();
    if (!$crons) return 'Unbekannt';

    foreach ($crons as $timestamp => $hooks) {
        if (isset($hooks['beitragsverfall_cron_hook'])) {
            foreach ($hooks['beitragsverfall_cron_hook'] as $hook) {
                if (isset($hook['schedule'])) {
                    switch ($hook['schedule']) {
                        case 'every_five_minutes':
                            return 'Alle 5 Minuten (Testmodus)';
                        case 'hourly':
                            return 'Stündlich (Live-Modus)';
                        default:
                            return $hook['schedule'];
                    }
                }
            }
        }
    }

    return 'Nicht gefunden';
}

function beitragsverfall_timezone_longname($abbr) {
    return [
        'CET'  => 'Mitteleuropäische Zeit',
        'CEST' => 'Mitteleuropäische Sommerzeit',
        'UTC'  => 'Koordinierte Weltzeit',
        'GMT'  => 'Greenwich Mean Time',
    ][$abbr] ?? $abbr;
}

function beitragsverfall_get_timezone_string() {
    $tz = get_option('timezone_string');
    if ($tz) return $tz;

    $offset = get_option('gmt_offset');
    return timezone_name_from_abbr('', $offset * 3600, 0) ?: 'UTC';
}
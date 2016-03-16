<?php

/**
 * Backup the widget state
 *
 * @return void
 */
function ppi_backup_widget_state() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%widget_%'";
    $results = $wpdb->get_results($sql);

    $backup = array('__template__' => get_option('template'));
    foreach ($results as $result) {
        $backup[$result->option_name] = unserialize($result->option_value);
    }

    update_option('ppi_widget_backup', $backup, false);
}

/**
 * Hide delete inactive widgets button and append delete warning
 *
 * @return void
 */
function ppi_prevent_delete_inactive_widgets() {
    $warning = 'Be careful deleting! These widgets may be used by ProPhoto 6.';
    $css  = ".sidebar-description p:after {content: ' $warning'}";
    $css .= '.inactive-sidebar > .description, .remove-inactive-widgets {display: none}';
    echo "<style>$css</style>";
}

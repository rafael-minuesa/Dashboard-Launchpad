<?php
/**
 * Uninstall handler for Dashboard Launchpad
 *
 * This file is executed when the plugin is deleted via the WordPress admin.
 * It removes all plugin options and settings from the database.
 *
 * @package Dashboard_Launchpad
 * @since 1.2.0
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('dashboard_launchpad_options');
delete_option('dashboard_launchpad_custom_buttons');
delete_transient('dashboard_launchpad_buttons_cache');

// For multisite installations, delete options from all sites
if (is_multisite()) {
    global $wpdb;

    // Get all blog IDs
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('dashboard_launchpad_options');
        delete_option('dashboard_launchpad_custom_buttons');
        delete_transient('dashboard_launchpad_buttons_cache');
        restore_current_blog();
    }
}

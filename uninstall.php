<?php
/**
 * Uninstall handler for Simple LaunchPad
 *
 * This file is executed when the plugin is deleted via the WordPress admin.
 * It removes all plugin options and settings from the database.
 *
 * @package Simple_LaunchPad
 * @since 1.2.0
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'simple_launchpad_options' );
delete_option( 'simple_launchpad_custom_buttons' );
delete_transient( 'simple_launchpad_buttons_cache' );

// For multisite installations, delete options from all sites
if ( is_multisite() ) {
	global $wpdb;

	// Get all blog IDs
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Required for multisite uninstall, no caching needed
	$simple_launchpad_blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $simple_launchpad_blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );

		delete_option( 'simple_launchpad_options' );
		delete_option( 'simple_launchpad_custom_buttons' );
		delete_transient( 'simple_launchpad_buttons_cache' );

		restore_current_blog();
	}
}

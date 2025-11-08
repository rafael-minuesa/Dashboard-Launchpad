<?php
/**
 * Simple LaunchPad
 *
 * A lightweight dashboard launchpad for quick-access admin shortcuts.
 *
 * Plugin Name: Simple LaunchPad
 * Plugin URI:  https://github.com/rafael-minuesa/simple-launchpad
 * Description: Quick-access command center with customizable buttons to all WordPress admin areas. Fully WCAG 2.1 AA accessible with screen reader support. Appears as the first menu item above Dashboard.
 * Version:     3.0.1
 * Author:      Rafael Minuesa
 * Author URI:  https://prowoos.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-launchpad
 * Domain Path: /languages
 *
 * @package SimpleLaunchPad
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Version and Constants.
 *
 * @since 1.0.0
 */
if ( ! defined( 'SIMPLE_LAUNCHPAD_VERSION' ) ) {
	define( 'SIMPLE_LAUNCHPAD_VERSION', '3.0.1' );
}
if ( ! defined( 'SIMPLE_LAUNCHPAD_PLUGIN_DIR' ) ) {
	define( 'SIMPLE_LAUNCHPAD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'SIMPLE_LAUNCHPAD_PLUGIN_URL' ) ) {
	define( 'SIMPLE_LAUNCHPAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Note: Translation loading is handled automatically by WordPress for plugins
 * hosted on WordPress.org since version 4.6. No need for load_plugin_textdomain().
 *
 * @since 1.3.0
 */

/* ---------------------------------------------------------------------------
 * Includes
 * --------------------------------------------------------------------------- */

/**
 * Include required files.
 *
 * The includes directory contains core classes for settings, dashboard, and
 * custom buttons.
 */
require_once SIMPLE_LAUNCHPAD_PLUGIN_DIR . 'includes/class-settings.php';
require_once SIMPLE_LAUNCHPAD_PLUGIN_DIR . 'includes/class-dashboard.php';
require_once SIMPLE_LAUNCHPAD_PLUGIN_DIR . 'includes/class-custom-buttons.php';

/**
 * Initialize the plugin.
 *
 * Loads the settings and dashboard classes and initializes their hooks.
 * This function is called on the 'plugins_loaded' action hook.
 *
 * @since 1.0.0
 * @return void
 */
function simple_launchpad_init() {
	Dashboard_LaunchPad_Settings::init();
	Dashboard_LaunchPad_Dashboard::init();
	Dashboard_LaunchPad_Custom_Buttons::init();
}
add_action( 'plugins_loaded', 'simple_launchpad_init' );

/**
 * Add Settings link to plugin action links on plugins page.
 *
 * Adds a "Settings" link under the plugin name on the plugins list page
 * that directs users to the LaunchPad page where settings are integrated.
 *
 * @since 1.4.0
 * @param array $links Existing plugin action links.
 * @return array Modified plugin action links.
 */
function simple_launchpad_plugin_action_links( $links ) {
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'admin.php?page=simple-launchpad#launchpad-settings' ) ),
		esc_html__( 'Settings', 'simple-launchpad' )
	);
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'simple_launchpad_plugin_action_links' );

/**
 * Enqueue admin styles and scripts.
 *
 * Loads CSS and JavaScript files for the dashboard and settings pages.
 * Assets are only loaded on their respective pages to optimize performance.
 *
 * @since 1.0.0
 * @param string $hook The current admin page hook.
 * @return void
 */
function simple_launchpad_enqueue_admin_assets( $hook ) {
	// Enqueue keyboard shortcut script globally on all admin pages
	wp_enqueue_script(
		'simple-launchpad-global',
		SIMPLE_LAUNCHPAD_PLUGIN_URL . 'assets/js/simple-launchpad.js',
		array( 'jquery' ),
		SIMPLE_LAUNCHPAD_VERSION,
		true
	);

	// Pass the LaunchPad URL to JavaScript for keyboard shortcut
	wp_localize_script(
		'simple-launchpad-global',
		'simpleLaunchpadData',
		array(
			'launchpadUrl' => admin_url( 'admin.php?page=simple-launchpad' ),
		)
	);

	// Enqueue page-specific assets on LaunchPad page
	if ( 'toplevel_page_simple-launchpad' === $hook ) {
		wp_enqueue_style(
			'simple-launchpad',
			SIMPLE_LAUNCHPAD_PLUGIN_URL . 'assets/css/simple-launchpad.css',
			array(),
			SIMPLE_LAUNCHPAD_VERSION
		);

		wp_localize_script(
			'simple-launchpad-global',
			'simpleLaunchpad',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'simple_launchpad_nonce' ),
			)
		);

		// Also enqueue settings assets on LaunchPad page (settings are now at bottom of page)
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			'simple-launchpad-settings',
			SIMPLE_LAUNCHPAD_PLUGIN_URL . 'assets/js/settings.js',
			array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
			SIMPLE_LAUNCHPAD_VERSION,
			true
		);

		wp_enqueue_style(
			'simple-launchpad-settings',
			SIMPLE_LAUNCHPAD_PLUGIN_URL . 'assets/css/settings.css',
			array(),
			SIMPLE_LAUNCHPAD_VERSION
		);
	}
}
add_action( 'admin_enqueue_scripts', 'simple_launchpad_enqueue_admin_assets' );

/**
 * Plugin activation hook.
 *
 * Sets up default plugin options when the plugin is first activated.
 * Only creates options if they don't already exist to preserve user settings.
 * WooCommerce buttons are disabled by default - users must enable them manually.
 *
 * @since 1.0.0
 * @return void
 */
function simple_launchpad_activate() {
	// Get all buttons
	$all_buttons = simple_launchpad_get_default_buttons();
	$all_button_ids = array_keys( $all_buttons );

	// Exclude WooCommerce buttons from default enabled buttons
	$woocommerce_buttons = array( 'woocommerce', 'wc_settings', 'wc_orders', 'wc_products' );
	$default_enabled = array_diff( $all_button_ids, $woocommerce_buttons );

	// Set default options
	$default_options = array(
		'enabled_buttons'       => array_values( $default_enabled ),
		'button_order'          => $all_button_ids,
		'button_color'          => '#2271b1',
		'button_hover_color'    => '#135e96',
		'button_bg_color'       => '#ffffff',
		'button_hover_bg_color' => '#f6f7f7',
		'role_visibility'       => array(),
	);

	if ( ! get_option( 'simple_launchpad_options' ) ) {
		add_option( 'simple_launchpad_options', $default_options );
	}
}
register_activation_hook( __FILE__, 'simple_launchpad_activate' );

/**
 * Plugin deactivation hook.
 *
 * Performs cleanup tasks when the plugin is deactivated.
 * Currently does not perform any actions. Settings are preserved.
 *
 * @since 1.0.0
 * @return void
 */
function simple_launchpad_deactivate() {
	// Deactivation tasks if needed
}
register_deactivation_hook( __FILE__, 'simple_launchpad_deactivate' );

/**
 * Get default button configuration.
 *
 * Returns an array of all available dashboard buttons with their labels,
 * URLs, icons, and required capabilities. These can be filtered using the
 * 'simple_launchpad_default_buttons' filter.
 *
 * Uses transient caching to improve performance. Cache is cleared when
 * settings are updated.
 *
 * @since 1.0.0
 * @return array Array of button configurations keyed by button ID.
 */
function simple_launchpad_get_default_buttons() {
	// Try to get from cache first
	$cache_key = 'simple_launchpad_buttons_cache';
	$buttons   = get_transient( $cache_key );

	if ( false !== $buttons ) {
		return $buttons;
	}

	// Build buttons array if not cached
	// Organized in 4 rows: Row 1: Posts/Pages, Row 2: Appearance, Row 3: Admin, Row 4: WooCommerce
	$buttons = array(
		// Row 1: Content Management (5 columns on desktop, 2 on mobile)
		'posts' => array(
			'label'      => __( 'Posts', 'simple-launchpad' ),
			'url'        => 'edit.php',
			'icon'       => 'dashicons-admin-post',
			'capability' => 'edit_posts',
		),
		'categories' => array(
			'label'      => __( 'Categories', 'simple-launchpad' ),
			'url'        => 'edit-tags.php?taxonomy=category',
			'icon'       => 'dashicons-category',
			'capability' => 'manage_categories',
		),
		'tags' => array(
			'label'      => __( 'Tags', 'simple-launchpad' ),
			'url'        => 'edit-tags.php?taxonomy=post_tag',
			'icon'       => 'dashicons-tag',
			'capability' => 'manage_categories',
		),
		'pages' => array(
			'label'      => __( 'Pages', 'simple-launchpad' ),
			'url'        => 'edit.php?post_type=page',
			'icon'       => 'dashicons-admin-page',
			'capability' => 'edit_pages',
		),
		'media' => array(
			'label'      => __( 'Media', 'simple-launchpad' ),
			'url'        => 'upload.php',
			'icon'       => 'dashicons-admin-media',
			'capability' => 'upload_files',
		),
		// Row 2: Appearance (5 columns on desktop, 2 on mobile)
		'themes' => array(
			'label'      => __( 'Themes', 'simple-launchpad' ),
			'url'        => 'themes.php',
			'icon'       => 'dashicons-admin-appearance',
			'capability' => 'switch_themes',
		),
		'widgets' => array(
			'label'      => __( 'Widgets', 'simple-launchpad' ),
			'url'        => 'widgets.php',
			'icon'       => 'dashicons-screenoptions',
			'capability' => 'edit_theme_options',
		),
		'menus' => array(
			'label'      => __( 'Menus', 'simple-launchpad' ),
			'url'        => 'nav-menus.php',
			'icon'       => 'dashicons-menu',
			'capability' => 'edit_theme_options',
		),
		'customizer' => array(
			'label'      => __( 'Customizer', 'simple-launchpad' ),
			'url'        => 'customize.php',
			'icon'       => 'dashicons-admin-customizer',
			'capability' => 'customize',
		),
		'plugins' => array(
			'label'      => __( 'Plugins', 'simple-launchpad' ),
			'url'        => 'plugins.php',
			'icon'       => 'dashicons-admin-plugins',
			'capability' => 'activate_plugins',
		),
		// Row 3: Administration (5 columns on desktop, 2 on mobile)
		'users' => array(
			'label'      => __( 'Users', 'simple-launchpad' ),
			'url'        => 'users.php',
			'icon'       => 'dashicons-admin-users',
			'capability' => 'list_users',
		),
		'settings' => array(
			'label'      => __( 'Settings', 'simple-launchpad' ),
			'url'        => 'options-general.php',
			'icon'       => 'dashicons-admin-settings',
			'capability' => 'manage_options',
		),
		'tools' => array(
			'label'      => __( 'Tools', 'simple-launchpad' ),
			'url'        => 'tools.php',
			'icon'       => 'dashicons-admin-tools',
			'capability' => 'manage_options',
		),
		'updates' => array(
			'label'      => __( 'Updates', 'simple-launchpad' ),
			'url'        => 'update-core.php',
			'icon'       => 'dashicons-update',
			'capability' => 'update_core',
		),
		'site_health' => array(
			'label'      => __( 'Site Health', 'simple-launchpad' ),
			'url'        => 'site-health.php',
			'icon'       => 'dashicons-heart',
			'capability' => 'manage_options',
		),
		// Row 4: WooCommerce (4 columns, only shown if WooCommerce is active)
		'woocommerce' => array(
			'label'      => __( 'WooCommerce', 'simple-launchpad' ),
			'url'        => 'admin.php?page=wc-admin',
			'icon'       => 'dashicons-cart',
			'capability' => 'manage_woocommerce',
		),
		'wc_settings' => array(
			'label'      => __( 'WC Settings', 'simple-launchpad' ),
			'url'        => 'admin.php?page=wc-settings',
			'icon'       => 'dashicons-admin-generic',
			'capability' => 'manage_woocommerce',
		),
		'wc_orders' => array(
			'label'      => __( 'Orders', 'simple-launchpad' ),
			'url'        => 'admin.php?page=wc-orders',
			'icon'       => 'dashicons-clipboard',
			'capability' => 'edit_shop_orders',
		),
		'wc_products' => array(
			'label'      => __( 'Products', 'simple-launchpad' ),
			'url'        => 'edit.php?post_type=product',
			'icon'       => 'dashicons-products',
			'capability' => 'edit_products',
		),
	);

	/**
	 * Filter the default button configuration.
	 *
	 * Allows developers to add, remove, or modify buttons.
	 *
	 * @since 1.2.0
	 * @param array $buttons Array of button configurations.
	 */
	$buttons = apply_filters( 'simple_launchpad_default_buttons', $buttons );

	// Merge with custom buttons
	$buttons = Dashboard_LaunchPad_Custom_Buttons::merge_buttons( $buttons );

	// Cache for 1 hour (can be cleared when settings are saved)
	set_transient( $cache_key, $buttons, HOUR_IN_SECONDS );

	return $buttons;
}

/**
 * Clear button cache.
 *
 * Called when plugin settings are updated to ensure cached data stays fresh.
 *
 * @since 1.2.0
 * @return void
 */
function simple_launchpad_clear_cache() {
	delete_transient( 'simple_launchpad_buttons_cache' );
}

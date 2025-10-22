<?php
/**
 * Simple Launchpad
 *
 * A lightweight dashboard launchpad for quick-access admin shortcuts.
 * (Formerly "Dashboard Launchpad".)
 *
 * Plugin Name: Simple Launchpad
 * Plugin URI:  https://github.com/rafael-minuesa/dashboard-launchpad
 * Description: Quick-access command center with customizable buttons to all WordPress admin areas. Appears as the first menu item above Dashboard.
 * Version:     1.4.0
 * Author:      Rafael Minuesa
 * Author URI:  https://prowoos.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dashboard-launchpad
 * Domain Path: /languages
 *
 * @package SimpleLaunchpad
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin Version.
 *
 * Note: Keep constant names used by other files for compatibility.
 */
if ( ! defined( 'DASHBOARD_LAUNCHPAD_VERSION' ) ) {
    define( 'DASHBOARD_LAUNCHPAD_VERSION', '1.4.0' );
}
if ( ! defined( 'DASHBOARD_LAUNCHPAD_PLUGIN_DIR' ) ) {
    define( 'DASHBOARD_LAUNCHPAD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'DASHBOARD_LAUNCHPAD_PLUGIN_URL' ) ) {
    define( 'DASHBOARD_LAUNCHPAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Load plugin textdomain for translations.
 *
 * We keep the existing textdomain ('dashboard-launchpad') to remain compatible
 * with existing translation files. If you later rename the textdomain to
 * 'simple-launchpad', update translation files and all translation calls
 * across the codebase.
 *
 * @since 1.3.0
 * @return void
 */
function dashboard_launchpad_load_textdomain() {
    load_plugin_textdomain( 'dashboard-launchpad', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'dashboard_launchpad_load_textdomain' );

/* ---------------------------------------------------------------------------
 * Includes
 * --------------------------------------------------------------------------- */

/**
 * Include required files.
 *
 * The includes directory contains core classes for settings, dashboard, and
 * custom buttons. Keep these require_once lines unchanged unless you rename
 * included files or classes.
 */
require_once DASHBOARD_LAUNCHPAD_PLUGIN_DIR . 'includes/class-settings.php';
require_once DASHBOARD_LAUNCHPAD_PLUGIN_DIR . 'includes/class-dashboard.php';
require_once DASHBOARD_LAUNCHPAD_PLUGIN_DIR . 'includes/class-custom-buttons.php';

/**
 * Initialize the plugin.
 *
 * Loads the settings and dashboard classes and initializes their hooks.
 * This function is called on the 'plugins_loaded' action hook.
 *
 * @since 1.0.0
 * @return void
 */
function dashboard_launchpad_init() {
    Dashboard_Launchpad_Settings::init();
    Dashboard_Launchpad_Dashboard::init();
    Dashboard_Launchpad_Custom_Buttons::init();
}
add_action( 'plugins_loaded', 'dashboard_launchpad_init' );

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
function dashboard_launchpad_enqueue_admin_assets( $hook ) {
    // Enqueue on Launchpad page
    if ( 'toplevel_page_dashboard-launchpad' === $hook ) {
        wp_enqueue_style(
            'dashboard-launchpad',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/css/dashboard-launchpad.css',
            array(),
            DASHBOARD_LAUNCHPAD_VERSION
        );

        wp_enqueue_script(
            'dashboard-launchpad-sortable',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/js/dashboard-launchpad.js',
            array( 'jquery', 'jquery-ui-sortable' ),
            DASHBOARD_LAUNCHPAD_VERSION,
            true
        );

        wp_localize_script(
            'dashboard-launchpad-sortable',
            'dashboardLaunchpad',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'dashboard_launchpad_nonce' ),
            )
        );

        // Also enqueue settings assets on launchpad page (settings are now at bottom of page)
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
            'dashboard-launchpad-settings',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/js/settings.js',
            array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
            DASHBOARD_LAUNCHPAD_VERSION,
            true
        );

        wp_enqueue_style(
            'dashboard-launchpad-settings',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/css/settings.css',
            array(),
            DASHBOARD_LAUNCHPAD_VERSION
        );
    }

    // Enqueue on settings page (kept for backward compatibility, though settings page is removed)
    if ( 'settings_page_dashboard-launchpad' === $hook ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
            'dashboard-launchpad-settings',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/js/settings.js',
            array( 'jquery', 'wp-color-picker' ),
            DASHBOARD_LAUNCHPAD_VERSION,
            true
        );

        wp_enqueue_style(
            'dashboard-launchpad-settings',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/css/settings.css',
            array(),
            DASHBOARD_LAUNCHPAD_VERSION
        );
    }
}
add_action( 'admin_enqueue_scripts', 'dashboard_launchpad_enqueue_admin_assets' );

/**
 * Plugin activation hook.
 *
 * Sets up default plugin options when the plugin is first activated.
 * Only creates options if they don't already exist to preserve user settings.
 *
 * @since 1.0.0
 * @return void
 */
function dashboard_launchpad_activate() {
    // Set default options
    $default_options = array(
        'enabled_buttons'       => array_keys( dashboard_launchpad_get_default_buttons() ),
        'button_order'          => array_keys( dashboard_launchpad_get_default_buttons() ),
        'button_color'          => '#2271b1',
        'button_hover_color'    => '#135e96',
        'button_bg_color'       => '#ffffff',
        'button_hover_bg_color' => '#f6f7f7',
        'role_visibility'       => array(),
    );

    if ( ! get_option( 'dashboard_launchpad_options' ) ) {
        add_option( 'dashboard_launchpad_options', $default_options );
    }
}
register_activation_hook( __FILE__, 'dashboard_launchpad_activate' );

/**
 * Plugin deactivation hook.
 *
 * Performs cleanup tasks when the plugin is deactivated.
 * Currently does not perform any actions. Settings are preserved.
 *
 * @since 1.0.0
 * @return void
 */
function dashboard_launchpad_deactivate() {
    // Deactivation tasks if needed
}
register_deactivation_hook( __FILE__, 'dashboard_launchpad_deactivate' );

/**
 * Get default button configuration.
 *
 * Returns an array of all available dashboard buttons with their labels,
 * URLs, icons, and required capabilities. These can be filtered using the
 * 'dashboard_launchpad_default_buttons' filter.
 *
 * Uses transient caching to improve performance. Cache is cleared when
 * settings are updated.
 *
 * @since 1.0.0
 * @return array Array of button configurations keyed by button ID.
 */
function dashboard_launchpad_get_default_buttons() {
    // Try to get from cache first
    $cache_key = 'dashboard_launchpad_buttons_cache';
    $buttons   = get_transient( $cache_key );

    if ( false !== $buttons ) {
        return $buttons;
    }

    // Build buttons array if not cached
    // Organized in 3 rows: Row 1: Posts/Pages, Row 2: Appearance, Row 3: Admin
    $buttons = array(
        // Row 1: Content Management (5 columns on desktop, 2 on mobile)
        'posts' => array(
            'label'      => __( 'Posts', 'dashboard-launchpad' ),
            'url'        => 'edit.php',
            'icon'       => 'dashicons-admin-post',
            'capability' => 'edit_posts',
        ),
        'categories' => array(
            'label'      => __( 'Categories', 'dashboard-launchpad' ),
            'url'        => 'edit-tags.php?taxonomy=category',
            'icon'       => 'dashicons-category',
            'capability' => 'manage_categories',
        ),
        'tags' => array(
            'label'      => __( 'Tags', 'dashboard-launchpad' ),
            'url'        => 'edit-tags.php?taxonomy=post_tag',
            'icon'       => 'dashicons-tag',
            'capability' => 'manage_categories',
        ),
        'pages' => array(
            'label'      => __( 'Pages', 'dashboard-launchpad' ),
            'url'        => 'edit.php?post_type=page',
            'icon'       => 'dashicons-admin-page',
            'capability' => 'edit_pages',
        ),
        'media' => array(
            'label'      => __( 'Media', 'dashboard-launchpad' ),
            'url'        => 'upload.php',
            'icon'       => 'dashicons-admin-media',
            'capability' => 'upload_files',
        ),
        // Row 2: Appearance (5 columns on desktop, 2 on mobile)
        'themes' => array(
            'label'      => __( 'Themes', 'dashboard-launchpad' ),
            'url'        => 'themes.php',
            'icon'       => 'dashicons-admin-appearance',
            'capability' => 'switch_themes',
        ),
        'widgets' => array(
            'label'      => __( 'Widgets', 'dashboard-launchpad' ),
            'url'        => 'widgets.php',
            'icon'       => 'dashicons-screenoptions',
            'capability' => 'edit_theme_options',
        ),
        'menus' => array(
            'label'      => __( 'Menus', 'dashboard-launchpad' ),
            'url'        => 'nav-menus.php',
            'icon'       => 'dashicons-menu',
            'capability' => 'edit_theme_options',
        ),
        'customizer' => array(
            'label'      => __( 'Customizer', 'dashboard-launchpad' ),
            'url'        => 'customize.php',
            'icon'       => 'dashicons-admin-customizer',
            'capability' => 'customize',
        ),
        'plugins' => array(
            'label'      => __( 'Plugins', 'dashboard-launchpad' ),
            'url'        => 'plugins.php',
            'icon'       => 'dashicons-admin-plugins',
            'capability' => 'activate_plugins',
        ),
        // Row 3: Administration (5 columns on desktop, 2 on mobile)
        'users' => array(
            'label'      => __( 'Users', 'dashboard-launchpad' ),
            'url'        => 'users.php',
            'icon'       => 'dashicons-admin-users',
            'capability' => 'list_users',
        ),
        'settings' => array(
            'label'      => __( 'Settings', 'dashboard-launchpad' ),
            'url'        => 'options-general.php',
            'icon'       => 'dashicons-admin-settings',
            'capability' => 'manage_options',
        ),
        'tools' => array(
            'label'      => __( 'Tools', 'dashboard-launchpad' ),
            'url'        => 'tools.php',
            'icon'       => 'dashicons-admin-tools',
            'capability' => 'manage_options',
        ),
        'updates' => array(
            'label'      => __( 'Updates', 'dashboard-launchpad' ),
            'url'        => 'update-core.php',
            'icon'       => 'dashicons-update',
            'capability' => 'update_core',
        ),
        'site_health' => array(
            'label'      => __( 'Site Health', 'dashboard-launchpad' ),
            'url'        => 'site-health.php',
            'icon'       => 'dashicons-heart',
            'capability' => 'manage_options',
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
    $buttons = apply_filters( 'dashboard_launchpad_default_buttons', $buttons );

    // Merge with custom buttons
    $buttons = Dashboard_Launchpad_Custom_Buttons::merge_buttons( $buttons );

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
function dashboard_launchpad_clear_cache() {
    delete_transient( 'dashboard_launchpad_buttons_cache' );
}
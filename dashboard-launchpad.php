<?php
/**
 * Plugin Name: Dashboard Launchpad
 * Plugin URI: https://github.com/rafael-minuesa/dashboard-launchpad
 * Description: Quick-access command center with customizable buttons to all WordPress admin areas. Appears as the first menu item above Dashboard.
 * Version: 1.3.0
 * Author: Rafael Minuesa
 * Author URI: https://prowoos.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dashboard-launchpad
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('DASHBOARD_LAUNCHPAD_VERSION', '1.3.0');
define('DASHBOARD_LAUNCHPAD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DASHBOARD_LAUNCHPAD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
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
add_action('plugins_loaded', 'dashboard_launchpad_init');

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
function dashboard_launchpad_enqueue_admin_assets($hook) {
    // Enqueue on Launchpad page
    if ('toplevel_page_dashboard-launchpad' === $hook) {
        wp_enqueue_style(
            'dashboard-launchpad',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/css/dashboard-launchpad.css',
            array(),
            DASHBOARD_LAUNCHPAD_VERSION
        );

        wp_enqueue_script(
            'dashboard-launchpad-sortable',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/js/dashboard-launchpad.js',
            array('jquery', 'jquery-ui-sortable'),
            DASHBOARD_LAUNCHPAD_VERSION,
            true
        );

        wp_localize_script('dashboard-launchpad-sortable', 'dashboardLaunchpad', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dashboard_launchpad_nonce')
        ));
    }
    
    // Enqueue on settings page
    if ('settings_page_dashboard-launchpad' === $hook) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script(
            'dashboard-launchpad-settings',
            DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/js/settings.js',
            array('jquery', 'wp-color-picker'),
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
add_action('admin_enqueue_scripts', 'dashboard_launchpad_enqueue_admin_assets');

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
        'enabled_buttons' => array_keys(dashboard_launchpad_get_default_buttons()),
        'button_order' => array_keys(dashboard_launchpad_get_default_buttons()),
        'button_color' => '#2271b1',
        'button_hover_color' => '#135e96',
        'button_bg_color' => '#ffffff',
        'button_hover_bg_color' => '#f6f7f7',
        'role_visibility' => array()
    );
    
    if (!get_option('dashboard_launchpad_options')) {
        add_option('dashboard_launchpad_options', $default_options);
    }
}
register_activation_hook(__FILE__, 'dashboard_launchpad_activate');

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
register_deactivation_hook(__FILE__, 'dashboard_launchpad_deactivate');

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
    $buttons = get_transient($cache_key);

    if (false !== $buttons) {
        return $buttons;
    }

    // Build buttons array if not cached
    $buttons = array(
        'posts' => array(
            'label' => __('Posts', 'dashboard-launchpad'),
            'url' => 'edit.php',
            'icon' => 'dashicons-admin-post',
            'capability' => 'edit_posts'
        ),
        'new_post' => array(
            'label' => __('Add New Post', 'dashboard-launchpad'),
            'url' => 'post-new.php',
            'icon' => 'dashicons-edit',
            'capability' => 'edit_posts'
        ),
        'pages' => array(
            'label' => __('Pages', 'dashboard-launchpad'),
            'url' => 'edit.php?post_type=page',
            'icon' => 'dashicons-admin-page',
            'capability' => 'edit_pages'
        ),
        'new_page' => array(
            'label' => __('Add New Page', 'dashboard-launchpad'),
            'url' => 'post-new.php?post_type=page',
            'icon' => 'dashicons-welcome-add-page',
            'capability' => 'edit_pages'
        ),
        'media' => array(
            'label' => __('Media', 'dashboard-launchpad'),
            'url' => 'upload.php',
            'icon' => 'dashicons-admin-media',
            'capability' => 'upload_files'
        ),
        'comments' => array(
            'label' => __('Comments', 'dashboard-launchpad'),
            'url' => 'edit-comments.php',
            'icon' => 'dashicons-admin-comments',
            'capability' => 'moderate_comments'
        ),
        'appearance' => array(
            'label' => __('Appearance', 'dashboard-launchpad'),
            'url' => 'themes.php',
            'icon' => 'dashicons-admin-appearance',
            'capability' => 'switch_themes'
        ),
        'plugins' => array(
            'label' => __('Plugins', 'dashboard-launchpad'),
            'url' => 'plugins.php',
            'icon' => 'dashicons-admin-plugins',
            'capability' => 'activate_plugins'
        ),
        'users' => array(
            'label' => __('Users', 'dashboard-launchpad'),
            'url' => 'users.php',
            'icon' => 'dashicons-admin-users',
            'capability' => 'list_users'
        ),
        'settings' => array(
            'label' => __('Settings', 'dashboard-launchpad'),
            'url' => 'options-general.php',
            'icon' => 'dashicons-admin-settings',
            'capability' => 'manage_options'
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
    $buttons = apply_filters('dashboard_launchpad_default_buttons', $buttons);

    // Merge with custom buttons
    $buttons = Dashboard_Launchpad_Custom_Buttons::merge_buttons($buttons);

    // Cache for 1 hour (can be cleared when settings are saved)
    set_transient($cache_key, $buttons, HOUR_IN_SECONDS);

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
    delete_transient('dashboard_launchpad_buttons_cache');
}

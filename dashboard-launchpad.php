<?php
/**
 * Plugin Name: Dashboard Launchpad
 * Plugin URI: https://github.com/yourusername/dashboard-launchpad
 * Description: Transform your WordPress dashboard into a streamlined command center with quick-access buttons to all admin areas
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
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
define('DASHBOARD_LAUNCHPAD_VERSION', '1.0.0');
define('DASHBOARD_LAUNCHPAD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DASHBOARD_LAUNCHPAD_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Remove default dashboard widgets
 */
function dashboard_launchpad_remove_widgets() {
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'dashboard_launchpad_remove_widgets');

/**
 * Add custom dashboard widget with launchpad
 */
function dashboard_launchpad_add_widget() {
    wp_add_dashboard_widget(
        'dashboard_launchpad_widget',
        __('Quick Launch', 'dashboard-launchpad'),
        'dashboard_launchpad_render_widget'
    );
}
add_action('wp_dashboard_setup', 'dashboard_launchpad_add_widget');

/**
 * Render the launchpad widget
 */
function dashboard_launchpad_render_widget() {
    $buttons = dashboard_launchpad_get_buttons();
    ?>
    <div class="dashboard-launchpad">
        <?php foreach ($buttons as $button): ?>
            <a href="<?php echo esc_url(admin_url($button['url'])); ?>" class="launchpad-button">
                <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                <span class="button-label"><?php echo esc_html($button['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Get launchpad buttons configuration
 */
function dashboard_launchpad_get_buttons() {
    $buttons = array(
        array('label' => __('Posts', 'dashboard-launchpad'), 'url' => 'edit.php', 'icon' => 'dashicons-admin-post'),
        array('label' => __('Add New Post', 'dashboard-launchpad'), 'url' => 'post-new.php', 'icon' => 'dashicons-edit'),
        array('label' => __('Pages', 'dashboard-launchpad'), 'url' => 'edit.php?post_type=page', 'icon' => 'dashicons-admin-page'),
        array('label' => __('Add New Page', 'dashboard-launchpad'), 'url' => 'post-new.php?post_type=page', 'icon' => 'dashicons-welcome-add-page'),
        array('label' => __('Media', 'dashboard-launchpad'), 'url' => 'upload.php', 'icon' => 'dashicons-admin-media'),
        array('label' => __('Comments', 'dashboard-launchpad'), 'url' => 'edit-comments.php', 'icon' => 'dashicons-admin-comments'),
        array('label' => __('Appearance', 'dashboard-launchpad'), 'url' => 'themes.php', 'icon' => 'dashicons-admin-appearance'),
        array('label' => __('Plugins', 'dashboard-launchpad'), 'url' => 'plugins.php', 'icon' => 'dashicons-admin-plugins'),
        array('label' => __('Users', 'dashboard-launchpad'), 'url' => 'users.php', 'icon' => 'dashicons-admin-users'),
        array('label' => __('Settings', 'dashboard-launchpad'), 'url' => 'options-general.php', 'icon' => 'dashicons-admin-settings'),
    );
    
    return apply_filters('dashboard_launchpad_buttons', $buttons);
}

/**
 * Enqueue admin styles
 */
function dashboard_launchpad_enqueue_styles($hook) {
    if ('index.php' !== $hook) {
        return;
    }
    
    wp_enqueue_style(
        'dashboard-launchpad',
        DASHBOARD_LAUNCHPAD_PLUGIN_URL . 'assets/css/dashboard-launchpad.css',
        array(),
        DASHBOARD_LAUNCHPAD_VERSION
    );
}
add_action('admin_enqueue_scripts', 'dashboard_launchpad_enqueue_styles');

/**
 * Plugin activation hook
 */
function dashboard_launchpad_activate() {
    // Activation tasks if needed
}
register_activation_hook(__FILE__, 'dashboard_launchpad_activate');

/**
 * Plugin deactivation hook
 */
function dashboard_launchpad_deactivate() {
    // Deactivation tasks if needed
}
register_deactivation_hook(__FILE__, 'dashboard_launchpad_deactivate');

<?php
/**
 * Launchpad Page functionality
 *
 * Creates a standalone admin page with quick-access buttons, positioned
 * as the first menu item in the WordPress admin sidebar (above Dashboard).
 *
 * @package Dashboard_Launchpad
 * @since 1.3.0
 */

class Dashboard_Launchpad_Dashboard {

    /**
     * Initialize the launchpad page.
     *
     * Registers the top-level menu page and AJAX handlers.
     *
     * @since 1.0.0
     * @return void
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_launchpad_menu'), 1);
        add_action('wp_ajax_dashboard_launchpad_save_order', array(__CLASS__, 'save_button_order'));
        add_action('admin_head', array(__CLASS__, 'add_custom_styles'));
    }

    /**
     * Add the Launchpad menu as the first menu item.
     *
     * Creates a top-level menu page positioned above Dashboard (position 1).
     * Uses a rocket icon and includes the menu page rendering callback.
     *
     * @since 1.3.0
     * @return void
     */
    public static function add_launchpad_menu() {
        add_menu_page(
            __('Launchpad', 'dashboard-launchpad'),           // Page title
            __('Launchpad', 'dashboard-launchpad'),           // Menu title
            'read',                                            // Capability (all logged-in users)
            'dashboard-launchpad',                             // Menu slug
            array(__CLASS__, 'render_launchpad_page'),         // Callback function
            'dashicons-rocket',                                // Icon (rocket)
            1                                                  // Position 1 (before Dashboard at position 2)
        );
    }

    /**
     * Render the Launchpad page.
     *
     * Displays the full-page button grid, filtering buttons based on user
     * capabilities, role visibility settings, and enabled status.
     *
     * @since 1.0.0
     * @return void
     */
    public static function render_launchpad_page() {
        $options = get_option('dashboard_launchpad_options');
        $all_buttons = dashboard_launchpad_get_default_buttons();
        $enabled_buttons = $options['enabled_buttons'] ?? array_keys($all_buttons);
        $button_order = $options['button_order'] ?? array_keys($all_buttons);
        $role_visibility = $options['role_visibility'] ?? array();

        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;

        // Filter and order buttons
        $buttons_to_display = array();
        foreach ($button_order as $button_id) {
            // Check if button is enabled
            if (!in_array($button_id, $enabled_buttons)) {
                continue;
            }

            // Check if button exists
            if (!isset($all_buttons[$button_id])) {
                continue;
            }

            $button = $all_buttons[$button_id];

            // Check capability
            if (!current_user_can($button['capability'])) {
                continue;
            }

            // Check role visibility
            if (!empty($role_visibility[$button_id])) {
                $allowed_roles = $role_visibility[$button_id];
                $has_access = false;
                foreach ($user_roles as $role) {
                    if (in_array($role, $allowed_roles)) {
                        $has_access = true;
                        break;
                    }
                }
                if (!$has_access) {
                    continue;
                }
            }

            $buttons_to_display[] = array(
                'id' => $button_id,
                'label' => $button['label'],
                'url' => $button['url'],
                'icon' => $button['icon']
            );
        }

        // Apply filter for developers
        $buttons_to_display = apply_filters('dashboard_launchpad_buttons', $buttons_to_display);

        ?>
        <div class="wrap dashboard-launchpad-page">
            <h1 class="launchpad-title">
                <span class="dashicons dashicons-rocket"></span>
                <?php echo esc_html__('Launchpad', 'dashboard-launchpad'); ?>
            </h1>

            <?php if (current_user_can('manage_options')): ?>
                <p class="launchpad-subtitle">
                    <?php _e('Quick access to your most-used admin areas', 'dashboard-launchpad'); ?>
                    &nbsp;•&nbsp;
                    <a href="<?php echo admin_url('options-general.php?page=dashboard-launchpad'); ?>">
                        <?php _e('Configure Buttons', 'dashboard-launchpad'); ?>
                    </a>
                </p>
            <?php else: ?>
                <p class="launchpad-subtitle">
                    <?php _e('Quick access to your most-used admin areas', 'dashboard-launchpad'); ?>
                </p>
            <?php endif; ?>

            <?php if (empty($buttons_to_display)): ?>
                <div class="notice notice-info">
                    <p>
                        <?php _e('No buttons configured or available.', 'dashboard-launchpad'); ?>
                        <?php if (current_user_can('manage_options')): ?>
                            <a href="<?php echo admin_url('options-general.php?page=dashboard-launchpad'); ?>">
                                <?php _e('Configure buttons in Settings', 'dashboard-launchpad'); ?>
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="dashboard-launchpad" id="dashboard-launchpad-buttons">
                    <?php foreach ($buttons_to_display as $button): ?>
                        <a href="<?php echo esc_url(admin_url($button['url'])); ?>"
                           class="launchpad-button"
                           data-button-id="<?php echo esc_attr($button['id']); ?>">
                            <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                            <span class="button-label"><?php echo esc_html($button['label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Save button order via AJAX.
     *
     * Handles AJAX requests to save the button order when users drag and drop
     * buttons. Verifies nonce and user capabilities before saving.
     *
     * @since 1.0.0
     * @return void Sends JSON response and exits.
     */
    public static function save_button_order() {
        check_ajax_referer('dashboard_launchpad_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'dashboard-launchpad')));
        }

        // Validate input
        if (!isset($_POST['order']) || !is_array($_POST['order'])) {
            wp_send_json_error(array('message' => __('Invalid data format', 'dashboard-launchpad')));
        }

        $order = array_map('sanitize_key', $_POST['order']);

        // Verify all button IDs are valid
        $all_buttons = dashboard_launchpad_get_default_buttons();
        $valid_order = array();
        foreach ($order as $button_id) {
            if (isset($all_buttons[$button_id])) {
                $valid_order[] = $button_id;
            }
        }

        $options = get_option('dashboard_launchpad_options');
        if (!is_array($options)) {
            $options = array();
        }
        $options['button_order'] = $valid_order;
        update_option('dashboard_launchpad_options', $options);

        // Clear cache after updating order
        dashboard_launchpad_clear_cache();

        wp_send_json_success(array('message' => __('Order saved', 'dashboard-launchpad')));
    }

    /**
     * Add custom styles based on user settings.
     *
     * Outputs inline CSS in the admin header to apply user-customized colors
     * to the launchpad buttons. Only runs on the launchpad page.
     *
     * @since 1.0.0
     * @return void
     */
    public static function add_custom_styles() {
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'toplevel_page_dashboard-launchpad') {
            return;
        }

        $options = get_option('dashboard_launchpad_options');
        $button_color = $options['button_color'] ?? '#2271b1';
        $button_hover_color = $options['button_hover_color'] ?? '#135e96';
        $button_bg_color = $options['button_bg_color'] ?? '#ffffff';
        $button_hover_bg_color = $options['button_hover_bg_color'] ?? '#f6f7f7';

        ?>
        <style>
            /* Page header styling */
            .dashboard-launchpad-page .launchpad-title {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 23px;
                font-weight: 400;
                margin: 0;
                padding: 9px 0 4px;
                line-height: 1.3;
            }

            .dashboard-launchpad-page .launchpad-title .dashicons {
                font-size: 28px;
                width: 28px;
                height: 28px;
                color: #2271b1;
            }

            .dashboard-launchpad-page .launchpad-subtitle {
                color: #646970;
                font-size: 14px;
                margin: 5px 0 20px;
            }

            .dashboard-launchpad-page .launchpad-subtitle a {
                color: #2271b1;
                text-decoration: none;
            }

            .dashboard-launchpad-page .launchpad-subtitle a:hover {
                color: #135e96;
            }

            /* Custom button colors */
            .launchpad-button {
                color: <?php echo esc_attr($button_color); ?> !important;
                background-color: <?php echo esc_attr($button_bg_color); ?> !important;
            }

            .launchpad-button:hover {
                color: <?php echo esc_attr($button_hover_color); ?> !important;
                background-color: <?php echo esc_attr($button_hover_bg_color); ?> !important;
                border-color: <?php echo esc_attr($button_hover_color); ?> !important;
            }
        </style>
        <?php
    }
}

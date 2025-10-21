<?php
/**
 * Custom Button Management
 *
 * Manages user-created custom buttons that can be added to the dashboard launchpad.
 * Custom buttons are stored separately from default buttons and can be created,
 * edited, and deleted through the settings interface.
 *
 * @package Dashboard_Launchpad
 * @since 1.3.0
 */

class Dashboard_Launchpad_Custom_Buttons {

    /**
     * Initialize custom button management.
     *
     * Registers AJAX handlers for CRUD operations on custom buttons.
     *
     * @since 1.3.0
     * @return void
     */
    public static function init() {
        add_action('wp_ajax_dashboard_launchpad_add_custom_button', array(__CLASS__, 'add_custom_button'));
        add_action('wp_ajax_dashboard_launchpad_update_custom_button', array(__CLASS__, 'update_custom_button'));
        add_action('wp_ajax_dashboard_launchpad_delete_custom_button', array(__CLASS__, 'delete_custom_button'));
    }

    /**
     * Get all custom buttons.
     *
     * Retrieves custom buttons from the database. Returns an empty array if
     * no custom buttons have been created.
     *
     * @since 1.3.0
     * @return array Array of custom button configurations keyed by button ID.
     */
    public static function get_custom_buttons() {
        $custom_buttons = get_option('dashboard_launchpad_custom_buttons', array());

        if (!is_array($custom_buttons)) {
            return array();
        }

        return $custom_buttons;
    }

    /**
     * Merge custom buttons with default buttons.
     *
     * Combines default and custom buttons into a single array for rendering.
     * Custom buttons can override default buttons if they use the same ID.
     *
     * @since 1.3.0
     * @param array $default_buttons Array of default button configurations.
     * @return array Merged array of default and custom buttons.
     */
    public static function merge_buttons($default_buttons) {
        $custom_buttons = self::get_custom_buttons();
        return array_merge($default_buttons, $custom_buttons);
    }

    /**
     * Add a new custom button via AJAX.
     *
     * Handles the AJAX request to create a new custom button. Validates all
     * input fields and saves to the database if valid.
     *
     * @since 1.3.0
     * @return void Sends JSON response and exits.
     */
    public static function add_custom_button() {
        check_ajax_referer('dashboard_launchpad_custom_button_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'dashboard-launchpad')));
        }

        // Validate required fields
        if (empty($_POST['button_id']) || empty($_POST['label']) || empty($_POST['url'])) {
            wp_send_json_error(array('message' => __('Missing required fields', 'dashboard-launchpad')));
        }

        $button_id = sanitize_key($_POST['button_id']);
        $label = sanitize_text_field($_POST['label']);
        $url = sanitize_text_field($_POST['url']);
        $icon = sanitize_text_field($_POST['icon'] ?? 'dashicons-admin-generic');
        $capability = sanitize_text_field($_POST['capability'] ?? 'read');

        // Check if button ID already exists (in defaults or customs)
        $all_default_buttons = dashboard_launchpad_get_default_buttons();
        $custom_buttons = self::get_custom_buttons();

        if (isset($all_default_buttons[$button_id]) || isset($custom_buttons[$button_id])) {
            wp_send_json_error(array('message' => __('Button ID already exists', 'dashboard-launchpad')));
        }

        // Validate button ID format (alphanumeric, underscores, hyphens only)
        if (!preg_match('/^[a-z0-9_-]+$/', $button_id)) {
            wp_send_json_error(array('message' => __('Button ID can only contain lowercase letters, numbers, underscores, and hyphens', 'dashboard-launchpad')));
        }

        // Validate capability exists
        $all_caps = array('read', 'edit_posts', 'edit_pages', 'edit_others_posts', 'publish_posts',
                         'manage_categories', 'moderate_comments', 'manage_options', 'upload_files',
                         'activate_plugins', 'edit_theme_options', 'list_users', 'switch_themes');
        if (!in_array($capability, $all_caps)) {
            $capability = 'read'; // Default to safest capability
        }

        // Create the button
        $custom_buttons[$button_id] = array(
            'label' => $label,
            'url' => $url,
            'icon' => $icon,
            'capability' => $capability,
            'custom' => true  // Mark as custom for identification
        );

        update_option('dashboard_launchpad_custom_buttons', $custom_buttons);

        // Clear cache
        dashboard_launchpad_clear_cache();

        wp_send_json_success(array(
            'message' => __('Custom button added successfully', 'dashboard-launchpad'),
            'button' => $custom_buttons[$button_id]
        ));
    }

    /**
     * Update an existing custom button via AJAX.
     *
     * Modifies an existing custom button's properties. Only custom buttons
     * (not default buttons) can be updated.
     *
     * @since 1.3.0
     * @return void Sends JSON response and exits.
     */
    public static function update_custom_button() {
        check_ajax_referer('dashboard_launchpad_custom_button_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'dashboard-launchpad')));
        }

        if (empty($_POST['button_id'])) {
            wp_send_json_error(array('message' => __('Button ID required', 'dashboard-launchpad')));
        }

        $button_id = sanitize_key($_POST['button_id']);
        $custom_buttons = self::get_custom_buttons();

        if (!isset($custom_buttons[$button_id])) {
            wp_send_json_error(array('message' => __('Custom button not found', 'dashboard-launchpad')));
        }

        // Update fields if provided
        if (!empty($_POST['label'])) {
            $custom_buttons[$button_id]['label'] = sanitize_text_field($_POST['label']);
        }
        if (!empty($_POST['url'])) {
            $custom_buttons[$button_id]['url'] = sanitize_text_field($_POST['url']);
        }
        if (!empty($_POST['icon'])) {
            $custom_buttons[$button_id]['icon'] = sanitize_text_field($_POST['icon']);
        }
        if (!empty($_POST['capability'])) {
            $custom_buttons[$button_id]['capability'] = sanitize_text_field($_POST['capability']);
        }

        update_option('dashboard_launchpad_custom_buttons', $custom_buttons);

        // Clear cache
        dashboard_launchpad_clear_cache();

        wp_send_json_success(array(
            'message' => __('Custom button updated successfully', 'dashboard-launchpad'),
            'button' => $custom_buttons[$button_id]
        ));
    }

    /**
     * Delete a custom button via AJAX.
     *
     * Removes a custom button from the database. Only custom buttons
     * (not default buttons) can be deleted.
     *
     * @since 1.3.0
     * @return void Sends JSON response and exits.
     */
    public static function delete_custom_button() {
        check_ajax_referer('dashboard_launchpad_custom_button_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'dashboard-launchpad')));
        }

        if (empty($_POST['button_id'])) {
            wp_send_json_error(array('message' => __('Button ID required', 'dashboard-launchpad')));
        }

        $button_id = sanitize_key($_POST['button_id']);
        $custom_buttons = self::get_custom_buttons();

        if (!isset($custom_buttons[$button_id])) {
            wp_send_json_error(array('message' => __('Custom button not found', 'dashboard-launchpad')));
        }

        unset($custom_buttons[$button_id]);
        update_option('dashboard_launchpad_custom_buttons', $custom_buttons);

        // Also remove from enabled buttons and button order
        $options = get_option('dashboard_launchpad_options', array());
        if (isset($options['enabled_buttons']) && is_array($options['enabled_buttons'])) {
            $options['enabled_buttons'] = array_diff($options['enabled_buttons'], array($button_id));
        }
        if (isset($options['button_order']) && is_array($options['button_order'])) {
            $options['button_order'] = array_diff($options['button_order'], array($button_id));
        }
        update_option('dashboard_launchpad_options', $options);

        // Clear cache
        dashboard_launchpad_clear_cache();

        wp_send_json_success(array('message' => __('Custom button deleted successfully', 'dashboard-launchpad')));
    }

    /**
     * Get available WordPress capabilities for dropdown.
     *
     * Returns an array of common WordPress capabilities that can be used
     * for custom button access control.
     *
     * @since 1.3.0
     * @return array Array of capabilities with labels.
     */
    public static function get_available_capabilities() {
        return array(
            'read' => __('Read (All Users)', 'dashboard-launchpad'),
            'edit_posts' => __('Edit Posts', 'dashboard-launchpad'),
            'edit_pages' => __('Edit Pages', 'dashboard-launchpad'),
            'edit_others_posts' => __('Edit Others Posts', 'dashboard-launchpad'),
            'publish_posts' => __('Publish Posts', 'dashboard-launchpad'),
            'manage_categories' => __('Manage Categories', 'dashboard-launchpad'),
            'moderate_comments' => __('Moderate Comments', 'dashboard-launchpad'),
            'upload_files' => __('Upload Files', 'dashboard-launchpad'),
            'activate_plugins' => __('Activate Plugins', 'dashboard-launchpad'),
            'edit_theme_options' => __('Edit Theme Options', 'dashboard-launchpad'),
            'list_users' => __('List Users', 'dashboard-launchpad'),
            'manage_options' => __('Manage Options (Administrator)', 'dashboard-launchpad'),
            'switch_themes' => __('Switch Themes', 'dashboard-launchpad'),
        );
    }
}

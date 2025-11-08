<?php
/**
 * Settings page functionality
 *
 * Manages the plugin settings page, form rendering, input sanitization,
 * and integration with the WordPress Settings API.
 *
 * @package Simple_LaunchPad
 * @since 1.0.0
 */

class Dashboard_LaunchPad_Settings {

    /**
     * Initialize the settings.
     *
     * Registers action hooks for the admin menu and settings registration.
     *
     * @since 1.0.0
     * @return void
     */
    public static function init() {
        // Settings are now displayed on the LaunchPad page itself, not as a separate menu item
        // add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }

    /**
     * Add settings page to WordPress admin.
     *
     * Creates a settings page under Settings > Dashboard LaunchPad.
     * Requires 'manage_options' capability.
     *
     * @since 1.0.0
     * @return void
     */
    public static function add_settings_page() {
        add_options_page(
            __('Dashboard LaunchPad Settings', 'simple-launchpad'),
            __('Dashboard LaunchPad', 'simple-launchpad'),
            'manage_options',
            'simple-launchpad',
            array(__CLASS__, 'render_settings_page')
        );
    }

    /**
     * Register plugin settings.
     *
     * Registers the plugin settings with WordPress Settings API.
     * Includes sanitization callback for all options.
     *
     * @since 1.0.0
     * @return void
     */
    public static function register_settings() {
        register_setting(
            'simple_launchpad_options',
            'simple_launchpad_options',
            array(__CLASS__, 'sanitize_options')
        );
    }

    /**
     * Sanitize options before saving.
     *
     * Validates and sanitizes all plugin options including button IDs,
     * hex colors, and role visibility settings.
     *
     * @since 1.0.0
     * @param array $input Raw input data from the settings form.
     * @return array Sanitized options ready to be saved.
     */
    public static function sanitize_options($input) {
        $sanitized = array();
        $all_buttons = simple_launchpad_get_default_buttons();

        // Sanitize enabled buttons - only allow valid button IDs
        if (isset($input['enabled_buttons']) && is_array($input['enabled_buttons'])) {
            $enabled = array_map('sanitize_key', $input['enabled_buttons']);
            $sanitized['enabled_buttons'] = array_filter($enabled, function($button_id) use ($all_buttons) {
                return isset($all_buttons[$button_id]);
            });
        } else {
            $sanitized['enabled_buttons'] = array();
        }

        // Sanitize button order (comes as comma-separated string from hidden field)
        // Only allow valid button IDs
        if (isset($input['button_order'])) {
            if (is_string($input['button_order'])) {
                $order_array = explode(',', $input['button_order']);
                $order = array_map('sanitize_key', $order_array);
            } elseif (is_array($input['button_order'])) {
                $order = array_map('sanitize_key', $input['button_order']);
            } else {
                $order = array();
            }
            // Filter to only valid button IDs
            $sanitized['button_order'] = array_filter($order, function($button_id) use ($all_buttons) {
                return isset($all_buttons[$button_id]);
            });
        } else {
            $sanitized['button_order'] = array();
        }

        // Sanitize colors
        $sanitized['button_color'] = sanitize_hex_color($input['button_color'] ?? '#2271b1');
        $sanitized['button_hover_color'] = sanitize_hex_color($input['button_hover_color'] ?? '#135e96');
        $sanitized['button_bg_color'] = sanitize_hex_color($input['button_bg_color'] ?? '#ffffff');
        $sanitized['button_hover_bg_color'] = sanitize_hex_color($input['button_hover_bg_color'] ?? '#f6f7f7');

        // Sanitize dark mode setting - only allow 'auto', 'light', or 'dark'
        $dark_mode = $input['dark_mode'] ?? 'light';
        $sanitized['dark_mode'] = in_array($dark_mode, array('auto', 'light', 'dark'), true) ? $dark_mode : 'light';

        // Sanitize role visibility - only allow valid button IDs and role names
        if (isset($input['role_visibility']) && is_array($input['role_visibility'])) {
            $sanitized['role_visibility'] = array();
            $valid_roles = array_keys(wp_roles()->get_names());

            foreach ($input['role_visibility'] as $button_id => $roles) {
                $button_id = sanitize_key($button_id);
                // Only save if button ID is valid
                if (isset($all_buttons[$button_id]) && is_array($roles)) {
                    $clean_roles = array_map('sanitize_key', $roles);
                    // Only save valid role names
                    $sanitized['role_visibility'][$button_id] = array_filter($clean_roles, function($role) use ($valid_roles) {
                        return in_array($role, $valid_roles);
                    });
                }
            }
        } else {
            $sanitized['role_visibility'] = array();
        }

        // Clear button cache when settings are updated
        simple_launchpad_clear_cache();

        return $sanitized;
    }

    /**
     * Render the settings page.
     *
     * Displays the tabbed settings interface with sections for:
     * - Button enable/disable and ordering
     * - Color customization
     * - Role-based visibility controls
     *
     * @since 1.0.0
     * @return void
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if settings were just saved
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- WordPress sets this parameter after settings save
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            add_settings_error(
                'simple_launchpad_messages',
                'simple_launchpad_message',
                __('Settings saved successfully!', 'simple-launchpad'),
                'updated'
            );
        }

        // Show error/update messages
        settings_errors('simple_launchpad_messages');

        $options = get_option('simple_launchpad_options');
        $all_buttons = simple_launchpad_get_default_buttons();
        $enabled_buttons = $options['enabled_buttons'] ?? array_keys($all_buttons);
        $button_order = $options['button_order'] ?? array_keys($all_buttons);
        $role_visibility = $options['role_visibility'] ?? array();

        // Get WordPress roles
        $roles = wp_roles()->get_names();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields('simple_launchpad_options'); ?>

                <h2 class="nav-tab-wrapper">
                    <a href="#buttons-tab" class="nav-tab nav-tab-active"><?php esc_html_e('Buttons', 'simple-launchpad'); ?></a>
                    <a href="#appearance-tab" class="nav-tab"><?php esc_html_e('Appearance', 'simple-launchpad'); ?></a>
                    <a href="#roles-tab" class="nav-tab"><?php esc_html_e('Role Visibility', 'simple-launchpad'); ?></a>
                </h2>

                <!-- Buttons Tab -->
                <div id="buttons-tab" class="tab-content active">
                    <h2><?php esc_html_e('Enable/Disable & Reorder Buttons', 'simple-launchpad'); ?></h2>
                    <p><?php esc_html_e('Check the buttons you want to display and drag to reorder them.', 'simple-launchpad'); ?></p>

                    <?php
                    // Check if WooCommerce is active
                    $is_woocommerce_active = class_exists('WooCommerce');
                    if (!$is_woocommerce_active):
                    ?>
                        <div class="notice notice-info inline">
                            <p>
                                <strong><?php esc_html_e('WooCommerce Not Detected:', 'simple-launchpad'); ?></strong>
                                <?php esc_html_e('WooCommerce buttons are available but will appear greyed out below. Install and activate WooCommerce to enable these buttons.', 'simple-launchpad'); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="simple_launchpad_options[button_order]" id="button_order" value="<?php echo esc_attr(implode(',', $button_order)); ?>">

                    <ul id="sortable-buttons" class="sortable-buttons">
                        <?php
                        // Sort buttons according to saved order
                        $ordered_buttons = array();
                        foreach ($button_order as $button_id) {
                            if (isset($all_buttons[$button_id])) {
                                $ordered_buttons[$button_id] = $all_buttons[$button_id];
                            }
                        }
                        // Add any new buttons that weren't in the order
                        foreach ($all_buttons as $button_id => $button) {
                            if (!isset($ordered_buttons[$button_id])) {
                                $ordered_buttons[$button_id] = $button;
                            }
                        }

                        // Define WooCommerce buttons
                        $woocommerce_buttons = array('woocommerce', 'wc_settings', 'wc_orders', 'wc_products', 'wc_product_categories');

                        foreach ($ordered_buttons as $button_id => $button):
                            // Check if this is a WooCommerce button
                            $is_wc_button = in_array($button_id, $woocommerce_buttons);
                            $disabled_class = ($is_wc_button && !$is_woocommerce_active) ? 'woocommerce-disabled' : '';
                            $disabled_attr = ($is_wc_button && !$is_woocommerce_active) ? 'disabled' : '';
                        ?>
                            <li class="button-item <?php echo esc_attr($disabled_class); ?>" data-button-id="<?php echo esc_attr($button_id); ?>">
                                <span class="dashicons dashicons-menu drag-handle"></span>
                                <label>
                                    <input type="checkbox"
                                           name="simple_launchpad_options[enabled_buttons][]"
                                           value="<?php echo esc_attr($button_id); ?>"
                                           <?php checked(in_array($button_id, $enabled_buttons)); ?>
                                           <?php echo esc_attr($disabled_attr); ?>>
                                    <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                                    <?php echo esc_html($button['label']); ?>
                                    <?php if ($is_wc_button && !$is_woocommerce_active): ?>
                                        <span class="woocommerce-required-badge"><?php esc_html_e('(Requires WooCommerce)', 'simple-launchpad'); ?></span>
                                    <?php endif; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Appearance Tab -->
                <div id="appearance-tab" class="tab-content">
                    <h2><?php esc_html_e('Customize Colors', 'simple-launchpad'); ?></h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="button_color"><?php esc_html_e('Button Text Color', 'simple-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text"
                                       name="simple_launchpad_options[button_color]"
                                       id="button_color"
                                       value="<?php echo esc_attr($options['button_color'] ?? '#2271b1'); ?>"
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_hover_color"><?php esc_html_e('Button Hover Text Color', 'simple-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text"
                                       name="simple_launchpad_options[button_hover_color]"
                                       id="button_hover_color"
                                       value="<?php echo esc_attr($options['button_hover_color'] ?? '#135e96'); ?>"
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_bg_color"><?php esc_html_e('Button Background Color', 'simple-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text"
                                       name="simple_launchpad_options[button_bg_color]"
                                       id="button_bg_color"
                                       value="<?php echo esc_attr($options['button_bg_color'] ?? '#ffffff'); ?>"
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_hover_bg_color"><?php esc_html_e('Button Hover Background Color', 'simple-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text"
                                       name="simple_launchpad_options[button_hover_bg_color]"
                                       id="button_hover_bg_color"
                                       value="<?php echo esc_attr($options['button_hover_bg_color'] ?? '#f6f7f7'); ?>"
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="dark_mode"><?php esc_html_e('Choose Mode', 'simple-launchpad'); ?></label>
                            </th>
                            <td>
                                <select name="simple_launchpad_options[dark_mode]" id="dark_mode">
                                    <option value="light" <?php selected($options['dark_mode'] ?? 'light', 'light'); ?>>
                                        <?php esc_html_e('Light Mode', 'simple-launchpad'); ?>
                                    </option>
                                    <option value="dark" <?php selected($options['dark_mode'] ?? 'light', 'dark'); ?>>
                                        <?php esc_html_e('Dark Mode', 'simple-launchpad'); ?>
                                    </option>
                                    <option value="auto" <?php selected($options['dark_mode'] ?? 'light', 'auto'); ?>>
                                        <?php esc_html_e('Auto (Follow System Preference)', 'simple-launchpad'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php esc_html_e('Choose whether buttons should use light mode, dark mode, or automatically follow your system preference.', 'simple-launchpad'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Role Visibility Tab -->
                <div id="roles-tab" class="tab-content">
                    <h2><?php esc_html_e('Role-Based Button Visibility', 'simple-launchpad'); ?></h2>
                    <p><?php esc_html_e('Select which user roles can see each button. Leave empty to show to all roles with the required capability.', 'simple-launchpad'); ?></p>

                    <table class="form-table role-visibility-table">
                        <?php foreach ($all_buttons as $button_id => $button): ?>
                            <tr>
                                <th scope="row">
                                    <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                                    <?php echo esc_html($button['label']); ?>
                                </th>
                                <td>
                                    <?php foreach ($roles as $role_id => $role_name): ?>
                                        <label style="display: inline-block; margin-right: 15px;">
                                            <input type="checkbox"
                                                   name="simple_launchpad_options[role_visibility][<?php echo esc_attr($button_id); ?>][]"
                                                   value="<?php echo esc_attr($role_id); ?>"
                                                   <?php checked(isset($role_visibility[$button_id]) && in_array($role_id, $role_visibility[$button_id])); ?>>
                                            <?php echo esc_html($role_name); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>


                <?php submit_button(__('Save Settings', 'simple-launchpad')); ?>
            </form>
        </div>
        <?php
    }
}

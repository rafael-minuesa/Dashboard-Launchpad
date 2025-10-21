<?php
/**
 * Settings page functionality
 *
 * @package Dashboard_Launchpad
 */

class Dashboard_Launchpad_Settings {
    
    /**
     * Initialize the settings
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }
    
    /**
     * Add settings page to WordPress admin
     */
    public static function add_settings_page() {
        add_options_page(
            __('Dashboard Launchpad Settings', 'dashboard-launchpad'),
            __('Dashboard Launchpad', 'dashboard-launchpad'),
            'manage_options',
            'dashboard-launchpad',
            array(__CLASS__, 'render_settings_page')
        );
    }
    
    /**
     * Register plugin settings
     */
    public static function register_settings() {
        register_setting(
            'dashboard_launchpad_options',
            'dashboard_launchpad_options',
            array(__CLASS__, 'sanitize_options')
        );
    }
    
    /**
     * Sanitize options before saving
     */
    public static function sanitize_options($input) {
        $sanitized = array();
        
        // Sanitize enabled buttons
        if (isset($input['enabled_buttons']) && is_array($input['enabled_buttons'])) {
            $sanitized['enabled_buttons'] = array_map('sanitize_key', $input['enabled_buttons']);
        } else {
            $sanitized['enabled_buttons'] = array();
        }
        
        // Sanitize button order
        if (isset($input['button_order']) && is_array($input['button_order'])) {
            $sanitized['button_order'] = array_map('sanitize_key', $input['button_order']);
        } else {
            $sanitized['button_order'] = array();
        }
        
        // Sanitize colors
        $sanitized['button_color'] = sanitize_hex_color($input['button_color'] ?? '#2271b1');
        $sanitized['button_hover_color'] = sanitize_hex_color($input['button_hover_color'] ?? '#135e96');
        $sanitized['button_bg_color'] = sanitize_hex_color($input['button_bg_color'] ?? '#ffffff');
        $sanitized['button_hover_bg_color'] = sanitize_hex_color($input['button_hover_bg_color'] ?? '#f6f7f7');
        
        // Sanitize role visibility
        if (isset($input['role_visibility']) && is_array($input['role_visibility'])) {
            $sanitized['role_visibility'] = array();
            foreach ($input['role_visibility'] as $button_id => $roles) {
                $button_id = sanitize_key($button_id);
                if (is_array($roles)) {
                    $sanitized['role_visibility'][$button_id] = array_map('sanitize_key', $roles);
                }
            }
        } else {
            $sanitized['role_visibility'] = array();
        }
        
        return $sanitized;
    }
    
    /**
     * Render the settings page
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save settings
        if (isset($_POST['dashboard_launchpad_save'])) {
            check_admin_referer('dashboard_launchpad_settings');
            
            $options = array(
                'enabled_buttons' => isset($_POST['enabled_buttons']) ? $_POST['enabled_buttons'] : array(),
                'button_order' => isset($_POST['button_order']) ? explode(',', $_POST['button_order']) : array(),
                'button_color' => sanitize_hex_color($_POST['button_color'] ?? '#2271b1'),
                'button_hover_color' => sanitize_hex_color($_POST['button_hover_color'] ?? '#135e96'),
                'button_bg_color' => sanitize_hex_color($_POST['button_bg_color'] ?? '#ffffff'),
                'button_hover_bg_color' => sanitize_hex_color($_POST['button_hover_bg_color'] ?? '#f6f7f7'),
                'role_visibility' => isset($_POST['role_visibility']) ? $_POST['role_visibility'] : array()
            );
            
            update_option('dashboard_launchpad_options', $options);
            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'dashboard-launchpad') . '</p></div>';
        }
        
        $options = get_option('dashboard_launchpad_options');
        $all_buttons = dashboard_launchpad_get_default_buttons();
        $enabled_buttons = $options['enabled_buttons'] ?? array_keys($all_buttons);
        $button_order = $options['button_order'] ?? array_keys($all_buttons);
        $role_visibility = $options['role_visibility'] ?? array();
        
        // Get WordPress roles
        $roles = wp_roles()->get_names();
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('dashboard_launchpad_settings'); ?>
                
                <h2 class="nav-tab-wrapper">
                    <a href="#buttons-tab" class="nav-tab nav-tab-active"><?php _e('Buttons', 'dashboard-launchpad'); ?></a>
                    <a href="#appearance-tab" class="nav-tab"><?php _e('Appearance', 'dashboard-launchpad'); ?></a>
                    <a href="#roles-tab" class="nav-tab"><?php _e('Role Visibility', 'dashboard-launchpad'); ?></a>
                </h2>
                
                <!-- Buttons Tab -->
                <div id="buttons-tab" class="tab-content active">
                    <h2><?php _e('Enable/Disable & Reorder Buttons', 'dashboard-launchpad'); ?></h2>
                    <p><?php _e('Check the buttons you want to display and drag to reorder them.', 'dashboard-launchpad'); ?></p>
                    
                    <input type="hidden" name="button_order" id="button_order" value="<?php echo esc_attr(implode(',', $button_order)); ?>">
                    
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
                        
                        foreach ($ordered_buttons as $button_id => $button):
                            $checked = in_array($button_id, $enabled_buttons) ? 'checked' : '';
                        ?>
                            <li class="button-item" data-button-id="<?php echo esc_attr($button_id); ?>">
                                <span class="dashicons dashicons-menu drag-handle"></span>
                                <label>
                                    <input type="checkbox" 
                                           name="enabled_buttons[]" 
                                           value="<?php echo esc_attr($button_id); ?>" 
                                           <?php echo $checked; ?>>
                                    <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                                    <?php echo esc_html($button['label']); ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Appearance Tab -->
                <div id="appearance-tab" class="tab-content">
                    <h2><?php _e('Customize Colors', 'dashboard-launchpad'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="button_color"><?php _e('Button Text Color', 'dashboard-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       name="button_color" 
                                       id="button_color" 
                                       value="<?php echo esc_attr($options['button_color'] ?? '#2271b1'); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_hover_color"><?php _e('Button Hover Text Color', 'dashboard-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       name="button_hover_color" 
                                       id="button_hover_color" 
                                       value="<?php echo esc_attr($options['button_hover_color'] ?? '#135e96'); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_bg_color"><?php _e('Button Background Color', 'dashboard-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       name="button_bg_color" 
                                       id="button_bg_color" 
                                       value="<?php echo esc_attr($options['button_bg_color'] ?? '#ffffff'); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_hover_bg_color"><?php _e('Button Hover Background Color', 'dashboard-launchpad'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       name="button_hover_bg_color" 
                                       id="button_hover_bg_color" 
                                       value="<?php echo esc_attr($options['button_hover_bg_color'] ?? '#f6f7f7'); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Role Visibility Tab -->
                <div id="roles-tab" class="tab-content">
                    <h2><?php _e('Role-Based Button Visibility', 'dashboard-launchpad'); ?></h2>
                    <p><?php _e('Select which user roles can see each button. Leave empty to show to all roles with the required capability.', 'dashboard-launchpad'); ?></p>
                    
                    <table class="form-table role-visibility-table">
                        <?php foreach ($all_buttons as $button_id => $button): ?>
                            <tr>
                                <th scope="row">
                                    <span class="dashicons <?php echo esc_attr($button['icon']); ?>"></span>
                                    <?php echo esc_html($button['label']); ?>
                                </th>
                                <td>
                                    <?php foreach ($roles as $role_id => $role_name): ?>
                                        <?php
                                        $checked = isset($role_visibility[$button_id]) && in_array($role_id, $role_visibility[$button_id]) ? 'checked' : '';
                                        ?>
                                        <label style="display: inline-block; margin-right: 15px;">
                                            <input type="checkbox" 
                                                   name="role_visibility[<?php echo esc_attr($button_id); ?>][]" 
                                                   value="<?php echo esc_attr($role_id); ?>" 
                                                   <?php echo $checked; ?>>
                                            <?php echo esc_html($role_name); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <?php submit_button(__('Save Settings', 'dashboard-launchpad'), 'primary', 'dashboard_launchpad_save'); ?>
            </form>
        </div>
        <?php
    }
}

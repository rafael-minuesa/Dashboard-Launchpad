<?php
/**
 * Dashboard functionality
 *
 * @package Dashboard_Launchpad
 */

class Dashboard_Launchpad_Dashboard {
    
    /**
     * Initialize the dashboard
     */
    public static function init() {
        add_action('wp_dashboard_setup', array(__CLASS__, 'remove_default_widgets'));
        add_action('wp_dashboard_setup', array(__CLASS__, 'add_launchpad_widget'));
        add_action('wp_ajax_dashboard_launchpad_save_order', array(__CLASS__, 'save_button_order'));
        add_action('admin_head', array(__CLASS__, 'add_custom_styles'));
    }
    
    /**
     * Remove default dashboard widgets
     */
    public static function remove_default_widgets() {
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
    }
    
    /**
     * Add the launchpad widget
     */
    public static function add_launchpad_widget() {
        wp_add_dashboard_widget(
            'dashboard_launchpad_widget',
            __('Quick Launch', 'dashboard-launchpad'),
            array(__CLASS__, 'render_launchpad_widget')
        );
    }
    
    /**
     * Render the launchpad widget
     */
    public static function render_launchpad_widget() {
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
        
        if (empty($buttons_to_display)) {
            echo '<p>' . __('No buttons configured. Visit Settings &gt; Dashboard Launchpad to configure your buttons.', 'dashboard-launchpad') . '</p>';
            return;
        }
        
        ?>
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
        
        <?php if (current_user_can('manage_options')): ?>
            <p style="text-align: center; margin-top: 20px;">
                <a href="<?php echo admin_url('options-general.php?page=dashboard-launchpad'); ?>">
                    <?php _e('Configure Launchpad', 'dashboard-launchpad'); ?>
                </a>
            </p>
        <?php endif; ?>
        <?php
    }
    
    /**
     * Save button order via AJAX
     */
    public static function save_button_order() {
        check_ajax_referer('dashboard_launchpad_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'dashboard-launchpad')));
        }
        
        $order = isset($_POST['order']) ? $_POST['order'] : array();
        $order = array_map('sanitize_key', $order);
        
        $options = get_option('dashboard_launchpad_options');
        $options['button_order'] = $order;
        update_option('dashboard_launchpad_options', $options);
        
        wp_send_json_success(array('message' => __('Order saved', 'dashboard-launchpad')));
    }
    
    /**
     * Add custom styles based on user settings
     */
    public static function add_custom_styles() {
        if (get_current_screen()->id !== 'dashboard') {
            return;
        }
        
        $options = get_option('dashboard_launchpad_options');
        $button_color = $options['button_color'] ?? '#2271b1';
        $button_hover_color = $options['button_hover_color'] ?? '#135e96';
        $button_bg_color = $options['button_bg_color'] ?? '#ffffff';
        $button_hover_bg_color = $options['button_hover_bg_color'] ?? '#f6f7f7';
        
        ?>
        <style>
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

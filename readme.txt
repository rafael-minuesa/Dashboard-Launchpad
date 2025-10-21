=== Dashboard Launchpad ===
Contributors: rafaelminuesa
Tags: dashboard, admin, quick-access, buttons, customization
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Transform your WordPress dashboard into a streamlined command center with quick-access buttons to all admin areas.

== Description ==

**Dashboard Launchpad** replaces the default WordPress dashboard with a beautiful, responsive grid of quick-access buttons that take you directly to the admin pages you use most.

Perfect for:
* **Site administrators** who want faster navigation
* **Agencies** managing multiple client sites
* **Developers** who need quick access to tools
* **Content teams** focusing on specific admin areas

= Key Features =

**🎯 Quick Access Buttons**
* 10 pre-configured buttons for common admin areas (Posts, Pages, Media, Comments, etc.)
* One-click access to any WordPress admin page
* Visual icons using WordPress Dashicons
* Fully customizable button order via drag-and-drop

**🎨 Full Customization**
* Enable/disable individual buttons
* Reorder buttons via intuitive drag-and-drop interface
* Customize text colors (normal and hover states)
* Customize background colors (normal and hover states)
* Responsive design that works on all devices
* Dark mode support

**👥 Role-Based Visibility**
* Control which user roles can see each button
* Capability-based security (respects WordPress permissions)
* Granular per-button access control
* Perfect for multi-user sites with different team roles

**⚡ Performance**
* Lightweight and fast
* Assets only load where needed
* Clean, semantic code
* No jQuery dependencies (except admin)
* Optimized for WordPress standards

**🔧 Developer Friendly**
* Extensible with WordPress filters and hooks
* Well-documented code with PHPDoc comments
* Clean, object-oriented architecture
* Translation-ready (i18n)
* Compatible with WordPress Coding Standards

= Use Cases =

**For Bloggers:**
Quickly access Posts, Media, and Comments without navigating through menus.

**For E-commerce Sites:**
Add custom buttons for WooCommerce pages like Products, Orders, and Coupons.

**For Agencies:**
Configure different button layouts for different client sites or user roles.

**For Developers:**
Extend the plugin with custom buttons using the provided filter hooks.

= Developer Hooks =

**Filters:**

`dashboard_launchpad_default_buttons` - Modify or add default buttons
`dashboard_launchpad_buttons` - Modify buttons before rendering

**Example - Add Custom Button:**

`
add_filter('dashboard_launchpad_default_buttons', function($buttons) {
    $buttons['my_custom'] = array(
        'label' => 'Custom Area',
        'url' => 'admin.php?page=my-custom-page',
        'icon' => 'dashicons-admin-generic',
        'capability' => 'manage_options'
    );
    return $buttons;
});
`

= Translations =

Dashboard Launchpad is translation-ready! Help translate it into your language:
* Text Domain: dashboard-launchpad
* All strings wrapped with proper i18n functions
* POT file included for translators

= Privacy =

Dashboard Launchpad does not:
* Collect any user data
* Use cookies
* Connect to external services
* Track analytics

All settings are stored locally in your WordPress database.

== Installation ==

= Automatic Installation =

1. Log in to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "Dashboard Launchpad"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin zip file
2. Log in to your WordPress admin panel
3. Navigate to Plugins → Add New → Upload Plugin
4. Choose the downloaded zip file and click "Install Now"
5. Activate the plugin

= Configuration =

1. After activation, go to **Settings → Dashboard Launchpad**
2. Choose which buttons to enable/disable
3. Drag and drop to reorder buttons
4. Customize colors in the Appearance tab
5. Configure role visibility in the Role Visibility tab
6. Click "Save Settings"
7. Visit your WordPress Dashboard to see the new launchpad!

== Frequently Asked Questions ==

= Does this plugin work with the latest WordPress version? =

Yes! Dashboard Launchpad is tested with the latest WordPress version and follows WordPress coding standards.

= Can I add my own custom buttons? =

Yes! Use the `dashboard_launchpad_default_buttons` filter hook to add custom buttons. See the "Developer Hooks" section in the description for examples.

= Can I change the button order? =

Absolutely! Go to Settings → Dashboard Launchpad → Buttons tab, then drag and drop buttons to reorder them.

= Does it work with mobile devices? =

Yes! Dashboard Launchpad is fully responsive and works beautifully on tablets and mobile phones.

= Can I control which users see which buttons? =

Yes! Use the Role Visibility tab to configure which WordPress roles can see each button. The plugin also respects WordPress capabilities, so users will only see buttons for pages they have permission to access.

= Will this conflict with other plugins? =

Dashboard Launchpad is designed to be compatible with other plugins. It uses WordPress best practices and only modifies the dashboard area. If you experience any conflicts, please report them in the support forum.

= Does it remove all default dashboard widgets? =

Yes, by default it removes the standard WordPress dashboard widgets (Right Now, Activity, Quick Press, etc.) to provide a clean slate. However, widgets added by other plugins are not affected.

= Can I restore the default WordPress dashboard? =

Simply deactivate the plugin and your dashboard will return to normal. Your settings are preserved if you reactivate later.

= Is it translation-ready? =

Yes! All text is wrapped with WordPress i18n functions and ready for translation.

= Does it work with multisite? =

Yes! Dashboard Launchpad works on both single-site and multisite WordPress installations.

= How do I uninstall the plugin? =

Simply deactivate and delete the plugin through the WordPress admin. All settings will be automatically removed from the database.

== Screenshots ==

1. Dashboard view showing the quick-access button grid
2. Settings page - Buttons tab with drag-and-drop reordering
3. Settings page - Appearance tab with color customization
4. Settings page - Role Visibility tab for access control
5. Mobile responsive view of the dashboard buttons
6. Dark mode support

== Changelog ==

= 1.2.0 - 2025-10-21 =
* Added: Comprehensive PHPDoc documentation throughout codebase
* Added: Developer filter hook `dashboard_launchpad_default_buttons`
* Added: Proper uninstall handler with multisite support
* Improved: Enhanced input validation and sanitization
* Improved: Refactored settings page to use WordPress Settings API properly
* Improved: Better security with button ID and role name validation
* Fixed: Version constant now matches plugin header version
* Fixed: Duplicate form processing in settings page
* Fixed: Better error handling in AJAX operations

= 1.1.0 - 2025-01-15 =
* Added: Role-based button visibility controls
* Added: Dark mode support
* Improved: Responsive design for tablets and mobile
* Improved: Better accessibility with focus states

= 1.0.0 - 2025-01-01 =
* Initial release
* 10 pre-configured admin buttons
* Drag-and-drop button reordering
* Color customization (text and background)
* Enable/disable individual buttons
* Capability-based security

== Upgrade Notice ==

= 1.2.0 =
Major security and code quality improvements. Enhanced developer extensibility with new filter hooks. Recommended update for all users.

= 1.1.0 =
Adds role-based visibility controls and dark mode support. Recommended for multi-user sites.

= 1.0.0 =
Initial release of Dashboard Launchpad.

== Credits ==

Developed by [Rafael Minuesa](https://prowoos.com)

== Support ==

For support, feature requests, or bug reports, please visit:
* [Plugin Support Forum](https://wordpress.org/support/plugin/dashboard-launchpad/)
* [GitHub Repository](https://github.com/rafael-minuesa/dashboard-launchpad)

== Donations ==

If you find this plugin helpful, please consider:
* [Leaving a 5-star review](https://wordpress.org/support/plugin/dashboard-launchpad/reviews/)
* [Contributing on GitHub](https://github.com/rafael-minuesa/dashboard-launchpad)

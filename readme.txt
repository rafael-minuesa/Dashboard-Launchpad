=== Simple LaunchPad ===
Contributors: rafaelminuesa
Tags: dashboard, admin, quick-access, buttons, customization
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WCAG 2.1 AA accessible admin dashboard with quick-access buttons to all WordPress areas—perfect for screen reader users.

== Description ==

**Simple LaunchPad** appears as the first menu item in your WordPress admin, providing instant access to 15 carefully organized buttons across three categories: Content Management, Appearance, and Administration.

= Why This Plugin Exists =

If you manage multiple WordPress installations with different plugins (like I do), you know the frustration: plugin developers arbitrarily decide where their menu entries appear, constantly pushing core WordPress menu items like Themes, Plugins, and Settings up and down the sidebar. One site has "Plugins" at position 5, another at position 12, and yet another at position 8. You're forced to scroll endlessly, hunting for the same common entries you use every day.

**I got tired of complaining about it, so I did something about it.**

Simple LaunchPad puts all your most-used admin pages in ONE predictable location at the top of your admin menu. No more scrolling. No more hunting. Just click and go. This is my contribution to the WordPress community—a solution to a problem we've all experienced but rarely talk about.

Perfect for:
* **Site administrators** who want faster navigation
* **Agencies** managing multiple client sites
* **Developers** who need quick access to tools
* **Content teams** focusing on specific admin areas
* **Users with visual disabilities** who rely on screen readers and predictable navigation

= Accessibility Features =

Simple LaunchPad is fully **WCAG 2.1 AA compliant** and specifically designed to help users with visual disabilities navigate WordPress more efficiently.

**Why This Matters for Accessibility:**

When you manage multiple WordPress sites with different plugins, the admin menu becomes unpredictable. One site has "Posts" at position 3, another at position 7. Plugin developers arbitrarily inject their menu items anywhere, constantly shifting core WordPress items up and down. For sighted users, this is frustrating. **For screen reader users, this is a nightmare.**

Every time a screen reader user logs into a different site, they must:
- Navigate through the entire menu structure to find familiar items
- Re-learn where "Posts", "Pages", "Plugins", and "Settings" are located
- Tab through dozens of unfamiliar plugin menu items just to reach core WordPress functions

**Simple LaunchPad solves this by providing ONE predictable location** at the top of the admin menu where all essential WordPress areas are always accessible.

**Accessibility Compliance Features:**

✓ **Keyboard Navigation:** Full keyboard support with visible focus states
✓ **Global Keyboard Shortcut:** Press Alt+Shift+L (Windows/Linux) or Control+Option+L (Mac) to instantly open LaunchPad from anywhere in WordPress admin
✓ **Screen Reader Support:** Proper ARIA labels and semantic HTML structure
✓ **Skip Links:** "Skip to admin shortcuts" link for faster navigation
✓ **Descriptive Labels:** Each button includes clear, descriptive aria-labels (e.g., "Go to Posts", "Go to Settings")
✓ **Icon Accessibility:** All icons are properly marked as decorative (`aria-hidden="true"`)
✓ **Semantic HTML:** Proper use of navigation landmarks and heading structure
✓ **Large Click Targets:** 64px icons with generous padding meet WCAG touch target guidelines
✓ **Focus Management:** Clear visual focus indicators with high contrast
✓ **Consistent Location:** Always appears as the first menu item for predictable navigation

**Real-World Impact:**

Instead of tabbing through 20+ menu items that change position on every site, screen reader users can:
1. Log in to WordPress
2. Tab once to "LaunchPad" (always first in the menu)
3. Access any of 15 core WordPress areas with predictable, consistent labels

This reduces cognitive load, speeds up navigation, and makes WordPress administration accessible to everyone—not just sighted users who can visually scan the menu.

= Key Features =

**🎯 Quick Access Buttons**
* 15 pre-configured buttons organized in 3 logical rows
  - **Row 1:** Posts, Categories, Tags, Pages, Media
  - **Row 2:** Themes, Widgets, Menus, Customizer, Plugins
  - **Row 3:** Users, Settings, Tools, Updates, Site Health
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

**♿ Accessibility**
* WCAG 2.1 AA compliant
* Full keyboard navigation support
* Global keyboard shortcut: Alt+Shift+L to open LaunchPad from anywhere
* Screen reader optimized with proper ARIA labels
* Skip links for faster navigation
* Large click targets (64px icons)
* Consistent, predictable menu position
* Reduces cognitive load for users with disabilities

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

**For Users with Visual Disabilities:**
Access all core WordPress admin areas from one predictable location with full screen reader support. No more hunting through constantly changing menu structures.

= Developer Hooks =

**Filters:**

`simple_launchpad_default_buttons` - Modify or add default buttons
`simple_launchpad_buttons` - Modify buttons before rendering

**Example - Add Custom Button:**

`
add_filter('simple_launchpad_default_buttons', function($buttons) {
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

Simple LaunchPad is translation-ready! Help translate it into your language:
* Text Domain: simple-launchpad
* All strings wrapped with proper i18n functions
* POT file included for translators

= Privacy =

Dashboard LaunchPad does not:
* Collect any user data
* Use cookies
* Connect to external services
* Track analytics

All settings are stored locally in your WordPress database.

== Installation ==

= Automatic Installation =

1. Log in to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "Dashboard LaunchPad"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin zip file
2. Log in to your WordPress admin panel
3. Navigate to Plugins → Add New → Upload Plugin
4. Choose the downloaded zip file and click "Install Now"
5. Activate the plugin

= Configuration =

1. After activation, go to **Settings → Dashboard LaunchPad**
2. Choose which buttons to enable/disable
3. Drag and drop to reorder buttons
4. Customize colors in the Appearance tab
5. Configure role visibility in the Role Visibility tab
6. Click "Save Settings"
7. Visit your WordPress Dashboard to see the new launchpad!

== Frequently Asked Questions ==

= Is there a keyboard shortcut to open LaunchPad? =

Yes! Press **Alt+Shift+L** (Windows/Linux) or **Control+Option+L** (Mac) from anywhere in the WordPress admin to instantly jump to the LaunchPad page. This follows WordPress's standard keyboard shortcut conventions and works globally across all admin pages.

= Is this plugin accessible to users with disabilities? =

Yes! Simple LaunchPad is fully WCAG 2.1 AA compliant with:
* Full keyboard navigation support
* Global keyboard shortcut (Alt+Shift+L) for instant access
* Proper ARIA labels for screen readers
* Skip links for faster navigation
* Large click targets (64px icons)
* Consistent menu position for predictable navigation

The plugin specifically helps screen reader users by providing ONE predictable location for all essential WordPress admin areas, eliminating the need to navigate through constantly changing menu structures across different sites.

= Does this plugin work with the latest WordPress version? =

Yes! Dashboard LaunchPad is tested with the latest WordPress version and follows WordPress coding standards.

= Can I add my own custom buttons? =

Yes! Use the `dashboard_launchpad_default_buttons` filter hook to add custom buttons. See the "Developer Hooks" section in the description for examples.

= Can I change the button order? =

Absolutely! Go to Settings → Dashboard LaunchPad → Buttons tab, then drag and drop buttons to reorder them.

= Does it work with mobile devices? =

Yes! Dashboard LaunchPad is fully responsive and works beautifully on tablets and mobile phones.

= Can I control which users see which buttons? =

Yes! Use the Role Visibility tab to configure which WordPress roles can see each button. The plugin also respects WordPress capabilities, so users will only see buttons for pages they have permission to access.

= Will this conflict with other plugins? =

Dashboard LaunchPad is designed to be compatible with other plugins. It uses WordPress best practices and only modifies the dashboard area. If you experience any conflicts, please report them in the support forum.

= Does it remove all default dashboard widgets? =

Yes, by default it removes the standard WordPress dashboard widgets (Right Now, Activity, Quick Press, etc.) to provide a clean slate. However, widgets added by other plugins are not affected.

= Can I restore the default WordPress dashboard? =

Simply deactivate the plugin and your dashboard will return to normal. Your settings are preserved if you reactivate later.

= Is it translation-ready? =

Yes! All text is wrapped with WordPress i18n functions and ready for translation.

= Does it work with multisite? =

Yes! Dashboard LaunchPad works on both single-site and multisite WordPress installations.

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

= 1.5.3 - 2025-10-23 =
* Added: Global keyboard shortcut Alt+Shift+L (Windows/Linux) or Control+Option+L (Mac) to open LaunchPad from anywhere in WordPress admin
* Improved: Enhanced accessibility with instant keyboard access to LaunchPad page
* Improved: JavaScript now loads globally to support keyboard shortcut functionality

= 1.5.2 - 2025-10-23 =
* Fixed: Replaced all _e() calls with esc_html_e() for proper output escaping
* Fixed: Added wp_unslash() before sanitizing all $_POST variables in AJAX handlers
* Fixed: Added translators comment for sprintf() with placeholder
* Fixed: Updated "Tested up to" version from 6.7 to 6.8
* Fixed: Reduced plugin tags from 8 to 5 (WordPress.org requirement)
* Fixed: Created /languages directory for translations
* Fixed: Removed forbidden files (.gitignore, phpunit.xml.dist) from plugin directory
* Improved: Enhanced WordPress.org coding standards compliance

= 1.5.1 - 2025-10-23 =
* Improved: Updated readme.txt with compelling origin story explaining why this plugin exists
* Improved: Better description highlighting the frustration of plugins moving menu items around
* Fixed: Updated all filter hook examples to use new naming (simple_launchpad_*)
* Fixed: Updated text domain references in documentation
* Fixed: Corrected support/GitHub URLs to use simple-launchpad slug

= 1.5.0 - 2025-10-23 =
* Changed: Complete code refactoring - renamed all identifiers from dashboard-launchpad to simple-launchpad
* Changed: Class names updated: Dashboard_Launchpad_* → Dashboard_LaunchPad_* (capital P)
* Changed: Function names: dashboard_launchpad_*() → simple_launchpad_*()
* Changed: All constants, text domain, CSS classes, menu slugs, options, AJAX actions, and filter hooks renamed
* Changed: Asset files renamed: dashboard-launchpad.css/js → simple-launchpad.css/js
* Improved: Simplified codebase by removing unnecessary compatibility code (no existing users)
* Improved: Cleaner code structure aligned with plugin branding

= 1.4.0 - 2025-10-22 =
* Added: 9 new buttons (Categories, Tags, Themes, Widgets, Menus, Customizer, Tools, Updates, Site Health)
* Added: Settings integrated directly into LaunchPad page (bottom section)
* Added: WordPress.org asset directory structure with placeholders
* Changed: Reorganized buttons into 3 logical rows (Content, Appearance, Administration)
* Changed: Grid layout now fixed 5 columns (desktop) / 2 columns (mobile)
* Changed: Increased icon size from 48px to 64px (+33% larger)
* Changed: Reduced button padding for more efficient space usage
* Removed: Settings submenu entry (settings now on main page)
* Removed: 5 buttons (Add New Post, Add New Page, Comments, old Appearance)
* Fixed: LaunchPad menu icon now displays correctly in admin sidebar
* Fixed: Menu icon properly styled (gray with blue hover)
* Fixed: Uninstall cleanup now removes custom buttons and cache
* Fixed: Drag-to-reorder functionality now works properly

= 1.3.0 - 2025-10-22 =
* Changed: Plugin renamed from "Dashboard LaunchPad" to "Simple LaunchPad"
* Improved: Updated branding and documentation
* Maintained: Backward compatibility (textdomain unchanged)

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

= 1.5.3 =
Added global keyboard shortcut! Press Alt+Shift+L to instantly open LaunchPad from anywhere in WordPress admin. Enhanced accessibility feature.

= 1.5.2 =
WordPress.org compliance fixes: improved output escaping, input sanitization, and coding standards compliance. Ready for plugin directory submission.

= 1.5.1 =
Documentation improvements with compelling origin story. Minor fixes to filter hook examples and URLs.

= 1.5.0 =
Major code refactoring for consistency. All identifiers renamed to simple-launchpad naming scheme. Cleaner codebase.

= 1.4.0 =
Major UI overhaul! 15 buttons organized in 3 logical rows. Settings integrated into main page. Larger icons, better layout.

== Credits ==

Developed by [Rafael Minuesa](https://prowoos.com)

== Support ==

For support, feature requests, or bug reports, please visit:
* [Plugin Support Forum](https://wordpress.org/support/plugin/simple-launchpad/)
* [GitHub Repository](https://github.com/rafael-minuesa/simple-launchpad)

== Donations ==

If you find this plugin helpful, please consider:
* [Leaving a 5-star review](https://wordpress.org/support/plugin/simple-launchpad/reviews/)
* [Contributing on GitHub](https://github.com/rafael-minuesa/simple-launchpad)

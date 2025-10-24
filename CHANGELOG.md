# Changelog

All notable changes to Simple LaunchPad will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.5.3] - 2025-10-23

### Added
- **Global Keyboard Shortcut:** Press Alt+Shift+L (Windows/Linux) or Control+Option+L (Mac) to instantly open LaunchPad from anywhere in WordPress admin
- JavaScript now loads globally on all admin pages to support keyboard shortcut functionality

### Improved
- Enhanced accessibility with instant keyboard access to LaunchPad page
- Follows WordPress standard keyboard shortcut conventions (Alt+Shift+Key)

## [1.5.2] - 2025-10-23

### Fixed
- **WordPress.org Coding Standards Compliance:**
  - Replaced all `_e()` calls with `esc_html_e()` for proper output escaping
  - Added `wp_unslash()` before sanitizing all `$_POST` variables in AJAX handlers
  - Added translators comment for `sprintf()` with placeholder in aria-label
  - Updated "Tested up to" version from 6.7 to 6.8
  - Reduced plugin tags from 8 to 5 (WordPress.org guideline)
  - Created `/languages` directory for translation files
  - Removed forbidden files (`.gitignore`, `phpunit.xml.dist`) from plugin directory

### Security
- Enhanced input sanitization with proper `wp_unslash()` usage throughout AJAX handlers
- Improved output escaping compliance across all template files

## [1.5.1] - 2025-10-23

### Improved
- **readme.txt:** Added compelling "Why This Plugin Exists" section
  - Personal story about managing multiple WordPress installations
  - Explains frustration with plugins arbitrarily moving menu items
  - Positions plugin as community contribution vs just complaining
  - More relatable and engaging for WordPress.org users
- Documentation clarity and user engagement

### Fixed
- Updated all filter hook examples: `dashboard_launchpad_*` → `simple_launchpad_*`
- Updated text domain references in Translations section
- Corrected support/GitHub URLs to use `simple-launchpad` slug
- Fixed WordPress.org plugin directory URLs

## [1.5.0] - 2025-10-23

### Changed
- **Major Code Refactoring** - Complete identifier rename for consistency
  - Plugin identifiers renamed from `dashboard-launchpad` to `simple-launchpad`
  - Class names updated: `Dashboard_Launchpad_*` → `Dashboard_LaunchPad_*` (capital P)
  - Function names: `dashboard_launchpad_*()` → `simple_launchpad_*()`
  - Constants: `DASHBOARD_LAUNCHPAD_*` → `SIMPLE_LAUNCHPAD_*`
  - Text domain: `'dashboard-launchpad'` → `'simple-launchpad'`
  - CSS classes: `.dashboard-launchpad` → `.simple-launchpad`
  - Menu slugs/URLs: `dashboard-launchpad` → `simple-launchpad`
  - Database options: `dashboard_launchpad_*` → `simple_launchpad_*`
  - AJAX actions: `dashboard_launchpad_*` → `simple_launchpad_*`
  - Filter hooks: `dashboard_launchpad_*` → `simple_launchpad_*`
- **Asset files renamed:**
  - `dashboard-launchpad.css` → `simple-launchpad.css`
  - `dashboard-launchpad.js` → `simple-launchpad.js`
- Updated Plugin URI: `simple-launchpad` repository slug
- Simplified codebase by removing unnecessary compatibility code

## [1.4.0] - 2025-10-22

### Added
- **9 New Default Buttons:**
  - Categories - Quick access to post categories
  - Tags - Quick access to post tags
  - Themes - Browse and activate themes
  - Widgets - Manage sidebar widgets
  - Menus - Configure navigation menus
  - Customizer - Live theme customization
  - Tools - WordPress tools and utilities
  - Updates - Plugin, theme, and core updates
  - Site Health - WordPress site health checker
- **Accessibility Improvements (WCAG 2.1 AA Compliant):**
  - Added ARIA labels to all buttons for screen readers
  - Added navigation landmark with descriptive label
  - Added skip link for keyboard users ("Skip to admin shortcuts")
  - Added aria-hidden to decorative icons
  - Screen reader support for JAWS, NVDA, VoiceOver
  - Full keyboard navigation support
- WordPress.org submission preparation:
  - Complete `.wordpress-org/` directory structure
  - Asset placeholder files with detailed specifications
  - Comprehensive asset creation guide (README.md)
  - Banner placeholders (772x250, 1544x500)
  - Icon placeholders (128x128, 256x256)
  - Screenshot placeholders (1-6)
- Settings integration directly into LaunchPad page
- Anchor link navigation to settings section
- Menu icon CSS fix for proper display
- Settings link on plugins page

### Changed
- **Main plugin file renamed:** `dashboard-launchpad.php` → `simple-launchpad.php`
  - Aligns with "Simple LaunchPad" branding
  - Cleaner, more professional naming
  - No user impact (pre-release rename)
- **Menu icon changed:** Rocket → Grid View (dashicons-grid-view, f509)
  - Better represents the plugin's grid layout
  - More intuitive for users
- **Major Button Reorganization:**
  - Organized into 3 logical rows (Content, Appearance, Administration)
  - Desktop: Fixed 5-column grid (previously auto-fill)
  - Mobile: Fixed 2-column grid (previously auto-fill)
  - Total buttons increased from 10 to 15
- **UI/UX Improvements:**
  - Increased icon size from 48px to 64px (+33% larger)
  - Reduced button padding from 25px/15px to 15px/10px
  - Improved visual hierarchy and button grouping
  - Cleaner, more efficient use of screen space
- Settings moved from separate submenu to bottom of LaunchPad page
- "Configure Buttons" now uses anchor link instead of page navigation
- Settings submenu entry removed from WordPress admin
- User-facing text: "Launchpad" → "LaunchPad" (capital P)

### Removed
- **5 Buttons Removed:**
  - Add New Post (users can click Posts button instead)
  - Add New Page (users can click Pages button instead)
  - Comments (streamlined for core content management)
  - Appearance (replaced by Themes, Widgets, Menus, Customizer)
  - Redundant quick-add buttons in favor of main section access

### Fixed
- LaunchPad menu icon now displays correctly in WordPress admin sidebar
- Menu icon properly styled (gray default, blue on hover/active)
- Uninstall cleanup now removes custom buttons option
- Uninstall cleanup now removes transient cache
- Drag-to-reorder functionality now works in settings
- Settings CSS/JS now loads on LaunchPad page

### Security
- Uninstall process now completely removes all plugin data
- Multisite cleanup improved and verified
- Transient cache properly cleared on uninstall

## [1.3.0] - 2025-10-22

### Changed
- Plugin renamed from "Dashboard LaunchPad" to "Simple LaunchPad"
- Updated all branding and documentation
- Maintained backward compatibility (textdomain unchanged)

### Fixed
- Various PHPDoc improvements
- Code quality enhancements

## [1.2.0] - 2025-10-21

### Added
- Comprehensive PHPDoc documentation throughout the entire codebase
- New filter hook `dashboard_launchpad_default_buttons` for developer extensibility
- Proper `uninstall.php` file for clean plugin deletion
- Multisite support in uninstall handler
- Enhanced input validation for all AJAX handlers
- Button ID validation against whitelist in sanitization functions
- Role name validation against WordPress roles
- Data type checking before processing arrays and strings
- Settings success messages using WordPress Settings API
- Complete inline documentation for all functions and methods

### Changed
- Refactored settings page to use proper WordPress Settings API
- Improved form field naming to be compatible with Settings API
- Enhanced security checks in AJAX save_button_order handler
- Better error handling and user feedback throughout
- Improved code organization and readability

### Fixed
- Version mismatch between plugin header (1.2.0) and constant (was 1.0.0)
- Duplicate form processing in settings page (manual + Settings API)
- Missing validation in button order saving
- Inconsistent sanitization in role visibility settings

### Security
- Enhanced nonce verification
- Stricter capability checks
- Whitelist validation for button IDs
- Role name validation against WordPress core roles
- Improved sanitization throughout all input processing

## [1.1.0] - 2025-01-15

### Added
- Role-based button visibility controls in new "Role Visibility" tab
- Dark mode support using CSS media queries
- Improved responsive design for tablets (600-782px breakpoint)
- Better mobile experience (< 600px screens)
- Accessibility improvements with proper focus states
- ARIA attributes for screen readers

### Changed
- Enhanced CSS grid layout for better responsiveness
- Improved color contrast for accessibility
- Better touch targets for mobile devices

## [1.0.0] - 2025-01-01

### Added
- Initial release of Dashboard LaunchPad
- 10 pre-configured admin buttons (Posts, Pages, Media, Comments, Appearance, Plugins, Users, Settings, etc.)
- Drag-and-drop button reordering via jQuery UI Sortable
- Enable/disable individual buttons through settings
- Color customization:
  - Button text color (normal state)
  - Button text color (hover state)
  - Button background color (normal state)
  - Button background color (hover state)
- Tabbed settings interface with three sections:
  - Buttons: Enable/disable and reorder
  - Appearance: Color customization with WordPress Color Picker
  - (Future) Advanced settings placeholder
- Capability-based security (respects WordPress user permissions)
- Responsive grid layout that adapts to mobile devices
- WordPress Coding Standards compliant code
- Translation-ready with proper i18n implementation

### Technical
- Clean, object-oriented architecture with separate classes
- Transient caching for improved performance
- AJAX-based button reordering (live save)
- WordPress Settings API integration
- Proper sanitization and validation throughout
- Nonce verification for security

---

## License

Simple LaunchPad is licensed under the [GPLv2 or later](LICENSE).

## Support & Contributions

- **GitHub:** https://github.com/rafael-minuesa/simple-launchpad
- **WordPress.org:** https://wordpress.org/plugins/simple-launchpad/
- **Author:** Rafael Minuesa (https://prowoos.com)

If you find this plugin helpful, please consider:
- ⭐ Leaving a 5-star review on WordPress.org
- 🐛 Reporting bugs or requesting features on GitHub
- 🌍 Contributing translations
- 💬 Sharing with others who manage multiple WordPress sites

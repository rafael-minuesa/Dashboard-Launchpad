# Changelog

All notable changes to Simple Launchpad will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
- Settings integration directly into Launchpad page
- Anchor link navigation to settings section
- Menu icon CSS fix for proper display

### Changed
- **Main plugin file renamed:** `dashboard-launchpad.php` → `simple-launchpad.php`
  - Aligns with "Simple Launchpad" branding
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
- Settings moved from separate submenu to bottom of Launchpad page
- "Configure Buttons" now uses anchor link instead of page navigation
- Settings submenu entry removed from WordPress admin

### Removed
- **5 Buttons Removed:**
  - Add New Post (users can click Posts button instead)
  - Add New Page (users can click Pages button instead)
  - Comments (streamlined for core content management)
  - Appearance (replaced by Themes, Widgets, Menus, Customizer)
  - Redundant quick-add buttons in favor of main section access

### Fixed
- Launchpad menu icon now displays correctly in WordPress admin sidebar
- Menu icon properly styled (gray default, blue on hover/active)
- Uninstall cleanup now removes custom buttons option
- Uninstall cleanup now removes transient cache
- Drag-to-reorder functionality now works in settings
- Settings CSS/JS now loads on Launchpad page

### Security
- Uninstall process now completely removes all plugin data
- Multisite cleanup improved and verified
- Transient cache properly cleared on uninstall

### Developer
- Added comments documenting button organization in code
- Settings can still be accessed programmatically
- Backward compatibility maintained for settings hooks
- Filter hooks unchanged

## [1.3.0] - 2025-10-22

### Changed
- Plugin renamed from "Dashboard Launchpad" to "Simple Launchpad"
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
- Initial release of Dashboard Launchpad
- 10 pre-configured admin buttons (Posts, Pages, Media, Comments, Appearance, Plugins, Users, Settings, etc.)
- Drag-and-drop button reordering via jQuery UI Sortable
- Enable/disable individual buttons through settings
- Color customization:
  - Button text color (normal state)
  - Button text color (hover state)
  - Button background color (normal state)
  - Button background color (hover state)
- WordPress Color Picker integration
- Tabbed settings interface (Buttons, Appearance, Role Visibility)
- Capability-based security (users only see buttons they have permission to access)
- Translation-ready with `dashboard-launchpad` text domain
- Clean, semantic CSS with WordPress admin styling
- AJAX functionality for button order saving
- Responsive grid layout
- Dashboard widget removal (removes default WP widgets)
- Proper WordPress plugin structure with class-based organization

### Security
- Nonce verification on all forms and AJAX requests
- Capability checks using `current_user_can()`
- Input sanitization using WordPress core functions
- Output escaping using `esc_attr()`, `esc_html()`, `esc_url()`

### Developer Features
- Object-oriented architecture with static methods
- Proper WordPress hooks integration
- Filter hooks for customization
- Clean, commented code
- Follows WordPress Coding Standards
- Modular file structure

---

## Version History Summary

- **v1.4.0** - Major UI overhaul, 15 buttons in 3 rows, settings integration, WordPress.org prep
- **v1.3.0** - Renamed to Simple Launchpad, branding updates
- **v1.2.0** - Security & code quality improvements, developer extensibility
- **v1.1.0** - Role visibility, dark mode, responsive enhancements
- **v1.0.0** - Initial release with core features

---

## Upgrade Notes

### From 1.3.0 to 1.4.0
- **Button Changes:** Default buttons changed from 10 to 15
  - 9 new buttons added (Categories, Tags, Themes, Widgets, Menus, Customizer, Tools, Updates, Site Health)
  - 5 buttons removed (Add New Post, Add New Page, Comments, old Appearance button)
- **Settings Location:** Settings page removed from WordPress admin menu
  - Settings now appear at bottom of Launchpad page
  - All functionality preserved, just different location
- **Layout Changes:** Grid changed from auto-fill to fixed 5 columns (desktop) / 2 columns (mobile)
- **No Data Loss:** All your existing enabled buttons and customizations are preserved
- **User Impact:** Existing users will see new buttons automatically added to their layout
- **Tip:** Visit Launchpad page and scroll down to configure your new buttons

### From 1.2.0 to 1.3.0
- Plugin renamed from "Dashboard Launchpad" to "Simple Launchpad"
- No breaking changes
- All settings and data preserved

### From 1.1.0 to 1.2.0
- No breaking changes
- Settings are preserved during upgrade
- New developer filter hooks available
- Uninstall process now properly cleans up database

### From 1.0.0 to 1.1.0
- No breaking changes
- New role visibility settings default to showing all buttons to all roles
- Dark mode activates automatically based on user OS preference

---

## Future Roadmap

### Planned for 1.5.0
- [ ] Custom button management UI (add your own buttons via interface)
- [ ] Dashicons picker for custom buttons
- [ ] Import/export settings
- [ ] Button presets for common use cases
- [ ] WordPress.org submission

### Planned for 1.6.0
- [ ] Button analytics (track most-used buttons)
- [ ] Quick action support (perform actions from button hover)
- [ ] Search/filter buttons
- [ ] Button categories/grouping in settings

### Under Consideration
- [ ] Multi-dashboard support (different layouts per user)
- [ ] Button shortcuts/keyboard navigation
- [ ] Integration with popular plugins (WooCommerce, etc.)
- [ ] Widget support (embed launchpad in other areas)

---

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

For major changes, please open an issue first to discuss what you would like to change.

## License

Dashboard Launchpad is licensed under the [GPLv2 or later](LICENSE).

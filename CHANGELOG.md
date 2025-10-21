# Changelog

All notable changes to Dashboard Launchpad will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

- **v1.2.0** - Security & code quality improvements, developer extensibility
- **v1.1.0** - Role visibility, dark mode, responsive enhancements
- **v1.0.0** - Initial release with core features

---

## Upgrade Notes

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

### Planned for 1.3.0
- [ ] Custom button management UI (add your own buttons)
- [ ] Dashicons picker for custom buttons
- [ ] Import/export settings
- [ ] Button presets for common use cases

### Planned for 1.4.0
- [ ] Button analytics (track most-used buttons)
- [ ] Quick action support (perform actions from button hover)
- [ ] Button grouping/categories
- [ ] Search/filter buttons

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

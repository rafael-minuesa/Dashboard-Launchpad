# Changelog

All notable changes to Simple LaunchPad will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-10-23

**First Public Release to WordPress.org** 🎉

Simple LaunchPad solves the frustration of managing multiple WordPress installations where plugins constantly push menu items around. All your most-used admin pages in ONE predictable location at the top of your admin menu.

### Core Features

**🎯 15 Quick-Access Buttons**
Organized in 3 logical rows for maximum efficiency:
- **Row 1 (Content):** Posts, Categories, Tags, Pages, Media
- **Row 2 (Appearance):** Themes, Widgets, Menus, Customizer, Plugins
- **Row 3 (Administration):** Users, Settings, Tools, Updates, Site Health

**🎨 Full Customization**
- Enable/disable individual buttons
- Drag-and-drop button reordering
- Customize button colors (text and background, normal and hover states)
- Role-based visibility controls for multi-user sites
- Responsive grid layout: 5 columns (desktop) / 2 columns (mobile)

**♿ WCAG 2.1 AA Compliant**
- ARIA labels on all buttons for screen readers
- Navigation landmark with descriptive label
- Skip link for keyboard users
- Full keyboard navigation support
- Screen reader tested (JAWS, NVDA, VoiceOver)

**⚡ Performance**
- Lightweight and fast
- Assets only load where needed
- Transient caching for button configuration
- Clean, semantic code
- WordPress Coding Standards compliant

**🔧 Developer Friendly**
- Filter hooks: `simple_launchpad_default_buttons` and `simple_launchpad_buttons`
- Well-documented code with PHPDoc comments
- Clean, object-oriented architecture
- Translation-ready (text domain: `simple-launchpad`)
- Proper uninstall handler (removes all data)

### Technical Details

**File Structure:**
- Main file: `simple-launchpad.php`
- Classes: `Dashboard_LaunchPad_Dashboard`, `Dashboard_LaunchPad_Settings`, `Dashboard_LaunchPad_Custom_Buttons`
- Assets: `assets/css/simple-launchpad.css`, `assets/js/simple-launchpad.js`
- Uninstall: Complete cleanup including multisite support

**WordPress Integration:**
- Appears as first menu item (position 1, above Dashboard)
- Settings integrated into main LaunchPad page (no separate submenu)
- Respects WordPress capabilities and role permissions
- Compatible with WordPress 5.0+, tested up to 6.7
- Requires PHP 7.4 or higher

**Security:**
- Nonce verification on all AJAX requests
- Capability checks throughout
- Input sanitization and validation
- Output escaping for XSS prevention
- Complete data removal on uninstall

### License

GPLv2 or later - Free and open source software

---

## Development History

This plugin was developed through multiple iterations before public release. The version numbers 1.1.0 through 1.5.0 were used during pre-release development and testing. Version 1.0.0 represents the first stable, production-ready release for WordPress.org.

**Development milestones:**
- **Pre-1.0:** Initial development with 10 buttons, basic customization
- **Pre-1.1:** Added role-based visibility, dark mode support
- **Pre-1.2:** Added comprehensive documentation, developer hooks, proper uninstall
- **Pre-1.3:** Renamed from "Dashboard LaunchPad" to "Simple LaunchPad"
- **Pre-1.4:** Major UI overhaul, accessibility improvements, expanded to 15 buttons
- **Pre-1.5:** Complete code refactoring for naming consistency
- **v1.0.0:** First public release combining all improvements

---

## Support & Contributions

- **GitHub:** https://github.com/rafael-minuesa/simple-launchpad
- **WordPress.org:** https://wordpress.org/plugins/simple-launchpad/
- **Author:** Rafael Minuesa (https://prowoos.com)

If you find this plugin helpful, please consider:
- ⭐ Leaving a 5-star review on WordPress.org
- 🐛 Reporting bugs or requesting features on GitHub
- 🌍 Contributing translations
- 💬 Sharing with others who manage multiple WordPress sites

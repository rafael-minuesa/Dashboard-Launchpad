/**
 * Simple LaunchPad - Frontend JavaScript
 *
 * Includes keyboard shortcut support for quick access to LaunchPad page.
 */

jQuery(document).ready(function($) {
	'use strict';

	/**
	 * Global keyboard shortcut: Alt+Shift+L (Windows/Linux) or Control+Option+L (Mac)
	 * Opens the LaunchPad page from anywhere in WordPress admin.
	 */
	$(document).on('keydown', function(e) {
		// Check for Alt+Shift+L (Windows/Linux) or Control+Option+L (Mac)
		// Alt key = e.altKey, Shift key = e.shiftKey, L key = keyCode 76
		if (e.altKey && e.shiftKey && e.keyCode === 76) {
			e.preventDefault();

			// Get the LaunchPad URL from wp_localize_script data
			if (typeof simpleLaunchpadData !== 'undefined' && simpleLaunchpadData.launchpadUrl) {
				window.location.href = simpleLaunchpadData.launchpadUrl;
			}
		}
	});

	// Make buttons sortable on dashboard (for admins only)
	if ($('#simple-launchpad-buttons').length) {
		// Optional: Add drag functionality in future versions
		// For now, ordering is done in settings page
	}
});

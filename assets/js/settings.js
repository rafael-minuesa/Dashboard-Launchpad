/**
 * Dashboard Launchpad - Settings Page JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize color pickers
    $('.color-picker').wpColorPicker();
    
    // Tab switching
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs and content
        $('.nav-tab').removeClass('nav-tab-active');
        $('.tab-content').removeClass('active');
        
        // Add active class to clicked tab
        $(this).addClass('nav-tab-active');
        
        // Show corresponding content
        var target = $(this).attr('href');
        $(target).addClass('active');
    });
    
    // Make buttons sortable
    $('#sortable-buttons').sortable({
        handle: '.drag-handle',
        placeholder: 'sortable-placeholder',
        update: function(event, ui) {
            // Update hidden input with new order
            var order = [];
            $('#sortable-buttons li').each(function() {
                order.push($(this).data('button-id'));
            });
            $('#button_order').val(order.join(','));
        }
    });
    
    // Disable sortable on checkbox labels to allow clicking
    $('#sortable-buttons label').on('mousedown', function(e) {
        e.stopPropagation();
    });
    
    // Toggle all buttons
    $('#toggle-all-buttons').on('click', function(e) {
        e.preventDefault();
        var checkboxes = $('#sortable-buttons input[type="checkbox"]');
        var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
    });
    
    // Confirmation before leaving with unsaved changes
    var formChanged = false;
    
    $('form input, form select').on('change', function() {
        formChanged = true;
    });
    
    $('form').on('submit', function() {
        formChanged = false;
    });
    
    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
});

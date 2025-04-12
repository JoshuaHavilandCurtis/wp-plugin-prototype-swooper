<?php
/*
Plugin Name: User Location Tracker
Description: Tracks and stores users' geolocation for display on a map.
Version: 1.2
Author: Joshua Curtis
*/


// Include the settings HTML file
require_once plugin_dir_path(__FILE__) . 'includes/html.php';
require_once plugin_dir_path(__FILE__) . 'includes/db.php';


// Enqueue Scripts and Styles
function my_pwa_plugin_scripts() {
    wp_enqueue_style('my-pwa-style', plugin_dir_url(__FILE__) . 'css/style.css');

    // Enqueue the JavaScript file
    wp_enqueue_script('my-pwa-script', plugin_dir_url(__FILE__) . 'js/script.js', array(), null, true);

    // Retrieve the Google Maps API key from the WordPress options
    $google_maps_api_key = get_option('my_pwa_google_maps_api_key');

    if (!empty($google_maps_api_key)) {
        // Enqueue Google Maps API script if key exists
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&callback=initMap', array(), null, true);
    } else {
        // Handle case where the API key is not set, you can log an error or alert admin
        error_log('Google Maps API key is not set!');
    }

    // Check if the user is logged in and pass the user ID to JavaScript
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();  // Get the logged-in user's ID
        wp_localize_script('my-pwa-script', 'userData', array(
            'userId' => $user_id,
        ));
    }
}

add_action('wp_enqueue_scripts', 'my_pwa_plugin_scripts');


// Create the settings menu page
function my_pwa_plugin_settings_page() {
    add_options_page(
        'User Location Tracker Settings', // Page title
        'User Location Tracker', // Menu title
        'manage_options', // Capability required to access
        'user_location_tracker', // Menu slug
        'user_location_tracker_html' // Callback function to display the settings page
    );
}
add_action('admin_menu', 'my_pwa_plugin_settings_page');


// Register settings
function my_pwa_plugin_register_settings() {
    register_setting('my_pwa_plugin_settings', 'my_pwa_google_maps_api_key');
}
add_action('admin_init', 'my_pwa_plugin_register_settings');

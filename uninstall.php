<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Access the global $wpdb object for database queries
global $wpdb;

// Drop the user locations table
$table_name = $wpdb->prefix . 'user_locations';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// Delete the Google Maps API key option
delete_option('my_pwa_google_maps_api_key');

<?php
register_activation_hook(__FILE__, 'ult_create_table');

function ult_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_locations';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20),
        lat FLOAT(10, 6),
        lng FLOAT(10, 6),
        last_updated DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Handle incoming location data
add_action('rest_api_init', function () {
    register_rest_route('ult/v1', '/location', [
        'methods' => 'POST',
        'callback' => 'ult_store_location',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('ult/v1', '/locations', [
        'methods' => 'GET',
        'callback' => 'ult_get_all_locations',
        'permission_callback' => '__return_true'
    ]);
});

function ult_store_location($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'user_locations';

    $lat = floatval($request['lat']);
    $lng = floatval($request['lng']);
    $user_id = sanitize_text_field($request['user_id']); // Get the user_id from the request

    if (!$lat || !$lng || !$user_id) return new WP_Error('missing_data', 'Missing lat/lng or user_id', ['status' => 400]);

    // Delete the previous location for this user
    $wpdb->delete($table, ['user_id' => $user_id]);

    // Insert new location data
    $wpdb->insert($table, [
        'user_id' => $user_id,
        'lat' => $lat,
        'lng' => $lng,
        'last_updated' => current_time('mysql')
    ]);

    return ['success' => true];
}

function ult_cleanup_old_locations() {
    global $wpdb;
    $table = $wpdb->prefix . 'user_locations';

    // Delete locations older than 1 day (86400 seconds)
    $wpdb->query(
        "DELETE FROM $table WHERE last_updated < NOW() - INTERVAL 1 DAY"
    );
}

// Set it to run daily
if (!wp_next_scheduled('ult_cleanup_old_locations_hook')) {
    wp_schedule_event(time(), 'daily', 'ult_cleanup_old_locations_hook');
}

add_action('ult_cleanup_old_locations_hook', 'ult_cleanup_old_locations');

function ult_get_all_locations() {
    global $wpdb;
    $table = $wpdb->prefix . 'user_locations';

    $results = $wpdb->get_results("SELECT lat, lng FROM $table", ARRAY_A);

    return $results;
}

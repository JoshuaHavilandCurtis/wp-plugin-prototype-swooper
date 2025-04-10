<?php
function my_pwa_theme_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());

    // Enqueue the JavaScript file
    wp_enqueue_script('my-pwa-script', get_template_directory_uri() . '/js/my-pwa-script.js', array(), null, true);

    // Enqueue Google Maps API script
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDgCyI_4Gt6ZTf_1tDz-af9hjQFAb-b7jg&callback=initMap', array(), null, true);

    // Check if the user is logged in and pass the user ID to JavaScript
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();  // Get the logged-in user's ID
        wp_localize_script('my-pwa-script', 'userData', array(
            'userId' => $user_id,
        ));
    }
}

add_action('wp_enqueue_scripts', 'my_pwa_theme_scripts');

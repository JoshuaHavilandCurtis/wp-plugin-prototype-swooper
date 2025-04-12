<?php
function user_location_tracker_html() {
    ?>
    <div class="wrap">
        <h1>User Location Tracker Settings</h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('my_pwa_plugin_settings');
            do_settings_sections('my_pwa_plugin');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Google Maps API Key</th>
                    <td>
                        <input type="text" name="my_pwa_google_maps_api_key" value="<?php echo esc_attr(get_option('my_pwa_google_maps_api_key')); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <h2>API Endpoints</h2>
        <ul>
            <li><strong>POST /wp-json/ult/v1/location</strong>: 
                <br>Full URL: <code><?php echo esc_url(rest_url('ult/v1/location')); ?></code>
                <br>Send location data (Lat, Lng, user_id)
            </li>
            <li><strong>GET /wp-json/ult/v1/locations</strong>: 
                <br>Full URL: <code><?php echo esc_url(rest_url('ult/v1/locations')); ?></code>
                <br>Retrieve all location data
            </li>
        </ul>

    </div>
    <?php
}
?>

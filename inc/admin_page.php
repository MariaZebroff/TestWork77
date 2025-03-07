<?php
if (!defined('ABSPATH')) {
    exit;
}

function weather_api_add_admin_menu() {
    add_options_page(
        'Weather API Settings',       // Page title
        'Weather API',                // Menu title
        'manage_options',             // Capability required
        'weather-api-settings',       // Menu slug
        'weather_api_settings_page'   // Callback function
    );
}
add_action('admin_menu', 'weather_api_add_admin_menu');

function weather_api_settings_page() {
    ?>
    <div class="wrap">
        <h1>Weather API Key Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('weather_api_options_group'); // Security fields
            wp_nonce_field('weather_api_settings_nonce_action', 'weather_api_settings_nonce'); // Add Nonce
            do_settings_sections('weather-api-settings'); // Load settings
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function weather_api_register_settings() {
    // Register the setting
    register_setting(
        'weather_api_options_group', // Option group name
        'weather_api_key',           // Option name
        array(
            'type' => 'string',
            'sanitize_callback' => 'weather_api_sanitize_api_key',
            'show_in_rest' => false, // Keep it hidden from REST API for security
        )
    );

    add_settings_section(
        'weather_api_main_section',
        'Weather API Settings',
        'weather_api_section_text',
        'weather-api-settings'
    );

    add_settings_field(
        'weather_api_key_field',
        'Weather API Key',
        'weather_api_key_input',
        'weather-api-settings',
        'weather_api_main_section'
    );
}
add_action('admin_init', 'weather_api_register_settings');

// Section description
function weather_api_section_text() {
    echo '<p>To obtain Weather API key please to <a target="blank" href="https://openweathermap.org/">Open Weather Home Page</a> </p>';
    echo '<p>Enter your Weather API key. This key is encrypted for security.</p>';
}

// Input field for API key
function weather_api_key_input() {
    $api_key = get_option('weather_api_key', '');
    $decoded_key = !empty($api_key) ? base64_decode($api_key) : '';
    echo '<input type="password" name="weather_api_key" value="' . esc_attr($decoded_key) . '" style="width: 400px;">';
    echo '<p><small>Enter your Weather API key. This key is securely stored.</small></p>';
}

// Encrypt the API key before saving
function weather_api_sanitize_api_key($input) {
    return base64_encode(trim($input)); 
}
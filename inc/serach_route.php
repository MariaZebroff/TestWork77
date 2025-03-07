<?php

add_action('rest_api_init', 'citySearch');



// https://task1:8890/wp-json/cities/v1/search?term=Vernon
//https://task1:8890/wp-json/cities/v1/search



function citySearch(){
    register_rest_route('cities/v1', '/search/', array(
        'methods'  => 'GET',
        'callback' => 'citySearchResults',
        'permission_callback' => '__return_true', // Allows public access
    ));
}
function get_weather($lat, $lon) {
    if (!$lat || !$lon) return 'N/A';

    $api_key = OPENWEATHER_API_KEY; // Fetch API key securely
    $api_url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid={$api_key}";

    $response = wp_remote_get($api_url);
    
    if (is_wp_error($response)) return 'Error';

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    return isset($data->main->temp) ? round($data->main->temp) . "°C" : 'N/A';
}

function citySearchResults($data){
    global $wpdb;

    $search_term = isset($data['term']) ? sanitize_text_field($data['term']) : '';

    $query = $wpdb->prepare("
        SELECT 
    p.ID, 
    p.post_title AS city, 
    t.name AS country, 
    pm1.meta_value AS latitude, 
    pm2.meta_value AS longitude
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
    LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'country'
    LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
    LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_city_latitude'
    LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_city_longitude'
    WHERE p.post_type = 'cities' 
    AND p.post_status = 'publish' 
    AND (tt.taxonomy = 'country' OR tt.taxonomy IS NULL) 
    AND p.post_title LIKE %s
    ORDER BY t.name ASC, p.post_title ASC
    ", '%' . $wpdb->esc_like($search_term) . '%');

    $cities = $wpdb->get_results($query);

    if (empty($cities)) {
        return new WP_REST_Response(['message' => 'No cities found'], 404);
    }

    $response = array();
    foreach ($cities as $city) {
        $temperature = function_exists('get_weather') ? esc_html(get_weather($city->latitude, $city->longitude)) : 'N/A';
        
        $response[] = array(
            'city' => esc_html($city->city),
            'country' => esc_html($city->country),
            'temperature' => $temperature,
            'search_term' => $search_term, //For debugging
        );
    }

    return new WP_REST_Response($response, 200);
}

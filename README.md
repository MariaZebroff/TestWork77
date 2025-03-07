Custom Post Type "Cities" Implementation

Overview

This document describes the implementation of a custom post type (CPT) called "Cities" along with a custom taxonomy "Countries" and additional features such as custom fields, a widget displaying city temperature, a searchable table, and a settings page for API key configuration. All modifications are made within a child theme of Storefront and do not rely on plugins.

Technical Characteristics

WordPress Version: 6.5.2

PHP Version: 8.2.0

Theme: Storefront (Child Theme)

1. Custom Post Type "Cities"

A new custom post type "Cities" is created to store city-related information.

Code Implementation:

function create_cities_cpt() {
    $labels = array(
        'name'               => 'Cities',
        'singular_name'      => 'City',
        'menu_name'          => 'Cities',
        'name_admin_bar'     => 'City',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New City',
        'new_item'           => 'New City',
        'edit_item'          => 'Edit City',
        'view_item'          => 'View City',
        'all_items'          => 'All Cities',
        'search_items'       => 'Search Cities',
        'not_found'          => 'No cities found.',
        'not_found_in_trash' => 'No cities found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_menu'       => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'            => array('slug' => 'cities'),
        'show_in_rest'       => true,
    );

    register_post_type('cities', $args);
}
add_action('init', 'create_cities_cpt');

2. Custom Fields: Latitude & Longitude

Metaboxes are added to the "Cities" post type, allowing users to enter latitude and longitude values.

3. Custom Taxonomy "Countries"

The "Countries" taxonomy is attached to the "Cities" post type, allowing each city to be assigned to a country. Only one country can be assigned to each city.

Code Implementation:

function create_cities_taxonomy() {
    $labels = array(
        'name'              => 'Countries',
        'singular_name'     => 'Country',
        'search_items'      => 'Search Countries',
        'all_items'         => 'All Countries',
        'edit_item'         => 'Edit Country',
        'update_item'       => 'Update Country',
        'add_new_item'      => 'Add New Country',
        'new_item_name'     => 'New Country Name',
        'menu_name'         => 'Countries',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => false, // Acts like tags
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'country'),
        'show_in_rest'      => true,
    );

    register_taxonomy('country', array('cities'), $args);
}
add_action('init', 'create_cities_taxonomy');

4. City Temperature Widget

A widget is created to display the city name and its current temperature using an external API.

5. Custom Page Template with City Search

A Custom Page Template is created to display a searchable table of cities, countries, and temperatures. The search is implemented using WP Ajax. A REST API custom endpoint was created for data fetch.

Query with $wpdb:

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

6. API Key Settings Page

A settings page is created to store and configure the API key used for fetching weather data. This ensures better user convenience to maintain the web app from the WP backend without coding.

Code Implementation:

function weather_api_add_admin_menu() {
    add_options_page(
        'Weather API Settings',      
        'Weather API',               
        'manage_options',            
        'weather-api-settings',      
        'weather_api_settings_page'   
    );
}
add_action('admin_menu', 'weather_api_add_admin_menu');

Summary

Implemented Custom Post Type "Cities"

Created Custom Fields (Latitude & Longitude)

Developed Custom Taxonomy "Countries"

Built a City Temperature Widget

Added a Custom Page Template with WP Ajax search

Included an API Key Settings Page for weather data

This ensures a complete, efficient, and user-friendly solution within the Storefront child theme without requiring additional plugins.
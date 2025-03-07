<?php

if (!defined('ABSPATH')) {
    exit;
}

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

// Force only one country per city (remove extra selections)
function enforce_single_country_per_city($post_id, $post) {
    if ($post->post_type !== 'cities') {
        return;
    }

    $terms = wp_get_post_terms($post_id, 'country', array('fields' => 'ids'));

    if (count($terms) > 1) {
        // Keep only the first term and remove others
        wp_set_post_terms($post_id, array($terms[0]), 'country');
    }
}
add_action('save_post', 'enforce_single_country_per_city', 10, 2);



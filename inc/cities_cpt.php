<?php

if (!defined('ABSPATH')) {
    exit;
}

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
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'rewrite'            => array( 'slug' => 'cities' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'cities', $args );
}
add_action( 'init', 'create_cities_cpt' );
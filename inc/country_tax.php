<?php

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
        'hierarchical'      => false, // true = categories, false = tags
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'country' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'country', array( 'cities' ), $args );
}
add_action( 'init', 'create_cities_taxonomy' );
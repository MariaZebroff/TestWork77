<?php

require 'inc/cities_cpt.php';
require 'inc/cities_metabox.php';
require 'inc/country_tax.php';
require 'inc/weather_widget.php';
require 'inc/serach_route.php';

function storefront_child_enqueue_scripts() {
    // Enqueue frontend script 
    wp_enqueue_script(
        'storefront-child-frontend',
        get_stylesheet_directory_uri() . '/build/app.js',
        array(), 
        filemtime(get_stylesheet_directory() . '/build/app.js'),
        true
    );
    wp_localize_script('storefront-child-frontend', 'cityData', array(
        'root_url' =>get_site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_scripts');

function storefront_child_enqueue_admin_scripts() {
    // Enqueue admin script 
    wp_enqueue_script(
        'storefront-child-admin',
        get_stylesheet_directory_uri() . '/build/admin.js',
        array(), 
        filemtime(get_stylesheet_directory() . '/build/admin.js'),
        true
    );
}
add_action('admin_enqueue_scripts', 'storefront_child_enqueue_admin_scripts');

function action_test_before(){
echo 'Test Action Before';
}
function action_test_after(){
    echo 'Test Action After';
}
add_action('city_action_before_table', 'action_test_before');
add_action('city_action_after_table', 'action_test_after');





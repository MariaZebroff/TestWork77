<?php

function cities_add_meta_boxes() {
    add_meta_box(
        'cities_location_meta_box', 
        'City Location', 
        'cities_location_meta_callback', 
        'cities', 
        'normal', 
        'high'
    );
}
add_action('add_meta_boxes', 'cities_add_meta_boxes');

function cities_location_meta_callback($post) {
    $latitude = get_post_meta($post->ID, '_city_latitude', true);
    $longitude = get_post_meta($post->ID, '_city_longitude', true);
    ?>
     <div id="city-location-error" style="color: red">Latitude and Longitude are required fields</div>
    <p>
        <label for="city_latitude">Latitude:</label>
        <input type="text" id="city_latitude" name="city_latitude" value="<?php echo esc_attr($latitude); ?>" style="width:100%;">
    </p>
    <p>
        <label for="city_longitude">Longitude:</label>
        <input type="text" id="city_longitude" name="city_longitude" value="<?php echo esc_attr($longitude); ?>" style="width:100%;">
    </p>
    <?php
}

// Saving Fields
function cities_save_meta_fields($post_id) {
    if (isset($_POST['city_latitude'])) {
        update_post_meta($post_id, '_city_latitude', sanitize_text_field($_POST['city_latitude']));
    }
    if (isset($_POST['city_longitude'])) {
        update_post_meta($post_id, '_city_longitude', sanitize_text_field($_POST['city_longitude']));
    }
}
add_action('save_post', 'cities_save_meta_fields');





// $latitude = get_post_meta(get_the_ID(), '_city_latitude', true);
// $longitude = get_post_meta(get_the_ID(), '_city_longitude', true);

// if ($latitude && $longitude) {
//     echo "Location: $latitude, $longitude";
// }
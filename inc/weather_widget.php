<?php
if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

class Selected_City_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'selected_city_widget', 
            'Choose City with Weather', 
            array('description' => 'Display selected city weather from OpenWeatherMap')
        );
    }

    public function widget($args, $instance) {
        if (!empty($instance['city_id'])) {
            $city = get_post($instance['city_id']); 
    
            if ($city) {
                $latitude = get_post_meta($city->ID, '_city_latitude', true);
                $longitude = get_post_meta($city->ID, '_city_longitude', true);
                $weather_data = $this->get_weather($latitude, $longitude);
    
                // Get country taxonomy
                $terms = get_the_terms($city->ID, 'country');
                $country_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
    
                // Display title with city and country
                $title = esc_html($city->post_title);
                if (!empty($country_name)) {
                    $title .= ' (' . esc_html($country_name) . ')';
                }
    
                echo $args['before_widget'];
                echo $args['before_title'] . $title . $args['after_title'];
    
                // Display Weather Data
                if ($weather_data) {
                    echo '<p><strong>Temperature:</strong> ' . esc_html($weather_data['temp']) . '°C</p>';
                    echo '<p><strong>Weather:</strong> ' . esc_html($weather_data['description']) . '</p>';
                    echo '<p><img src="https://openweathermap.org/img/wn/' . esc_attr($weather_data['icon']) . '@2x.png" alt="Weather icon"></p>';
                } else {
                    echo '<p>Weather data not available.</p>';
                }
    
                echo $args['after_widget'];
            }
        }
    }

    private function get_weather($lat, $lon) {
        if (empty($lat) || empty($lon)) {
            return false;
        }

        // Use the API key from config.php
        // var_dump(OPENWEATHER_API_KEY);
        $api_key = defined('OPENWEATHER_API_KEY') ? OPENWEATHER_API_KEY : '';
        // var_dump(OPENWEATHER_API_KEY);
        if (empty($api_key)) {
            return false; 
        }

        // $api_key = 'f0a470e4f44ebc27e4753628308d22d3';
        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid={$api_key}";

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['main']['temp'])) {
            return false;
        }

        return array(
            'temp' => round($data['main']['temp']),
            'description' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon']
        );
    }

    public function form($instance) {
        $selected_city = !empty($instance['city_id']) ? $instance['city_id'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('city_id')); ?>">Choose City:</label>
            <select id="<?php echo esc_attr($this->get_field_id('city_id')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('city_id')); ?>" 
                    class="widefat">
                <option value="">-- Choose City --</option>
                <?php
                $cities = get_posts(array(
                    'post_type' => 'cities',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                foreach ($cities as $city) {
                    echo '<option value="' . esc_attr($city->ID) . '" ' . selected($selected_city, $city->ID, false) . '>' . esc_html($city->post_title) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? intval($new_instance['city_id']) : '';
        return $instance;
    }
}

function register_selected_city_widget() {
    register_widget('Selected_City_Widget');
}
add_action('widgets_init', 'register_selected_city_widget');





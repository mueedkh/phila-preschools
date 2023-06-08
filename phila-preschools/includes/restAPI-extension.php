<?php
class Registration_Preschools_API_Ext
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_phila_preschools_rest_endpoint'));
        add_filter('rest_prepare_phila-preschools', array($this, 'preschool_registration_rest_api_fields'));
        add_action('rest_api_init', array($this, 'register_phila_preschools_loop_rest_endpoint'));
        add_filter('rest_phila-preschools_query', array($this, 'preschool_registration_rest_api_query'));
    }

    // Register custom REST API endpoint for "phila-preschools" CPT
    public function register_phila_preschools_rest_endpoint()
    {
        register_rest_route('wp/v2', '/preschool-registration/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_phila_preschool_registration'),
        ));
    }

    // Custom callback function for the REST API endpoint
    public function get_phila_preschool_registration($request)
    {
        $post_id = $request->get_param('id');
        $post = get_post($post_id);

        if (empty($post) || $post->post_type !== 'phila-preschools') {
            return new WP_Error('invalid_post', 'Invalid post ID', array('status' => 404));
        }

        // Retrieve the necessary custom fields
        $preschool_name = get_post_meta($post_id, 'preschool_name', true);
        $address = get_post_meta($post_id, 'address', true);
        $weekday_timings = get_post_meta($post_id, 'weekday_timings', true);
        $location_accepting_registrations = get_post_meta($post_id, 'location_accepting_registrations', true);

        $data = array(
            'preschool_name' => $preschool_name,
            'address' => $address,
            'weekday_timings' => $weekday_timings,
            'location_accepting_registrations' => $location_accepting_registrations,
        );

        return $data;
    }

    // Register custom REST API endpoint to loop through all "phila-preschools" CPT posts
    public function register_phila_preschools_loop_rest_endpoint()
    {
        register_rest_route('wp/v2', '/preschool-registration', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_phila_preschools_loop'),
        ));
    }

    // Custom callback function for the REST API endpoint
    public function get_phila_preschools_loop($request)
    {
        $args = array(
            'post_type' => 'phila-preschools',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);
        $posts = array();
        while ($query->have_posts()) {
            $query->the_post();
            // Retrieve the necessary custom fields
            $preschool_name = get_post_meta(get_the_ID(), 'preschool_name', true);
            $address = get_post_meta(get_the_ID(), 'address', true);
            $time_of_registration = get_post_meta(get_the_ID(), 'time_of_registration', true);
            $location_accepting_registrations = get_post_meta(get_the_ID(), 'location_accepting_registrations', true);

            $post_data = array(
                'id' => get_the_ID(),
                'preschool_name' => $preschool_name,
                'address' => $address,
                'time_of_registration' => $time_of_registration,
                'location_accepting_registrations' => $location_accepting_registrations,
            );

            $posts[] = $post_data;
        }
        wp_reset_postdata();
        return $posts;
    }

    // Modify the REST API query for the "phila-preschools" CPT to sort by date-time
    public function preschool_registration_rest_api_query($args, $request)
    {
        $weekday_timings = $request->get_param('weekday_timings');

        if ($weekday_timings) {
            $args['meta_query'][] = array(
                'key'     => 'weekday_timings',
                'value'   => array($weekday_timings),
                'compare' => '>=',
                'type'    => 'DATETIME',
            );
        }

        return $args;
    }

    // Customize the response fields for the REST API endpoint
    public function preschool_registration_rest_api_fields($data)
    {
        $post_id = $data->data['id'];

        // Retrieve additional custom fields
        $custom_field = get_post_meta($post_id, 'custom_field', true);

        $data->data['custom_field'] = $custom_field;

        return $data;
    }
}

new Registration_Preschools_API_Ext();

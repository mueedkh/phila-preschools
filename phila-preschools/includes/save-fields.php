<?php
class Preschool_Registration_Save_Data
{
    // Save Custom Field Values
    public function preschool_registration_save_fields($post_id)
    {

        if (!isset($_POST['preschool_registration_meta_nonce']) || !wp_verify_nonce($_POST['preschool_registration_meta_nonce'], 'preschool_registration_meta_action')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['post_type']) && 'phila-preschools' !== $_POST['post_type']) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save Name of Preschool
        if (isset($_POST['preschool_name'])) {
            update_post_meta($post_id, 'preschool_name', sanitize_text_field($_POST['preschool_name']));
        }

        // Save Address
        if (isset($_POST['address'])) {
            update_post_meta($post_id, 'address', sanitize_textarea_field($_POST['address']));
        }

        // Save Time of Registration
        // Save the meta values for each weekday
        $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $weekday_timings = array();

        foreach ($weekdays as $weekday) {
            $meta_key_start = 'weekday_timings_start_' . strtolower($weekday);
            $meta_key_end = 'weekday_timings_end_' . strtolower($weekday);

            if (isset($_POST[$meta_key_start]) && isset($_POST[$meta_key_end])) {
                $weekday_timings[$weekday]['start'] = sanitize_text_field($_POST[$meta_key_start]);
                $weekday_timings[$weekday]['end'] = sanitize_text_field($_POST[$meta_key_end]);
            }
        }
        // Update the meta value
        update_post_meta($post_id, 'weekday_timings', $weekday_timings);

        // Save Location Accepting Registrations
        if (isset($_POST['location_accepting_registrations'])) {
            update_post_meta($post_id, 'location_accepting_registrations', sanitize_text_field($_POST['location_accepting_registrations']));
        }
    }
}

<?php
class Preschool_Registration_Meta_Boxes
{
    // Add Custom Fields to Preschool Registration
    public function preschool_registration_custom_fields()
    {
        add_meta_box('preschool_registration_fields', 'Preschool Registration Fields', array($this, 'preschool_registration_display_fields'), 'phila-preschools', 'normal');
    }

    public function preschool_registration_display_fields($post)
    {
        wp_nonce_field('preschool_registration_meta_action', 'preschool_registration_meta_nonce');

        // wp_nonce_field(basename(__FILE__), 'preschool_registration_meta_nonce');

        $preschool_name = get_post_meta($post->ID, 'preschool_name', true);
        $address = get_post_meta($post->ID, 'address', true);
        $weekday_timings = get_post_meta($post->ID, 'weekday_timings', true);
        $location_accepting_registrations = get_post_meta($post->ID, 'location_accepting_registrations', true);

?>
        <label for="preschool_name">Name of Preschool:</label>
        <input type="text" id="preschool_name" name="preschool_name" value="<?php echo esc_attr($preschool_name); ?>" style="width: 100%;" /><br /><br />

        <label for="address">Address:</label>
        <textarea id="address" name="address" style="width: 100%;"><?php echo esc_textarea($address); ?></textarea><br /><br />
        <label>Time of registration during the week:</label>
        <?php
        // Display the input fields for each weekday
        $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        foreach ($weekdays as $weekday) {
            $meta_key_start = 'weekday_timings_start_' . strtolower($weekday);
            $meta_key_end = 'weekday_timings_end_' . strtolower($weekday);
            $value_start = isset($weekday_timings[$weekday]['start']) ? $weekday_timings[$weekday]['start'] : '';
            $value_end = isset($weekday_timings[$weekday]['end']) ? $weekday_timings[$weekday]['end'] : '';

            echo '<table><tr>';
            echo '<td style="min-width: 80px;"><label for="' . esc_attr($meta_key_start) . '">' . esc_html($weekday) . ':</label></td>';
            echo '<td>Start Time: <input type="time" id="' . esc_attr($meta_key_start) . '" name="' . esc_attr($meta_key_start) . '" value="' . esc_attr($value_start) . '" /></td>';
            echo '<td>End Time: <input type="time" id="' . esc_attr($meta_key_end) . '" name="' . esc_attr($meta_key_end) . '" value="' . esc_attr($value_end) . '" /></td>';
            echo '</tr></table>';
        }
        ?>
        <br />
        <label for="location_accepting_registrations">Location Accepting Registrations?</label>
        <select id="location_accepting_registrations" name="location_accepting_registrations">
            <option value="Yes" <?php selected($location_accepting_registrations, 'Yes'); ?>>Yes</option>
            <option value="No" <?php selected($location_accepting_registrations, 'No'); ?>>No</option>
        </select><br /><br />
<?php
    }
}

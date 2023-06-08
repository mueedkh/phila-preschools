<?php
class Preschool_Registration_Import_Export
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_import_export_page'));
        add_action('admin_post_preschool_registration_export', array($this, 'export_data'));
        add_action('admin_post_preschool_registration_import', array($this, 'import_data'));
    }

    public function add_import_export_page()
    {
        add_submenu_page(
            'edit.php?post_type=phila-preschools',
            'Import/Export',
            'Import/Export',
            'manage_options',
            'preschool-registration-import-export',
            array($this, 'render_import_export_page')
        );
    }
    public function render_import_export_page()
    {
?>
        <div class="wrap">
            <h1>Import/Export Preschool Registration Data</h1>

            <h2>Export Data</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="preschool_registration_export">
                <?php wp_nonce_field('preschool_registration_export_nonce', 'preschool_registration_export_nonce'); ?>
                <input type="submit" name="export_data" class="button button-primary" value="Export Data">
            </form>

            <h2>Import Data</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="preschool_registration_import">
                <?php wp_nonce_field('preschool_registration_import_nonce', 'preschool_registration_import_nonce'); ?>
                <input type="file" name="import_file">
                <input type="submit" name="import_data" class="button button-primary" value="Import Data">
            </form>
        </div>
<?php
    }

    public function export_data()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Verify the export nonce
        if (!isset($_POST['preschool_registration_export_nonce']) || !wp_verify_nonce($_POST['preschool_registration_export_nonce'], 'preschool_registration_export_nonce')) {
            wp_die('Invalid nonce.');
        }

        // Prepare data for export
        $args = array(
            'post_type' => 'phila-preschools',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);

        $data = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $post_id = get_the_ID();
                $school_description = get_the_content();
                $preschool_name = get_post_meta($post_id, 'preschool_name', true);
                $address = get_post_meta($post_id, 'address', true);
                $weekday_timings = get_post_meta($post_id, 'weekday_timings', true);
                $location_accepting_registrations = get_post_meta($post_id, 'location_accepting_registrations', true);

                // Convert the weekday_timings array to a string representation
                $weekday_timings_string = json_encode($weekday_timings);

                $data[] = array(
                    'Preschool Name' => $preschool_name,
                    'Address' => $address,
                    'Registration Time' => $weekday_timings_string,
                    'Location Accepting Registrations' => $location_accepting_registrations,
                    'Preschool Description' => $school_description,
                );
            }
        }

        // Generate and download the export file
        $filename = 'preschool-registration-export-' . date('Y-m-d') . '.csv';
        $directory = plugin_dir_path(__FILE__) . 'exports/';

        // Create the directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory);
        }

        $filepath = $directory . $filename;
        $file = fopen($filepath, 'w');
        fputcsv($file, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        // Send the file to the browser
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        readfile($filepath);

        exit;
    }
    public function import_data()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Verify the import nonce
        if (!isset($_POST['preschool_registration_import_nonce']) || !wp_verify_nonce($_POST['preschool_registration_import_nonce'], 'preschool_registration_import_nonce')) {
            wp_die('Invalid nonce.');
        }

        // Process the imported file
        if (isset($_FILES['import_file'])) {
            $file = $_FILES['import_file'];

            // Check if the file upload encountered any errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                wp_die('File upload error: ' . $file['error']);
            }

            // Read the CSV data from the file
            $csv = array_map('str_getcsv', file($file['tmp_name']));

            // Remove the CSV header row
            $header = array_shift($csv);

            // Process each row and save the data as custom post type entries
            foreach ($csv as $row) {
                $data = array_combine($header, $row);

                // Create a new custom post type entry
                $post_args = array(
                    'post_type' => 'phila-preschools',
                    'post_title' => $data['Preschool Name'],
                    'post_status' => 'publish',
                    'post_content' => $data['Preschool Description'], // Set the post content
                );
                $post_id = wp_insert_post($post_args);

                // Save the custom field values
                update_post_meta($post_id, 'preschool_name', $data['Preschool Name']);
                update_post_meta($post_id, 'address', $data['Address']);

                // Convert the "Registration Time" string back to an array
                $weekday_timings = json_decode($data['Registration Time'], true);

                update_post_meta($post_id, 'weekday_timings', $weekday_timings);
                update_post_meta($post_id, 'location_accepting_registrations', $data['Location Accepting Registrations']);
            }

            wp_redirect(admin_url('edit.php?post_type=phila-preschools'));
            exit;
        }
    }
}

new Preschool_Registration_Import_Export();

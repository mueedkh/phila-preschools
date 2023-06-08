<?php

/**
 * Plugin Name: Preschool Registration
 * Plugin URI:  phila.gov
 * Author:      Mueed Ullah
 * Author URI:  https://www.linkedin.com/in/mueedullahkhan/
 * Description: This plugin is design for preschools registration across Philadephia city.
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: phila-preschool
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Load plugin files.
require_once plugin_dir_path(__FILE__) . 'includes/preschool-registration.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/save-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/restAPI-extension.php';
require_once plugin_dir_path(__FILE__) . 'import-export-schools.php';

class Preschool_Registration_Plugin
{
    public function __construct()
    {
        $post_type = new Preschool_Registration_Post_Type();
        add_action('init', array($post_type, 'preschool_registration_custom_post_type'));

        $meta_boxes = new Preschool_Registration_Meta_Boxes();
        add_action('add_meta_boxes', array($meta_boxes, 'preschool_registration_custom_fields'));

        $save_fields = new Preschool_Registration_Save_Data();
        add_action('save_post', array($save_fields, 'preschool_registration_save_fields'));
        //Main JS file for the Plugin
        add_action('admin_enqueue_scripts', array($this, 'preschool_registration_enqueue_assets'));
        // Add an action hook to enqueue the Main CSS file
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    //adding script for time-date field
    public function preschool_registration_enqueue_assets()
    {
        wp_enqueue_script('preschool_registration_custom_fields', plugin_dir_url(__FILE__) . 'assets/js/phila-preschools.js', array('jquery'), '1.0', true);
    }
    public function enqueue_styles()
    {
        // Enqueue your CSS file
        wp_enqueue_style('phila-preschools-styles', plugin_dir_url(__FILE__) . 'assets/css/phila-preschools.css', array(), '1.0');
    }
    // Uninstall callback function
    public static function uninstall()
    {
        // Delete the custom post type and its posts
        $args = array(
            'post_type' => 'phila-preschools',
            'posts_per_page' => -1,
            'post_status' => 'any',
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                wp_delete_post(get_the_ID(), true);
            }
        }

        // Delete the custom post type
        unregister_post_type('phila-preschools');
    }
}

new Preschool_Registration_Plugin();
register_uninstall_hook(__FILE__, array('Preschool_Registration_Plugin', 'uninstall'));

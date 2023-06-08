<?php
class Preschool_Registration_Post_Type
{
    public function preschool_registration_custom_post_type()
    {
        $labels = array(
            'name'                  => 'Preschool Registrations',
            'singular_name'         => 'Preschool Registration',
            'menu_name'             => 'Preschool Registrations',
            'name_admin_bar'        => 'Preschool Registration',
            'archives'              => 'Registration Archives',
            'attributes'            => 'Registration Attributes',
            'parent_item_colon'     => 'Parent Item:',
            'all_items'             => 'All Registrations',
            'add_new_item'          => 'Add New Registration',
            'add_new'               => 'Add New',
            'new_item'              => 'New Registration',
            'edit_item'             => 'Edit Registration',
            'update_item'           => 'Update Registration',
            'view_item'             => 'View Registration',
            'view_items'            => 'View Registrations',
            'search_items'          => 'Search Registration',
            'not_found'             => 'No registrations found',
            'not_found_in_trash'    => 'No registrations found in Trash',
            'featured_image'        => 'Featured Image',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'Registrations list',
            'items_list_navigation' => 'Registrations list navigation',
            'filter_items_list'     => 'Filter registrations list',
        );
        $args   = array(
            'label'               => 'Preschool Registration',
            'description'         => 'Custom post type for preschool registrations',
            'labels'              => $labels,
            'supports'            => array('title', 'editor', 'thumbnail'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-clipboard',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'rewrite'             =>  array('slug' => 'preschool-registration'),
        );
        register_post_type('phila-preschools', $args);
    }
}

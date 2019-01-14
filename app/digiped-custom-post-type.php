<?php
/**
 * Custom post type for DigiPed Artifacts.
 *
 * @package digiped-theme
 */

namespace App;

use function create_post_type;
use function create_taxonomy;

class DigiPed_Artifact
{
    
    function init()
    {
        $this->do_post_types();
        $this->default_values();
    }
    function do_post_types()
    {
        $artifacts = array(
            'args' => array(
                'label' => __('Artifacts', 'learningspace'),
                'labels' => array(
                    'name' => _x('Artifacts', 'Post Type General Name', 'learningspace'),
                    'singular_name' => _x('Artifact', 'Post Type Singular Name', 'learningspace'),
                    'menu_name' => __('Artifacts', 'learningspace'),
                    'parent_item_colon' => __('Parent Artifact:', 'learningspace'),
                    'all_items' => __('All Artifacts', 'learningspace'),
                    'view_item' => __('View Artifact', 'learningspace'),
                    'add_new_item' => __('Select Artifact:', 'learningspace'),
                    'add_new' => __('Add New', 'learningspace'),
                    'edit_item' => __('Edit Artifact', 'learningspace'),
                    'update_item' => __('Update Artifact', 'learningspace'),
                    'search_items' => __('Search Artifact', 'learningspace'),
                    'not_found' => __('Not Found', 'learningspace'),
                    'not_found_in_trash' => __('Not found in Trash', 'learningspace'),),
                'description' => __('Learning space\'s active Artifacts', 'learningspace'),
                // Features this CPT supports in Post Editor
                'supports' => array('title', 'thumbnail', 'editor'),
                // You can associate this CPT with a taxonomy or custom taxonomy.
                'taxonomies' => array('Digiped Keyword'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'menu_position' => 5,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',)
        );
        create_post_type('Digiped Artifact', $Artifacts['args']);

        $Collection = array(
            'args' => array(
                'label' => __('Collections', 'learningspace'),
                'labels' => array(
                    'name' => _x('Collections', 'Post Type General Name', 'learningspace'),
                    'singular_name' => _x('Collection', 'Post Type Singular Name', 'learningspace'),
                    'menu_name' => __('Collections', 'learningspace'),
                    'parent_item_colon' => __('Parent Collection:', 'learningspace'),
                    'all_items' => __('All Collections', 'learningspace'),
                    'view_item' => __('View Collection', 'learningspace'),
                    'add_new_item' => __('Select Collection:', 'learningspace'),
                    'add_new' => __('Add New', 'learningspace'),
                    'edit_item' => __('Edit Collection', 'learningspace'),
                    'update_item' => __('Update Collection', 'learningspace'),
                    'search_items' => __('Search Collection', 'learningspace'),
                    'not_found' => __('Not Found', 'learningspace'),
                    'not_found_in_trash' => __('Not found in Trash', 'learningspace'),),
                'description' => __('Learning space\'s active Collections', 'learningspace'),
                // Features this CPT supports in Post Editor
                'supports' => array('title', 'thumbnail', 'editor'),
                // You can associate this CPT with a taxonomy or custom taxonomy.
                'taxonomies' => array('Digiped Collection Type'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'menu_position' => 5,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',)
        );
        create_post_type('Digiped Collection', $Collection['args']);
    }

    function default_values()
    {
            
    }
    
    public function redirect_artifact () {
        $queried_post_type = get_query_var('post_type');
        if (  is_single() && 'digiped_artifact' ==  $queried_post_type  ) {
            wp_redirect( home_url(), 301 );
            exit;
        }
    }
}

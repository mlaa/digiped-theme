<?php
/**
 * Custom post type for DigiPed Artifacts.
 *
 * @package digiped-theme
 */
class CustomPostType
{
    
    public function init()
    {
        $this->doPostTypes();
        $this->defaultValues();
    }

    public function doPostTypes()
    {
        $artifacts = array(
            'args' => array(
                'label' => __('Artifacts', 'digiped'),
                'labels' => array(
                    'name' => __('Artifacts', 'Post Type General Name', 'digiped'),
                    'singular_name' => _x('Artifact', 'Post Type Singular Name', 'digiped'),
                    'menu_name' => __('Artifacts', 'digiped'),
                    'parent_item_colon' => __('Parent Artifact:', 'digiped'),
                    'all_items' => __('All Artifacts', 'digiped'),
                    'view_item' => __('View Artifact', 'digiped'),
                    'add_new_item' => __('Select Artifact:', 'digiped'),
                    'add_new' => __('Add New', 'digiped'),
                    'edit_item' => __('Edit Artifact', 'digiped'),
                    'update_item' => __('Update Artifact', 'digiped'),
                    'search_items' => __('Search Artifact', 'digiped'),
                    'not_found' => __('Not Found', 'digiped'),
                    'not_found_in_trash' => __('Not found in Trash', 'digiped'),),
                'description' => __('Learning space\'s active Artifacts', 'digiped'),
                // Features this CPT supports in Post Editor
                'supports' => array('title', 'thumbnail', 'editor'),
                // You can associate this CPT with a taxonomy or custom taxonomy.
                'taxonomies' => array('Keyword'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'menu_position' => 4,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',)
        );
        createCustomPostType('Artifact', $artifacts['args']);

        $collection = array(
            'args' => array(
                'label' => __('Collections', 'digiped'),
                'labels' => array(
                    'name' => __('Collections', 'Post Type General Name', 'digiped'),
                    'singular_name' => _x('Collection', 'Post Type Singular Name', 'digiped'),
                    'menu_name' => __('Collections', 'digiped'),
                    'parent_item_colon' => __('Parent Collection:', 'digiped'),
                    'all_items' => __('All Collections', 'digiped'),
                    'view_item' => __('View Collection', 'digiped'),
                    'add_new_item' => __('Select Collection:', 'digiped'),
                    'add_new' => __('Add New', 'digiped'),
                    'edit_item' => __('Edit Collection', 'digiped'),
                    'update_item' => __('Update Collection', 'digiped'),
                    'search_items' => __('Search Collection', 'digiped'),
                    'not_found' => __('Not Found', 'digiped'),
                    'not_found_in_trash' => __('Not found in Trash', 'digiped'),),
                'description' => __('Learning space\'s active Collections', 'digiped'),
                // Features this CPT supports in Post Editor
                'supports' => array('title', 'thumbnail', 'editor'),
                // You can associate this CPT with a taxonomy or custom taxonomy.
                'taxonomies' => array('Collection Type'),
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
        createCustomPostType('Collection', $collection['args']);
    }

    public function defaultValues()
    {
        //
    }
    
    public function redirectArtifact()
    {
        $queried_post_type = get_query_var('post_type');
        if (is_single() && 'digiped_artifact' == $queried_post_type) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }
}

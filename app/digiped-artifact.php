<?php
/**
 * Custom post type for DigiPed Artifacts.
 *
 * @package digiped-theme
 */

class DigiPed_Artifact
{
    function create_post_type()
    {
        $args = array(
            'labels'       => array(
                'name'          => 'Artifacts',
                'singular_name' => 'Artifact',
            ),
            'description'  => '',
            'rewrite'           => array( 'slug' => 'artifact' ),
            'publicly_queryable'  => false,
            'public'       => true,
            'show_ui'      => true,
            'hierarchical' => true,
            'supports'     => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'custom-fields',
                'revisions',
            ],
            'taxonomies' => [
                'post_tag',
                'category'
            ],
        );
        register_post_type('digiped_artifact', $args);
    }

    public function redirect_artifact () {
        $queried_post_type = get_query_var('post_type');
        if (  is_single() && 'digiped_artifact' ==  $queried_post_type  ) {
            wp_redirect( home_url(), 301 );
            exit;
        }
    }
}

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
}

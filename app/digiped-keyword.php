<?php
/**
 * Custom post type for DigiPed Keywords.
 *
 * @package digiped-theme
 */

class DigiPed_Keyword
{
    function create_post_type()
    {
        $args = [
            'labels' => [
                'name'          => 'Keywords',
                'singular_name' => 'Keyword',
            ],
            'rewrite'           => array( 'slug' => 'keyword' ),
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
        ];
        register_post_type('digiped_keyword', $args);
    }
}

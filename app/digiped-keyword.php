<?php
/**
 * Custom post type for DigiPed Keywords.
 *
 * @package digiped-theme
 */

class DigiPed_Keyword {
	function create_post_type() {
		$args = array(
			'labels'       => array(
				'name'          => 'Keywords',
				'singular_name' => 'Keyword',
			),
			'description'  => '',
			'public'       => true,
			'show_ui'      => true,
			'hierarchical' => true,
			'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
			'taxonomies' => array('post_tag', 'category'),
		);
		register_post_type( 'digiped_keyword', $args );
	}
}

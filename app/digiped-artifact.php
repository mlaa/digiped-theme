<?php
/**
 * Custom post type for DigiPed Artifacts.
 *
 * @package digiped-theme
 */

class DigiPed_Artifact {
	function create_post_type() {
		$args = array(
			'labels'       => array(
				'name'          => 'Artifacts',
				'singular_name' => 'Artifact',
			),
			'description'  => 'A description for your post type',
			'public'       => true,
			'show_ui'      => true,
			'hierarchical' => true,
			'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
			'taxonomies' => array('post_tag', 'category'),
		);
		register_post_type( 'digiped_artifact', $args );
	}
}

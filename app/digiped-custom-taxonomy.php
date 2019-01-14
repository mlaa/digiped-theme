<?php
/**
 * Custom post type for DigiPed Artifacts.
 *
 * @package digiped-theme
 */

namespace App;

use function create_post_type;
use function create_taxonomy;

class DigiPed_Custom_Taxonomy
{
    function init()
    {
        $this->do_taxonomies();
        $this->default_artifact_type_values();
        $this->default_artifact_keyword_values();
        $this->default_collection_type_values();
    }
    
    function do_taxonomies()
    {
        create_taxonomy( 'Artifact Keyword', array("digiped_artifact"), true, true );
        create_taxonomy( 'Artifact Type', array("digiped_artifact"), true, true );
        create_taxonomy( 'Artifact Tag', array("digiped_artifact"), true, false );
        create_taxonomy( 'Collection Type', array("digiped_collection"), true, true );
    }

    function default_artifact_keyword_values ()
    { 
            $terms = [
                "Network",
                "Mapping",
                "Digital divides",
                "Race",
                "Video",
                "Design",
                "Future",
                "Blogging",
                "Open",
                "Archive",
                "Labor",
                "Poetry",
                "Hybrid",
                "Prototype",
                "Praxis",
                "Affect",
                "Language learning",
                "Online",
                "Fiction",
                "Visualization",
                "Community college",
                "Information",
                "Hashtag",
                "Remix",
                "Annotation",
                "Classroom",
                "Disability",
                "Code",
                "Mulitimodal",
                "History",
                "Makerspaces",
                "Assessment",
                "Storytelling",
                "Curation",
                "Queer",
                "Public",
                "Fieldwork",
                "Iteration",
                "Gaming",
                "Professionalization",
                "Gender",
                "Community",
                "Rhetoric",
                "Diaspora",
                "Indigenous",
                "Intersectionality",
                "Sexuality",
                "Reading",
                "Text analysis",
                "Eportfolio",
                "Failure",
                "Interface",
                "Play",
                "Collaboration",
                "Hacking",
                "Access",
                "Hacking",
                "Authorship",
                "Project management",
                "Sound",
                "Social justice",
            ];
            $this->save_taxonomy_term( $terms, 'artifact_keyword' );
            return true;
    }

    function default_artifact_type_values()
    { 
            $terms = [
                "ACTIVITY",
                "ASSIGMENTS",
                "ASSIGNMENT SEQUENCE",
                "ASSIGNMENT",
                "ASSIGNMENTS",
                "BLOG POST",
                "CLASS ACTIVITY POST-SECONDARY",
                "COLLABORATIVE PROJECTS",
                "COURSE ASSIGNMENT",
                "COURSE SITES",
                "COURSE SYLLABUS",
                "CURATED RESOURCE",
                "ELABORATE HACK",
                "EXERCISE VIDEO",
                "ONLINE RESOURCE",
                "ONLINE DATABASE",
                "PUBLISHED ARTICLE",
                "RUBRIC",
                "STEP-BY-STEP TUTORIAL",
                "STUDENT WORK",
                "SYLLABUS",
                "TEACHING GUIDE",
                "TEACHING GUIDELINES",
                "TEACHING RESOURCE",
                "ASSIGNMENT AND STUDENT WORK",
                "ASSIGNMENT",
                "COLLABORATIVE PROJECTS",
                "DIGITAL COLLECTION",
                "FILM",
                "LEARNING OBJECTIVE",
                "LESSON PLAN",
                "STUDENT WORK",
                "SYLLABUS",
                "VIDEO",
            ];
            $this->save_taxonomy_term( $terms, 'artifact_type' );
            return true;
    }

    function default_artifact_tag_values()
    { 
        return true;
    }

    function default_collection_type_values()
    { 
        return true;
    }

    function save_taxonomy_term ($terms, $taxonomy) 
    {
        if( !is_string($terms) ) {
            $terms = array($terms);
        }

        foreach ( $terms as $term ) {
            if ( !term_exists(strtolower( $term ), $taxonomy) ) {
                wp_insert_term ( strtolower( $term ),  $taxonomy );
            }
        }
    }
}
<?php
/**
 * Custom taxonomies for the artifact and collection post types.
 *
 * @package digiped-theme
 */
class CustomTaxonomy
{
    
    public function init()
    {
        $this->doTaxonomies();
        $this->defaultArtifactGenreValues();
        $this->defaultArtifactKeywordValues();
    }
    
    public function doTaxonomies()
    {
        createCustomTaxonomy('Keyword', array("artifact"), true);
        // createCustomTaxonomy( 'File Type', array("artifact"), true, true);
        createCustomTaxonomy('Genre', array("artifact"), true);
        createCustomTaxonomy('Author', array("artifact"), false);
        createCustomTaxonomy('Citation', array("artifact"), true);
        createCustomTaxonomy('Related Work', array("artifact"), true);
        
        createCustomTaxonomy('Collection Type', array("collection"), true);
    }

    public function defaultArtifactKeywordValues()
    {
            $terms = [
                "Network" => "",
                "Mapping" => "",
                "Digital divides" => "",
                "Race" => "",
                "Video" => "",
                "Design" => "",
                "Future" => "",
                "Blogging" => "",
                "Open" => "",
                "Archive" => "",
                "Labor" => "",
                "Poetry" => "",
                "Hybrid" => "",
                "Prototype" => "",
                "Praxis" => "",
                "Affect" => "",
                "Language learning" => "",
                "Online" => "",
                "Fiction" => "",
                "Visualization" => "",
                "Community college" => "",
                "Information" => "",
                "Hashtag" => "",
                "Remix" => "",
                "Annotation" => "",
                "Classroom" => "",
                "Disability" => "",
                "Code" => "",
                "Mulitimodal" => "",
                "History" => "",
                "Makerspaces" => "",
                "Assessment" => "",
                "Storytelling" => "",
                "Curation" => "",
                "Queer" => "",
                "Public" => "",
                "Fieldwork" => "",
                "Iteration" => "",
                "Gaming" => "",
                "Professionalization" => "",
                "Gender" => "",
                "Community" => "",
                "Rhetoric" => "",
                "Diaspora" => "",
                "Indigenous" => "",
                "Intersectionality" => "",
                "Sexuality" => "",
                "Reading" => "",
                "Text analysis" => "",
                "Eportfolio" => "",
                "Failure" => "",
                "Interface" => "",
                "Play" => "",
                "Collaboration" => "",
                "Hacking" => "",
                "Access" => "",
                "Hacking" => "",
                "Authorship" => "",
                "Project management" => "",
                "Sound" => "",
                "Social justice" => "",
            ];
            $this->maybeInsertTaxonomyTerms($terms, 'keyword');
            return true;
    }

    public function defaultArtifactGenreValues()
    {
            $terms = [
                "CURATION STATEMENT" => "",
                "RELATED WORK" => "",
                "CITATION" => "",
                "ACTIVITY" => "",
                "ASSIGNMENT" => "",
                "BLOG POST" => "",
                "CLASS ACTIVITY" => "",
                "COLLABORATIVE PROJECTS" => "",
                "COURSE ASSIGNMENT" => "",
                "COURSE SITES" => "",
                "COURSE SYLLABUS" => "",
                "CURATED RESOURCE" => "",
                "ELABORATE HACK" => "",
                "EXERCISE VIDEO" => "",
                "ONLINE RESOURCE" => "",
                "ARTICLE" => "",
                "RUBRIC" => "",
                "TUTORIAL" => "",
                "STUDENT WORK" => "",
                "SYLLABUS" => "",
                "TEACHING GUIDE" => "",
                "TEACHING GUIDELINES" => "",
                "TEACHING RESOURCE" => "",
                "COLLABORATIVE PROJECTS" => "",
                "DIGITAL COLLECTION" => "",
                "FILM" => "",
                "LEARNING OBJECTIVE" => "",
                "LESSON PLAN" => "",
                "STUDENT WORK" => "",
                "SYLLABUS" => "",
                "VIDEO" => "",
            ];
            $this->maybeInsertTaxonomyTerms($terms, 'genre');
            return true;
    }

    public function defaultArtifactTagValues()
    {
        return true;
    }

    public function defaultCollectionTypeValues()
    {
        return true;
    }

    public function maybeInsertTaxonomyTerms($terms, $taxonomy)
    {
        if (is_string($terms)) {
            $terms = array( $terms => "" );
        }

        foreach ($terms as $term => $desc) {
            if (!term_exists(strtolower($term), $taxonomy)) {
                wp_insert_term(strtolower($term), $taxonomy, [ 'description' => $desc ]);
            }
        }
    }
}

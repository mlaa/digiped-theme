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
        $this->actions();
    }
    
    public function actions()
    {
        add_action('created_author', array( $this, 'saveTaxMeta'), 10, 2);
        add_action('author_add_form_fields', array( $this, 'addTaxMeta'), 10, 2);
        add_action('author_edit_form_fields', array( $this, 'updateTaxMeta'), 10, 2);
        add_action('edited_author', array( $this, 'updateTaxMeta'), 10, 2);
    }

    public function saveTaxMeta($term_id, $tt_id)
    {
        if (isset($_POST['first_name']) && '' !== $_POST['first_name']) {
            add_term_meta($term_id, 'first_name', $_POST['first_name'], true);
        }
        if (isset($_POST['last_name']) && '' !== $_POST['last_name']) {
            add_term_meta($term_id, 'last_name', $_POST['last_name'], true);
        }
        if (isset($_POST['affiliations']) && '' !== $_POST['affiliations']) {
            add_term_meta($term_id, 'affiliations', $_POST['affiliations'], true);
        }
    }

    public function addTaxMeta()
    {
        ?>
<div class="form-field">
	<label for="first_name">
		<?php _e('First Name', $this->plugin_name); ?></label>
	<p>
		<input type="text" class="author-first-name" id="first_name" name="first_name" value="<?= $first_name; ?>" />
	</p>
</div>
<div class="form-field">
	<label for="last_name">
		<?php _e('Last Name', $this->plugin_name); ?></label>
	<p>
		<input type="text" class="author-last-name" id="last_name" name="last_name" value="<?= $last_name; ?>" />
	</p>

</div>
<div class="form-field">
	<label for="affiliations">
		<?php _e('Affiliations', $this->plugin_name); ?></label>
	<p>
		<input type="text" class="author-affiliations" id="affiliations" name="affiliations" value="<?= $affiliations; ?>" />
	</p>
</div>
<?php
    }
    
    public function updateTaxMeta($term, $taxonomy)
    {
        $first_name = get_term_meta($term->term_id, 'first_name', true);
        $last_name = get_term_meta($term->term_id, 'last_name', true);
        $affiliations = get_term_meta($term->term_id, 'affiliations', true);
        ?>
<tr class="form-field">
	<th scope="row">
		<label for="first_name">
			<?php _e('First Name', $this->plugin_name); ?></label>
	</th>
	<td>
		<p>
			<input type="text" class="author-first-name" id="first_name" name="first_name" value="<?= $first_name; ?>" />
		</p>
	</td>
</tr>
<tr class="form-field">
	<th scope="row">
		<label for="last_name">
			<?php _e('Last Name', $this->plugin_name); ?></label>
	</th>
	<td>
		<p>
			<input type="text" class="author-last-name" id="last_name" name="last_name" value="<?= $last_name; ?>" />
		</p>
	</td>
</tr>
<tr class="form-field">
	<th scope="row">
		<label for="affiliations">
			<?php _e('Affiliations', $this->plugin_name); ?></label>
	</th>
	<td>
		<p>
			<input type="text" class="author-affiliations" id="affiliations" name="affiliations" value="<?= $affiliations; ?>" />
		</p>
	</td>
</tr>
<?php
    }


    public function doTaxonomies()
    {
        createCustomTaxonomy('Keyword', array("artifact"), true);
        // createCustomTaxonomy( 'File Type', array("artifact"), true, true);
        createCustomTaxonomy('Genre', array("artifact"), true);
        createCustomTaxonomy('Author', array("artifact"), false);
        createCustomTaxonomy('Citation', array("artifact"), false);
        createCustomTaxonomy('Related Work', array("artifact"), false);
        
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

<?php
/**
 * Manager for DigiPed Collections.
 *
 * @package digiped-theme
 */

/**
 * Encapsulates data management to support collections.
 */

class DigiPed_Collection
{

    /**
     * User meta key used to store collection data.
     */
    const USER_META_KEY = 'digiped-collections';

    /**
     * Alphanumeric unique ID.
     *
     * @var string
     */
    protected $id;

    /**
     * Collection name.
     *
     * @var string
     */
    protected $name;

    /**
     * Array of artifact IDs.
     *
     * @var array
     */
    protected $artifacts;

    /**
     * Instantiate an existing collection by ID.
     *
     * @param string $id Collection ID.
     */
    public function __construct(string $id)
    {
        $args = array(
            'orderby' => 'comment_karma',
            'order' => 'ASC',
            'post_type' => 'collection',
            'status' => 'all',
            'post_id' => $id,
        );

        $collection = get_post($id);
        if ($collection) {
            $comments_query = new WP_Comment_Query;
            $comments = $comments_query->query($args);
            $this->id = $id;
            $this->name = $collection->post_title;
            $artifacts = array();
            foreach ($comments as $artifact) {
                $artifacts[] = $artifact->comment_content;
            }
            $this->artifacts = $artifacts;
        } else {
            throw new Exception("Collection '$id' not found.");
        }
    }

    /**
     * Get collection properties.
     *
     * @param string $key Property name.
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->$key;
    }

    /**
     * Save this artifact to collection
     *
     * @param string $key   Property name.
     * @param mixed  $value Property value.
     * @return bool
     */
    private function saveArtifact()
    {
        $commentdata = array(
            'comment_post_ID' => $this->id,
            'comment_type' => 'artifact',
            'comment_approved' => 1,
            'comment_content' => $this->artifacts,
            'user_id' => get_current_user_id(),
        );

        //Insert new comment and get the comment ID
        $comment_id = wp_new_comment($commentdata);
        if (!$comment_id) {
            throw new Exception("Unable to save collection '$id'.");
        }
        return $comment_id;
    }

    // remove artifact from collection
    private function removeArtifact()
    {
        $args = array(
            'post_type' => 'collection',
            'ID' => $this->artifacts,
        );

        $comments_query = new WP_Comment_Query;
        $comment = $comments_query->query($args);
        if ($comment) {
            $delete = wp_delete_comment($comment[0]->comment_ID, true);
        } else {
            throw new Exception("Unable to save collection '$this->id'.");
        }
        return $comment->comment_ID;
    }

    private function removeCollection()
    {
        return wp_delete_post($this->id, true);
    }

    private static function saveCollection($name)
    {
        return wp_insert_post(array('post_status' => 'publish', 'post_type' => 'collection','post_title' => $name), false);
    }

    /**
     * Remove this collection from the database.
     *
     * @return bool
     */
    private function destroy()
    {
        $this->removeCollection();
    }

    /**
     * Add an artifact to this collection.
     *
     * @param int $artifact_id Artifact ID.
     * @return bool
     */
    public function add_artifact(int $artifact_id)
    {
        $this->artifacts = $artifact_id;
        return $this->saveArtifact();
    }

    /**
     * Remove an artifact from this collection.
     *
     * @param int $artifact_id Artifact ID.
     * @return bool
     */
    public function remove_artifact(int $artifact_id)
    {
        $this->artifacts = $artifact_id;
        return $this->removeArtifact();
    }

    public static function list()
    {
        
        $args = array(
            'post_type' => 'collection',
            'post_author' => get_current_user_id(),
            'orderby' => 'post_date',
            'order' => 'DESC',
        );
        $collection_posts = get_posts($args);
        $collections = [];

        foreach ($collection_posts as $post) {
            $collections[] = new self($post->ID);
        }

        return $collections;
    }

    /**
     * Create a new collection for the current user.
     *
     * @param string $name Collection name.
     * @return DigiPed_Collection
     */
    public static function create(string $name)
    {
        $id = self::saveCollection($name);
        $c = new self($id);
        return $c;
    }
}

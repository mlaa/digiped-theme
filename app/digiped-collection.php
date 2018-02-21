<?php
/**
 * Manager for DigiPed Collections.
 *
 * @package digiped-theme
 */

/**
 * Encapsulates data management to support collections.
 */
class DigiPed_Collection {

    /**
     * User meta key used to store collection data.
     */
    const USER_META_KEY = 'digiped-collections';

    /**
     * Meta value of self::USER_META_KEY.
     * @var array
     */
    protected $user_meta;

    /**
     * Collection ID.
     * @var int
     */
    public $id;

    /**
     * Collection name.
     * @var string
     */
    public $name;

    /**
     * Array of artifacts in this collection..
     * @var array
     */
    public $artifacts;

    /**
     * Instantiate an existing collection by ID.
     *
     * @param string $id ID of collection to instantiate.
     * @return DigiPed_Collection
     */
    function __construct( string $id ) {
        $this->user_meta = get_user_meta( get_current_user_id(), self::USER_META_KEY, true );

        if ( isset( $this->user_meta[ $id ] ) ) {
            $this->id = $id;
            $this->name = $this->user_meta[ $id ]['name'];
            $this->artifacts = $this->user_meta[ $id ]['artifacts'];
            return $this;
        } else {
            return false; // TODO Exception
        }
    }

    /**
     * Get all collections for the current user.
     *
     * @return array Array of DigiPed_Collection objects.
     */
    static function list() {
        $user_meta = get_user_meta( get_current_user_id(), self::USER_META_KEY, true );
        $collections = [];

        foreach( $user_meta as $id => $attrs ) {
            $collections[] = new self( $id );
        }

        return $collections;
    }

    /**
     * Create a new collection for the current user.
     *
     * @param string $name Name of collection.
     * @return DigiPed_Collection
     */
    static function create( string $name ) {
        $user_meta = get_user_meta( get_current_user_id(), self::USER_META_KEY, true );

        $id = uniqid();

        if ( ! $user_meta ) {
            $user_meta = [];
        }

        $user_meta[ $id ] = [
            'name' => $name,
            'artifacts' => [],
        ];

        // TODO error handle?
        $result = update_user_meta( get_current_user_id(), self::USER_META_KEY, $user_meta );

        return new self( $id );
    }

    /**
     * Save this collection to user meta.
     *
     * @return bool
     */
    function save() {
        $this->user_meta[ $this->id ]->name = $this->name;
        $this->user_meta[ $this->id ]->artifacts = $this->artifacts;
        return update_user_meta( get_current_user_id(), self::USER_META_KEY, $this->user_meta );
    }

    /**
     * Add an artifact to this collection.
     *
     * @param int $artifact_id ID of the artifact to add.
     * @return bool
     */
    function add_artifact( int $artifact_id ) {
        // TODO array_unique
        $this->user_meta[ $this->id ]['artifacts'][] = $artifact_id;
        return $this->save();
    }

    /**
     * Remove an artifact from this collection.
     *
     * @param int $artifact_id ID of the artifact to remove.
     * @return bool
     */
    function remove_artifact( int $artifact_id ) {
        $result = true;
        $artifact_key = array_search( $artifact_id, $this->user_meta[ $this->id ]['artifacts'] );

        if ( isset( $this->user_meta[ $this->id ]['artifacts'][ $artifact_key ] ) ) {
            unset( $this->user_meta[ $this->id ]['artifacts'][ $artifact_key ] );
            $result = $this->save();
        }

        return $result;
    }
}

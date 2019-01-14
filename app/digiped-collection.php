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
    function __construct(string $id)
    {
        global $wpdb;

        if (is_user_logged_in()) {
            $user_meta = get_user_meta(get_current_user_id(), self::USER_META_KEY, true);
        } else {
            $result = $wpdb->get_var("
				SELECT meta_value
				FROM $wpdb->usermeta
				WHERE meta_key = '" . self::USER_META_KEY . "'
				AND meta_value LIKE '%$id%'
			");

            if ($result) {
                $user_meta = unserialize($result);
            } else {
                throw new Exception("Collection '$id' not found.");
            }
        }

        if (isset($user_meta[ $id ])) {
            $this->id = $id;
            $this->name = $user_meta[ $id ]['name'];
            $this->artifacts = $user_meta[ $id ]['artifacts'];
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
    function __get(string $key)
    {
        return $this->$key;
    }

    /**
     * Save this collection to user meta.
     *
     * @param string $key   Property name.
     * @param mixed  $value Property value.
     * @return bool
     */
    function save()
    {
        $user_meta = get_user_meta(get_current_user_id(), self::USER_META_KEY, true);

        if ($user_meta) {
            $result = true;

            $user_meta[ $this->id ] = [
                'name' => $this->name,
                'artifacts' => $this->artifacts,
            ];

            if (get_user_meta(get_current_user_id(), self::USER_META_KEY, true) !== $user_meta) {
                $result = update_user_meta(get_current_user_id(), self::USER_META_KEY, $user_meta);
            }

            return $result;
        } else {
            throw new Exception("Unable to save collection '$id'.");
        }
    }

    /**
     * Remove this collection from the database.
     *
     * @return bool
     */
    function destroy()
    {
        $user_meta = get_user_meta(get_current_user_id(), self::USER_META_KEY, true);

        if ($user_meta) {
            unset($user_meta[ $this->id ]);
            return update_user_meta(get_current_user_id(), self::USER_META_KEY, $user_meta);
        } else {
            throw new Exception("Unable to destroy collection '$id'.");
        }
    }

    /**
     * Add an artifact to this collection.
     *
     * @param int $artifact_id Artifact ID.
     * @return bool
     */
    function add_artifact(int $artifact_id)
    {
        $this->artifacts = array_unique(array_values(array_merge($this->artifacts, [ $artifact_id ])));
        return $this->save();
    }

    /**
     * Remove an artifact from this collection.
     *
     * @param int $artifact_id Artifact ID.
     * @return bool
     */
    function remove_artifact(int $artifact_id)
    {
        $this->artifacts = array_unique(array_values(array_diff($this->artifacts, [ $artifact_id ])));
        return $this->save();
    }

    /**
     * Create a new collection for the current user.
     *
     * @param string $name Collection name.
     * @return DigiPed_Collection
     */
    static function create(string $name)
    {
        $id = uniqid();

        $user_meta = get_user_meta(get_current_user_id(), self::USER_META_KEY, true);

        if (! $user_meta) {
            $user_meta = [];
        }

        $user_meta[ $id ] = [
            'name'      => $name,
            'artifacts' => [],
        ];

        update_user_meta(get_current_user_id(), self::USER_META_KEY, $user_meta);

        return new self($id);
    }

    /**
     * Get all collections for the current user.
     *
     * @return array List of DigiPed_Collection objects.
     */
    static function list()
    {
        $user_meta   = get_user_meta(get_current_user_id(), self::USER_META_KEY, true);
        $collections = [];

        foreach ($user_meta as $id => $attrs) {
            $collections[] = new self($id);
        }

        return $collections;
    }
}

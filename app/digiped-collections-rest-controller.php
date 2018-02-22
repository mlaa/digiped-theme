<?php
/**
 * REST Controller for managing DigiPed artifact collections.
 *
 * @package digiped-theme
 */

/**
 * Controller with routes to manage collections and add/remove artifacts.
 */
class DigiPed_Collections_REST_Controller extends WP_REST_Controller {

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = 'digiped/v' . $version;
		$base      = 'collections';

		// Create and list collections.
		register_rest_route(
			$namespace, "/$base", array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			)
		);

		// Read, edit, and delete existing collections by ID.
		register_rest_route(
			$namespace, "/$base/(?P<collection_id>[\w]+)", array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( true ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( false ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
			)
		);

		// Add and remove artifacts to/from existing collections.
		register_rest_route(
			$namespace, "/$base/(?P<collection_id>[\w]+)/artifact/(?P<artifact_id>[\d]+)", array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( false ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Create one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_item( $request ) {
		$item = $this->prepare_item_for_database( $request );

		$result = DigiPed_Collection::create( $item['name'] );

		if ( $result ) {
			return new WP_REST_Response( $result, 201 );
		}

		return new WP_Error( 'cant-create', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
	}

	/**
	 * Get the current user's collections.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$url_params = $request->get_url_params();

		if ( isset( $url_params['collection_id'] ) ) {
			$items = [ new DigiPed_Collection( $url_params['collection_id'] ) ];
		} else {
			$items = DigiPed_Collection::list();
		}

		$data = array();
		foreach ( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[]   = $this->prepare_response_for_collection( $itemdata );
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Update one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_item( $request ) {
		$url_params = $request->get_url_params();

		$collection = new DigiPed_Collection( $url_params['collection_id'] );

		if ( isset( $url_params['artifact_id'] ) ) {
			if ( 'PUT' === $request->get_method() ) {
				$result = $collection->add_artifact( $url_params['artifact_id'] );
			}
			if ( 'DELETE' === $request->get_method() ) {
				$result = $collection->remove_artifact( $url_params['artifact_id'] );
			}
		} else {
			// TODO handle updating name (etc?)
			return new WP_Error( 'not-supported', __( 'message', 'text-domain' ), array( 'status' => 400 ) );
		}

		if ( $result ) {
			return new WP_REST_Response( $result, 200 );
		}

		return new WP_Error( 'cant-update', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
	}

	/**
	 * Delete one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function delete_item( $request ) {
		$url_params = $request->get_url_params();

		$collection = new DigiPed_Collection( $url_params['collection_id'] );

		$result = $collection->destroy();

		if ( $result ) {
			return new WP_REST_Response( $result, 200 );
		}

		return new WP_Error( 'cant-delete', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return current_user_can( 'read' );
	}

	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return current_user_can( 'read' );
	}

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to delete a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}

	/**
	 * Prepare the item for create or update operation
	 *
	 * @param WP_REST_Request $request Request object
	 * @return WP_Error|object $prepared_item
	 */
	protected function prepare_item_for_database( $request ) {
		return $request->get_body_params();
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed           $item WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return [
			'id' => $item->id,
			'name' => $item->name,
			'artifacts' => $item->artifacts,
		];
	}
}

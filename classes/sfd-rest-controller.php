<?php

/**
 * REST API controller class.
 *
 * This is used to register REST API endpoints and define methods to query posts.
 *
 * @since   2.0.0
 * @package Search Filter Display
 */
class SFD_REST_Controller {

	public static $namespace = 'sfd/v1';
	public $config;

	public function __construct() {
		$this->config = new SFD_Config();
	}

	// Register our routes.
	public function register_routes() {
		register_rest_route( self::$namespace, '/(?P<name>[\w-]+)', array(
			array(
				'methods'   => 'POST',
				'callback'  => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
			),
		) );
	}

    /**
	 * Check permissions.
	 * 
	 * @since   2.0.0
	 *
	 * @param WP_REST_Request $request	Current request.
	 * 
	 * @return boolean     True.
	 */
	public function get_items_permissions_check( $request ) {
		// No permission check needed at this time
		return true;
	}

	/**
	 * Query for pods with filter params and output as a rest response.
	 * 
	 * @since   2.0.0
	 *
	 * @param WP_REST_Request $request     Current request.
	 * 
	 * @return WP_REST_Response     Post results.
	 */
	public function get_items( $request ) {

		// Get the values from the request and prepare to query
        $name = $request['name'];
		$data = $request->get_body_params();

		error_log(print_r($data, true));
        
		$params = [];
		$params['limit'] = 25;
		$where = [];
		$display = 'grid';
		$grid_template = '';
		$table_template = '';

		if (array_key_exists('display', $data)) {
			$display = $data['display'];
		}

		if (array_key_exists('grid', $data)) {
			$grid_template = $data['grid'];
		}

		if (array_key_exists('table', $data)) {
			$table_template = $data['table'];
		}

		if (array_key_exists('page', $data)) {
			$params['page'] = $data['page'];
		}

		if (array_key_exists('per-page', $data)) {
			$params['limit'] = $data['per-page'];
		}

		// Get associated query values for input types
		$queries = $this->config->get_queries($name);

		// Get orderby parameter
		// TODO: Ability to change between ASC and DESC
		$params['orderby'] = $this->config->get_order_config($name);

		// Taxonomy query builder
		if (array_key_exists('taxonomy', $data)) {
			foreach ($data['taxonomy'] as $taxonomy => $terms) {
				$where[] = "$taxonomy.term_id IN ('" . implode("' , '", $terms) . "')";
			}
		}

		// Year query builder
		if (array_key_exists('year', $data) && array_key_exists('year', $queries)) {
			$year = $data['year'];

			if ($year > 0) {
				$where[] = preg_replace('/{VALUE}/', $year, $queries['year']);
			}
		}

		// Conference query builder
		if (array_key_exists('conference', $data) && array_key_exists('conference', $queries)) {
			$conference = $data['conference'];

			if ($conference == 'siggraph') {
				$where[] = $queries['conference'] . ".post_title NOT LIKE '%Asia%'";
			}
			if ($conference == 'siggraph-asia') {
				$where[] = $queries['conference'] . ".post_title LIKE '%Asia%'";
			}
		}

		// Checkbox query builder
		if (array_key_exists('checkbox', $data) && array_key_exists('checkbox', $queries)) {
			foreach ($data['checkbox'] as $input) {
				if (array_key_exists($input, $queries['checkbox'])) {
					$where[] = $queries['checkbox'][$input];
				}
			}
		}

		// Build query string for 'where' parameter
		$params['where'] = implode(' AND ', $where);

		// Query
		$posts = pods($name);
		$posts = $posts->find($params);

		$results = [];

		$results['total'] = $posts->total_found();

		// Get template, default is grid
		$template = $grid_template;

		if ($display == 'table') {
			$template = $table_template;
		}

		// Get template output
		$output = $posts->template($template);
		$results['output'] = $output;

		// Return response to form-handler.js
		$response = new WP_REST_Response($results, 200);
		return $response;
	}
}

// Function to register our new routes from the controller.
function sfd_register_my_rest_routes() {
	$controller = new SFD_REST_Controller();
	$controller->register_routes();
}
add_action( 'rest_api_init', 'sfd_register_my_rest_routes' );
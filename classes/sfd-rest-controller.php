<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
				'methods'   => 'GET',
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
		$qstr = $request->get_query_params();

		if (array_key_exists('q', $qstr)) {
			$q = \LZCompressor\LZString::decompressFromEncodedURIComponent($qstr['q']);
			$q = urldecode($q);
			parse_str($q, $data);
		}
        
		$params = [];
		$params['limit'] = 25;
		$where = [];
		$display = 'grid';
		$grid_template = '';
		$table_template = '';
		$sort_option = '';
		$cache = false;
		$search = '';

		if (array_key_exists('display', $data)) {
			$display = $data['display'];
		}

		if (array_key_exists('grid', $data)) {
			$grid_template = $data['grid'];
		}

		if (array_key_exists('table', $data)) {
			$table_template = $data['table'];
		}

		if (array_key_exists('cache', $data)) {
			// Since 2.3.0: ability to cache is disabled for now. Remove in the future if definitively not needed.
			// $cache = filter_var($data['cache'], FILTER_VALIDATE_BOOLEAN);
			$cache = false;
		}

		if (array_key_exists('page', $data)) {
			$params['page'] = $data['page'];
		}

		if (array_key_exists('per-page', $data)) {
			$params['limit'] = $data['per-page'];
		}

		if (array_key_exists('sort', $data)) {
			$sort_option = $data['sort'];
		}

		if (array_key_exists('search', $data)) {
			$search = sanitize_text_field($data['search']);
		}

		// Get associated query values for input types
		$queries = $this->config->get_queries($name);

		// Get orderby parameter
		$params['orderby'] = $this->config->get_order_config($name, $sort_option);

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

		// Search title query builder
		if ( !empty($search) ) {
			$where[] = "post_title LIKE '%$search%'";
		}

		// Build query string for 'where' parameter
		$params['where'] = implode(' AND ', $where);

		// Get template, default is grid
		$template = $grid_template;

		if ($display == 'table') {
			$template = $table_template;
		}

		$cache_key = implode($params) . $template;

		// Query if results not cached yet or if cache is disabled from shortcode
		if ( false == ($results = pods_transient_get($cache_key)) || false == $cache ) {
			$posts = pods($name);
			$posts = $posts->find($params);

			$results = [];

			$results['total'] = $posts->total_found();

			// Get template output
			$output = $posts->template($template);
			$results['output'] = $output;

			if (true == $cache) {
				pods_transient_set( $cache_key, $results, DAY_IN_SECONDS );
			}
		}

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
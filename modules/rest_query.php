<?php

/* require 'helpers.php'; */


// REST api 'GET' method
add_filter('rest_api_init', function() {
  register_rest_route('/sfd/v1', '/archive_inventory/', array(
    'methods' => 'GET',
    'callback' => 'sfd_archive_inventory_callback',
  ));
});

// REST api 'POST' method
add_filter('rest_api_init', function() {
  register_rest_route('/sfd/v1', '/archive_inventory/', array(
    'methods' => 'POST',
    'callback' => 'sfd_inventory_filter_callback',
  ));
});


// POST callback for filtering in the SPA
function sfd_inventory_filter_callback( WP_REST_Request $request) {

  $param_list = $request->get_query_params();
  $data = file_get_contents('php://input');
  $json = json_decode($data, true);
  $sfd = sfd_pods($json);
  $response = new WP_REST_Response($sfd, 200);
  /* $data = array( 'some', 'response', 'data'); */
  /* var_dump($request); */
  $response->header('X-WP-Total', $sfd['total_found']);
  $response->header('X-WP-Totalpages', $sfd['pages']);
  /* $response->header('X-WP-Total', "100000"); */
  return $response;
}




/**
  * sfd_archive_inventory_callback( WP_REST_Request $request)
  * ---
  * Callback for GET Queries with the REST API. Handles the parameters and
  * hands off to the appropriate function. Sort of like directing traffic.
  * @param   {Named Array}      $id       - params from user or default
  * @returns {WP_REST_Response} $response - pods entries
  */

function sfd_archive_inventory_callback( WP_REST_Request $request) {
   // Sample url encode: q[filter]=inventory_main_type.term_id,12769
  $param_list = $request->get_query_params();
  $q = $param_list['q'] ?? null; // q for query

  if (isset($q['filter'])) {
    $filter = $q['filter'];
  }
  if (isset($q['childof'])) {
    $childof = $q['childof'];
  }
  if (isset($q['orderby'])) {
    $orderby = $q['orderby'];
  }
  if (isset($q['perpage'])) {
    $limit = $q['perpage'];
  }
  if (isset($q['page'])) {
    $page = $q['page'];
  }
  if (isset($q['order'])) {
    $order = $q['order'];
  }
  // setting $tax allows taxonomy and terms lookup to be queried up front
  if (isset($q['taxonomy'])) {
    $tax = $q['taxonomy'];
  }

  // taxonomy and terms query
  if (isset($tax)) {
    $pod_name = 'archive_inventory';
    $pod_params = array(
      'pod' => $pod_name, 
      'taxonomy' => $tax
    );

    $sfd = getTermHierarchy($pod_params);
    $response = new WP_REST_Response($sfd, 200);

  } else {
    // default query returning everything / view all
    
    $pod_name = 'archive_inventory';
    $pod_params = array(
      'pod' => $pod_name,
      'filter' => $filter ?? null,
      'orderby' => $orderby ?? null,
      'order' => $order ?? 'DESC',
      'perpage' => $limit ?? null,
      'page' => $page ?? '1');
    if (isset($childof)) {
      $pod_params['childof'] = $childof;
    }
    $sfd = sfd_pods($pod_params);
    $response = new WP_REST_Response($sfd, 200);
    $response->header('X-WP-Total', $sfd['total_found']);
    $response->header('X-WP-Totalpages', $sfd['pages']);
  }
  return $response;
}



/**
  * function sfd_pods($data)
  * ---
  * Function queries the DB for pods items matching the parameters given
  * @param   {Named Array}      $data    - params from user or default
  * @returns {WP_REST_Response} $results - pods entries
  */

function sfd_pods($data) {

  $pod_name = $data['pod'];
  $limit = $data['perpage'] ?? 10;
  $pagin = true;
  $current_page = $data['page'];
  $orderby = $data['orderby'] . " " . $data['order'];

  if (isset($data['filter'])) {
    $filter_where = [];

    foreach ($data['filter'] as $params) {
      $flt_tax = $params['taxonomy'] . '.term_id';
      $flt_arr = $params['terms'];
      $flt_terms = implode("', '", $flt_arr);

      $filter_where[] = "$flt_tax IN ('$flt_terms')";
    }

    $where_query = implode(' AND ', $filter_where);

    $params = array(
      'limit' => $limit, 
      'pagination' => $pagin,
      'page' => $current_page, 
      'orderby' => $orderby,
      'where' => $where_query,
    );
    /* var_dump($params); */
  } else {
    $params = array(
      'limit' => $limit, 
      'pagination' => $pagin, 
      'page' => $current_page, 
      'orderby' => $orderby
    );
  }
  /* if (isset($data['childof'])) { */
  /*   $childof = $data['childof']; */
  /*   $children = get_term_children($childof, 'inventory_main_type'); */
  /*   $child_ids = join("','", $children); */
  /*   $params['where'] = "inventory_item_main_type.term_id IN ('$child_ids')"; */
  /* } */

  $pod_obj = pods($pod_name);
  $pod_obj->find($params);

  $total_records = $pod_obj->total_found();
  $proc = [];

  if ($total_records != 0) {
    $total_pages = $total_records/$limit;
    $total_pages = (int) $total_pages;
  } else {
    $total_pages = 'unknown';
  }

  while ($pod_obj->fetch()) {
    $id = $pod_obj->field('id');
    $donated_by = $pod_obj->field('inventory_donated_by');
    $link = $pod_obj->field('permalink');
    $item = [
      "id" => $id,
      /* "all" => $pod_obj->export(), */
      "url" => esc_url($link), 
      "year" => $pod_obj->display('inventory_year'),
      "title" => $pod_obj->field('inventory_title'),
      "subtitle" => $pod_obj->display('inventory_subtitle'),
      "item_main_type" => $pod_obj->field('inventory_item_main_type.term_id'),
      "item_type_id" => $pod_obj->display('inventory_item_main_type.term_id'),
      "parent_type_id" => $pod_obj->display('inventory_item_main_type.parent'),
      "volume" => $pod_obj->display('inventory_volume'),
      "number" => $pod_obj->display('inventory_number'),
      "quantity" => $pod_obj->field('inventory_total_number_of_item'),
      "image" => $pod_obj->display('inventory_featured_image.guid'),
    ];

    // Try to get the highest level parent term, like Collectible or Publication
    $parent_term = '';

    if (!empty($item['item_type_id'])) {
      $current_term = $item['item_type_id'];

      while (!empty($current_term)) {
        $parent_term = $current_term;
        $current_term = wp_get_term_taxonomy_parent_id($current_term, 'inventory_main_type');
      }
    }
    $item['parent_type_id'] = $parent_term;

    array_push($proc, $item);
  }

  // this is the part that gets the terms and lookup 
  $tax_attr = [];
  $hierarchy = _get_term_hierarchy('inventory_main_type');
  $tax_attr['taxonomy'] = 'inventory_main_type';
  $tax_attr['lookup'] = true;
  $term_lookup = createLookup($tax_attr);
  $results = [
    "entries" => $proc,
    "total_found" => $total_records,
    "pages" => $total_pages,
    "hierarchy" => $hierarchy, 
    "lookup" => $term_lookup,
    "params" => $params];
  if (isset($children)) {
    $results['children'] = $children;
  }
  if (isset($childof)) {
    $results['parent'] = $childof;
  }
  return $results;
}





// self-explanatory helper functions
// ----------------------------------------------------
// provide an entry id to identify the taxonomy term_id
function getTermById($term_id) {
  $term = get_term($term_id);
  return $term;
}

// provide a term_id to identify the taxonomy
function getTaxByTermId($term_id) {
  $term = get_term($term_id);
  $taxonomy = get_taxonomy($term->taxonomy);
  return $taxonomy;
}

// provide the child id to get the direct parent entry
function getParentObj($child_id) {
  $child_term = get_term($child_id);
  $parent_id = $child_term->parent;
  $parent_obj = get_term($parent_id);
  return $parent_obj;
}

// provide child id to find parent entry name
function getParentName($child_id) {
  $parent_name = getParentObj($child_id)->name;
  return $parent_name;
}

// recursive traversal up heirarchy
function getHierarchyById($id, &$store) {
  $term = get_term($id);
  $obj = array( 
    'name' => $term->name,
    'term_id' => $term->term_id,
    'parent_id' => $term->parent);
  $pid = $obj['parent_id'];
  array_push($store, $obj);
  
  if ($pid === 0) {
    return $store;
  } else {
    getHierarchyById($pid, $store);
  }
}

// query whether child is child of parent
function is_ancestor($child_term_id, $anc_term_id) {
  $term = get_term($child_term_id);
  if ($term.parent === $anc_term_id) {
    return true;
  } elseif (!($term.parent === 0)) {
    is_ancestor($term.parent, $anc_term_id);   
  } else {
    return false;
  }
}




// get all terms from a specific taxonomy
function getAllTerms($data) {
  $results = [];
  /* var_dump($data); */
  $terms = get_terms(array(
      'taxonomy' => $data['taxonomy'],
      'hide_empty' => false));
  // taxonomy search
  if (isset($data['fields'])) {
    // more than one supplied field
    $all_fields = $data['fields'];
    /* var_dump($all_fields); */
    $i = 0;
    if (substr_count($all_fields, ",") > 0) {
      $fields_arr = explode(",", $all_fields);
      /* var_dump($fields_arr); */
      foreach ($terms as $term) {
        $temp_arr = [];
        foreach ($fields_arr as $field) {
          $temp_arr[$field] = $term->$field;
          /* array_push($temp_arr, $temp); */
        }
        array_push($results, $temp_arr);
        } // end of condition-met loop
    } else {
      // only one supplied field
      $field = $all_fields;
      foreach ($terms as $term_item) {
        $results[$i] = $term_item->$field;
        $i = $i + 1;
      }
    } // end of else loop
  } else {
    // all fields
    $results = $terms;
  }
  /* return getTaxByTermId(17417); */
  return $results;
}


// process WP_REST_Response to Array
function resultsProcToArr($data) {
  $newarr = [];
  foreach($data as $record) {
    $newarr[] = $record->to_array();
  }
  return $newarr;
  }


// process returned Array data into bare-bones 
function stripData($data) {
  $newarr = [];
  foreach($data as $record) {
    $new_record = array(
      'name' => $record['name'],
      'term_id' => $record['term_id'],
      'slug' => $record['slug'],
      'parent' => $record['parent'] );
    $newarr[] = $new_record;
  }
  return $newarr;
}


// returns a hierarchical search object that conveys item lineage
function getSearchObject($data) {
  $raw = getAllTerms($data);
  // NOTE: $raw array of WP_TERM objects
    
  $term_array = resultsProcToArr($raw);
  $cleaned = stripData($term_array); 
  $top_level = [];
  
  $root_parents = getChildren($cleaned, 0);
  foreach($root_parents as &$root_parent) {
    $children = getChildren($cleaned, $root_parent['term_id']);
    foreach($children as &$child) {
      $grandchildren = getChildren($cleaned, $child['term_id']);
      foreach($grandchildren as &$grandchild) {
        $greatgrandchildren = getChildren($cleaned, $grandchild['term_id']);
        $grandchild['children'] = $greatgrandchildren;
      }
      $child['children'] = $grandchildren;
    }
    $root_parent['children'] = $children;
  }

  return $root_parents;
  /* return $cleaned; */
}
  
// returns all children of a specified parent id
function getChildren($all_items, $parent_id) {
  $child_arr = [];
  foreach($all_items as $item) {
    if ($item['parent'] == $parent_id) {
      $child_arr[] = $item;
    } 
  }
  return $child_arr;
}


// pulls alot of unconstrained pods information 
function getAllData($data) {
  $pod_name = $data['pod'];
  $pod_obj = pods($pod_name);
  if (isset($data['id'])) {
    $id = $data['id'];
    $condition = "id={$id}";
  $params = array('limit' => 1, 'pagination' => true, 'page' => 1, 'where' => $condition);
  }
  $pod_obj->find($params);
  $result = [];
  while ($pod_obj->fetch()) {  
    array_push($result, $pod_obj->export());
  }
  return $result;
}


/**
 * This function build a map of id codes to names.
 * Expects an array where keys 'taxonomy' is set to the item,
 * and 'lookup' is set to true.
 *
 * @param array $atts Arguments passed from main call.
 * @return array Returns a key value array mapping of term ids to term names.
 *
 */
function createLookup($atts) {
  $raw = getAllTerms($atts);
  $term_array = resultsProcToArr($raw);
  $newarr = [];
  foreach($term_array as $record) {
    $newarr[$record['term_id']] = $record['name'];
  }
  return $newarr;
}



function sfd_childof($data) {
  $pod_name = $data['pod'];
  $limit = $data['limit'] ?? 10;
  if (isset($data['childof'])) {
    $childof = $data['childof'];
    $children = get_term_children($childof, 'inventory_main_type');
    $child_ids = join("','", $children);
    $params = array('limit' => $limit, 'where' => "inventory_item_main_type.term_id IN ('$child_ids')");
  }
  $pod_obj = pods($pod_name);
  $pod_obj->find($params);
  $proc = [];

  while ($pod_obj->fetch()) {
    $id = $pod_obj->field('id');
    $term_id = $pod_obj->display('inventory_main_type.term_id');
    $item = [
      "id" => $id,
      "term_id" => $term_id,
      "title" => $pod_obj->field('inventory_title')
    ];

    array_push($proc, $item);
  }
  $results = [
    "params" => $params,
    "entries" => $proc
  ];
  return $results;
}

/**
  * termTree
  * ---
  * Function to get terms from a taxonomy and create a hierarchical-structured array.
  * 
  * @param string  $taxonomy  Name of taxonomy.
  * 
  * @return array             Returns an array of terms.
  *
**/
function termTree($taxonomy) {
  // Get all terms in unstructured array
  $terms = get_terms([
    'taxonomy' => $taxonomy,
    'hide_empty' => false,
    'fields' => 'all',
  ]);

  // Put terms in array with parent id as key value
  $parents = [];
  foreach ($terms as $term) {
    $term_fields = [
      'name' => $term->name,
      'term_id' => $term->term_id,
      'slug' => $term->slug,
      'parent' => $term->parent,
    ];
    
    $parents[$term->parent][] = $term_fields;
  }

  // Populate structured array using $parents as a reference
  $terms_list = [];
  return termChildren($parents, $terms_list);
}

/**
  * termChildren
  * ---
  * Recursive function used by termTree() to populate an array with terms in a hierarchical structure.
  * 
  * @param array  $parents   Flattened array created by termTree used to make a new structured array.
  * @param array  $children  Structured array to use for output.
  * @param int    $root      Key value to use for $parents array.
  *
  * @return array            Returns an array of structured terms ($children).
  *
**/
function termChildren($parents, &$children, $root = 0) {
  // Return empty array if parent ID is not a key in reference array
  if (!array_key_exists($root, $parents)) {
    return [];
  }

  // Go through reference array for each parent and populate structured array, recursively calling termChildren for children terms
  $i = 0;
  foreach ($parents[$root] as $term) {
    $children[$i] = [
      'name' => $term['name'],
      'term_id' => $term['term_id'],
      'slug' => $term['slug'],
      'parent' => $term['parent'],
      'children' => termChildren($parents, $children[$i], $term['term_id'])
    ];

    ++$i;
  }
  return $children;
}


function getTermHierarchy($data) {
  $result = [];
  /* var_dump($data); */
  if (isset($data['taxonomy'])) {
    $tax_string = $data['taxonomy'];
    // split input string into array
    $tax_arr = explode(",", $tax_string);
    
    // check for array
    if (is_array($tax_arr)) {

      // loop through the input taxonomy strings in the array
      foreach ($tax_arr as $tax) {
        // attempt to pull hierarchy (object of sub objects)
        $h = termTree($tax);

        // if returned data is 0 then its not hierarchical so we need terms instead
        if (sizeof($h)=== 0) {
          // pull terms (array of WP objects)...
          $types = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
          $term_arr = [];
          foreach ($types as $type) {
            // sanitize out each entry
            $type_entry = array('term_id' => $type->term_id, 'name' => $type->name, 'slug' => $type->slug);
            $term_arr[] = $type_entry;
          }
          $result[$tax] = $term_arr;
        } else {
          $result[$tax] = $h;
        }
      }
    }
  }
  $tax_attr = [];
  $tax_attr['taxonomy'] = 'inventory_main_type';
  $tax_attr['lookup'] = true;
  $term_lookup = createLookup($tax_attr);
  $term_srch = getSearchObject($tax_attr);
  $result['lookup'] = $term_lookup;
  $result['search'] = $term_srch;
  return $result;
}





add_filter( 'register_post_type_args', 'custom_inventory_args', 10, 2);
function custom_inventory_args ( $args, $post_type ) {
  if ('archive_inventory' === $post_type ) {
    $args['show_in_rest'] = true;
  }
  return $args;
}


// registers the filter hook
/* add_filter('rest_archive_inventory_query', 'filter_posts_by_field', 10, 2); */

/* function filter_posts_by_field( $args, $request ) { */
/*   if ( ! isset( $request['year'] ) ) { */
/*     return $args; */
/*   } */
/* /*   // http://localhost/wp-json/wp/v2/archive_inventory/?year=1990   works */
/*   $item_type_value = sanitize_)text_field( $request['year'] ); */
/*   $item_type_meta_query = array('key' => 'inventory_year', 'value' => $item_type_value); */
/*   if ( isset( $args['meta_query'] ) ) { */
/*     $args['meta_query']['relation'] = 'AND'; */
/*     $args['meta_query'][] = $item_type_meta_query; */
/*   } else { */
/*     $args['meta_query'] = array(); */
/*     $args['meta_query'][] = $item_type_meta_query; */
/*   } */
/**/
/*   $jj = var_dump($args); */
/*   write_log($jj); */
/*   return $args; */
/* } */



/* add_action('rest_api_init', 'rest_api_filter_add_filters'); */
/* function rest_api_filter_add_filters() { */
/*   foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) { */
/* 		add_filter('rest_' . $post_type->name . '_query', 'rest_api_filter_add_filter_param', 10, 2); */
/*   } */
/* } */




// NOTE: Implementation for just archive_inventory

add_filter('rest_archive_inventory_collection_params', function($params) {
  $fields = ["year","inventory_year","inventory_total_number_of_item","year"];
  foreach ($fields as $key => $value) {
    $params['orderby']['enum'][] = $value;
    }
    return $params;
    },
  10, 1 );



add_filter('rest_archive_inventory_query', function($args, $request) {
    /* $fields = ["inventory_year","inventory_total_number_of_item"]; */
    $f = $request->get_param( 'orderby' );
    if ( isset( $f ) && 'inventory_year' === $f ) {
      /* $args['meta_key'] = 'inventory_year'; */
      /* $args['orderby'] = 'meta_value_num'; */
    /* } */
  
      /* $args = array( */
      /*   'meta_query' => array( */
      /*     'relation' => 'AND', */
      /*     'filter' => array( */
      /*         'key' => 'inventory_item_main_type', */
      /*         'value' => '17270', */
      /*         'compare' => '=', */
      /*         'type' => 'META' */
      /*     ), */
      /*     'year_clause' => array( */
      /*         'key' => 'inventory_year', */
      /*         'compare' => 'EXISTS' */
      /*       ) */
      /*   ), */
      /*   'orderby' => array( */
      /*     'year_clause' => 'DESC', */
      /*   ), */
      /* ); */
    }
    /* $jj = var_dump($args); */
    /* write_log($jj); */
    return $args;
  }, 10, 2 );



/* add_action('rest_api_init', 'rest_api_custom_filters'); */
function rest_api_custom_filters() {
  add_filter('rest_archive_inventory_query', 'rest_archive_inventory_filter_param', 10, 2);
}


function rest_archive_inventory_filter_param($args, $request) {
  
  /* $fields = ["inventory_title","inventory_year","inventory_total_number_of_item"]; */
  /* $order_by = $request->get_param( 'orderby' ); */
  /* if ( isset( $order_by ) && in_array( $order_by, $fields ) ) { */
  /*   $args['meta_key'] = $order_by; */
  /*   $args['orderby'] = 'meta_value_num'; */
  /* } */
  if (empty($request['filter']) || ! is_array($request['filter'])) {
	  if (empty($request['orderby']) || ! is_array($request['orderby'])) {
	     return $args;
	   }
	}
  
  $filter = $request['filter'];
	if (isset($filter['posts_per_page']) && ((int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100)) {
		$args['posts_per_page'] = $filter['posts_per_page'];
  }
  global $wp;

  // allows us to modify the var array for query changes
  $vars = apply_filters('rest_query_vars', $wp->public_query_vars);

  function allow_meta_query($valid_vars) {
    $valid_vars = array_merge($valid_vars, array('meta_query', 'meta_key', 'meta_value', 'meta_compare'));
    return $valid_vars;
  }
  
  $vars = allow_meta_query($vars);

	foreach ($vars as $var) {
		if (isset($filter[$var])) {
		  $args[$var] = $filter[$var];
		}
  }

  /* $args['meta_key'] = $order_by; */
  /* $args['orderby'] = 'meta_value_num'; */

  /* $jj = var_dump($args); */
  /* write_log($jj); */

  /* hardwiring */
  /* $args['orderby'] = 'inventory_year'; */
  return $args;
}
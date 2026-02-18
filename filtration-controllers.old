<?php
namespace OPChild\Filtration;

function entry_filtration_buttons_template($atts=array()) {
	// Atts array allows user to pass in values to the shortcode
	// Takes in text changes, as well as the actual where clause of what is being looked at
	ob_start();
	get_template_part('src/filtration_new/templates/filter_entries_by_buttons_template', null, $atts);
	return ob_get_clean();
}

add_shortcode('entry_filtration_buttons', __NAMESPACE__.'\entry_filtration_buttons_template');

function formatting() {
    wp_enqueue_script('jquery');
    $table_css_rel = '/src/filtration_feb2026/css/tables.css';
    $css_rel = '/src/filtration_feb2026/css/simpui.css';
    $js_rel  = '/src/filtration_feb2026/js/simpui.js';
    $table_css_path = get_stylesheet_directory() . $table_css_rel;
    $css_path = get_stylesheet_directory() . $css_rel;
    $js_path  = get_stylesheet_directory() . $js_rel;
    $table_css_ver = file_exists($table_css_path) ? filemtime($table_css_path) : time();
    $css_ver = file_exists($css_path) ? filemtime($css_path) : time();
    $js_ver  = file_exists($js_path)  ? filemtime($js_path)  : time();

    wp_enqueue_style(
        'filtration-tables-style',
        get_stylesheet_directory_uri() . $table_css_rel . '?v=' . $table_css_ver,
        [],
        null
    );

    wp_enqueue_style(
        'filtration-buttons-style-v1',
        get_stylesheet_directory_uri() . $css_rel . '?v=' . $css_ver,
        [],
        null
    );

    wp_enqueue_script(
        'filtration-buttons-v1',
        get_stylesheet_directory_uri() . $js_rel . '?v=' . $js_ver,
        ['jquery'],
        null,
        true
    );
}

add_action('wp_enqueue_scripts', __NAMESPACE__.'\formatting');


function child_theme_url() {
  $proc = esc_url( get_template_directory_uri());
  $pattern = '/optimizer_pro/i';
  $result = preg_replace($pattern, 'optimizer_pro-child', $proc);
  return $result;
}

function child_theme_dir() {
  $proc = esc_url( get_template_directory());
  $pattern = '/optimizer_pro/i';
  $result = preg_replace($pattern, 'optimizer_pro-child', $proc);
  return $result;
}

function filter_ui ($atts) {
  $defaults = shortcode_atts(array(
    'querytype' => 'Inventories',
    'returntype' => 'Collectibles',
  ), $atts, 'button');

  $filter_url = child_theme_url() . "/src/filtration_feb2026/icons/filter-funnel.svg";
  $grid_url = child_theme_url() . "/src/filtration_feb2026/icons/layout-grid.svg";
  $list_url = child_theme_url() . "/src/filtration_feb2026/icons/layout-list.svg";

  $button_template = '
    <button class="simpui-btn secondary xs" style="padding: 2px; margin: 0px;" id="iconPrimaryBtn">
      <img style="filter: invert(100%); margin: 0px" src="xxxx"/ >
    </button>';
  
  $search_component = '
      <div class="simpui-input-wrapper">
        <svg class="simpui-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20 20l-4.05-4.05m0 0a7 7 0 1 0-9.9-9.9a7 7 0 0 0 9.9 9.9"/></svg>
        <input type="text" id="search" style="height: 28px; padding-left: 30px; padding-top: 2px; padding-bottom: 2px;" class="simpui-input" placeholder="Search..." required />
      </div>
      <div class="simpui-error">Search keyword is required</div>';

  $list_button = preg_replace('/xxxx/', $list_url, $button_template);
  $grid_button = preg_replace('/xxxx/', $grid_url, $button_template);
  $filter_button = preg_replace('/xxxx/', $filter_url, $button_template);
  
  $dropdown_url = child_theme_dir() . "/src/filtration_feb2026/components/rpp.html";
  $rpp = file_get_contents("$dropdown_url");
  
  $container = '
    <section id="ui" data-page="1" data-filter="none" style="width: 100%">
      <div id="toolbar" style="display: flex; flex-direction: row; border-bottom: 1px solid #bbbbbb; margin-top: 15px; padding-bottom: 3px;">
        <div id="filter" style="width: 30px">AAAA</div>
        <div class="simpui-form-group" style="margin-left: 4px; width: 300px; height: 30px;">BBBB</div>
        <div id="spacing" style="flex: grow;"></div>
        <div id="results-per-page" style="width: 100px; margin-left: auto; margin-right: 0px;">CCCC</div>
        <div id="display" style="width: auto; margin-left: 6px; margin-right: 0px;">DDDD</div>
      </div>
    </section>';

  $c1 = preg_replace("/AAAA/", "$filter_button", $container);
  $c2 = preg_replace("/BBBB/", "$search_component", $c1);
  $c3 = preg_replace("/CCCC/", "$rpp", $c2);
  $c4 = preg_replace("/DDDD/", "$list_button$grid_button", $c3);
  $table_url = child_theme_dir() . "/src/filtration_feb2026/components/table.html";
  $table = file_get_contents("$table_url");

  $pod_obj = pods('archive_inventory');
  $params = array('limit' => 100, 'page' => 1, 'orderby' => 'inventory_year DESC');
  $pod_obj->find($params);

  $results = '<div style="display: flex; flex-direction: column;">';
  while ($pod_obj->fetch()){
    $results = $results . "<span>" . $pod_obj->field('inventory_title') . "</span>";
    /* $results = $results . "<span>" . $pod_obj->display('*') . "</span>"; */
  }
  $results = $results . "</div>";
  $r2 = pods_serial_comma($pod_obj->fields());

  return $c4 . $results;
}

function register_filter() {
  add_shortcode('filt_k', __NAMESPACE__.'\filter_ui');
}

add_action('init', __NAMESPACE__.'\register_filter');


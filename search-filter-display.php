<?php
/**
 * Search Filter Display
 *
 * @author            Ken Stewart
 * @license           MIT
 *
 * @wordpress-plugin
 * Plugin Name:       Search Filter Display
 * Description:       Heavily customizable search and filtration options per page via WP shortcodes. 
 * Version:           2.1.1
 * Author:            Ken Stewart
 * Author URI:        https:/kenstewart.ca
 * Text Domain:       search-filter-display
 * License:           MIT
 * License URI:       https://mit-license.org/
 * Requires Plugins:  pods
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Current plugin version
define( 'SFD_VERSION', '2.1.1' );

define( 'SFD_URL', plugin_dir_url( __FILE__ ) );
define( 'SFD_DIR', plugin_dir_path( __FILE__ ) );

require_once( SFD_DIR . 'classes/sfd-init.php' );

$plugin = new SFD_Init();
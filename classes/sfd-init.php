<?php

/**
 * Plugin initialization class.
 *
 * This is used to load dependencies across Wordpress.
 *
 * @since   2.0.0
 * @package Search Filter Display
 */
class SFD_Init {

    private $version;
    private $plugin_name;

    /**
	 * Constructor
     * 
     * Define plugin variables and load dependencies.
	 *
	 * @since   2.0.0
	 */
    public function __construct() {
        $this->version = SFD_VERSION;
		$this->plugin_name = 'sfd';

		$this->load_dependencies();
	}

    /**
	 * Load the required initial dependencies for this plugin.
	 *
	 * @since    2.0.0
	 */
    private function load_dependencies() {
        require_once( SFD_DIR . 'classes/sfd-rest-controller.php' ); // Class responsible for REST API    
        require_once( SFD_DIR . 'includes/shortcode.php' ); // Shortcode function and registration
        require_once( SFD_DIR . 'classes/sfd-loader.php' ); // Loader class to be used by shortcode
        require_once( SFD_DIR . 'classes/sfd-config.php' ); // Config for form
    }

    /**
	 * The name of the plugin used to uniquely identify it within Wordpress.
	 *
	 * @since   2.0.0
     * 
	 * @return string   The name of the plugin.
	 */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
	 * Retrieve the version number of the plugin.
	 *
	 * @since   2.0.0
     * 
	 * @return string   The version number of the plugin.
	 */
    public function get_version() {
        return $this->version;
    }
}
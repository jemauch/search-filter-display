<?php

/**
 * Shortcode loader class.
 *
 * This is used to load anything that only needs to be loaded when the shortcode is used.
 *
 * @since   2.0.0
 * @package Search Filter Display
 */
class SFD_Loader {

    private $pod;               // Pod slug.
    private $endpoint;          // REST API endpoint.
    private $config;            // SFD_Config.
    private $grid_template;     // Name of template used for grid.
    private $table_template;    // Name of template used for table.
    private $display;           // Default display at first load.

    /**
     * Constructor
     * 
     * Loads dependencies and scripts that are only needed when the shortcode is used.
     * (Prevents loading on pages unrelated to filter.)
     *
     * @since   2.0.0
     *
     * @param string $pod               The slug of the pod to be filtered.
     * @param string $grid_template     Pod template name for grid.
     * @param string $table_template    Pod template name for table.
     */
    public function __construct($pod, $grid_template, $table_template, $display) {
		$this->pod = $pod;
        $this->endpoint = get_site_url() . '/wp-json/' . SFD_REST_Controller::$namespace . '/' . $this->pod;

        $this->grid_template = $grid_template;
        $this->table_template = $table_template;
        $this->display = $display;

        $this->load_dependencies();
        $this->enqueue_scripts();

        $this->config = new SFD_Config();
	}

    /**
	 * Load the required dependencies when the shortcode is used.
	 *
	 * @since    2.0.0
	 */
    private function load_dependencies() {
        require_once( SFD_DIR . 'classes/sfd-form.php' );
        require_once( SFD_DIR . 'classes/sfd-input.php' );
        require_once( SFD_DIR . 'includes/helpers.php' );
    }

    /**
	 * Enqueue scripts and stylesheets when the shortcode is used.
	 *
	 * @since    2.0.0
	 */
    private function enqueue_scripts() {
        // state.js
        wp_enqueue_script(
            'sfd-state',
            SFD_URL . '/static/js/state.js',
            ['jquery'],
            filemtime(SFD_DIR . '/static/js/state.js')
        );

        // disclosure.js
        wp_enqueue_script(
            'sfd-disclosure',
            SFD_URL . '/static/js/disclosure.js',
            array(),
            filemtime(SFD_DIR . '/static/js/disclosure.js')
        );

        // checkboxes.js
        wp_enqueue_script(
            'sfd-checkboxes',
            SFD_URL . '/static/js/checkboxes.js',
            ['jquery'],
            filemtime(SFD_DIR . '/static/js/checkboxes.js')
        );

        // form-handler.js
        wp_enqueue_script(
            'sfd-filter-handler',
            SFD_URL . '/static/js/form-handler.js',
            ['jquery'],
            filemtime(SFD_DIR . '/static/js/form-handler.js')
        );
        wp_add_inline_script(
            'sfd-filter-handler',
            "const sfd = {
                endpoint: '$this->endpoint',
                grid: '$this->grid_template',
                table: '$this->table_template',
                default_display: '$this->display',
            };",
            'before'
	    );

        // styles.css
        wp_enqueue_style(
            'sfd-styles',
            SFD_URL . '/static/css/styles.css',
            array(),
            filemtime(SFD_DIR . '/static/css/styles.css')
        );

        // tables.css
        wp_enqueue_style(
            'sfd-tables',
            SFD_URL . '/static/css/tables.css',
            array(),
            filemtime(SFD_DIR . '/static/css/tables.css')
        );
    }

    /**
	 * Retrieve the REST API endpoint.
	 *
	 * @since    2.0.0
     * 
     * @return string    The REST API endpoint.
	 */
    public function get_endpoint() {
        return $this->endpoint;
    }

    /**
	 * Retrieve filter config for the requested pod.
	 *
	 * @since    2.0.0
     * 
     * @return array    Filter settings if key exists in config, empty if not.
	 */
    public function get_config() {
        return $this->config->get_config($this->pod);
    }

    /**
	 * Build HTML.
	 *
	 * @since    2.0.0
     * 
     * @return array    HTML page output.
	 */
    public function build() {
        $filterConfig = $this->config->get_config($this->pod);
        $builder = new SFD_Form($filterConfig);

        return $builder->build();
    }
}
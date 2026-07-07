<?php

/**
 * Filter form class.
 *
 * This is used to build the filter page.
 *
 * @since   2.0.0
 * @package Search Filter Display
 */
class SFD_Form {

    private $filters;   // Array of SFD_Input objects.
    private $display;   // Default display from shortcode.

    /**
	 * Constructor
     * 
     * @since   2.0.0
     * 
     * @param array $filters    Array of filter settings defined by SFD_Config.
	 */
    public function __construct($filters = [], $display = '') {

        foreach ($filters as $input) {
            if ($input['type'] == 'taxonomy') {
                $this->filters[] = new SFD_Input_Taxonomy($input['legend'], $input['slug']);
            }

            if ($input['type'] == 'year') {
                $this->filters[] = new SFD_Input_Year($input['legend'], $input['slug']);
            }

            if ($input['type'] == 'conference') {
                $this->filters[] = new SFD_Input_Conference($input['legend'], $input['slug']);
            }

            if ($input['type'] == 'checkbox') {
                $this->filters[] = new SFD_Input_Checkbox($input['legend'], $input['slug'], $input['options']);
            }
        }

        $this->display = $display;
    }

    /**
	 * Function to build and return HTML of filter page in a string.
	 *
	 * @since    2.0.0
     * 
     * @return string   HTML output.
	 */
    public function build() {
        // Filter toolbar + form
        $form = "<div class='filter-toolbar'><div class='sfd-filter'><button class='sfd-filter__button modal-toggle sfd-button' aria-expanded='false' aria-controls='sfd-filter-card'>" . sfd_get_icon('filter-funnel') . "Filters</button><search class='sfd-filter__card' id='sfd-filter-card' hidden><form id='filters' class='sfd-filter-form'>
                    <div class='sfd-filter-header'><h2>Filters</h2><button class='icon-button modal-close' type='button'><span class='sr-only'>Close</span>" . sfd_get_icon('gravity-ui--xmark') . "</button></div>";

        // Build HTML for each form input
        foreach ($this->filters as $input) {
            $form .= $input->build();
        }
        // Hidden form input elements
        $form .= "<input type='hidden' id='page' name='page' value='1'>";
        $form .= "<input type='hidden' id='display' name='display' value='" . $this->display . "'>";
        // Form buttons
        $form .= "<div class='sfd-filter-actions'><input class='sfd-filter-actions__reset sfd-button' type='reset' value='Reset'><input class='sfd-filter-actions__submit sfd-button modal-close' type='submit' value='Apply'></div></form></search></div>";

        // Grid and table view buttons
        $form .= "<div class='sfd-view-options'>" . sfd_get_html('per-page') . "<div class='view__output'><button id='table-layout' class='sfd-button'>" . sfd_get_icon('layout-list') . "</button><button id='grid-layout' class='sfd-button'>" . sfd_get_icon('layout-grid') . "</button></div></div></div>";

        // Results
        $form .= "<section>
                    <div id='loader' class='loader-wrapper'><div class='loader-wrapper__loader'>" . sfd_get_icon('svg-spinners--bars-rotate-fade') . "<span>Loading...</span></div></div>
                    <div class='applied-filters'><h3>Applied Filters: </h3><p id='applied-filters__none' class='applied-filters__total'>(none)</p><details id='applied-filters__selected' open><summary class='applied-filters__total sfd-button--link'><span id='applied-filters__num'></span> Total</summary><ul id='applied-filters-list' class='applied-filters-list'></ul></details></div>
                    <h2>Results:</h2><output id='total'></output>";
        $form .= '<div id="output-view"></div></section>';

        // Pagination
        $form .= "<nav class='filter-nav' aria-label='pagination'><ul class='filter-pagination list'>
        <li class='filter-pagination__item'><button class='filter-pagination-button sfd-button' id='pagination-first'>" . sfd_get_icon('push-chevron-left') . "</button></li>
        <li class='filter-pagination__item'><button class='filter-pagination-button sfd-button' id='pagination-previous'>" . sfd_get_icon('chevron-left') . "</button></li>
        <li class='filter-pagination__item'><span id='pagination-page-counter'> 1 / 1 </span></li>
        <li class='filter-pagination__item'><button class='filter-pagination-button sfd-button' id='pagination-next'>" . sfd_get_icon('chevron-right') . "</button></li>
        <li class='filter-pagination__item'><button class='filter-pagination-button sfd-button' id='pagination-last'>" . sfd_get_icon('push-chevron-right') . "</button></li>
        </ul></nav>";

        return $form;
    }
}
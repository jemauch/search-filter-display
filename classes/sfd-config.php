<?php

/**
 * Plugin config class.
 *
 * This is used to build filter settings for each pod.
 *
 * @since   2.0.0
 * @package Search Filter Display
 */
class SFD_Config {

    private $config = [];
    private $orderConfig = [];

    /**
	 * Constructor
     * 
     * Edit the array to manually update config settings.
     * TODO: This should be configurable through an admin page.
	 *
	 * @since   2.0.0
	 */
    public function __construct() {
        $this->config = [
            'archive_inventory' => [
                'Item Type' => [
                    'legend' => 'Item Type', 
                    'slug' => 'inventory_main_type', 
                    'type' => 'taxonomy',
                ],
                'Subject' => [
                    'legend' => 'Subject', 
                    'slug' => 'inventory_item_origin', 
                    'type' => 'taxonomy',
                ],
                'Media' => [
                    'legend' => 'Media', 
                    'slug' => 'media_type', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "inventory_year.meta_value IN ('{VALUE}')",
                ],
                'Quantity' => [
                    'legend' => 'Quantity', 
                    'slug' => 'quantity', 
                    'type' => 'checkbox',
                    'options' => [
                        1 => [
                            'label' => 'Show missing and needed items only',
                            'value' => 'missing',
                            'query' => "inventory_total_number_of_item.meta_value IN ('1', '0') AND NOT (inventory_digital_exclusive.meta_value = '1' AND inventory_digital.meta_value = '1') AND inventory_not_needed.meta_value != '1'",
                            'checked' => false,
                        ],
                    ],
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'inventory_conference',
                ],
            ],
            'award' => [
                'Award Type' => [
                    'legend' => 'Award Type', 
                    'slug' => 'award_type', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "award_year.meta_value IN ('{VALUE}')",
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'award_conference',
                ],
            ],
            'experience' => [
                'Experience Type' => [
                    'legend' => 'Experience Type', 
                    'slug' => 'experience_type', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "experience_conference.conference_year.meta_value IN ('{VALUE}')",
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'experience_conference',
                ],
            ],
            'learning' => [
                'Learning Type' => [
                    'legend' => 'Learning Type', 
                    'slug' => 'learning_type', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "learning_conference.conference_year.meta_value IN ('{VALUE}')",
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'learning_conference',
                ],
            ],
            'animation_video_pod' => [
                'Animation Type' => [
                    'legend' => 'Animation Type', 
                    'slug' => 'animation_video_event_type', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "animation_video_conference_year.meta_value IN ('{VALUE}')",
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'animation_video_conference',
                ],
            ],
            'artwork' => [
                'Artwork Type' => [
                    'legend' => 'Artwork Type', 
                    'slug' => 'artwork_category', 
                    'type' => 'taxonomy',
                ],
                'Year' => [
                    'legend' => 'Year', 
                    'slug' => 'year', 
                    'type' => 'year',
                    'query' => "artwork_exhibition.exhibition_year.meta_value IN ('{VALUE}')",
                ],
                'Conference' => [
                    'legend' => 'Conference', 
                    'slug' => 'conference', 
                    'type' => 'conference',
                    'query' => 'artwork_exhibition.exhibition_conference',
                ],
            ],
        ];

        $this->orderConfig = [
            'archive_inventory' => [
                'yearASC' => 'inventory_year.meta_value ASC, inventory_volume.meta_value ASC, CAST(inventory_number.meta_value AS int) ASC, inventory_number.meta_value ASC',
                'yearDESC' => 'inventory_year.meta_value DESC, inventory_volume.meta_value DESC, CAST(inventory_number.meta_value AS int) DESC, inventory_number.meta_value DESC',
                'titleASC' => 'inventory_title.meta_value ASC',
                'titleDESC' => 'inventory_title.meta_value DESC',
            ],
            'award' => [
                'yearASC' => 'award_year ASC',
                'yearDESC' => 'award_year DESC',
                'titleASC' => 'award_category ASC',
                'titleDESC' => 'award_category DESC',
            ],
            'experience' => [
                'yearASC' => 'experience_conference.conference_year.meta_value ASC',
                'yearDESC' => 'experience_conference.conference_year.meta_value DESC',
                'titleASC' => 'experience_title ASC',
                'titleDESC' => 'experience_title DESC',
            ],
            'learning' => [
                'yearASC' => 'learning_conference.conference_year.meta_value ASC',
                'yearDESC' => 'learning_conference.conference_year.meta_value DESC',
                'titleASC' => 'learning_title ASC',
                'titleDESC' => 'learning_title DESC',
            ],
            'animation_video_pod' => [
                'yearASC' => 'animation_video_conference_year ASC',
                'yearDESC' => 'animation_video_conference_year DESC',
                'titleASC' => 'animation_video_title ASC',
                'titleDESC' => 'animation_video_title DESC',
            ],
            'artwork' => [
                'yearASC' => 'artwork_exhibition.exhibition_year.meta_value ASC',
                'yearDESC' => 'artwork_exhibition.exhibition_year.meta_value DESC',
                'titleASC' => 'artwork_title ASC',
                'titleDESC' => 'artwork_title DESC',
            ],
        ];
    }

    /**
	 * Retrieve filter config for the requested pod.
	 *
	 * @since   2.0.0
     * 
     * @return array    Filter settings if key exists in config, empty if not.
	 */
    public function get_config($pod = '') {
        if (array_key_exists($pod, $this->config)) {
            return $this->config[$pod];
        }
        
        return [];
    }

    /**
	 * Retrieve queries associated with input.
	 *
	 * @since   2.0.0
     * 
     * @return array    List of input slugs with associated queries.
	 */
    public function get_queries($pod = '') {
        $queries = [];

        if (!array_key_exists($pod, $this->config)) {
            return [];
        }

        $config = $this->config[$pod];

        foreach ($config as $input) {
            if (array_key_exists('query', $input)) {
                $queries[$input['slug']] = $input['query'];
            }
            if (array_key_exists('options', $input)) {
                foreach ($input['options'] as $option) {
                    $queries['checkbox'][$option['value']] = $option['query'];
                }
            }
        }

        return $queries;
    }

    /**
	 * Retrieve order config for the requested pod.
	 *
	 * @since   2.0.0
     * 
     * @return string    Orderby settings if key exists in config, empty if not.
	 */
    public function get_order_config($pod = '', $order = '') {
        $val = '';

        if (array_key_exists($pod, $this->orderConfig)) {
            if (array_key_exists($order, $this->orderConfig[$pod])) {
                $val = $this->orderConfig[$pod][$order];
            }
        }
        
        return $val;
    }
}
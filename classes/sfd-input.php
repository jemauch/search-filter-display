<?php

/**
 * Form input class.
 *
 * This is an abstract class for form input classes.
 *
 * @since   2.0.0
 * @package    Search Filter Display
 */
abstract class SFD_Input {

    public $legend;
    public $slug;

    /**
	 * Constructor
	 *
	 * @since   2.0.0
	 *
	 * @param string $legend    The fieldset legend / label of input.
	 * @param string $slug      Used for HTML IDs and values. In the case of taxonomy input, also used as the taxonomy slug for pulling terms.
	 */
    public function __construct($legend, $slug) {
        $this->legend = $legend;
        $this->slug = $slug;
    }

    /**
	 * Abstract function to return input and its options as HTML string.
	 *
	 * @since    2.0.0
     * 
     * @return   string
	 */
    abstract public function build();
}

/**
 * Taxonomy input class.
 *
 * This is for taxonomy-related filter inputs.
 *
 * @since   2.0.0
 * @package    Search Filter Display
 */
class SFD_Input_Taxonomy extends SFD_Input {

    public function build() {
        $terms = $this->term_tree();

        
        $output = "<fieldset class='sfd-fieldset--accordion'><legend class='sfd-fieldset__legend sfd-fieldset__control disclosure-trigger' aria-expanded='false' aria-controls='$this->slug-options' tabindex='0'>$this->legend<span class='disclosure-trigger__down'>" . sfd_get_icon('gravity-ui--chevron-down') . "</span><span class='disclosure-trigger__up'>" . sfd_get_icon('gravity-ui--chevron-up') . "</span></legend>";

        $output = $output . "<ul id='$this->slug-options' class='sfd-fieldset__options list' hidden>" . $this->build_options($terms) . "</ul>";
        $output = $output . "</fieldset>";

        return $output;
    }

    /**
	 * Function to build each option for input.
	 *
	 * @since    2.0.0
     * 
	 * @access   private
     * @return   string
	 */
    private function build_options($terms) {
        $output = '';

        foreach ($terms as $term) {
            $name = $term['name'];
            $id = $term['term_id'];
            $children = $term['children'];

            $output = $output . "<li class='term-group'><div class='term-group__term'><div class='term__input'><input type='checkbox' class='input__checkbox' name='taxonomy[$this->slug][]' id='$id' value='$id'><label class='input__label' for='$id'>$name</label></div>";
            if (!empty($children)) {
                $output = $output . "<button class='icon-button disclosure-trigger' type='button' aria-expanded='false' aria-controls='$id-children'><span class='sr-only' id='$id-children-accessibility'>$name subtypes</span><div class='disclosure-trigger__plus'>" . sfd_get_icon('gravity-ui--square-plus') . "</div><div class='disclosure-trigger__minus'>" . sfd_get_icon('gravity-ui--square-minus') . "</div></button>";
            }
            $output = $output . "</div>";
            if (!empty($children)) {
                $output = $output . "<ul class='term-group__children list' id='$id-children' hidden>" . $this->build_options($children) . "</ul>";
            }
            $output = $output . "</li>";
        }

        return $output;
    }

    /**
	 * Function to get array of terms in hierarchical structure.
	 *
	 * @since    2.0.0
     * 
	 * @access   private
     * @return   array
	 */
    private function term_tree() {
        // Get all terms in unstructured array
        $terms = get_terms([
            'taxonomy' => $this->slug,
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
        return $this->term_children($parents, $terms_list);
    }

    /**
	 * Recursive function to populate array with children terms.
	 *
	 * @since    2.0.0
     * 
	 * @access   private
     * @return   array
	 */
    private function term_children($parents, &$children, $root = 0) {
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
            'children' => $this->term_children($parents, $children[$i], $term['term_id'])
            ];

            ++$i;
        }
        return $children;
    }
}

/**
 * Year input class.
 *
 * This is for year-related filter inputs.
 *
 * @since   2.0.0
 * @package    Search Filter Display
 */
class SFD_Input_Year extends SFD_Input {

    public function build() {
        $first_year = 1974;
        $current_year = (int) date('Y');
        $years = range($current_year, $first_year);

        $output = "<fieldset class='sfd-fieldset'><legend class='sfd-fieldset__legend'>$this->legend</legend><div class='sfd-fieldset__options'><select name='$this->slug' id='$this->slug'><option value='-1'>--View All--</option>";

        foreach ($years as $year) {
            $output = $output . "<option value=\"$year\">$year</option>";
        }

        $output = $output . "</select></div></fieldset>";

        return $output;
    }
}

/**
 * Conference input class.
 *
 * This is for conference-related filter inputs.
 *
 * @since   2.0.0
 * @package    Search Filter Display
 */
class SFD_Input_Conference extends SFD_Input {

    public function build() {

        $output = "<fieldset class='sfd-fieldset'><legend class='sfd-fieldset__legend'>$this->legend</legend><div class='sfd-fieldset__options'>";
        $output = $output . "<div><input type='radio' name='$this->slug' id='siggraph' value='siggraph'><label for='siggraph'>SIGGRAPH</label></div>";
        $output = $output . "<div><input type='radio' name='$this->slug' id='siggraph-asia' value='siggraph-asia'><label for='siggraph-asia'>SIGGRAPH Asia</label></div>";
        $output = $output . "<div><input type='radio' name='$this->slug' id='both' value='both' checked><label for='both'>Both Conferences</label></div>";
        $output = $output . "</div></fieldset>";

        return $output;
    }
}

/**
 * Checkbox input class.
 *
 * This is for unique checkbox filter inputs.
 *
 * @since   2.0.0
 * @package    Search Filter Display
 */
class SFD_Input_Checkbox extends SFD_Input {

    private $options;   // Array of each checkbox input option as defined by the config.

    public function __construct($legend, $slug, $options = []) {
        $this->legend = $legend;
        $this->slug = $slug;
        $this->options = $options;
    }

    public function build() {
        $output = "<fieldset class='sfd-fieldset'><legend class='sfd-fieldset__legend'>$this->legend</legend><div class='sfd-fieldset__options'>";

        foreach ($this->options as $option) {
            $label = $option['label'];
            $value = $option['value'];

            $output = $output . "<div><input type='checkbox' name='checkbox[$value]' id='$value' value='$value'><label for='$value'>$label</label></div>";
        }

        $output = $output . "</div></fieldset>";

        return $output;
    }
}
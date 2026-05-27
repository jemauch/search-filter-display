<?php

/**
 * Shortcode support for use anywhere that support WP Shortcodes.
 * Will return empty string on failure.
 *
 * @param array  $tags    An associative array of shortcode properties.
 * @param string $content A string that represents a template override.
 *
 * @return string
 */
function sfd_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'pod' => '',
        'grid' => '',
        'table' => '',
        'display' => 'grid',
    ), $atts);

    $pod = $atts['pod'];

    if ( ! pods($pod, null, true) ) {
        // Return early if pod is not valid
        return '';
    }

    $loader = new SFD_Loader($pod, $atts['grid'], $atts['table'], $atts['display']);

    return $loader->build();
}

add_shortcode('sfd', 'sfd_shortcode');
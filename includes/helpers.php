<?php

/**
 * Function to output HTML from a file.
 *
 * @param string $name    HTML file name.
 *
 * @return string   HTML.
 */
function sfd_get_html($name) {
  return file_get_contents( SFD_DIR . "static/html/$name.html" );
}

/**
 * Function to output SVG icons.
 *
 * @param string $name    SVG icon name.
 *
 * @return string   SVG icon.
 */
function sfd_get_icon($name) {
  return file_get_contents( SFD_DIR . "static/svg/$name.svg" );
}
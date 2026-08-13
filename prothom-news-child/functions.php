<?php
/**
 * ProthomNews Child Theme Functions
 *
 * @package ProthomNewsChild
 */

if (!defined('ABSPATH')) {
    exit;
}

function prothom_news_child_enqueue_styles() {
    wp_enqueue_style('prothom-news-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('prothom-news-child-style', get_stylesheet_uri(), array('prothom-news-parent-style'));
}
add_action('wp_enqueue_scripts', 'prothom_news_child_enqueue_styles');

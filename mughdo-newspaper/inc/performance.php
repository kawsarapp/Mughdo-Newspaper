<?php
/**
 * Production-Level Performance Engine for ProthomNews
 * Head Cleanup, Resource Hints, Defer Non-critical JS, WebP & Transient Caching
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Performance {

    public static function init() {
        // Clean WP Head Bloat
        add_action('init', array(__CLASS__, 'clean_head_bloat'));
        
        // Add Resource Hints (DNS prefetch & preconnect)
        add_action('wp_head', array(__CLASS__, 'add_resource_hints'), 1);

        // Defer Scripts for Faster FCP / LCP PageSpeed Scores
        add_filter('script_loader_tag', array(__CLASS__, 'defer_non_critical_scripts'), 10, 2);

        // Clear Theme Query Transients on Post Change
        add_action('save_post', array(__CLASS__, 'clear_query_transients'));
    }

    public static function clean_head_bloat() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }

    public static function add_resource_hints() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '<link rel="dns-prefetch" href="//img.youtube.com">' . "\n";
    }

    public static function defer_non_critical_scripts($tag, $handle) {
        if (is_admin()) {
            return $tag;
        }

        // Defer theme SPA and app scripts for instant page render
        if (in_array($handle, array('prothom-news-spa', 'prothom-news-app'), true)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Clear Theme Transient Caches upon Post Publishing/Editing
     */
    public static function clear_query_transients() {
        delete_transient('prothom_trending_posts_cache');
        delete_transient('prothom_rest_search_cache');
    }
}

ProthomNews_Performance::init();

<?php
/**
 * Custom REST API Endpoints for ProthomNews SPA Engine & Live Features
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_REST_API {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_routes'));
    }

    public static function register_routes() {
        // Namespace: prothom-news/v1

        // Endpoint 1: Live Search Autocomplete
        register_rest_route('prothom-news/v1', '/search', array(
            'methods'             => 'GET',
            'callback'            => array(__CLASS__, 'live_search'),
            'permission_callback'=> '__return_true',
        ));

        // Endpoint 2: Latest & Popular Tabbed News
        register_rest_route('prothom-news/v1', '/trending', array(
            'methods'             => 'GET',
            'callback'            => array(__CLASS__, 'get_trending'),
            'permission_callback'=> '__return_true',
        ));

        // Endpoint 3: SPA Page Fetcher (renders template partial via REST API for instant transition)
        register_rest_route('prothom-news/v1', '/spa-page', array(
            'methods'             => 'GET',
            'callback'            => array(__CLASS__, 'get_spa_page'),
            'permission_callback'=> '__return_true',
        ));
    }

    /**
     * Live Search Callback
     */
    public static function live_search($request) {
        $query = sanitize_text_field($request->get_param('s'));
        if (empty($query) || mb_strlen($query) < 2) {
            return new WP_REST_Response(array('results' => array()), 200);
        }

        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            's'              => $query,
            'posts_per_page' => 6,
        );

        $posts = get_posts($args);
        $results = array();

        foreach ($posts as $post) {
            $thumbnail = get_the_post_thumbnail_url($post->ID, 'thumbnail');
            if (!$thumbnail) {
                $thumbnail = esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg');
            }

            $categories = get_the_category($post->ID);
            $cat_name = !empty($categories) ? $categories[0]->name : '';

            $results[] = array(
                'id'        => $post->ID,
                'title'     => get_the_title($post->ID),
                'link'      => get_permalink($post->ID),
                'date'      => ProthomNews_Bangla_Date::convert_number(get_the_date('j F Y', $post->ID)),
                'thumbnail' => $thumbnail,
                'category'  => $cat_name,
            );
        }

        return new WP_REST_Response(array('results' => $results), 200);
    }

    /**
     * Get Trending News (Latest vs Popular)
     */
    public static function get_trending($request) {
        $type = sanitize_text_field($request->get_param('type')); // 'latest' or 'popular'
        
        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
            'ignore_sticky_posts' => true,
        );

        if ($type === 'popular') {
            // Sort by comment count or view count fallback
            $args['orderby'] = 'comment_count';
            $args['order']   = 'DESC';
        } else {
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
        }

        $posts = get_posts($args);
        $items = array();

        $count = 1;
        foreach ($posts as $post) {
            $categories = get_the_category($post->ID);
            $cat_name = !empty($categories) ? $categories[0]->name : '';

            $items[] = array(
                'index'    => ProthomNews_Bangla_Date::convert_number($count),
                'id'       => $post->ID,
                'title'    => get_the_title($post->ID),
                'link'     => get_permalink($post->ID),
                'time'     => ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($post->post_date)),
                'category' => $cat_name,
            );
            $count++;
        }

        return new WP_REST_Response(array('items' => $items), 200);
    }

    /**
     * SPA Page Content Callback
     */
    public static function get_spa_page($request) {
        $url = esc_url_raw($request->get_param('url'));
        if (empty($url)) {
            return new WP_Error('invalid_url', 'URL is required', array('status' => 400));
        }

        $post_id = url_to_postid($url);
        if ($post_id) {
            $post = get_post($post_id);
            setup_postdata($post);

            ob_start();
            include locate_template('single.php');
            $html = ob_get_clean();

            return new WP_REST_Response(array(
                'type'    => 'single',
                'title'   => get_the_title($post_id) . ' - ' . get_bloginfo('name'),
                'content' => $html,
            ), 200);
        }

        return new WP_REST_Response(array('type' => 'fallback', 'url' => $url), 200);
    }
}

ProthomNews_REST_API::init();

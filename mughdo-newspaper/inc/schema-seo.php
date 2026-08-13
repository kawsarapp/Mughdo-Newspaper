<?php
/**
 * Schema.org JSON-LD Microdata & Rich Snippets Engine for ProthomNews
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Schema_SEO {

    public static function init() {
        add_action('wp_head', array(__CLASS__, 'output_schema_json_ld'), 5);
    }

    public static function output_schema_json_ld() {
        $site_name = get_bloginfo('name');
        $site_url  = home_url('/');
        $logo_url  = get_template_directory_uri() . '/assets/images/placeholder.svg';
        
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo_data = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo_data) {
                $logo_url = $logo_data[0];
            }
        }

        // 1. Publisher Organization Schema
        $publisher_schema = array(
            '@type' => 'Organization',
            'name'  => $site_name,
            'url'   => $site_url,
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => $logo_url,
            ),
        );

        if (is_single()) {
            global $post;
            $post_id     = $post->ID;
            $post_url    = get_permalink($post_id);
            $post_title  = get_the_title($post_id);
            $post_thumb  = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
            $date_pub    = get_the_date('c', $post_id);
            $date_mod    = get_the_modified_date('c', $post_id);
            $author_name = get_the_author_meta('display_name', $post->post_author);

            // 2. NewsArticle Schema
            $article_schema = array(
                '@context'         => 'https://schema.org',
                '@type'            => 'NewsArticle',
                'mainEntityOfPage' => array(
                    '@type' => 'WebPage',
                    '@id'   => $post_url,
                ),
                'headline'         => $post_title,
                'image'            => array($post_thumb),
                'datePublished'    => $date_pub,
                'dateModified'     => $date_mod,
                'author'           => array(
                    '@type' => 'Person',
                    'name'  => $author_name,
                ),
                'publisher'        => $publisher_schema,
                'description'      => prothom_news_custom_excerpt(30, $post_id),
            );

            // Star Rating Microdata integration if rating exists
            $rating_val = get_post_meta($post_id, '_prothom_review_rating', true);
            if (!empty($rating_val)) {
                $article_schema['review'] = array(
                    '@type'        => 'Review',
                    'reviewRating' => array(
                        '@type'       => 'Rating',
                        'ratingValue' => $rating_val,
                        'bestRating'  => '5',
                    ),
                    'author'       => array(
                        '@type' => 'Person',
                        'name'  => $author_name,
                    ),
                );
            }

            echo '<script type="application/ld+json">' . wp_json_encode($article_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        } elseif (is_front_page() || is_home()) {
            // WebSite Schema
            $website_schema = array(
                '@context' => 'https://schema.org',
                '@type'    => 'WebSite',
                'name'     => $site_name,
                'url'      => $site_url,
                'potentialAction' => array(
                    '@type'       => 'SearchAction',
                    'target'      => $site_url . '?s={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ),
            );
            echo '<script type="application/ld+json">' . wp_json_encode($website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }
}

ProthomNews_Schema_SEO::init();

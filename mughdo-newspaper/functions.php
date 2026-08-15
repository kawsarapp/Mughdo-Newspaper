<?php
/**
 * Mughdo Newspaper Theme Functions & Production Setup
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include Theme Engine Core Modules
require_get_template_directory('/inc/bangla-date.php');
require_get_template_directory('/inc/theme-options.php');
require_get_template_directory('/inc/rest-api.php');
require_get_template_directory('/inc/performance.php');
require_get_template_directory('/inc/schema-seo.php');
require_get_template_directory('/inc/review-system.php');
require_get_template_directory('/inc/video-helper.php');
require_get_template_directory('/inc/widgets.php');
require_get_template_directory('/inc/demo-importer.php');
require_get_template_directory('/inc/license-system.php');

// Include 7 Enterprise Modules
require_get_template_directory('/inc/weather-aqi.php');
require_get_template_directory('/inc/stock-currency.php');
require_get_template_directory('/inc/prayer-ramadan.php');
require_get_template_directory('/inc/sports-scorecard.php');
require_get_template_directory('/inc/pwa-engine.php');

function require_get_template_directory($file) {
    require_once get_template_directory() . $file;
}

/**
 * Production Theme Setup
 */
function mughdo_newspaper_setup() {
    // i18n Translation Textdomain
    load_theme_textdomain('mughdo-newspaper', get_template_directory() . '/languages');

    // Theme Supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    
    // Gutenberg Editor Color Palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Mughdo Brand Red', 'mughdo-newspaper'),
            'slug'  => 'brand-red',
            'color' => '#CC0000',
        ),
        array(
            'name'  => __('Dark Surface', 'mughdo-newspaper'),
            'slug'  => 'dark-surface',
            'color' => '#0F172A',
        ),
        array(
            'name'  => __('Muted Text', 'mughdo-newspaper'),
            'slug'  => 'muted-text',
            'color' => '#64748B',
        ),
    ));

    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 280,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // WooCommerce & eShop Integration Support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Elementor Page Builder & Gutenberg Block Editor Full Theme Compatibility
    add_theme_support('elementor');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-line-height');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');
    add_theme_support('wp-block-styles');

    // Register Nav Menus
    register_nav_menus(array(
        'primary' => __('Main Navigation Menu', 'mughdo-newspaper'),
        'topbar'  => __('Top Bar Menu', 'mughdo-newspaper'),
        'footer'  => __('Footer Quick Links', 'mughdo-newspaper'),
    ));

    // Custom Thumbnail Sizes for Mughdo Grids
    add_image_size('prothom-lead-hero', 800, 450, true);   // Lead Featured Story (16:9)
    add_image_size('prothom-thumb-rect', 400, 250, true);  // Sub-lead Card Thumbnail
    add_image_size('prothom-thumb-square', 200, 200, true); // Compact List Item Thumbnail
}
add_action('after_setup_theme', 'mughdo_newspaper_setup');

/**
 * Enqueue Theme Assets & JS Drivers
 */
function mughdo_newspaper_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');

    // Main Stylesheet
    wp_enqueue_style('prothom-news-style', get_template_directory_uri() . '/assets/css/main.css', array(), $theme_version);

    // SPA Router Engine JavaScript
    wp_enqueue_script('prothom-news-spa', get_template_directory_uri() . '/assets/js/spa-router.js', array(), $theme_version, true);

    // Main App Logic
    wp_enqueue_script('prothom-news-app', get_template_directory_uri() . '/assets/js/app.js', array(), $theme_version, true);

    // Localize Data for Frontend Scripts
    wp_localize_script('prothom-news-app', 'ProthomNewsData', array(
        'apiUrl'   => esc_url_raw(rest_url('prothom-news/v1')),
        'siteUrl'  => esc_url_raw(home_url('/')),
        'nonce'    => wp_create_nonce('wp_rest'),
        'themeUrl' => esc_url_raw(get_template_directory_uri()),
    ));
}
add_action('wp_enqueue_scripts', 'mughdo_newspaper_enqueue_assets');

/**
 * Typography & Custom Fonts Engine
 */
function mughdo_newspaper_custom_fonts() {
    $heading_font = get_theme_mod('heading_font', 'solaimanlipi');
    $body_font    = get_theme_mod('body_font', 'solaimanlipi');
    $custom_url   = get_theme_mod('custom_font_url', '');

    $fonts_map = array(
        'solaimanlipi'       => "'SolaimanLipi', 'Hind Siliguri', sans-serif",
        'hind_siliguri'      => "'Hind Siliguri', 'SolaimanLipi', sans-serif",
        'noto_serif_bengali' => "'Noto Serif Bengali', serif",
        'noto_sans_bengali'  => "'Noto Sans Bengali', sans-serif",
        'kalpurush'          => "'Kalpurush', sans-serif",
        'tiro_bangla'        => "'Tiro Bangla', serif",
        'mina'               => "'Mina', sans-serif",
        'outfit'             => "'Outfit', sans-serif",
        'inter'              => "'Inter', sans-serif",
        'system'             => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
        'custom'             => "CustomFont, sans-serif",
    );

    $heading_ff = isset($fonts_map[$heading_font]) ? $fonts_map[$heading_font] : $fonts_map['solaimanlipi'];
    $body_ff    = isset($fonts_map[$body_font]) ? $fonts_map[$body_font] : $fonts_map['solaimanlipi'];

    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Inter:wght@400;600;700&family=Mina:wght@400;700&family=Noto+Sans+Bengali:wght@400;600;700&family=Noto+Serif+Bengali:wght@400;700&family=Outfit:wght@400;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">' . "\n";

    echo '<link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">' . "\n";
    echo '<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">' . "\n";

    if (!empty($custom_url)) {
        echo '<link href="' . esc_url($custom_url) . '" rel="stylesheet">' . "\n";
    }

    ?>
    <style id="mughdo-custom-typography">
      :root {
        --font-heading: <?php echo $heading_ff; ?>;
        --font-body: <?php echo $body_ff; ?>;
      }
      body, p, li, input, textarea, select, button {
        font-family: var(--font-body) !important;
      }
      h1, h2, h3, h4, h5, h6, .news-card-title, .lead-hero-title, .section-title, .entry-title {
        font-family: var(--font-heading) !important;
      }
    </style>
    <?php
}
add_action('wp_head', 'mughdo_newspaper_custom_fonts', 2);

/**
 * Dynamic Post Thumbnail Helper with Category Fallback Graphics
 */
function prothom_news_get_post_thumbnail($post_id = null, $size = 'prothom-thumb-rect') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }

    $categories = get_the_category($post_id);
    $cat_slug = !empty($categories) ? $categories[0]->slug : '';

    if (strpos($cat_slug, 'sports') !== false) {
        return get_template_directory_uri() . '/assets/images/news-sports.svg';
    } elseif (strpos($cat_slug, 'tech') !== false) {
        return get_template_directory_uri() . '/assets/images/news-tech.svg';
    }

    return get_template_directory_uri() . '/assets/images/news-national.svg';
}

/**
 * Custom Excerpt Length & Clean Format
 */
function prothom_news_custom_excerpt($length = 20, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $content = get_the_excerpt($post_id);
    if (empty($content)) {
        $content = strip_tags(get_post_field('post_content', $post_id));
    }
    return wp_trim_words($content, $length, '...');
}

/**
 * Estimated Reading Time in Bengali
 */
function prothom_news_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $content = get_post_field('post_content', $post_id);
    $word_count = count(preg_split('/\s+/', strip_tags($content)));
    $reading_minutes = max(1, ceil($word_count / 180));
    return ProthomNews_Bangla_Date::convert_number($reading_minutes) . ' মিনিট পড়া';
}

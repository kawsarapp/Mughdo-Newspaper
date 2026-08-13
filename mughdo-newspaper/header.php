<?php
/**
 * ProthomNews Header Template
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$gregorian_bn = ProthomNews_Bangla_Date::get_gregorian_bn();
$bangla_cal   = ProthomNews_Bangla_Date::get_bangla_calendar_date();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- 1. Top Header Date & Weather Bar -->
<header class="site-header-wrapper">
  <div class="top-header-bar">
    <div class="container">
      <div class="top-bar-inner">
        <div class="date-weather-display">
          <span>📅 <?php echo esc_html($gregorian_bn); ?></span>
          <span class="bn-date">| (বঙ্গাব্দ: <?php echo esc_html($bangla_cal); ?>)</span>
        </div>
        <div class="top-header-actions">
          <button id="theme-toggle-btn" class="theme-toggle-btn" aria-label="Toggle Dark Mode">
            🌙 <span>রাত</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Main Header Logo & Header Ad Slot -->
  <div class="main-header">
    <div class="container">
      <div class="header-brand-grid">
        <div class="brand-logo-area">
          <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
          <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
              <?php bloginfo('name'); ?>
            </a>
            <p class="site-tagline"><?php bloginfo('description'); ?></p>
          <?php endif; ?>
        </div>
        
        <!-- Header Leaderboard Ad Slot (728x90) -->
        <div class="header-ad-area">
          <?php ProthomNews_Theme_Options::render_ad('ad_header_top'); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Primary Navigation Category Bar -->
  <nav class="main-nav-bar" aria-label="Main Navigation">
    <div class="container">
      <div class="nav-wrapper">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'primary-menu',
                'fallback_cb'    => false,
            ));
        } else {
            echo '<ul class="primary-menu">';
            echo '<li><a href="' . esc_url(home_url('/')) . '">প্রচ্ছদ</a></li>';
            $top_cats = get_categories(array('number' => 9, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => false));
            foreach ($top_cats as $cat) {
                if ($cat->slug === 'uncategorized') continue;
                echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
        <button id="search-trigger-btn" class="search-trigger-btn" aria-label="Search Portal">
          🔍
        </button>
      </div>
    </div>
  </nav>

  <!-- 4. Breaking News Ticker -->
  <?php get_template_part('template-parts/breaking-ticker'); ?>

  <!-- 5. Below Ticker Ad Banner Slot -->
  <div class="container">
    <?php ProthomNews_Theme_Options::render_ad('ad_after_ticker'); ?>
  </div>
</header>

<!-- SPA Content Wrapper (Target for smooth page swapping without reload) -->
<main id="spa-content-container">

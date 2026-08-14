<?php
/**
 * Header Template for Mughdo Newspaper
 * Dynamic Topbar Widgets (Weather, Currency, Prayer, Sports), SPA Progress Indicator, Main Navigation & Live Search Modal with Bengali Voice Search.
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

$bn_date = ProthomNews_Bangla_Date::get_current_bangla_date();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Dynamic Theme Color for Mobile Browsers -->
    <meta name="theme-color" content="#CC0000">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- SPA Top Loading Progress Bar -->
<div id="spa-progress-bar"></div>

<header class="site-header">
  <!-- Topbar: Date, Weather, Currency, Prayer, Sports Scorecard, Theme Toggle -->
  <div class="top-header-bar">
    <div class="container top-bar-inner">
      
      <!-- Left side: Date & Dynamic Widgets -->
      <div class="date-weather-display">
        <span class="bn-date">🗓️ <?php echo esc_html($bn_date); ?></span>
        
        <?php 
        if (class_exists('Mughdo_Weather_AQI')) Mughdo_Weather_AQI::render('topbar');
        if (class_exists('Mughdo_Stock_Currency')) Mughdo_Stock_Currency::render('topbar');
        if (class_exists('Mughdo_Prayer_Ramadan')) Mughdo_Prayer_Ramadan::render('topbar');
        if (class_exists('Mughdo_Sports_Scorecard')) Mughdo_Sports_Scorecard::render('topbar');
        ?>
      </div>

      <!-- Right side: Dark Mode Theme Switcher -->
      <div class="top-header-actions">
        <button id="theme-toggle-btn" class="theme-toggle-btn" aria-label="Toggle Theme Mode">
          🌙 <span>রাত</span>
        </button>
      </div>

    </div>
  </div>

  <!-- Header Ad Slot (Header Top Banner 728x90) -->
  <?php ProthomNews_Theme_Options::render_ad('ad_header_top', 'my-2'); ?>

  <!-- Main Header Brand Grid (Logo & Tagline) -->
  <div class="main-header">
    <div class="container header-brand-grid">
      <div>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
          <?php 
          if (has_custom_logo()) {
              the_custom_logo();
          } else {
              bloginfo('name');
          }
          ?>
        </a>
        <p class="site-tagline"><?php bloginfo('description'); ?></p>
      </div>
      <div>
        <!-- Reserved Header Top Banner space -->
      </div>
    </div>
  </div>

  <!-- Sticky Main Navigation Bar -->
  <nav class="main-nav-bar" role="navigation" aria-label="Main Navigation">
    <div class="container nav-wrapper">
      <button id="mobile-menu-trigger" class="mobile-menu-trigger" aria-label="Open Mobile Menu">☰</button>
      
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
  </nav>

  <!-- Continuous Breaking News Marquee Ticker -->
  <?php get_template_part('template-parts/breaking-ticker'); ?>

</header>

<!-- SPA Content Wrapper (Target for smooth page swapping without reload) -->
<main id="spa-content-container">

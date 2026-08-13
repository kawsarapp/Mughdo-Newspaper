<?php
/**
 * Template Name: Homepage 2 - ম্যাগাজিন ও ভিডিও পোর্টাল (Magazine & Video Portal)
 *
 * @package ProthomNews
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container">
    <div class="main-content-layout">

      <!-- 1. Big Magazine Hero Block -->
      <?php get_template_part('template-parts/content-category-magazine-hero', null, array('post_count' => 7)); ?>

      <!-- Ad Slot -->
      <?php ProthomNews_Theme_Options::render_ad('ad_after_lead', 'my-3'); ?>

      <!-- 2. Video Grid Spotlight Block -->
      <?php get_template_part('template-parts/content-category-video-grid', null, array('post_count' => 4, 'title' => '🎥 ভিডিও সংবাদের বিশেষ আয়োজন')); ?>

      <!-- 3. Sub-category Tabbed News Block -->
      <?php get_template_part('template-parts/content-category-tabbed-cat', null, array('post_count' => 6)); ?>

      <!-- 4. 2-Column Split Magazine Block -->
      <?php get_template_part('template-parts/content-category-2col-split', null, array('post_count' => 6)); ?>

      <!-- 5. Editorial Spotlight Banner -->
      <?php get_template_part('template-parts/content-category-editorial-spotlight', null, array('post_count' => 1)); ?>

      <!-- 6. Opinion Columnists Grid -->
      <?php get_template_part('template-parts/content-category-opinion', null, array('post_count' => 4)); ?>

    </div>
  </div>
</main>

<?php
get_footer();

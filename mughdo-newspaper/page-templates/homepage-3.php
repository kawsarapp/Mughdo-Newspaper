<?php
/**
 * Template Name: Homepage 3 - ভিজ্যুয়াল মিডিয়া ও লাইভ আপডেট (Visual Media & Live Portal)
 *
 * @package ProthomNews
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container">
    <div class="main-content-layout">

      <!-- 1. Live Timeline Updates Feed -->
      <?php get_template_part('template-parts/content-category-live-timeline', null, array('post_count' => 5, 'title' => '⚡ লাইভ খবরের আপডেট')); ?>

      <!-- 2. Gradient Overlay Feature Cards -->
      <?php get_template_part('template-parts/content-category-overlay', null, array('post_count' => 4)); ?>

      <!-- 3. Fact Check Rating Grid -->
      <?php get_template_part('template-parts/content-category-fact-check', null, array('post_count' => 4)); ?>

      <!-- 4. Photo Slider Carousel Block -->
      <?php get_template_part('template-parts/content-category-slider-carousel', null, array('post_count' => 4)); ?>

      <!-- 5. Dark Photo / Video Gallery -->
      <?php get_template_part('template-parts/content-category-gallery', null, array('post_count' => 4)); ?>

      <!-- 6. Quote Card Block -->
      <?php get_template_part('template-parts/content-category-quote-block', null, array('post_count' => 2)); ?>

    </div>
  </div>
</main>

<?php
get_footer();

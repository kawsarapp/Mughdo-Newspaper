<?php
/**
 * Template Name: Homepage 1 - ক্লাসিক প্রথম আলো স্টাইল (Classic Portal)
 *
 * @package ProthomNews
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container">
    <div class="main-content-layout">

      <!-- Lead News Grid Component -->
      <?php get_template_part('template-parts/content-lead'); ?>

      <!-- Ad Slot: After Lead Grid -->
      <?php ProthomNews_Theme_Options::render_ad('ad_after_lead', 'my-3'); ?>

      <!-- Dynamic Customizer Section Blocks Loop (1 to 15) -->
      <?php
      $blocks = array();
      for ($i = 1; $i <= 15; $i++) {
          $enabled = get_theme_mod("enable_block_{$i}", ($i <= 10) ? 1 : 0);
          if ($enabled) {
              $blocks[] = array(
                  'index'      => $i,
                  'cat_id'     => get_theme_mod("cat_block_{$i}", 0),
                  'layout'     => get_theme_mod("layout_block_{$i}", '3col'),
                  'post_count' => get_theme_mod("count_block_{$i}", 6),
                  'order'      => get_theme_mod("order_block_{$i}", $i),
              );
          }
      }

      usort($blocks, function($a, $b) {
          return $a['order'] - $b['order'];
      });

      $block_counter = 0;
      foreach ($blocks as $block) {
          $block_counter++;
          $layout_file = 'template-parts/content-category-' . sanitize_file_name($block['layout']);
          
          get_template_part($layout_file, null, array(
              'cat_id'     => $block['cat_id'],
              'post_count' => $block['post_count'],
          ));

          if ($block_counter === 3) {
              ProthomNews_Theme_Options::render_ad('ad_middle_home', 'my-4');
          }
      }
      ?>

    </div>
  </div>
</main>

<?php
get_footer();

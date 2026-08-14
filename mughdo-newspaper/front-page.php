<?php
/**
 * Front Page Template Router for Mughdo Newspaper
 * Renders Custom Homepage Layouts & Presets whether WP Settings -> Reading is set to "Latest Posts" OR "A Static Page".
 * Smart Auto-Category Distribution & Custom Title Resolver.
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

$preset = get_theme_mod('homepage_preset', 'preset_1');

if ($preset === 'preset_2') {
    get_template_part('page-templates/homepage-2');
    return;
} elseif ($preset === 'preset_3') {
    get_template_part('page-templates/homepage-3');
    return;
}

// Default: Homepage 1 (Classic Prothom Alo Grid) or Custom 20 Dynamic Section Blocks
get_header();

// Fetch all available categories to auto-distribute distinct categories if unassigned
$all_categories = get_categories(array('hide_empty' => false));
$cat_count      = !empty($all_categories) ? count($all_categories) : 0;
?>

<main id="primary" class="site-main">
  <div class="container">
    <div class="main-content-layout">

      <!-- Lead News Grid Component -->
      <?php get_template_part('template-parts/content-lead'); ?>

      <!-- Ad Slot: After Lead Grid -->
      <?php ProthomNews_Theme_Options::render_ad('ad_after_lead', 'my-3'); ?>

      <!-- Dynamic Customizer Section Blocks Loop (1 to 20) -->
      <?php
      $blocks = array();
      for ($i = 1; $i <= 20; $i++) {
          $enabled = get_theme_mod("enable_block_{$i}", ($i <= 12) ? 1 : 0);
          if ($enabled) {
              $user_cat_id = get_theme_mod("cat_block_{$i}", 0);
              
              // Smart Auto-Category Assignment if unassigned (0)
              if ($user_cat_id == 0 && $cat_count > 0) {
                  $assigned_cat = $all_categories[($i - 1) % $cat_count];
                  $final_cat_id = $assigned_cat->term_id;
                  $default_title = $assigned_cat->name;
              } else {
                  $final_cat_id = $user_cat_id;
                  $cat_obj = get_category($final_cat_id);
                  $default_title = $cat_obj ? $cat_obj->name : '';
              }

              $user_title  = get_theme_mod("title_block_{$i}", '');
              $final_title = !empty($user_title) ? $user_title : $default_title;

              $blocks[] = array(
                  'index'      => $i,
                  'cat_id'     => $final_cat_id,
                  'title'      => $final_title,
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
              'cat_id'      => $block['cat_id'],
              'title'       => $block['title'],
              'post_count'  => $block['post_count'],
              'block_index' => $block['index'],
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

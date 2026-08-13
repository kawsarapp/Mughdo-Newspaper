<?php
/**
 * Editorial Choice Spotlight Banner Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_6';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');

$title = !empty($custom_title) ? $custom_title : __('সম্পাদকীয় পছন্দ ও বিশেষ প্রতিবেদন', 'prothom-news');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$spotlight_query = new WP_Query($query_args);

if ($spotlight_query->have_posts()) :
?>

<section class="category-block-wrapper">
  <?php while ($spotlight_query->have_posts()) : $spotlight_query->the_post(); 
      $post_id  = get_the_ID();
      $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
      $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
  ?>
    <div class="editorial-spotlight-banner">
      <div class="spotlight-img-area">
        <a href="<?php the_permalink(); ?>">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
        </a>
      </div>
      <div class="spotlight-content-area">
        <span class="spotlight-badge">⭐ <?php echo esc_html($title); ?></span>
        <h2 class="spotlight-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p class="spotlight-excerpt">
          <?php echo esc_html(prothom_news_custom_excerpt(30, $post_id)); ?>
        </p>
        <div style="margin-top:1rem; font-size:0.85rem; color:var(--text-muted);">
          <span>✍️ সম্পাদকের ডেস্ক</span> | <span>🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </div>
    </div>
  <?php endwhile; wp_reset_postdata(); ?>
</section>

<?php endif; ?>

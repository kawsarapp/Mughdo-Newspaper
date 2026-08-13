<?php
/**
 * Fact Check / সত্যতা যাচাই Layout Block Template
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id     = isset($args['cat_id']) ? $args['cat_id'] : 0;
$post_count = isset($args['post_count']) ? $args['post_count'] : 4;
$title      = isset($args['title']) ? $args['title'] : '';

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
    if (empty($title)) {
        $title = get_cat_name($cat_id);
    }
}

$block_query = new WP_Query($query_args);

if ($block_query->have_posts()) :
    $fact_statuses = array('সত্য', 'ভুল তথ্য / গুজব', 'আংশিক সত্য', 'বিভ্রান্তিকর');
    $status_colors = array('#10B981', '#EF4444', '#F59E0B', '#6366F1');
?>
<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title">🔍 <?php echo esc_html($title ? $title : 'সত্যতা যাচাই | Fact Check'); ?></h3>
    <?php if (!empty($cat_id) && $cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল যাচাই ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php 
    $idx = 0;
    while ($block_query->have_posts()) : $block_query->the_post();
        $p_id = get_the_ID();
        $st_title = $fact_statuses[$idx % count($fact_statuses)];
        $st_color = $status_colors[$idx % count($status_colors)];
        $idx++;
    ?>
      <article class="news-card" style="position:relative;">
        <span style="position:absolute; top:10px; left:10px; z-index:5; background:<?php echo esc_attr($st_color); ?>; color:#FFF; font-size:0.75rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:4px;">
          ✓ <?php echo esc_html($st_title); ?>
        </span>
        <div class="news-card-img">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url(prothom_news_get_post_thumbnail($p_id, 'prothom-thumb-rect')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="news-card-body">
          <h4 class="news-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

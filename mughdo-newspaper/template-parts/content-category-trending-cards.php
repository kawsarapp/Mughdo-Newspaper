<?php
/**
 * Trending Ranking Cards Layout Box Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id     = isset($args['cat_id']) ? $args['cat_id'] : 0;
$post_count = isset($args['post_count']) ? $args['post_count'] : 4;
$title      = isset($args['title']) ? $args['title'] : '';

if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('সর্বোচ্চ পঠিত ও ট্রেন্ডিং খবর', 'mughdo-newspaper');
}

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$block_query = new WP_Query($query_args);

if ($block_query->have_posts()) :
?>
<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title">🔥 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল ট্রেন্ডিং ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php 
    $rank = 0;
    while ($block_query->have_posts()) : $block_query->the_post();
        $rank++;
        $p_id = get_the_ID();
        $bn_rank = ProthomNews_Bangla_Date::convert_number($rank);
    ?>
      <article class="news-card" style="position:relative;">
        <span style="position:absolute; top:10px; right:10px; z-index:5; background:var(--brand-red); color:#FFF; font-size:0.85rem; font-weight:800; padding:0.25rem 0.6rem; border-radius:4px; box-shadow:0 2px 5px rgba(0,0,0,0.3);">
          #<?php echo esc_html($bn_rank); ?>
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
          <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem;">🔥 সংবাদের জনপ্রিয় র‍্যাংক #<?php echo esc_html($bn_rank); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

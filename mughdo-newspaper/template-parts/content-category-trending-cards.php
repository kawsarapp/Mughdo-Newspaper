<?php
/**
 * Trending Ranking (#1-#4) Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 4;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('ট্রেন্ডিং র‍্যাঙ্কিং (Trending Ranking)', 'mughdo-newspaper');
}

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$cat_query = new WP_Query($query_args);

if (!$cat_query->have_posts()) {
    unset($query_args['cat']);
    $cat_query = new WP_Query($query_args);
}

if ($cat_query->have_posts()) :
    $rank = 0;
?>

<section class="category-block-wrapper trending-ranking-wrapper">
  <div class="section-header">
    <h3 class="section-title">🔥 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল ট্রেন্ডিং ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $rank++;
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
      <article class="trending-rank-card" style="display:flex; align-items:center; gap:0.75rem; background:var(--bg-card); padding:0.75rem; border:1px solid var(--border-color); border-radius:var(--radius-md);">
        <div style="font-size:1.8rem; font-weight:900; color:var(--brand-red); min-width:32px; text-align:center;">#<?php echo esc_html($rank); ?></div>
        <div>
          <h4 style="font-size:0.9rem; font-weight:700; line-height:1.3; margin:0;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * Infographic & Stat Counter Layout Component
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
    $title = __('ইনফোগ্রাফিক ও ডাটা সংবাদ', 'mughdo-newspaper');
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
?>

<section class="category-block-wrapper stat-infographic-wrapper">
  <div class="section-header">
    <h3 class="section-title">📊 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল ইনফোগ্রাফিক ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
      <article class="stat-card" style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:0.85rem; text-align:center;">
        <div style="font-size:1.5rem; font-weight:800; color:var(--brand-red); margin-bottom:0.3rem;">100%+</div>
        <h4 style="font-size:0.9rem; font-weight:700; line-height:1.35;">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

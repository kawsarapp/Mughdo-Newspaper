<?php
/**
 * Fact Check Verdict Card Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 3;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('সত্যতা যাচাই (Fact Check)', 'mughdo-newspaper');
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

<section class="category-block-wrapper fact-check-wrapper" style="background:var(--bg-card); padding:1.25rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
  <div class="section-header" style="margin-bottom:1rem;">
    <h3 class="section-title" style="color:#059669;">🛡️ <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল তথ্য যাচাই ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-3col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="news-card" style="border:1px solid #10B981; border-radius:var(--radius-sm); overflow:hidden; position:relative;">
        <div style="position:absolute; top:8px; right:8px; background:#10B981; color:#FFF; font-size:0.75rem; font-weight:700; padding:3px 8px; border-radius:4px; z-index:2;">
          ✓ সঠিক (Verified)
        </div>
        <div class="news-card-img">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="news-card-body">
          <h4 class="news-card-title" style="font-size:0.95rem;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
          <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem; display:block;">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

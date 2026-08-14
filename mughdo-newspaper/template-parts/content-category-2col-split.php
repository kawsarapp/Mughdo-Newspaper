<?php
/**
 * 2-Column Split Magazine Layout Component
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
    $title = __('আন্তর্জাতিক ও বিশ্বসংবাদ', 'mughdo-newspaper');
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

// Fallback if selected category has 0 posts in database
if (!$cat_query->have_posts()) {
    unset($query_args['cat']);
    $cat_query = new WP_Query($query_args);
}

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title"><?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">আরও খবর ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-3col" style="grid-template-columns: 1fr 1fr;">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="news-card" style="display:grid; grid-template-columns: 140px 1fr; gap:0.85rem; padding:0.6rem;">
        <div class="news-card-img" style="aspect-ratio: 4/3; border-radius:4px;">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="news-card-body" style="padding:0;">
          <h4 class="news-card-title" style="font-size:0.95rem;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
          <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem;">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

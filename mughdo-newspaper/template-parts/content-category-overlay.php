<?php
/**
 * Overlay Gradient Cards Visual Layout Component
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
    $title = __('বিজ্ঞান ও প্রযুক্তি', 'mughdo-newspaper');
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

  <div class="grid-overlay">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id   = get_the_ID();
        $img_url   = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
        $time_str  = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="overlay-card">
        <div class="overlay-img-wrapper">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
        </div>
        <div class="overlay-card-content">
          <span style="font-size:0.75rem; color:#F59E0B; font-weight:700; text-transform:uppercase;">✨ ফিচারড</span>
          <h4 class="overlay-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
          <span class="overlay-meta">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * Live Timeline Card Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 5;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('লাইভ আপডেট টাইমলাইন', 'mughdo-newspaper');
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

<section class="category-block-wrapper live-timeline-wrapper">
  <div class="section-header">
    <h3 class="section-title" style="color:var(--brand-red);">🔴 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">লাইভ আপডেট ➔</a>
    <?php endif; ?>
  </div>

  <div class="timeline-container" style="border-left:3px solid var(--brand-red); padding-left:1.25rem; margin-left:0.5rem;">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="timeline-item" style="position:relative; margin-bottom:1.25rem;">
        <div style="position:absolute; left:-1.7rem; top:4px; width:12px; height:12px; background:var(--brand-red); border-radius:50%; border:2px solid #FFF;"></div>
        <span style="font-size:0.75rem; font-weight:700; color:var(--brand-red); display:block; margin-bottom:0.25rem;">⏱️ <?php echo esc_html($time_str); ?></span>
        <h4 style="font-size:0.95rem; font-weight:700; line-height:1.35; margin:0;">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

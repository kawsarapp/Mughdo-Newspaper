<?php
/**
 * Audio Podcast Player Layout Component
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
    $title = __('পডকাস্ট ও অডিও খবর', 'mughdo-newspaper');
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

<section class="category-block-wrapper podcast-wrapper" style="background:#1E1B4B; padding:1.25rem; border-radius:var(--radius-md); color:#FFF;">
  <div class="section-header" style="margin-bottom:1rem; border-bottom:1px solid rgba(255,255,255,0.1); pb:0.5rem;">
    <h3 class="section-title" style="color:#818CF8;">🎙️ <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#A5B4FC;">সকল পডকাস্ট ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-3col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
      <article class="podcast-card" style="background:rgba(255,255,255,0.06); padding:0.85rem; border-radius:var(--radius-sm);">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.6rem;">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" style="width:60px; height:60px; object-fit:cover; border-radius:6px;" />
          <button style="background:#6366F1; color:#FFF; border:none; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:0.9rem; cursor:pointer;">▶</button>
        </div>
        <h4 style="font-size:0.9rem; font-weight:700; color:#FFF; line-height:1.3; margin:0;">
          <a href="<?php the_permalink(); ?>" style="color:#FFF;"><?php the_title(); ?></a>
        </h4>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

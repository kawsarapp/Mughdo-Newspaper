<?php
/**
 * Video Play-Icon Grid Layout Component
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
    $title = __('ভিডিও খবর (Video News)', 'mughdo-newspaper');
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

<section class="category-block-wrapper video-grid-wrapper" style="background:#0F172A; padding:1.25rem; border-radius:var(--radius-md); color:#FFF;">
  <div class="section-header" style="margin-bottom:1rem; border-bottom:1px solid rgba(255,255,255,0.1); pb:0.5rem;">
    <h3 class="section-title" style="color:#EF4444;">🎬 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#FCA5A5;">সব ভিডিও ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
      <article class="video-card" style="position:relative; border-radius:6px; overflow:hidden;">
        <a href="<?php the_permalink(); ?>">
          <div style="position:relative; aspect-ratio:16/9;">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy" />
            <div style="position:absolute; inset:0; background:rgba(0,0,0,0.35); display:flex; align-items:center; justify-content:center;">
              <div style="width:40px; height:40px; background:#EF4444; color:#FFF; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; padding-left:3px; box-shadow:0 0 12px rgba(239,68,68,0.6);">▶</div>
            </div>
          </div>
          <h4 style="font-size:0.85rem; font-weight:700; color:#FFF; margin-top:0.5rem; line-height:1.3;">
            <?php the_title(); ?>
          </h4>
        </a>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

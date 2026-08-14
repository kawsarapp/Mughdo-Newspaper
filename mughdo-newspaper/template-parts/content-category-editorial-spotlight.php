<?php
/**
 * Editorial Spotlight Banner Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 1;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('সম্পাদকীয় পছন্দ (Editorial Choice)', 'mughdo-newspaper');
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

<section class="category-block-wrapper spotlight-wrapper" style="background:linear-gradient(135deg, #1E293B, #0F172A); padding:1.5rem; border-radius:var(--radius-md); color:#FFF;">
  <div class="section-header" style="margin-bottom:1rem; border-bottom:1px solid rgba(255,255,255,0.1); pb:0.5rem;">
    <h3 class="section-title" style="color:#F59E0B;">⭐ <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#FBBF24;">আরও দেখুন ➔</a>
    <?php endif; ?>
  </div>

  <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
      $post_id  = get_the_ID();
      $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
  ?>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; align-items:center;">
      <div style="aspect-ratio:16/9; overflow:hidden; border-radius:8px;">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy" />
      </div>
      <div>
        <span style="font-size:0.8rem; font-weight:700; color:#F59E0B; text-transform:uppercase;">বিশেষ প্রতিবেদন</span>
        <h3 style="font-size:1.35rem; font-weight:800; color:#FFF; line-height:1.3; margin:0.4rem 0;">
          <a href="<?php the_permalink(); ?>" style="color:#FFF;"><?php the_title(); ?></a>
        </h3>
        <p style="font-size:0.9rem; color:#94A3B8; margin-top:0.5rem;">
          <?php echo esc_html(prothom_news_custom_excerpt(25, $post_id)); ?>
        </p>
      </div>
    </div>
  <?php endwhile; wp_reset_postdata(); ?>
</section>

<?php endif; ?>

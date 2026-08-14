<?php
/**
 * Photo & Video Gallery Carousel/Grid Module Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 4;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = !empty($custom_title) ? $custom_title : __('ছবি ও ভিডিও গ্যালারি', 'mughdo-newspaper');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$cat_query = new WP_Query($query_args);

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper gallery-section-wrapper">
  <div class="section-header">
    <h3 class="section-title">📷 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল ছবি ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
      <article class="gallery-card" style="position:relative; overflow:hidden; border-radius:var(--radius-md);">
        <a href="<?php the_permalink(); ?>">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; aspect-ratio:4/3; object-fit:cover; display:block;" loading="lazy" />
          <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.85), transparent 70%); display:flex; align-items:flex-end; padding:0.75rem;">
            <h4 style="font-size:0.85rem; font-weight:700; color:#FFF; line-height:1.25; margin:0;">
              <?php the_title(); ?>
            </h4>
          </div>
        </a>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

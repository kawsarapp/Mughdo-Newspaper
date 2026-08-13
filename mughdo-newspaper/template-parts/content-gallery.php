<?php
/**
 * Photo & Video Gallery Carousel/Grid Module Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_5';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');

$title = !empty($custom_title) ? $custom_title : __('ছবি ও ভিডিও গ্যালারি', 'prothom-news');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$gallery_query = new WP_Query($query_args);

if ($gallery_query->have_posts()) :
?>

<section class="gallery-section-wrapper">
  <div class="section-header" style="border-bottom-color:#334155;">
    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#38BDF8;"><?php esc_html_e('সব গ্যালারি →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="gallery-grid">
    <?php while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
        $post_id = get_the_ID();
        $img_url = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
    ?>
      <article class="gallery-card">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
        <div class="gallery-overlay">
          <h3 class="gallery-title">
            <a href="<?php the_permalink(); ?>">📷 <?php the_title(); ?></a>
          </h3>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

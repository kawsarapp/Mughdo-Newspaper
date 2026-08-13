<?php
/**
 * Dynamic Video News Grid Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_5';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');
$posts_count  = get_theme_mod("posts_count_{$block_id}", 4);

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('ভিডিও নিউজ', 'prothom-news');
}

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_count,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$cat_query = new WP_Query($query_args);

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper video-section-container" style="background:#0F172A; padding:1.5rem; border-radius:var(--radius-lg); color:#FFF;">
  <div class="section-header" style="border-bottom-color:#334155;">
    <h2 class="section-title" style="color:#FFF;">🔴 <?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#38BDF8;"><?php esc_html_e('সব ভিডিও →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="video-card">
        <div class="video-thumb-wrapper">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
            <span class="video-play-icon">▶</span>
          </a>
        </div>
        <div style="padding:0.75rem 0;">
          <h3 class="video-card-title">
            <a href="<?php the_permalink(); ?>" style="color:#FFF; font-size:0.95rem; font-weight:600; line-height:1.35;"><?php the_title(); ?></a>
          </h3>
          <span style="font-size:0.75rem; color:#94A3B8; display:block; margin-top:0.3rem;">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

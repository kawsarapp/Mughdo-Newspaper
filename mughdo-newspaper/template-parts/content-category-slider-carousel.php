<?php
/**
 * Photo Highlight Slider Carousel Layout Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_6';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');
$posts_count  = get_theme_mod("posts_count_{$block_id}", 4);

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('বিশেষ সংবাদ ও অ্যালবামের ছবি', 'prothom-news');
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

<section class="category-block-wrapper">
  <div class="section-header">
    <h2 class="section-title">🖼️ <?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link"><?php esc_html_e('সব অ্যালবামে যান →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="slider-carousel-container">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="slider-card">
        <div class="slider-img-wrapper">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="slider-card-body">
          <h3 class="slider-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <span style="font-size:0.75rem; color:var(--text-muted);">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

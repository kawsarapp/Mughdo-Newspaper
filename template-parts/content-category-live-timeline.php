<?php
/**
 * Live Event Updates Timeline Component (Pulsing Badge)
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_3';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');
$posts_count  = get_theme_mod("posts_count_{$block_id}", 5);

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('লাইভ আপডেট ও বিশেষ ঘটনা', 'prothom-news');
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

<section class="category-block-wrapper live-timeline-section">
  <div class="section-header">
    <h2 class="section-title" style="display:flex; align-items:center; gap:0.5rem;">
      <span class="live-pulse-badge">🔴 লাইভ</span> <?php echo esc_html($title); ?>
    </h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link"><?php esc_html_e('সব আপডেট →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="timeline-feed-list">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="timeline-item">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
          <span class="timeline-time"><?php echo esc_html($time_str); ?></span>
          <h3 class="timeline-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <p style="font-size:0.9rem; color:var(--text-secondary); margin-top:0.25rem;">
            <?php echo esc_html(prothom_news_custom_excerpt(18, $post_id)); ?>
          </p>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

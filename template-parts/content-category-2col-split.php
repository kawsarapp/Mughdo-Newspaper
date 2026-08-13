<?php
/**
 * 2-Column Split Magazine Layout Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_4';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');
$posts_count  = get_theme_mod("posts_count_{$block_id}", 4);

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('অর্থনীতি ও বাণিজ্য', 'prothom-news');
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
    $posts = $cat_query->posts;
    $half  = ceil(count($posts) / 2);
    $col1  = array_slice($posts, 0, $half);
    $col2  = array_slice($posts, $half);
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link"><?php esc_html_e('আরও পড়ুন →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="grid-2col-split">
    <!-- Left Column -->
    <div class="split-col">
      <?php foreach ($col1 as $p) : 
          $p_id   = $p->ID;
          $img    = prothom_news_get_post_thumbnail($p_id, 'prothom-thumb-rect');
          $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($p->post_date));
      ?>
        <article class="split-card">
          <div class="split-img-wrapper">
            <a href="<?php echo esc_url(get_permalink($p_id)); ?>">
              <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($p_id)); ?>" loading="lazy" />
            </a>
          </div>
          <div>
            <h3 class="split-title">
              <a href="<?php echo esc_url(get_permalink($p_id)); ?>"><?php echo esc_html(get_the_title($p_id)); ?></a>
            </h3>
            <p style="font-size:0.88rem; color:var(--text-secondary); line-height:1.4;">
              <?php echo esc_html(prothom_news_custom_excerpt(18, $p_id)); ?>
            </p>
            <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem; display:block;">🕒 <?php echo esc_html($time_str); ?></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Right Column -->
    <div class="split-col" style="border-left: 1px solid var(--border-color); padding-left: 1.5rem;">
      <?php foreach ($col2 as $p) : 
          $p_id   = $p->ID;
          $img    = prothom_news_get_post_thumbnail($p_id, 'prothom-thumb-rect');
          $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($p->post_date));
      ?>
        <article class="split-card">
          <div class="split-img-wrapper">
            <a href="<?php echo esc_url(get_permalink($p_id)); ?>">
              <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($p_id)); ?>" loading="lazy" />
            </a>
          </div>
          <div>
            <h3 class="split-title">
              <a href="<?php echo esc_url(get_permalink($p_id)); ?>"><?php echo esc_html(get_the_title($p_id)); ?></a>
            </h3>
            <p style="font-size:0.88rem; color:var(--text-secondary); line-height:1.4;">
              <?php echo esc_html(prothom_news_custom_excerpt(18, $p_id)); ?>
            </p>
            <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem; display:block;">🕒 <?php echo esc_html($time_str); ?></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php 
wp_reset_postdata();
endif; 
?>

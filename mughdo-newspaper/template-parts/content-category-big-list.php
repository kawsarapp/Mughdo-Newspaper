<?php
/**
 * 1 Big Hero Card + Right Stacked List Component
 * (Classic Prothom Alo Highlight Section Layout)
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
    $title = __('সারাদেশ', 'prothom-news');
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
    $hero_post = array_shift($posts);
    $list_posts = $posts;
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link"><?php esc_html_e('আরও দেখুন →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="big-list-layout-container">
    <!-- Big Left Hero Card -->
    <?php if ($hero_post) : 
        $hero_id   = $hero_post->ID;
        $hero_img  = prothom_news_get_post_thumbnail($hero_id, 'prothom-lead-hero');
        $time_str  = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($hero_post->post_date));
    ?>
      <article class="big-list-hero-card">
        <div class="big-list-img-wrapper">
          <a href="<?php echo esc_url(get_permalink($hero_id)); ?>">
            <img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr(get_the_title($hero_id)); ?>" loading="lazy" />
          </a>
        </div>
        <div class="big-list-hero-body">
          <h3 class="big-list-hero-title">
            <a href="<?php echo esc_url(get_permalink($hero_id)); ?>"><?php echo esc_html(get_the_title($hero_id)); ?></a>
          </h3>
          <p class="big-list-hero-excerpt">
            <?php echo esc_html(prothom_news_custom_excerpt(22, $hero_id)); ?>
          </p>
          <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.5rem; display:block;">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
    <?php endif; ?>

    <!-- Right Stacked List -->
    <div class="big-list-stacked-items">
      <?php foreach ($list_posts as $sub) : 
          $sub_id   = $sub->ID;
          $sub_img  = prothom_news_get_post_thumbnail($sub_id, 'prothom-thumb-square');
          $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($sub->post_date));
      ?>
        <article class="big-list-item">
          <div class="big-list-thumb">
            <a href="<?php echo esc_url(get_permalink($sub_id)); ?>">
              <img src="<?php echo esc_url($sub_img); ?>" alt="<?php echo esc_attr(get_the_title($sub_id)); ?>" loading="lazy" />
            </a>
          </div>
          <div>
            <h4 class="big-list-item-title">
              <a href="<?php echo esc_url(get_permalink($sub_id)); ?>"><?php echo esc_html(get_the_title($sub_id)); ?></a>
            </h4>
            <span style="font-size:0.75rem; color:var(--text-muted);"><?php echo esc_html($time_str); ?></span>
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

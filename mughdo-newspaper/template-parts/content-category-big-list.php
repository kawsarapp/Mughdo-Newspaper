<?php
/**
 * Hero Card + Side List Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 5;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('খেলাধুলা', 'mughdo-newspaper');
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

// Fallback if selected category has 0 posts in database
if (!$cat_query->have_posts()) {
    unset($query_args['cat']);
    $cat_query = new WP_Query($query_args);
}

if ($cat_query->have_posts()) :
    $first = true;
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title"><?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">আরও খবর ➔</a>
    <?php endif; ?>
  </div>

  <div class="lead-stories-wrapper">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));

        if ($first) :
            $first = false;
            $img_url = prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero');
    ?>
      <article class="lead-hero-card">
        <div class="lead-hero-img-wrapper">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="lead-hero-body">
          <h4 class="lead-hero-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
          <p style="font-size:0.95rem; color:var(--text-secondary); margin-top:0.4rem;">
            <?php echo esc_html(prothom_news_custom_excerpt(20, $post_id)); ?>
          </p>
          <span style="font-size:0.8rem; color:var(--text-muted); margin-top:0.5rem;">🕒 <?php echo esc_html($time_str); ?></span>
        </div>
      </article>
      
      <div class="sub-lead-list">
    <?php else : 
        $img_url = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
    ?>
        <article class="sub-lead-item">
          <div class="sub-lead-img-wrapper">
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
            </a>
          </div>
          <div>
            <h5 class="sub-lead-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h5>
            <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem; display:block;">🕒 <?php echo esc_html($time_str); ?></span>
          </div>
        </article>
    <?php endif; endwhile; wp_reset_postdata(); ?>
      </div>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * Magazine Hero Layout Block Template (1 Big Hero + 2 Cards + 4 Compact Items)
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id     = isset($args['cat_id']) ? $args['cat_id'] : 0;
$post_count = isset($args['post_count']) ? $args['post_count'] : 7;
$title      = isset($args['title']) ? $args['title'] : '';

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
    if (empty($title)) {
        $title = get_cat_name($cat_id);
    }
}

$block_query = new WP_Query($query_args);

if ($block_query->have_posts()) :
?>
<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title"><?php echo esc_html($title ? $title : 'ম্যাগাজিন ফিচারের খবর'); ?></h3>
    <?php if (!empty($cat_id) && $cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">আরও খবর ➔</a>
    <?php endif; ?>
  </div>

  <div class="magazine-hero-grid">
    <?php 
    $post_index = 0;
    while ($block_query->have_posts()) : $block_query->the_post();
        $post_index++;
        $p_id = get_the_ID();
        $bn_date = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
        
        if ($post_index === 1) :
    ?>
      <!-- Main Big Hero Card -->
      <article class="mag-hero-card">
        <div class="mag-hero-img-wrapper">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url(prothom_news_get_post_thumbnail($p_id, 'prothom-lead-hero')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="mag-hero-body">
          <h2 class="mag-hero-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <p class="mag-hero-excerpt"><?php echo esc_html(prothom_news_custom_excerpt(25, $p_id)); ?></p>
          <span style="font-size:0.8rem; color:var(--text-muted);"><?php echo esc_html($bn_date); ?></span>
        </div>
      </article>

      <div class="mag-sub-wrapper">
    <?php elseif ($post_index <= 3) : ?>
      <!-- 2 Sub Cards -->
      <article class="mag-sub-card">
        <div class="mag-sub-img">
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url(prothom_news_get_post_thumbnail($p_id, 'prothom-thumb-rect')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          </a>
        </div>
        <div class="mag-sub-body">
          <h4 class="mag-sub-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
        </div>
      </article>
    <?php else : ?>
      <!-- Compact List Items -->
      <article class="compact-item" style="padding:0.4rem 0;">
        <span class="compact-bullet">🔴</span>
        <h4 class="compact-title" style="font-size:0.95rem;">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
      </article>
    <?php 
        endif;
    endwhile; 
    wp_reset_postdata(); 
    ?>
      </div><!-- /.mag-sub-wrapper -->
  </div>
</section>
<?php endif; ?>

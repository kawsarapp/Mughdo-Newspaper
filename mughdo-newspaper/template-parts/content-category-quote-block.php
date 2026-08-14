<?php
/**
 * Quote & Statement Card Layout Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 3;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('উক্তি ও বক্তব্য (Quote & Statement)', 'mughdo-newspaper');
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

<section class="category-block-wrapper quote-block-wrapper">
  <div class="section-header">
    <h3 class="section-title">💬 <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল উক্তি ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-3col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id     = get_the_ID();
        $author_id   = get_post_field('post_author', $post_id);
        $author_name = get_the_author_meta('display_name', $author_id);
    ?>
      <article class="quote-card" style="background:#FFFBEB; border:1px solid #FCD34D; padding:1.2rem; border-radius:var(--radius-md); position:relative;">
        <span style="font-size:3rem; font-family:serif; color:#F59E0B; position:absolute; top:-10px; left:12px; line-height:1;">“</span>
        <p style="font-size:0.95rem; font-style:italic; color:#78350F; line-height:1.45; margin-top:0.8rem; margin-bottom:0.6rem;">
          <?php echo esc_html(prothom_news_custom_excerpt(18, $post_id)); ?>
        </p>
        <div style="font-weight:700; font-size:0.85rem; color:#B45309; text-align:right;">
          — <a href="<?php the_permalink(); ?>" style="color:#B45309;"><?php the_title(); ?></a>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * Editorial Columnist Spotlight Layout Box Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id     = isset($args['cat_id']) ? $args['cat_id'] : 0;
$post_count = isset($args['post_count']) ? $args['post_count'] : 4;
$title      = isset($args['title']) ? $args['title'] : '';

if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('সম্পাদকীয় কলাম ও বিশ্লেষণ', 'mughdo-newspaper');
}

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$block_query = new WP_Query($query_args);

if ($block_query->have_posts()) :
?>
<section class="category-block-wrapper">
  <div class="section-header">
    <h3 class="section-title">✍️ <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল কলাম ➔</a>
    <?php endif; ?>
  </div>

  <div class="opinion-grid">
    <?php 
    while ($block_query->have_posts()) : $block_query->the_post();
        $p_id = get_the_ID();
        $author_name = get_the_author();
    ?>
      <article class="opinion-card">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/author-avatar.svg'); ?>" alt="<?php echo esc_attr($author_name); ?>" class="opinion-avatar" />
        <span class="opinion-author-name">✍️ <?php echo esc_html($author_name); ?></span>
        <h4 class="opinion-headline">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

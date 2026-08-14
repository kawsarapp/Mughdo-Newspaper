<?php
/**
 * Opinion & Columnists Module Component
 *
 * @package MughdoNewspaper
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 4;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = !empty($custom_title) ? $custom_title : __('মতামত ও কলাম', 'mughdo-newspaper');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $post_count,
    'post_status'    => 'publish',
);

if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$cat_query = new WP_Query($query_args);

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper opinion-section-wrapper">
  <div class="section-header">
    <h3 class="section-title">✍️ <?php echo esc_html($title); ?></h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link">সকল কলাম ➔</a>
    <?php endif; ?>
  </div>

  <div class="grid-4col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id     = get_the_ID();
        $author_id   = get_post_field('post_author', $post_id);
        $author_name = get_the_author_meta('display_name', $author_id);
        $avatar_url  = get_avatar_url($author_id, array('size' => 64));
    ?>
      <article class="opinion-card" style="display:flex; align-items:flex-start; gap:0.85rem; padding:1rem; background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md);">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($author_name); ?>" style="width:50px; height:50px; border-radius:50%; object-fit:cover;" />
        <div>
          <span style="font-size:0.8rem; font-weight:700; color:var(--brand-red); display:block; margin-bottom:0.2rem;"><?php echo esc_html($author_name); ?></span>
          <h4 style="font-size:0.95rem; font-weight:700; line-height:1.35;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

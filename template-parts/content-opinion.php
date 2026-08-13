<?php
/**
 * Opinion & Columnists Module Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_6';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');

$title = !empty($custom_title) ? $custom_title : __('মতামত ও কলাম', 'prothom-news');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$opinion_query = new WP_Query($query_args);

if ($opinion_query->have_posts()) :
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link"><?php esc_html_e('সব মতামত →', 'prothom-news'); ?></a>
    <?php endif; ?>
  </div>

  <div class="opinion-grid">
    <?php while ($opinion_query->have_posts()) : $opinion_query->the_post(); 
        $author_id   = get_the_author_meta('ID');
        $author_name = get_the_author();
        $avatar_url  = get_avatar_url($author_id, array('size' => 150));
    ?>
      <article class="opinion-card">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($author_name); ?>" class="opinion-avatar" />
        <span class="opinion-author-name"><?php echo esc_html($author_name); ?></span>
        <h3 class="opinion-headline">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

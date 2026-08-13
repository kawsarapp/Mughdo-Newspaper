<?php
/**
 * Dynamic Quote / Dialogue Card Block Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_7';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');

$title = !empty($custom_title) ? $custom_title : __('দিনের উক্তি / বক্তব্য', 'prothom-news');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$quote_query = new WP_Query($query_args);

if ($quote_query->have_posts()) :
?>

<section class="category-block-wrapper">
  <div class="section-header">
    <h2 class="section-title">💬 <?php echo esc_html($title); ?></h2>
  </div>

  <?php while ($quote_query->have_posts()) : $quote_query->the_post(); 
      $post_id     = get_the_ID();
      $author_name = get_the_author();
      $avatar_url  = get_avatar_url(get_the_author_meta('ID'), array('size' => 120));
  ?>
    <div class="quote-card-container">
      <div class="quote-symbol">“</div>
      <div class="quote-content-body">
        <blockquote class="quote-text">
          <a href="<?php the_permalink(); ?>"><?php echo esc_html(prothom_news_custom_excerpt(35, $post_id)); ?></a>
        </blockquote>
        <div class="quote-author-info">
          <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($author_name); ?>" class="quote-author-img" />
          <div>
            <h4 class="quote-author-name"><?php echo esc_html($author_name); ?></h4>
            <span style="font-size:0.8rem; color:var(--text-muted);">বিশেষ কলামিস্ট / বক্তা</span>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; wp_reset_postdata(); ?>
</section>

<?php endif; ?>

<?php
/**
 * Breaking News Ticker Component for ProthomNews
 * Continuous CSS Marquee Scroll with Pause on Hover
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$ticker_cat   = get_theme_mod('ticker_category', 0);
$ticker_label = get_theme_mod('ticker_title', 'শিরোনাম:');

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
);

if (!empty($ticker_cat) && $ticker_cat > 0) {
    $args['cat'] = $ticker_cat;
}

$ticker_query = new WP_Query($args);

if ($ticker_query->have_posts()) :
?>
<div class="breaking-ticker-wrapper">
  <div class="container">
    <div class="ticker-inner">
      <span class="ticker-badge">🔥 <?php echo esc_html($ticker_label); ?></span>
      <div class="ticker-content-slide">
        <div class="ticker-marquee-track">
          <?php 
          $titles = array();
          while ($ticker_query->have_posts()) : $ticker_query->the_post();
              $titles[] = '<a href="' . esc_url(get_permalink()) . '" class="ticker-link">' . esc_html(get_the_title()) . '</a>';
          endwhile;
          wp_reset_postdata();
          echo implode(' <span class="ticker-dot">•</span> ', $titles);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

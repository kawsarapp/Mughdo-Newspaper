<?php
/**
 * Lead News Section Grid (Prothom Alo Highlight Style)
 * 1 Big Hero Story + 4 Sub-lead Thumbnails + Side Trending Sidebar
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$lead_cat = get_theme_mod('lead_news_category', 0);

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
);

if (!empty($lead_cat) && $lead_cat > 0) {
    $args['cat'] = $lead_cat;
}

$lead_query = new WP_Query($args);

if ($lead_query->have_posts()) :
    $posts = $lead_query->posts;
    $hero_post = array_shift($posts); // First post is Hero
    $sub_posts = $posts;              // Next 5 posts are Sub-leads
?>

<div class="lead-grid-container">
  
  <!-- Main 1 Hero + 4 Sub-leads Grid -->
  <div class="lead-stories-wrapper">
    
    <!-- Big Featured Hero Card + Left Hero Bottom Ad Slot -->
    <div class="hero-left-column">
      <?php if ($hero_post) : 
          $hero_id        = $hero_post->ID;
          $hero_title     = get_the_title($hero_id);
          $hero_permalink = get_permalink($hero_id);
          $hero_img       = prothom_news_get_post_thumbnail($hero_id, 'prothom-lead-hero');
          $hero_excerpt   = prothom_news_custom_excerpt(25, $hero_id);
          $hero_cats      = get_the_category($hero_id);
          $hero_cat_name  = !empty($hero_cats) ? $hero_cats[0]->name : '';
          $hero_time      = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($hero_post->post_date));
      ?>
        <article class="lead-hero-card">
          <div class="lead-hero-img-wrapper">
            <a href="<?php echo esc_url($hero_permalink); ?>">
              <img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr($hero_title); ?>" loading="eager" />
            </a>
          </div>
          <div class="lead-hero-body">
            <?php if ($hero_cat_name) : ?>
              <span class="lead-category-badge"><?php echo esc_html($hero_cat_name); ?></span>
            <?php endif; ?>
            <h2 class="lead-hero-title">
              <a href="<?php echo esc_url($hero_permalink); ?>"><?php echo esc_html($hero_title); ?></a>
            </h2>
            <p class="lead-hero-excerpt"><?php echo esc_html($hero_excerpt); ?></p>
            <div class="post-meta-bar">
              <span>🕒 <?php echo esc_html($hero_time); ?></span>
              <span>⏱️ <?php echo esc_html(prothom_news_reading_time($hero_id)); ?></span>
            </div>
          </div>
        </article>
      <?php endif; ?>

      <!-- Left Hero Bottom Custom Ad Slot 14 -->
      <?php ProthomNews_Theme_Options::render_ad('ad_hero_left_bottom', 'mt-3'); ?>
    </div>

    <!-- 4 Sub-lead Rectangular Thumbnail Column -->
    <div class="sub-lead-list">
      <?php foreach ($sub_posts as $sub) : 
          $sub_id        = $sub->ID;
          $sub_title     = get_the_title($sub_id);
          $sub_permalink = get_permalink($sub_id);
          $sub_img       = prothom_news_get_post_thumbnail($sub_id, 'prothom-thumb-rect');
          $sub_cats      = get_the_category($sub_id);
          $sub_cat_name  = !empty($sub_cats) ? $sub_cats[0]->name : '';
      ?>
        <article class="sub-lead-item">
          <div class="sub-lead-img-wrapper">
            <a href="<?php echo esc_url($sub_permalink); ?>">
              <img src="<?php echo esc_url($sub_img); ?>" alt="<?php echo esc_attr($sub_title); ?>" loading="lazy" />
            </a>
          </div>
          <div>
            <?php if ($sub_cat_name) : ?>
              <span style="font-size:0.75rem; color:var(--brand-red); font-weight:700; display:block; margin-bottom:2px;"><?php echo esc_html($sub_cat_name); ?></span>
            <?php endif; ?>
            <h3 class="sub-lead-title">
              <a href="<?php echo esc_url($sub_permalink); ?>"><?php echo esc_html($sub_title); ?></a>
            </h3>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Marked Space Ad Slot: Lead Hero Section Bottom Wide Banner (Slot 6) -->
    <div class="lead-stories-marked-ad my-2" style="grid-column: 1 / -1; width: 100%;">
      <?php ProthomNews_Theme_Options::render_ad('ad_hero_section_bottom'); ?>
    </div>

  </div>

  <!-- Right Sidebar (Trending Widget & Single Sidebar Ad Slot 3) -->
  <aside class="lead-sidebar">
    <?php get_template_part('template-parts/content-trending'); ?>

    <!-- Lead News Sidebar Ad Slot 3: Square Ad (300x250) -->
    <?php ProthomNews_Theme_Options::render_ad('ad_lead_sidebar', 'mt-3'); ?>
  </aside>

</div>

<?php 
wp_reset_postdata();
endif; 
?>

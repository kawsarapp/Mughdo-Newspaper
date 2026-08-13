<?php
/**
 * Tabbed Trending Widget Component (সর্বশেষ / পঠিত)
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

// Query Latest 5 Posts
$latest_posts = get_posts(array(
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
));

// Query Popular 5 Posts
$popular_posts = get_posts(array(
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'orderby'        => 'comment_count',
    'order'          => 'DESC',
));
?>

<div class="sidebar-trending-card">
  <div class="trending-tabs">
    <button class="tab-btn active" data-tab="latest"><?php esc_html_e('সর্বশেষ', 'prothom-news'); ?></button>
    <button class="tab-btn" data-tab="popular"><?php esc_html_e('পঠিত', 'prothom-news'); ?></button>
  </div>

  <!-- Latest List -->
  <div class="trending-list-container" id="trending-list-latest" style="display: flex;">
    <div class="trending-list">
      <?php 
      $count = 1;
      foreach ($latest_posts as $post) : 
          $bn_count = ProthomNews_Bangla_Date::convert_number($count);
          $bn_time  = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($post->post_date));
      ?>
        <div class="trending-item">
          <span class="trending-number"><?php echo esc_html($bn_count); ?></span>
          <div>
            <h4 class="trending-title">
              <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
            </h4>
            <span style="font-size:0.75rem; color:var(--text-muted);"><?php echo esc_html($bn_time); ?></span>
          </div>
        </div>
      <?php 
        $count++;
      endforeach; 
      ?>
    </div>
  </div>

  <!-- Popular List -->
  <div class="trending-list-container" id="trending-list-popular" style="display: none;">
    <div class="trending-list">
      <?php 
      $count = 1;
      foreach ($popular_posts as $post) : 
          $bn_count = ProthomNews_Bangla_Date::convert_number($count);
          $bn_time  = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime($post->post_date));
      ?>
        <div class="trending-item">
          <span class="trending-number"><?php echo esc_html($bn_count); ?></span>
          <div>
            <h4 class="trending-title">
              <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
            </h4>
            <span style="font-size:0.75rem; color:var(--text-muted);"><?php echo esc_html($bn_time); ?></span>
          </div>
        </div>
      <?php 
        $count++;
      endforeach; 
      ?>
    </div>
  </div>
</div>

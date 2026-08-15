<?php
/**
 * Video Play-Icon & Embed Grid Layout Component
 * Supports Interactive YouTube, YT Shorts, Facebook Videos & Facebook Reels Responsive Embed Players across Desktop PC, Laptop, Tab & Mobile.
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

$cat_id       = isset($args['cat_id']) ? intval($args['cat_id']) : 0;
$post_count   = isset($args['post_count']) ? intval($args['post_count']) : 4;
$custom_title = isset($args['title']) ? $args['title'] : '';

$title = $custom_title;
if (empty($title) && $cat_id > 0) {
    $cat_obj = get_category($cat_id);
    if ($cat_obj) $title = $cat_obj->name;
}
if (empty($title)) {
    $title = __('🎬 ভিডিও গ্যালারি ও ফেসবুক রিলস (Video Gallery & Reels)', 'mughdo-newspaper');
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

// Fallback demo video URLs if posts don't have custom video URLs
$demo_videos = array(
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'https://www.facebook.com/reel/123456789',
    'https://www.youtube.com/watch?v=9bZkp7q19f0',
    'https://www.facebook.com/watch/?v=10153231379946729',
);

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper video-grid-wrapper" style="background:#0F172A; padding:1.5rem; border-radius:var(--radius-md); color:#FFF; margin-bottom:2rem;">
  <div class="section-header" style="margin-bottom:1.25rem; border-bottom:1px solid rgba(255,255,255,0.15); padding-bottom:0.75rem; display:flex; align-items:center; justify-content:space-between;">
    <h3 class="section-title" style="color:#EF4444; font-size:1.3rem; font-weight:800;">
      🎬 <?php echo esc_html($title); ?>
    </h3>
    <?php if ($cat_id > 0) : ?>
      <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-more-link" style="color:#FCA5A5; font-size:0.9rem; font-weight:700;">সব ভিডিও ➔</a>
    <?php endif; ?>
  </div>

  <div class="video-embed-grid">
    <?php 
    $index = 0;
    while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $video_url = get_post_meta($post_id, 'video_embed_url', true);
        if (empty($video_url)) {
            // Check post content for video links
            $content = get_the_content();
            if (preg_match('/(https?:\/\/(?:www\.)?(?:youtube\.com|youtu\.be|facebook\.com|fb\.watch)\/[^\s<"]+)/i', $content, $match)) {
                $video_url = $match[1];
            } else {
                $video_url = $demo_videos[$index % count($demo_videos)];
            }
        }
        $index++;
    ?>
      <article class="video-embed-card" style="background:#1E293B; border-radius:var(--radius-md); padding:0.75rem; border:1px solid rgba(255,255,255,0.1);">
        <!-- Interactive Multi-Device Responsive Embed Player -->
        <?php echo ProthomNews_Video_Helper::render_responsive_embed($video_url, get_the_title()); ?>
        
        <h4 style="font-size:0.95rem; font-weight:700; color:#FFF; margin-top:0.75rem; line-height:1.4;">
          <a href="<?php the_permalink(); ?>" style="color:#F8FAFC; text-decoration:none;">
            <?php the_title(); ?>
          </a>
        </h4>
        <div style="font-size:0.8rem; color:#94A3B8; margin-top:0.35rem; display:flex; align-items:center; justify-content:space-between;">
          <span>🕒 <?php echo esc_html(get_the_date('j F, Y')); ?></span>
          <span style="background:rgba(239,68,68,0.2); color:#FCA5A5; padding:2px 8px; border-radius:4px; font-weight:600;">ভিডিও</span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

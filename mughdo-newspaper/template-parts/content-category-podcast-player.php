<?php
/**
 * Audio Podcast News Player Block Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_5';
$cat_id   = get_theme_mod("cat_{$block_id}", 0);
$custom_title = get_theme_mod("title_{$block_id}", '');
$posts_count  = get_theme_mod("posts_count_{$block_id}", 3);

$title = !empty($custom_title) ? $custom_title : __('পডকাস্ট ও অডিও খবর', 'prothom-news');

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_count,
    'post_status'    => 'publish',
);
if (!empty($cat_id) && $cat_id > 0) {
    $query_args['cat'] = $cat_id;
}

$cat_query = new WP_Query($query_args);

if ($cat_query->have_posts()) :
?>

<section class="category-block-wrapper podcast-block-container" style="background:linear-gradient(135deg, #1E1B4B, #0F172A); padding:1.5rem; border-radius:var(--radius-lg); color:#FFF;">
  <div class="section-header" style="border-bottom-color:#312E81;">
    <h2 class="section-title" style="color:#FFF;">🎧 <?php echo esc_html($title); ?></h2>
  </div>

  <div class="grid-3col">
    <?php while ($cat_query->have_posts()) : $cat_query->the_post(); 
        $post_id  = get_the_ID();
        $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
        $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    ?>
      <article class="podcast-card" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-md); padding:1rem; display:flex; flex-direction:column; gap:0.75rem;">
        <div style="display:flex; gap:0.85rem; align-items:center;">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" style="width:65px; height:65px; border-radius:var(--radius-sm); object-fit:cover;" />
          <div>
            <span style="font-size:0.75rem; color:#A7F3D0; font-weight:700;">AUDIO EPISODE</span>
            <h3 style="font-size:0.95rem; font-weight:700; color:#FFF; line-height:1.3;">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
          </div>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem; background:rgba(0,0,0,0.3); padding:0.4rem 0.8rem; border-radius:var(--radius-sm);">
          <button class="podcast-play-btn" style="background:#10B981; color:#FFF; border:none; width:30px; height:30px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center;">▶</button>
          <div style="flex:1; height:4px; background:#374151; border-radius:2px;">
            <div style="width:40%; height:100%; background:#10B981; border-radius:2px;"></div>
          </div>
          <span style="font-size:0.75rem; color:#9CA3AF;">০৫:৩০</span>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php endif; ?>

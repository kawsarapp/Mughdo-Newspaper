<?php
/**
 * Single News Post Template for ProthomNews
 * Rich Post Experience: Dynamic Review Box Position, Reader Reactions, Author Bio, Next/Prev Nav, Print & Copy Link.
 *
 * @package ProthomNews
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
    $post_id         = get_the_ID();
    $categories      = get_the_category();
    $primary_cat     = !empty($categories) ? $categories[0] : null;
    $bn_date         = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
    $reading_time    = prothom_news_reading_time($post_id);
    $author_id       = get_the_author_meta('ID');
    $author_name     = get_the_author();
    $author_desc     = get_the_author_meta('description');

    $review_global   = get_theme_mod('enable_global_review', 1);
    $review_pos      = get_theme_mod('review_position', 'above_hero');
?>

<div class="container">
  <div class="single-article-container">
    
    <!-- Main Article Column -->
    <article class="main-article-content">
      
      <!-- Category Badge & Breadcrumb -->
      <div class="article-header">
        <?php if ($primary_cat) : ?>
          <div class="article-category-title">
            <a href="<?php echo esc_url(get_category_link($primary_cat->term_id)); ?>">
              <?php echo esc_html($primary_cat->name); ?>
            </a>
          </div>
        <?php endif; ?>

        <!-- Headline -->
        <h1 class="article-main-title"><?php the_title(); ?></h1>

        <!-- Meta Info -->
        <div class="article-meta-bar">
          <div>
            <span>✍️ <?php echo esc_html($author_name); ?></span> | 
            <span>🕒 প্রকাশিত: <?php echo esc_html($bn_date); ?></span>
          </div>
          <div>
            <span>⏱️ <?php echo esc_html($reading_time); ?></span>
          </div>
        </div>
      </div>

      <!-- Reader Tools Bar (Font Resizer, Audio TTS Player, Print, Copy Link) -->
      <div class="reader-tools-bar">
        <span style="font-weight:700; font-size:0.85rem; color:var(--text-muted);">পাঠক টুলস:</span>
        <button id="tts-play-btn" class="tool-btn">🔊 <span>খবর শুনুন</span></button>
        <button onclick="window.print();" class="tool-btn">🖨️ <span>প্রিন্ট</span></button>
        <button id="copy-link-btn" class="tool-btn" data-url="<?php echo esc_url(get_permalink()); ?>">📋 <span>লিংক কপি</span></button>
        
        <div style="margin-left:auto; display:flex; gap:0.3rem;">
          <button id="font-increase-btn" class="tool-btn" title="ফন্ট বড় করুন">A+</button>
          <button id="font-decrease-btn" class="tool-btn" title="ফন্ট ছোট করুন">A-</button>
          <button id="font-reset-btn" class="tool-btn" title="রিসেট">A</button>
        </div>
      </div>

      <!-- Position 1: Review Badge Above Hero Photo -->
      <?php if ($review_global && $review_pos === 'above_hero') : ?>
        <?php ProthomNews_Review_System::render_review_box($post_id); ?>
      <?php endif; ?>

      <!-- Featured Image -->
      <div class="article-featured-image">
        <img src="<?php echo esc_url(prothom_news_get_post_thumbnail($post_id, 'prothom-lead-hero')); ?>" alt="<?php the_title_attribute(); ?>" loading="eager" />
        <?php if (get_the_post_thumbnail_caption()) : ?>
          <p style="font-size:0.85rem; color:var(--text-muted); margin-top:0.4rem; text-align:center;">
            📷 <?php echo esc_html(get_the_post_thumbnail_caption()); ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Position 2: Review Badge Below Hero Photo -->
      <?php if ($review_global && $review_pos === 'below_hero') : ?>
        <?php ProthomNews_Review_System::render_review_box($post_id); ?>
      <?php endif; ?>

      <!-- In-Article Ad Slot -->
      <?php ProthomNews_Theme_Options::render_ad('ad_single_post', 'my-3'); ?>

      <!-- Article Main Body Text -->
      <div class="article-body-content">
        <?php the_content(); ?>
      </div>

      <!-- Position 3: Review Badge Below Article Text -->
      <?php if ($review_global && $review_pos === 'below_content') : ?>
        <?php ProthomNews_Review_System::render_review_box($post_id); ?>
      <?php endif; ?>

      <!-- Reader Reactions Section -->
      <div class="reader-reactions-wrapper">
        <h4 style="font-size:1.1rem; margin-bottom:1rem; text-align:center;">সংবাদটি সম্পর্কে আপনার মতামত জানান:</h4>
        <div class="reaction-buttons-grid">
          <button class="reaction-btn" data-reaction="love" data-post-id="<?php echo esc_attr($post_id); ?>">
            <span class="reaction-emoji">❤️</span>
            <span class="reaction-label">চমৎকার</span>
            <span class="reaction-count" id="react-love-count">১২</span>
          </button>
          <button class="reaction-btn" data-reaction="insight" data-post-id="<?php echo esc_attr($post_id); ?>">
            <span class="reaction-emoji">😮</span>
            <span class="reaction-label">তথ্যবহুল</span>
            <span class="reaction-count" id="react-insight-count">২৫</span>
          </button>
          <button class="reaction-btn" data-reaction="sad" data-post-id="<?php echo esc_attr($post_id); ?>">
            <span class="reaction-emoji">😢</span>
            <span class="reaction-label">দুঃখজনক</span>
            <span class="reaction-count" id="react-sad-count">৮</span>
          </button>
          <button class="reaction-btn" data-reaction="angry" data-post-id="<?php echo esc_attr($post_id); ?>">
            <span class="reaction-emoji">😡</span>
            <span class="reaction-label">ক্ষোভ</span>
            <span class="reaction-count" id="react-angry-count">৪</span>
          </button>
          <button class="reaction-btn" data-reaction="like" data-post-id="<?php echo esc_attr($post_id); ?>">
            <span class="reaction-emoji">👍</span>
            <span class="reaction-label">লাইক</span>
            <span class="reaction-count" id="react-like-count">৪৫</span>
          </button>
        </div>
      </div>

      <!-- Social Share Buttons -->
      <div class="social-share-buttons">
        <span style="font-weight:700; font-size:0.9rem; margin-right:0.5rem;">শেয়ার করুন:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-fb">📘 Facebook</a>
        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-tw">🐤 Twitter</a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-wa">💬 WhatsApp</a>
      </div>

      <!-- Author Bio Box -->
      <div class="author-bio-card">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/author-avatar.svg'); ?>" alt="<?php echo esc_attr($author_name); ?>" class="author-bio-avatar" />
        <div class="author-bio-info">
          <h4 class="author-bio-name"><?php echo esc_html($author_name); ?></h4>
          <p class="author-bio-desc">
            <?php echo esc_html($author_desc ? $author_desc : 'ProthomNews-এর জ্যেষ্ঠ সাংবাদিক ও রিপোর্টার। দেশ ও আন্তর্জাতিক খবর নিয়ে নিয়মিত প্রতিবেদন লিখছেন।'); ?>
          </p>
        </div>
      </div>

      <!-- Next / Previous Article Navigation Bar -->
      <div class="next-prev-nav-bar">
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        ?>
        <div class="nav-prev-link">
          <?php if ($prev_post) : ?>
            <span style="font-size:0.75rem; color:var(--text-muted);">⬅️ পূর্ববর্তী খবর</span>
            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>"><?php echo esc_html($prev_post->post_title); ?></a>
          <?php endif; ?>
        </div>
        <div class="nav-next-link" style="text-align:right;">
          <?php if ($next_post) : ?>
            <span style="font-size:0.75rem; color:var(--text-muted);">পরবর্তী খবর ➡️</span>
            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>"><?php echo esc_html($next_post->post_title); ?></a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Related Posts Section -->
      <?php
      if ($primary_cat) {
          $related_query = new WP_Query(array(
              'post_type'      => 'post',
              'posts_per_page' => 3,
              'post__not_in'   => array($post_id),
              'cat'            => $primary_cat->term_id,
          ));

          if ($related_query->have_posts()) :
      ?>
        <section style="margin-top:3rem;">
          <div class="section-header">
            <h3 class="section-title">সম্পর্কিত খবর</h3>
          </div>
          <div class="grid-3col">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
              <article class="news-card">
                <div class="news-card-img">
                  <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url(prothom_news_get_post_thumbnail(get_the_ID())); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                  </a>
                </div>
                <div class="news-card-body">
                  <h4 class="news-card-title" style="font-size:0.95rem;">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h4>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </section>
      <?php 
          endif; 
      }
      ?>

      <!-- Comments Section (Renders clean comments.php) -->
      <?php
      if (comments_open() || get_comments_number()) :
          comments_template();
      endif;
      ?>

    </article>

    <!-- Sidebar Column -->
    <aside class="single-sidebar">
      <?php get_template_part('template-parts/content-trending'); ?>
      <?php ProthomNews_Theme_Options::render_ad('ad_lead_sidebar', 'mt-4'); ?>
    </aside>

  </div>
</div>

<?php 
endwhile; endif;
get_footer();

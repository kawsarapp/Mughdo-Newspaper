<?php
/**
 * Search Results Page Template for ProthomNews
 *
 * @package ProthomNews
 */

get_header();
?>

<div class="container">
  <div class="section-header" style="margin-top:1.5rem;">
    <h1 class="section-title">
      অনুসন্ধানের ফলাফল: "<?php echo esc_html(get_search_query()); ?>"
    </h1>
  </div>

  <?php if (have_posts()) : ?>
    <div class="grid-3col">
      <?php while (have_posts()) : the_post(); 
          $post_id  = get_the_ID();
          $img_url  = prothom_news_get_post_thumbnail($post_id, 'prothom-thumb-rect');
          $time_str = ProthomNews_Bangla_Date::get_gregorian_bn(strtotime(get_the_date('Y-m-d H:i:s')));
      ?>
        <article class="news-card">
          <div class="news-card-img">
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
            </a>
          </div>
          <div class="news-card-body">
            <h3 class="news-card-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.4;">
              <?php echo esc_html(prothom_news_custom_excerpt(20)); ?>
            </p>
            <span style="font-size:0.75rem; color:var(--text-muted); margin-top:0.5rem;">🕒 <?php echo esc_html($time_str); ?></span>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div style="margin:2rem 0; text-align:center;">
      <?php
      the_posts_pagination(array(
          'mid_size'  => 2,
          'prev_text' => __('« পূর্ববর্তী', 'prothom-news'),
          'next_text' => __('পরবর্তী »', 'prothom-news'),
      ));
      ?>
    </div>
  <?php else : ?>
    <div style="padding: 4rem 1rem; text-align: center; color:var(--text-muted);">
      <h3>আপনার খোঁজা কিওয়ার্ডের সাথে মিল রেখে কোনো সংবাদ পাওয়া যায়নি।</h3>
      <p style="margin-top:0.5rem;">অনুগ্রহ করে অন্য কোনো কিওয়ার্ড দিয়ে পুনরায় চেষ্টা করুন।</p>
    </div>
  <?php endif; ?>

</div>

<?php
get_footer();

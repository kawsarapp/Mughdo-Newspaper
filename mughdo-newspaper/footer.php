<?php
/**
 * ProthomNews Footer Template
 * Responsive & Mobile Optimized Footer Component
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

$fb_url = get_theme_mod('social_fb', '#');
$tw_url = get_theme_mod('social_tw', '#');
$yt_url = get_theme_mod('social_yt', '#');
$ig_url = get_theme_mod('social_ig', '#');
?>
</div><!-- /#spa-content-container -->

<!-- Live REST API Search Modal Overlay -->
<div id="search-modal-overlay" class="search-modal-overlay">
  <div class="search-modal-box">
    <button id="modal-close-btn" class="modal-close-btn" aria-label="Close Search">✕</button>
    <h3 style="margin-bottom: 1rem; font-size:1.25rem;">খবর খুঁজুন</h3>
    <div class="search-input-group">
      <input type="text" id="live-search-input" class="search-input-field" placeholder="শিরোনাম বা কিওয়ার্ড লিখুন..." autocomplete="off" />
    </div>
    <div id="live-search-results" class="live-search-results-grid"></div>
  </div>
</div>

<!-- Mobile Off-Canvas Drawer Menu -->
<?php get_template_part('template-parts/mobile-drawer'); ?>

<!-- Mobile App-like Sticky Bottom Bar -->
<?php get_template_part('template-parts/mobile-bottom-bar'); ?>

<!-- Sticky Bottom Banner Ad Slot -->
<?php 
$sticky_ad = get_theme_mod('ad_sticky_bottom', '');
if (!empty($sticky_ad)) : 
?>
  <div class="prothom-ad-slot ad-sticky-bottom">
    <button class="ad-close-btn" title="বিজ্ঞাপন বন্ধ করুন">✕</button>
    <div class="ad-content"><?php echo $sticky_ad; ?></div>
  </div>
<?php endif; ?>

<!-- Main Responsive Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Footer Column 1: Brand -->
      <div>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" style="font-size:1.8rem; font-weight:900; color:var(--brand-red);">
          <?php bloginfo('name'); ?>
        </a>
        <p style="margin-top:0.75rem; font-size:0.9rem; max-width:400px; color:var(--text-muted); line-height:1.6;">
          সত্যের সন্ধানে নির্ভীক। সর্বাধিক পঠিত ও নির্ভরযোগ্য বাংলা নিউজ পোর্টাল।
        </p>
        
        <!-- Social Icons -->
        <div class="footer-social-links">
          <?php if ($fb_url) : ?><a href="<?php echo esc_url($fb_url); ?>" target="_blank" rel="noopener" class="footer-social-btn">📘 Facebook</a><?php endif; ?>
          <?php if ($tw_url) : ?><a href="<?php echo esc_url($tw_url); ?>" target="_blank" rel="noopener" class="footer-social-btn">🐤 X / Twitter</a><?php endif; ?>
          <?php if ($yt_url) : ?><a href="<?php echo esc_url($yt_url); ?>" target="_blank" rel="noopener" class="footer-social-btn">▶️ YouTube</a><?php endif; ?>
          <?php if ($ig_url) : ?><a href="<?php echo esc_url($ig_url); ?>" target="_blank" rel="noopener" class="footer-social-btn">📸 Instagram</a><?php endif; ?>
        </div>
      </div>

      <!-- Footer Column 2: Categories -->
      <div>
        <h4 style="font-weight:700; margin-bottom:1rem; color:var(--text-primary); font-size:1.05rem;">বিভাগসমূহ</h4>
        <?php
        if (has_nav_menu('footer')) {
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'footer-links',
            ));
        } else {
            echo '<ul class="footer-links">';
            echo '<li><a href="' . esc_url(home_url('/')) . '">জাতীয়</a></li>';
            echo '<li><a href="' . esc_url(home_url('/')) . '">রাজনীতি</a></li>';
            echo '<li><a href="' . esc_url(home_url('/')) . '">অর্থনীতি</a></li>';
            echo '<li><a href="' . esc_url(home_url('/')) . '">খেলাধুলা</a></li>';
            echo '<li><a href="' . esc_url(home_url('/')) . '">বিজ্ঞান ও প্রযুক্তি</a></li>';
            echo '</ul>';
        }
        ?>
      </div>

      <!-- Footer Column 3: Contact & Rights -->
      <div>
        <h4 style="font-weight:700; margin-bottom:1rem; color:var(--text-primary); font-size:1.05rem;">যোগাযোগ ও অন্যান্য</h4>
        <p style="font-size:0.88rem; line-height:1.7; color:var(--text-muted); margin:0;">
          📍 ঢাকা, বাংলাদেশ<br>
          ✉️ ইমেইল: news@mughdonews.com<br>
          📞 ফোন: +৮৮০ ১২৩৪ ৫৬৭৮৯০
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      <p style="margin:0;">
        © <?php echo esc_html(date('Y')); ?> <strong><?php bloginfo('name'); ?></strong>। সর্বস্বত্ব সংরক্ষিত। | ডেভেলপড বাই <strong>Kawsar Ahmed</strong>
      </p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

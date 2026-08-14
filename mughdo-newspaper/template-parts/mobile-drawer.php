<?php
/**
 * Off-Canvas Mobile Drawer Menu Template
 * Enhanced Mobile UI with Quick Search Box & Enterprise Modules Navigation
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Mobile Off-Canvas Drawer Backdrop -->
<div id="mobile-drawer-backdrop" class="mobile-drawer-backdrop"></div>

<!-- Mobile Drawer Sidebar Container -->
<aside id="mobile-drawer" class="mobile-drawer">
  <div class="mobile-drawer-header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" style="font-size:1.8rem; color:var(--brand-red); font-weight:900;">
      <?php bloginfo('name'); ?>
    </a>
    <button id="mobile-drawer-close" class="mobile-drawer-close-btn" aria-label="Close Menu">✕</button>
  </div>

  <div class="mobile-drawer-body">
    <!-- Quick Search Box inside Drawer -->
    <div style="margin-bottom:1.25rem;">
      <button id="drawer-search-trigger" onclick="document.getElementById('mobile-drawer').classList.remove('active'); document.getElementById('search-modal-overlay').classList.add('active');" style="width:100%; padding:0.75rem 1rem; border-radius:var(--radius-md); border:1px solid var(--border-color); background:var(--bg-secondary); color:var(--text-muted); font-size:0.9rem; display:flex; align-items:center; justify-content:space-between; cursor:pointer;">
        <span>🔍 খবর খুঁজুন...</span>
        <span style="background:var(--brand-red); color:#FFF; padding:2px 8px; border-radius:4px; font-size:0.75rem;">সার্চ</span>
      </button>
    </div>

    <!-- Category Navigation -->
    <nav class="mobile-category-menu">
      <h4 style="font-size:0.85rem; text-transform:uppercase; color:var(--text-muted); margin-bottom:0.75rem; font-weight:700;">বিভাগসমূহ</h4>
      <?php
      if (has_nav_menu('primary')) {
          wp_nav_menu(array(
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'mobile-menu-list',
          ));
      } else {
          echo '<ul class="mobile-menu-list"><li><a href="' . esc_url(home_url('/')) . '">প্রচ্ছদ</a></li></ul>';
      }
      ?>
    </nav>

    <!-- Enterprise Features Quick Bar -->
    <div style="margin-top:2rem; padding-top:1rem; border-top:1px solid var(--border-color);">
      <h4 style="font-size:0.85rem; text-transform:uppercase; color:var(--text-muted); margin-bottom:0.75rem; font-weight:700;">টুলস ও উইজেট</h4>
      <div style="display:flex; flex-direction:column; gap:0.6rem; font-size:0.9rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm);">
          <span>🌦️ আজকের আবহাওয়া</span>
          <span style="font-weight:700; color:var(--brand-blue);">২৯°C ঢাকা</span>
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm);">
          <span>📈 ইউএস ডলার রেট</span>
          <span style="font-weight:700; color:#10B981;">১২১.৫০ ৳</span>
        </div>
      </div>
    </div>
  </div>
</aside>

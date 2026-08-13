<?php
/**
 * Off-Canvas Mobile Drawer Menu Template
 *
 * @package ProthomNews
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
    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" style="font-size:1.8rem;">
      <?php bloginfo('name'); ?>
    </a>
    <button id="mobile-drawer-close" class="mobile-drawer-close-btn" aria-label="Close Menu">✕</button>
  </div>

  <div class="mobile-drawer-body">
    <!-- Category Navigation -->
    <nav class="mobile-category-menu">
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
  </div>
</aside>

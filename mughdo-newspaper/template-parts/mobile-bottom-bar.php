<?php
/**
 * Sticky Mobile App-like Bottom Navigation Bar Template
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Mobile App-like Bottom Navigation Bar -->
<div class="mobile-app-bottom-bar">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="bottom-bar-item">
    <span class="bar-icon">🏠</span>
    <span class="bar-label">প্রচ্ছদ</span>
  </a>
  <button id="mobile-menu-trigger" class="bottom-bar-item">
    <span class="bar-icon">📂</span>
    <span class="bar-label">বিভাগ</span>
  </button>
  <button id="mobile-search-trigger" class="bottom-bar-item">
    <span class="bar-icon">🔍</span>
    <span class="bar-label">খুঁজুন</span>
  </button>
  <button id="mobile-theme-trigger" class="bottom-bar-item">
    <span class="bar-icon">🌙</span>
    <span class="bar-label">নাইট</span>
  </button>
</div>

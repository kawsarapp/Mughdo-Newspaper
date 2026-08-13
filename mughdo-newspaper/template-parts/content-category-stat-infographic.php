<?php
/**
 * Infographic & Key Statistics Counter Grid Component
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = isset($args['block_id']) ? $args['block_id'] : 'block_7';
$custom_title = get_theme_mod("title_{$block_id}", '');
$title = !empty($custom_title) ? $custom_title : __('অর্থনৈতিক সূচক ও গুরুত্বপূর্ণ উপাত্ত', 'prothom-news');
?>

<section class="category-block-wrapper stat-infographic-wrapper">
  <div class="section-header">
    <h2 class="section-title">📊 <?php echo esc_html($title); ?></h2>
  </div>

  <div class="grid-4col">
    <div class="stat-card" style="background:var(--bg-card); border:1px solid var(--border-color); padding:1.25rem; border-radius:var(--radius-md); text-align:center;">
      <span style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">ডলার বিনিময় হার</span>
      <h3 style="font-family:var(--font-serif); font-size:1.8rem; color:var(--brand-red); margin:0.3rem 0;">৳১২১.৫০</h3>
      <span style="font-size:0.75rem; color:#10B981;">▲ +০.১৫%</span>
    </div>

    <div class="stat-card" style="background:var(--bg-card); border:1px solid var(--border-color); padding:1.25rem; border-radius:var(--radius-md); text-align:center;">
      <span style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">সোনার দাম (২২ ক্যারেট)</span>
      <h3 style="font-family:var(--font-serif); font-size:1.8rem; color:var(--brand-red); margin:0.3rem 0;">৳১,১৬,০০০</h3>
      <span style="font-size:0.75rem; color:#EF4444;">▼ -৳৫০০</span>
    </div>

    <div class="stat-card" style="background:var(--bg-card); border:1px solid var(--border-color); padding:1.25rem; border-radius:var(--radius-md); text-align:center;">
      <span style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">শেয়ার বাজার (DSEX)</span>
      <h3 style="font-family:var(--font-serif); font-size:1.8rem; color:var(--brand-red); margin:0.3rem 0;">৫,৪১২.৩০</h3>
      <span style="font-size:0.75rem; color:#10B981;">▲ +১২.৪</span>
    </div>

    <div class="stat-card" style="background:var(--bg-card); border:1px solid var(--border-color); padding:1.25rem; border-radius:var(--radius-md); text-align:center;">
      <span style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">আজকের তাপমাত্রা</span>
      <h3 style="font-family:var(--font-serif); font-size:1.8rem; color:var(--brand-red); margin:0.3rem 0;">৩২° সে.</h3>
      <span style="font-size:0.75rem; color:var(--text-muted);">ঢাকা, বাংলাদেশ</span>
    </div>
  </div>
</section>

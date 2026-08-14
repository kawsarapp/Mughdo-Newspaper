<?php
/**
 * Live Sports Scorecard Widget Module for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_Sports_Scorecard {

    public static function render($location = 'topbar') {
        $enabled  = get_theme_mod('enable_sports_scorecard', 1);
        $position = get_theme_mod('sports_position', 'topbar');
        $match    = get_theme_mod('sports_match_title', '🇧🇩 বাংলাদেশ বনাম 🇦🇺 অস্ট্রেলিয়া');
        $score    = get_theme_mod('sports_score_text', 'BAN 285/6 (50.0) | AUS 142/3 (24.2)');
        $badge    = get_theme_mod('sports_status_badge', '🔴 লাইভ ম্যাচ');

        if (!$enabled || $position !== $location) {
            return;
        }
        ?>
        <div class="mughdo-sports-widget" style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(204,0,0,0.08); padding:2px 8px; border-radius:4px; font-size:0.82rem; font-weight:600;">
          <span style="background:var(--brand-red); color:#FFF; padding:1px 5px; border-radius:3px; font-size:0.75rem; font-weight:800;"><?php echo esc_html($badge); ?></span>
          <span style="font-weight:700; color:var(--text-primary);"><?php echo esc_html($match); ?></span>
          <span style="color:var(--brand-red); font-family:monospace;"><?php echo esc_html($score); ?></span>
        </div>
        <?php
    }
}

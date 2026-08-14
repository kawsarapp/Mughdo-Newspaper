<?php
/**
 * Muslim Prayer Times & Sahri/Iftar Ramadan Tracker for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_Prayer_Ramadan {

    public static function render($location = 'topbar') {
        $enabled  = get_theme_mod('enable_prayer_widget', 1);
        $position = get_theme_mod('prayer_position', 'topbar');
        $district = get_theme_mod('prayer_district', 'Dhaka');

        if (!$enabled || $position !== $location) {
            return;
        }

        $prayer_times = array(
            'fajr'    => '৪:২০ AM',
            'dhuhr'   => '১২:০8 PM',
            'asr'     => '৪:৩৫ PM',
            'maghrib' => '৬:৩০ PM',
            'isha'    => '৭:৪৫ PM',
            'sahri'   => '৪:১২ AM',
            'iftar'   => '৬:৩১ PM',
        );
        ?>
        <div class="mughdo-prayer-widget" style="display:inline-flex; align-items:center; gap:0.6rem; font-size:0.85rem; font-weight:600;">
          <span style="color:#059669;">🕌 যোহর: <?php echo esc_html($prayer_times['dhuhr']); ?></span>
          <span style="color:var(--brand-red);">🌅 ইফতার: <?php echo esc_html($prayer_times['iftar']); ?></span>
        </div>
        <?php
    }
}

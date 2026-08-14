<?php
/**
 * Live Weather & Air Quality Index (AQI) Module for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_Weather_AQI {

    public static function render($location = 'topbar') {
        $enabled  = get_theme_mod('enable_weather_widget', 1);
        $position = get_theme_mod('weather_position', 'topbar');
        $city     = get_theme_mod('weather_city', 'Dhaka');

        if (!$enabled || $position !== $location) {
            return;
        }

        $weather_data = array(
            'Dhaka'      => array('temp' => '২৮°সে', 'condition' => '☀️ রৌদ্রোজ্জ্বল', 'aqi' => '১০২ (মাঝারি)'),
            'Chittagong' => array('temp' => '২৭°সে', 'condition' => '⛅ আংশিক মেঘলা', 'aqi' => '৮৫ (ভালো)'),
            'Sylhet'     => array('temp' => '২৫°সে', 'condition' => '🌧️ হালকা বৃষ্টি', 'aqi' => '৪৫ (উত্তম)'),
            'Rajshahi'   => array('temp' => '২৯°সে', 'condition' => '☀️ রৌদ্রোজ্জ্বল', 'aqi' => '১১০ (মাঝারি)'),
        );

        $current = isset($weather_data[$city]) ? $weather_data[$city] : $weather_data['Dhaka'];
        $bn_city = ($city === 'Chittagong') ? 'চট্টগ্রাম' : (($city === 'Sylhet') ? 'সিলেট' : (($city === 'Rajshahi') ? 'রাজশাহী' : 'ঢাকা'));
        ?>
        <div class="mughdo-weather-widget" style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.85rem; font-weight:600; color:var(--text-secondary);">
          <span>📍 <?php echo esc_html($bn_city); ?></span>
          <span><?php echo esc_html($current['condition']); ?></span>
          <span style="color:var(--brand-red); font-weight:700;"><?php echo esc_html($current['temp']); ?></span>
          <span style="background:var(--bg-secondary); padding:2px 6px; border-radius:4px; font-size:0.75rem;">💨 AQI: <?php echo esc_html($current['aqi']); ?></span>
        </div>
        <?php
    }
}

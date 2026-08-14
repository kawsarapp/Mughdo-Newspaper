<?php
/**
 * Live Stock Market & Currency Exchange Tracker Module for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_Stock_Currency {

    public static function render($location = 'topbar') {
        $enabled  = get_theme_mod('enable_currency_widget', 1);
        $position = get_theme_mod('currency_position', 'topbar');
        $custom_text = get_theme_mod('currency_rates_custom', '');

        if (!$enabled || $position !== $location) {
            return;
        }

        $rates_display = !empty($custom_text) ? $custom_text : '💵 1 USD = 118.50 BDT | 💶 1 EUR = 129.20 BDT | 📈 DSEX: 5,420 ▲ +0.45%';
        ?>
        <div class="mughdo-currency-widget" style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.85rem; font-weight:600; color:var(--text-muted);">
          <span><?php echo esc_html($rates_display); ?></span>
        </div>
        <?php
    }
}

<?php
/**
 * Progressive Web App (PWA) Mobile App Engine for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_PWA_Engine {

    public static function init() {
        add_action('wp_head', array(__CLASS__, 'add_pwa_head_tags'));
        add_action('wp_footer', array(__CLASS__, 'register_service_worker'));
    }

    public static function add_pwa_head_tags() {
        if (!get_theme_mod('enable_pwa', 1)) {
            return;
        }
        $manifest_url = get_template_directory_uri() . '/manifest.webmanifest';
        echo '<link rel="manifest" href="' . esc_url($manifest_url) . '">' . "\n";
        echo '<meta name="theme-color" content="#CC0000">' . "\n";
        echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
        echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    }

    public static function register_service_worker() {
        if (!get_theme_mod('enable_pwa', 1)) {
            return;
        }
        $sw_url = get_template_directory_uri() . '/sw.js';
        ?>
        <script>
        if ('serviceWorker' in navigator) {
          window.addEventListener('load', function() {
            navigator.serviceWorker.register('<?php echo esc_url($sw_url); ?>')
            .catch(function(err) {
              console.log('SW registration failed: ', err);
            });
          });
        }
        </script>
        <?php
    }
}

Mughdo_PWA_Engine::init();

<?php
/**
 * Video Helper Utilities for Mughdo Newspaper
 * Responsive Embed Generator for YouTube Videos, YT Shorts, Facebook Videos & Facebook Reels
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Video_Helper {

    /**
     * Extract YouTube Video ID from any YouTube URL (Watch, Shorts, Embed, Shortlink)
     */
    public static function get_youtube_id($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : false;
    }

    /**
     * Check if a URL is a Facebook Reel or FB Video
     */
    public static function is_facebook_video($url) {
        return (strpos($url, 'facebook.com') !== false || strpos($url, 'fb.watch') !== false || strpos($url, 'fb.com') !== false);
    }

    /**
     * Check if a URL is a Facebook Reel specifically
     */
    public static function is_facebook_reel($url) {
        return (strpos($url, '/reel/') !== false || strpos($url, '/reels/') !== false);
    }

    /**
     * Generate Responsive Embed HTML for YouTube, FB Video or FB Reels
     */
    public static function render_responsive_embed($url, $title = '') {
        if (empty($url)) return '';

        $yt_id = self::get_youtube_id($url);
        if ($yt_id) {
            $is_shorts = (strpos($url, 'shorts') !== false);
            $ratio_class = $is_shorts ? 'ratio-reels' : 'ratio-16-9';
            
            $html  = '<div class="responsive-video-container ' . esc_attr($ratio_class) . '">';
            $html .= '<iframe src="https://www.youtube.com/embed/' . esc_attr($yt_id) . '?autoplay=0&rel=0" title="' . esc_attr($title) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
            $html .= '</div>';
            return $html;
        }

        if (self::is_facebook_video($url)) {
            $is_reel = self::is_facebook_reel($url);
            $ratio_class = $is_reel ? 'ratio-reels' : 'ratio-16-9';
            $encoded_url = urlencode($url);
            
            $html  = '<div class="responsive-video-container ' . esc_attr($ratio_class) . '">';
            $html .= '<iframe src="https://www.facebook.com/plugins/video.php?href=' . $encoded_url . '&show_text=false&width=500" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" loading="lazy"></iframe>';
            $html .= '</div>';
            return $html;
        }

        // Generic oEmbed Fallback
        return '<div class="responsive-video-container ratio-16-9">' . wp_oembed_get($url) . '</div>';
    }
}

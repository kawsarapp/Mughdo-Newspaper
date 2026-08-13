<?php
/**
 * Video Helper Utilities for ProthomNews
 * Auto YouTube / Vimeo Thumbnail & Duration Extractor
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Video_Helper {

    /**
     * Extract YouTube Video ID from any YouTube URL
     */
    public static function get_youtube_id($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : false;
    }

    /**
     * Get High Res YouTube Thumbnail URL
     */
    public static function get_youtube_thumb($url) {
        $video_id = self::get_youtube_id($url);
        if ($video_id) {
            return "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
        }
        return false;
    }
}

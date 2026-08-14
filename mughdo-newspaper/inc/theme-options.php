<?php
/**
 * Dynamic Theme Customizer Options Engine for Mughdo Newspaper
 * Custom Category Dropdown Control, 20 Dynamic Homepage Section Blocks, Dynamic Image & AdSense Ads Manager, GA4, Search Console, AI Crawler Controls, Review Settings, 7 Enterprise Modules & Typography Custom Fonts Manager
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Theme_Options {

    public static function init() {
        add_action('customize_register', array(__CLASS__, 'register_customizer'));
    }

    public static function register_customizer($wp_customize) {
        
        // Panel: Mughdo Newspaper Options
        $wp_customize->add_panel('prothom_news_panel', array(
            'title'       => __('Mughdo Newspaper কন্ট্রোল প্যানেল', 'mughdo-newspaper'),
            'priority'    => 10,
            'description' => __('হোমপেজের ৩টি থিম প্রিসেট, ২০টি সেকশন ব্লক, ৭টি এন্টারপ্রাইজ মডিউল, ডাইনামিক টাইপোগ্রাফি ফন্ট ম্যানেজার, ডাইনামিক বিজ্ঞাপন ম্যানেজার, GA4, Search Console ও AI Bot কন্ট্রোল কাস্টমাইজ করুন।', 'mughdo-newspaper'),
        ));

        // Get Dynamic Categories List for Select Control Choices
        $categories_list = get_categories(array('hide_empty' => false));
        $cat_choices = array(0 => '— সকল ক্যাটাগরি (All Categories) —');
        if (!empty($categories_list)) {
            foreach ($categories_list as $c) {
                $cat_choices[$c->term_id] = $c->name . ' (' . $c->count . ')';
            }
        }

        // Section 0: Homepage Presets & Language Selector
        $wp_customize->add_section('prothom_preset_section', array(
            'title'    => __('০. হোমপেজ প্রিসেট ও ওয়েবসাইট ভাষা (Language)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 5,
        ));

        $wp_customize->add_setting('portal_language', array(
            'default'           => 'bn',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('portal_language', array(
            'label'   => __('ওয়েবসাইট ভাষা নির্বাচন করুন (Website Language)', 'mughdo-newspaper'),
            'section' => 'prothom_preset_section',
            'type'    => 'select',
            'choices' => array(
                'bn' => 'বাংলা (Bengali)',
                'en' => 'English (ইংরেজি)',
            ),
        ));

        $wp_customize->add_setting('homepage_preset', array(
            'default'           => 'preset_1',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('homepage_preset', array(
            'label'    => __('হোমপেজ রেডিমেড ডিজাইন প্রিসেট', 'mughdo-newspaper'),
            'section'  => 'prothom_preset_section',
            'type'     => 'select',
            'choices'  => array(
                'preset_1' => 'হোমপেজ ১: ক্লাসিক প্রথম আলো গ্রিড (Homepage 1 Classic)',
                'preset_2' => 'হোমপেজ ২: ম্যাগাজিন ও ভিডিও পোর্টাল (Homepage 2 Magazine)',
                'preset_3' => 'হোমপেজ ৩: ভিজ্যুয়াল ও লাইভ টাইমলাইন (Homepage 3 Visual)',
                'custom'   => 'কাস্টম পছন্দ: ২০টি ব্লকের অর্ডার ও লেআউট (Custom Dynamic Blocks)',
            ),
        ));

        // Section 1: Lead News Grid & Ticker
        $wp_customize->add_section('prothom_lead_section', array(
            'title'    => __('১. লিড নিউজ ও ব্রেকিং টিংকার', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 10,
        ));

        $wp_customize->add_setting('ticker_title', array(
            'default'           => 'শিরোনাম:',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('ticker_title', array(
            'label'    => __('ব্রেকিং টিংকার লেবেল', 'mughdo-newspaper'),
            'section'  => 'prothom_lead_section',
            'type'     => 'text',
        ));

        $wp_customize->add_setting('ticker_category', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('ticker_category', array(
            'label'   => __('ব্রেকিং নিউজ ক্যাটাগরি', 'mughdo-newspaper'),
            'section' => 'prothom_lead_section',
            'type'    => 'select',
            'choices' => $cat_choices,
        ));

        $wp_customize->add_setting('lead_news_category', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('lead_news_category', array(
            'label'   => __('প্রধান লিড নিউজ ক্যাটাগরি', 'mughdo-newspaper'),
            'section' => 'prothom_lead_section',
            'type'    => 'select',
            'choices' => $cat_choices,
        ));

        // Section 2: 20 Dynamic Homepage Blocks Setup Loop
        $wp_customize->add_section('prothom_blocks_section', array(
            'title'    => __('২. ২০টি হোমপেজ ডাইনামিক ব্লক সেটিং (Add/Edit/Hide)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 20,
            'description' => __('ℹ️ টিপস: কাস্টম টাইটেল লিখলে সেটি ক্যাটাগরির নামের জায়গায় দেখাবে। যেকোনো ক্যাটাগরিতে পোস্ট সংখ্যা ০ হলেও ফিক্সড ফলব্যাক চালুর কারণে ব্লকটি চমৎকার দেখাবে।', 'mughdo-newspaper'),
        ));

        $layouts_options = array(
            '3col'                => '৩-কলাম স্ট্যান্ডার্ড কার্ড (3 Column Grid)',
            '4col'                => '৪-কলাম কমপ্যাক্ট গ্রিড (4 Column Grid)',
            'big_list'            => 'বড় হিরো কার্ড + সাইড লিস্ট (Hero + List)',
            '2col_split'          => '২-কলাম ম্যাগাজিন স্প্লিট (2-Column Magazine)',
            'overlay'             => 'ছবি ও গ্রেডিয়েন্ট টেক্সট কার্ড (Overlay Card)',
            'compact'             => 'শুধু শিরোনাম ও টেক্সট লিস্ট (Compact Text)',
            'tabbed_cat'          => 'সাব-ক্যাটাগরি ট্যাব ফিল্টার (Sub-Category Tabs)',
            'magazine_hero'       => 'ম্যাগাজিন হিরো গ্রিড (Magazine Hero)',
            'fact_check'          => 'সত্যতা যাচাই কার্ড (Fact Check Grid)',
            'live_timeline'       => 'লাইভ আপডেট টাইমলাইন (Live Timeline)',
            'podcast_player'      => 'অডিও পডকাস্ট প্লেয়ার গ্রিড (Podcast Grid)',
            'stat_infographic'    => 'ইনফোগ্রাফিক ডাটা কাউন্টার (Stat Counter)',
            'editorial_spotlight' => 'সম্পাদকীয় পছন্দ ব্যানার (Editorial Banner)',
            'video_grid'          => 'ভিডিও খবর প্লে-আইকন গ্রিড (Video Grid)',
            'slider_carousel'     => 'ফটো স্লাইডার ক্যারোসেল (Photo Slider)',
            'quote_block'         => 'উক্তি ও উদ্ধৃতি ব্লক (Quote Card)',
            'gallery'             => 'ফটো গ্যালারি (Photo Gallery)',
            'opinion'             => 'কলামিস্ট ও মতামত (Opinion Columnists)',
            'trending_cards'      => 'ট্রেন্ডিং র‍্যাঙ্কিং কার্ড (#১-#৪) (Trending Ranking)',
            'editorial_columnist' => 'কলামিস্ট লেখক প্রোফাইল (Columnist Spotlight)',
        );

        for ($i = 1; $i <= 20; $i++) {
            $wp_customize->add_setting("enable_block_{$i}", array(
                'default'           => ($i <= 12) ? 1 : 0,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("enable_block_{$i}", array(
                'label'    => sprintf(__('ব্লক %d: চালু/হাইড রাখুন (Show/Hide Block %d)', 'mughdo-newspaper'), $i, $i),
                'section'  => 'prothom_blocks_section',
                'type'     => 'checkbox',
            ));

            $wp_customize->add_setting("title_block_{$i}", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control("title_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: কাস্টম শিরোনাম (Custom Title)', 'mughdo-newspaper'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'text',
                'description' => 'ফাঁকা রাখলে স্বয়ংক্রিয়ভাবে ক্যাটাগরির নাম শিরোনাম হিসেবে শো করবে।',
            ));

            $wp_customize->add_setting("cat_block_{$i}", array(
                'default'           => 0,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("cat_block_{$i}", array(
                'label'   => sprintf(__('ব্লক %d: ক্যাটাগরি নির্বাচন করুন', 'mughdo-newspaper'), $i),
                'section' => 'prothom_blocks_section',
                'type'    => 'select',
                'choices' => $cat_choices,
            ));

            $wp_customize->add_setting("layout_block_{$i}", array(
                'default'           => '3col',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control("layout_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: লেআউট স্টাইল (২০টি অপশন)', 'mughdo-newspaper'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'select',
                'choices'     => $layouts_options,
                'description' => 'ℹ️ গাইড: যেকোনো লেআউটে ছবি (Featured Image) থাকলে সেরা লুক আসবে। "ভিডিও গ্রিড" বেছে নিলে প্লে-আইকনOverlay দেখাবে। "উক্তি" ও "কলামিস্ট" টাইপে লেখকের ছবি/উক্তি ফুটে উঠবে।',
            ));

            $wp_customize->add_setting("count_block_{$i}", array(
                'default'           => 6,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("count_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: সংবাদের সংখ্যা (১-২০)', 'mughdo-newspaper'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'number',
                'input_attrs' => array('min' => 1, 'max' => 20),
            ));

            $wp_customize->add_setting("order_block_{$i}", array(
                'default'           => $i,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("order_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: পজিশন/ক্রম নম্বর (১-২০)', 'mughdo-newspaper'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'number',
                'input_attrs' => array('min' => 1, 'max' => 20),
            ));
        }

        // Section 3: 7 Enterprise Modules Controls
        $wp_customize->add_section('mughdo_enterprise_section', array(
            'title'    => __('৩. ৭টি এন্টারপ্রাইজ মডিউল কন্ট্রোল (Enterprise Modules)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 21,
        ));

        // Weather
        $wp_customize->add_setting('enable_weather_widget', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_weather_widget', array(
            'label'   => __('🌦️ ১. লাইভ আবহাওয়া ও AQI উইজেট চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Currency
        $wp_customize->add_setting('enable_currency_widget', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_currency_widget', array(
            'label'   => __('📈 ২. শেয়ার বাজার ও মুদ্রার হার ট্র্যাকার চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Prayer
        $wp_customize->add_setting('enable_prayer_widget', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_prayer_widget', array(
            'label'   => __('🌙 ৩. আজান, সেহরি ও ইফতারের সময়সূচি চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Sports
        $wp_customize->add_setting('enable_sports_scorecard', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_sports_scorecard', array(
            'label'   => __('⚽ ৪. লাইভ ক্রিকেট/ফুটবল স্কোরকার্ড চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Voice Search
        $wp_customize->add_setting('enable_voice_search', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_voice_search', array(
            'label'   => __('🎙️ ৫. বাংলায় ভয়েস সার্চ (Voice Search) চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // PWA
        $wp_customize->add_setting('enable_pwa', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_pwa', array(
            'label'   => __('📱 ৬. PWA মোবাইল অ্যাপ সুবিধা (Installable PWA) চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Bookmarks
        $wp_customize->add_setting('enable_bookmark_system', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_bookmark_system', array(
            'label'   => __('📌 ৭. পাঠক বুকমার্ক ও "পরে পড়ুন" সিস্টেম চালু রাখুন', 'mughdo-newspaper'),
            'section' => 'mughdo_enterprise_section',
            'type'    => 'checkbox',
        ));

        // Section 4: Single Article Display Settings
        $wp_customize->add_section('prothom_single_display_section', array(
            'title'    => __('৪. সিঙ্গেল সংবাদ ডিসপ্লে ও মেটা অপশন', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 22,
        ));

        $wp_customize->add_setting('enable_reading_time', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_reading_time', array(
            'label'    => __('⏱️ সংবাদের আনুমানিক পড়ার সময় (Estimated Reading Time) প্রদর্শন চালু রাখুন', 'mughdo-newspaper'),
            'section'  => 'prothom_single_display_section',
            'type'     => 'checkbox',
        ));

        $wp_customize->add_setting('enable_post_author', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_post_author', array(
            'label'    => __('✍️ লেখকের নাম (Author Name) প্রদর্শন চালু রাখুন', 'mughdo-newspaper'),
            'section'  => 'prothom_single_display_section',
            'type'     => 'checkbox',
        ));

        $wp_customize->add_setting('enable_post_date', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_post_date', array(
            'label'    => __('🕒 প্রকাশের তারিখ ও সময় (Publish Date & Time) প্রদর্শন চালু রাখুন', 'mughdo-newspaper'),
            'section'  => 'prothom_single_display_section',
            'type'     => 'checkbox',
        ));

        $wp_customize->add_setting('enable_post_category', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_post_category', array(
            'label'    => __('🏷️ ক্যাটাগরি ব্যাজ (Category Badge) প্রদর্শন চালু রাখুন', 'mughdo-newspaper'),
            'section'  => 'prothom_single_display_section',
            'type'     => 'checkbox',
        ));

        // Section 5: Review & Rating Settings
        $wp_customize->add_section('prothom_review_section', array(
            'title'    => __('৫. রিভিউ ও রেটিং বক্স সেটিংস', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 25,
        ));

        $wp_customize->add_setting('enable_global_review', array('default' => 1, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_global_review', array(
            'label'    => __('রিভিউ বক্স প্রদর্শন চালু রাখুন', 'mughdo-newspaper'),
            'section'  => 'prothom_review_section',
            'type'     => 'checkbox',
        ));

        // Section 6: Dynamic Ads Engine Manager (Image Banner Ads + AdSense Code Modes)
        $wp_customize->add_section('prothom_ads_section', array(
            'title'    => __('৬. ডাইনামিক বিজ্ঞাপন ম্যানেজার (Custom Image & AdSense Ads)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 30,
        ));

        $ad_slots = array(
            'ad_header_top'   => array('label' => '১. হেডার সেরা বিজ্ঞাপন (Header Top)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 728 x 90 পিক্সেল'),
            'ad_after_lead'   => array('label' => '২. লিড নিউজের পর মেগা লিডারবোর্ড (After Lead)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 970 x 90 পিক্সেল'),
            'ad_lead_sidebar' => array('label' => '৩. সাইডবার স্কাইস্ক্রেপার বিজ্ঞাপন (Sidebar)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 300 x 250 পিক্সেল'),
            'ad_middle_home'  => array('label' => '৪. হোমপেজ মাঝখানের বিজ্ঞাপন (Middle Home)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 728 x 90 পিক্সেল'),
            'ad_single_post'  => array('label' => '৫. সংবাদের ভেতরের ইন-আর্টিকেল বিজ্ঞাপন (In-Article)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 300 x 250 পিক্সেল'),
            'ad_sticky_bottom'=> array('label' => '৬. স্টিকি বটম মোবাইল বিজ্ঞাপন (Sticky Bottom)', 'size' => 'অনুমোদিত ব্যানার সাইজ: 320 x 50 পিক্সেল'),
        );

        foreach ($ad_slots as $slot_key => $slot_data) {
            
            // Ad Mode (Custom Image Banner vs HTML/AdSense Code)
            $wp_customize->add_setting($slot_key . '_type', array('default' => 'image', 'sanitize_callback' => 'sanitize_text_field'));
            $wp_customize->add_control($slot_key . '_type', array(
                'label'   => esc_html($slot_data['label']) . ' - বিজ্ঞাপন মোড',
                'section' => 'prothom_ads_section',
                'type'    => 'select',
                'choices' => array(
                    'image' => '🖼️ কাস্টম ব্যানার ইমেজ + টার্গেট লিংক (Custom Banner Image)',
                    'code'  => '💻 Google AdSense / HTML কোড (AdSense Code)',
                ),
                'description' => esc_html($slot_data['size']),
            ));

            // Image URL
            $wp_customize->add_setting($slot_key . '_img', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
            $wp_customize->add_control($slot_key . '_img', array(
                'label'       => '— ব্যানার ইমেজের URL (Image Banner Link)',
                'section'     => 'prothom_ads_section',
                'type'        => 'url',
                'description' => 'ছবি আপলোড করে URL বসান (ফাঁকা রাখলে ডেমো ইমেজ শো করবে)।',
            ));

            // Target Link URL
            $wp_customize->add_setting($slot_key . '_url', array('default' => '#', 'sanitize_callback' => 'esc_url_raw'));
            $wp_customize->add_control($slot_key . '_url', array(
                'label'       => '— টার্গেট ল্যান্ডিং পেজ লিংক (Target Destination Link)',
                'section'     => 'prothom_ads_section',
                'type'        => 'url',
                'description' => 'বিজ্ঞাপনে ক্লিক করলে পাঠকরা যে ওয়েবসাইটে যাবে।',
            ));

            // AdSense HTML Code
            $wp_customize->add_setting($slot_key, array('default' => '', 'sanitize_callback' => 'wp_kses_post'));
            $wp_customize->add_control($slot_key, array(
                'label'       => '— Google AdSense / HTML কোড',
                'section'     => 'prothom_ads_section',
                'type'        => 'textarea',
                'description' => 'AdSense কোড মোড সিলেক্ট করলে এই কোড কাজ করবে।',
            ));
        }

        // Section 7: Advanced SEO, GA4, Search Console & AI Bot Controls
        $wp_customize->add_section('mughdo_advanced_seo_section', array(
            'title'    => __('৭. ওয়েবমাস্টার, GA4 ও AI ক্রলার কন্ট্রোল (SEO, Analytics & AI Bots)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 35,
        ));

        // GA4
        $wp_customize->add_setting('ga4_measurement_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control('ga4_measurement_id', array(
            'label'       => __('📊 Google Analytics 4 Measurement ID', 'mughdo-newspaper'),
            'section'     => 'mughdo_advanced_seo_section',
            'type'        => 'text',
        ));

        // Search Console
        $wp_customize->add_setting('search_console_code', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control('search_console_code', array(
            'label'       => __('🔍 Google Search Console HTML Verification Code', 'mughdo-newspaper'),
            'section'     => 'mughdo_advanced_seo_section',
            'type'        => 'text',
        ));

        // AI Bot Blocking
        $wp_customize->add_setting('enable_ai_bot_blocking', array('default' => 0, 'sanitize_callback' => 'absint'));
        $wp_customize->add_control('enable_ai_bot_blocking', array(
            'label'       => __('🤖 AI ক্রলার প্রতিরোধ (Block AI Bots: ChatGPT, Claude, Perplexity)', 'mughdo-newspaper'),
            'section'     => 'mughdo_advanced_seo_section',
            'type'        => 'checkbox',
        ));

        // Section 8: Typography & Custom Fonts Manager
        $wp_customize->add_section('prothom_typography_section', array(
            'title'    => __('৮. টাইপোগ্রাফি ও কাস্টম ফন্ট (Typography & Fonts)', 'mughdo-newspaper'),
            'panel'    => 'prothom_news_panel',
            'priority' => 40,
            'description' => __('শিরোনাম ও মূল খবরের লেখার জন্য যেকোনো জনপ্রিয় বাংলা ফন্ট বেছে নিন অথবা আপনার নিজস্ব কাস্টম ফন্ট ফাইল লিঙ্ক করুন।', 'mughdo-newspaper'),
        ));

        // Heading Font Select
        $wp_customize->add_setting('heading_font', array('default' => 'solaimanlipi', 'sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control('heading_font', array(
            'label'   => __('🏷️ শিরোনাম / হেডিংয়ের ফন্ট (Heading & Title Font)', 'mughdo-newspaper'),
            'section' => 'prothom_typography_section',
            'type'    => 'select',
            'choices' => array(
                'solaimanlipi'       => 'সোলাইমানলিপি (SolaimanLipi - প্রপোজড বাংলা নিউজ)',
                'hind_siliguri'      => 'হিন্দ শিলিগুড়ি (Hind Siliguri - গোটিক আধুনিক)',
                'noto_serif_bengali' => 'নোতো শেরিফ বাংলা (Noto Serif Bengali - ক্লাসিক)',
                'kalpurush'          => 'কালপুরুষ (Kalpurush - সুপরিচিত বাংলা ফন্ট)',
                'tiro_bangla'        => 'তিরো বাংলা (Tiro Bangla - ট্রেডিশনাল প্রিন্ট)',
                'mina'               => 'মিনা (Mina - মডার্ন ডিজিটাল)',
                'outfit'             => 'Outfit (আকর্ষণীয় ইংরেজি ও বাংলা)',
                'inter'              => 'Inter (ক্লিন ও পরিষ্কার ডিজিটাল)',
                'custom'             => '🔗 কাস্টম ফন্ট ফাইল URL (Custom Web Font)',
            ),
        ));

        // Body Text Font Select
        $wp_customize->add_setting('body_font', array('default' => 'solaimanlipi', 'sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control('body_font', array(
            'label'   => __('📝 সংবাদের মূল লেখার ফন্ট (Body Text Font)', 'mughdo-newspaper'),
            'section' => 'prothom_typography_section',
            'type'    => 'select',
            'choices' => array(
                'solaimanlipi'      => 'সোলাইমানলিপি (SolaimanLipi - পড়ার জন্য সেরা)',
                'hind_siliguri'     => 'হিন্দ শিলিগুড়ি (Hind Siliguri)',
                'noto_sans_bengali' => 'নোতো সান্স বাংলা (Noto Sans Bengali)',
                'kalpurush'         => 'কালপুরুষ (Kalpurush)',
                'inter'             => 'Inter (ক্লিন)',
                'system'            => 'সিস্টেম ডিফল্ট (System Default)',
            ),
        ));

        // Custom Font URL Input
        $wp_customize->add_setting('custom_font_url', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        $wp_customize->add_control('custom_font_url', array(
            'label'       => __('🔗 কাস্টম ফন্ট সিএসএস বা ফাইল URL (Custom Font CSS URL)', 'mughdo-newspaper'),
            'section'     => 'prothom_typography_section',
            'type'        => 'url',
            'description' => 'কাস্টম ফন্ট সিলেক্ট করলে এখানে আপনার .css বা .woff2 ফাইল লিংক বসান।',
        ));
    }

    /**
     * Render Dynamic Banner Image Ads or AdSense Code
     */
    public static function render_ad($slot_key, $class_name = '') {
        $ad_type  = get_theme_mod($slot_key . '_type', 'image');
        $ad_code  = get_theme_mod($slot_key, '');
        $img_url  = get_theme_mod($slot_key . '_img', '');
        $link_url = get_theme_mod($slot_key . '_url', '#');
        $alt_text = get_theme_mod($slot_key . '_alt', 'স্পনসরড ব্যানার বিজ্ঞাপন');

        if (empty($img_url)) {
            $theme_uri = get_template_directory_uri();
            if ($slot_key === 'ad_header_top' || $slot_key === 'ad_middle_home') {
                $img_url = $theme_uri . '/assets/images/ads/ad-728x90.svg';
            } elseif ($slot_key === 'ad_after_lead') {
                $img_url = $theme_uri . '/assets/images/ads/ad-970x90.svg';
            } elseif ($slot_key === 'ad_lead_sidebar' || $slot_key === 'ad_single_post') {
                $img_url = $theme_uri . '/assets/images/ads/ad-300x250.svg';
            } elseif ($slot_key === 'ad_sticky_bottom') {
                $img_url = $theme_uri . '/assets/images/ads/ad-320x50.svg';
            }
        }

        echo '<div class="prothom-ad-slot ' . esc_attr($class_name) . '">';
        echo '<span class="ad-label">বিজ্ঞাপন</span>';
        
        if ($ad_type === 'code' && !empty($ad_code)) {
            echo wp_kses_post($ad_code);
        } else {
            echo '<a href="' . esc_url($link_url) . '" target="_blank" rel="noopener noreferrer">';
            echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($alt_text) . '" loading="lazy" style="max-width:100%; height:auto; display:block; margin:0 auto; border-radius:6px;" />';
            echo '</a>';
        }

        if ($slot_key === 'ad_sticky_bottom') {
            echo '<button class="ad-close-btn" style="position:absolute; top:2px; right:4px; background:rgba(0,0,0,0.6); color:#FFF; border:none; border-radius:50%; width:20px; height:20px; font-size:12px; cursor:pointer;">✕</button>';
        }
        
        echo '</div>';
    }
}

ProthomNews_Theme_Options::init();

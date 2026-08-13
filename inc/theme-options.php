<?php
/**
 * Dynamic Theme Customizer Options Engine for ProthomNews
 * Custom Category Dropdown Control, 15 Dynamic Homepage Section Blocks, 3 Preset Homepages, Review Settings & Ad Engine
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

// Custom Category Dropdown Control Class
if (class_exists('WP_Customize_Control')) {
    class ProthomNews_Category_Dropdown_Control extends WP_Customize_Control {
        public $type = 'prothom_category_dropdown';

        public function render_content() {
            $categories = get_categories(array('hide_empty' => false));
            ?>
            <label>
              <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
              <?php endif; ?>
              <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
              <?php endif; ?>

              <select <?php $this->link(); ?>>
                <option value="0"><?php esc_html_e('— সকল ক্যাটাগরি (All Categories) —', 'prothom-news'); ?></option>
                <?php foreach ($categories as $cat) : ?>
                  <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($this->value(), $cat->term_id); ?>>
                    <?php echo esc_html($cat->name . ' (' . $cat->count . ')'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
            <?php
        }
    }
}

class ProthomNews_Theme_Options {

    public static function init() {
        add_action('customize_register', array(__CLASS__, 'register_customizer'));
    }

    public static function register_customizer($wp_customize) {
        
        // Panel: ProthomNews Global Options
        $wp_customize->add_panel('prothom_news_panel', array(
            'title'       => __('ProthomNews কন্ট্রোল প্যানেল', 'prothom-news'),
            'priority'    => 10,
            'description' => __('হোমপেজের ৩টি থিম প্রিসেট, ১৫টি সেকশন ব্লক ও রিভিউ বক্স কাস্টমাইজ করুন।', 'prothom-news'),
        ));

        // Section 0: Homepage Presets Selector
        $wp_customize->add_section('prothom_preset_section', array(
            'title'    => __('০. হোমপেজ লেআউট প্রিসেট (Preset Select)', 'prothom-news'),
            'panel'    => 'prothom_news_panel',
            'priority' => 5,
        ));

        $wp_customize->add_setting('homepage_preset', array(
            'default'           => 'preset_1',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('homepage_preset', array(
            'label'    => __('হোমপেজ রেডিমেড ডিজাইন প্রিসেট', 'prothom-news'),
            'section'  => 'prothom_preset_section',
            'type'     => 'select',
            'choices'  => array(
                'preset_1' => 'হোমপেজ ১: ক্লাসিক প্রথম আলো গ্রিড (Homepage 1 Classic)',
                'preset_2' => 'হোমপেজ ২: ম্যাগাজিন ও ভিডিও পোর্টাল (Homepage 2 Magazine)',
                'preset_3' => 'হোমপেজ ৩: ভিজ্যুয়াল ও লাইভ টাইমলাইন (Homepage 3 Visual)',
                'custom'   => 'কাস্টম পছন্দ: ১৫টি ব্লকের অর্ডার ও লেআউট (Custom Dynamic Blocks)',
            ),
        ));

        // Section 1: Lead News Grid & Ticker
        $wp_customize->add_section('prothom_lead_section', array(
            'title'    => __('১. লিড নিউজ ও ব্রেকিং টিংকার', 'prothom-news'),
            'panel'    => 'prothom_news_panel',
            'priority' => 10,
        ));

        $wp_customize->add_setting('ticker_title', array(
            'default'           => 'শিরোনাম:',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('ticker_title', array(
            'label'    => __('ব্রেকিং টিংকার লেবেল', 'prothom-news'),
            'section'  => 'prothom_lead_section',
            'type'     => 'text',
        ));

        $wp_customize->add_setting('ticker_category', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new ProthomNews_Category_Dropdown_Control($wp_customize, 'ticker_category', array(
            'label'    => __('ব্রেকিং নিউজ ক্যাটাগরি', 'prothom-news'),
            'section'  => 'prothom_lead_section',
        )));

        $wp_customize->add_setting('lead_news_category', array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new ProthomNews_Category_Dropdown_Control($wp_customize, 'lead_news_category', array(
            'label'    => __('প্রধান লিড নিউজ ক্যাটাগরি', 'prothom-news'),
            'section'  => 'prothom_lead_section',
        )));

        // Section 2: 15 Dynamic Homepage Blocks Setup Loop
        $wp_customize->add_section('prothom_blocks_section', array(
            'title'    => __('২. ১৫টি হোমপেজ ডাইনামিক ব্লক সেটিং', 'prothom-news'),
            'panel'    => 'prothom_news_panel',
            'priority' => 20,
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
        );

        for ($i = 1; $i <= 15; $i++) {
            $wp_customize->add_setting("enable_block_{$i}", array(
                'default'           => ($i <= 10) ? 1 : 0,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("enable_block_{$i}", array(
                'label'    => sprintf(__('ব্লক %d: চালু রাখুন', 'prothom-news'), $i),
                'section'  => 'prothom_blocks_section',
                'type'     => 'checkbox',
            ));

            $wp_customize->add_setting("cat_block_{$i}", array(
                'default'           => 0,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control(new ProthomNews_Category_Dropdown_Control($wp_customize, "cat_block_{$i}", array(
                'label'    => sprintf(__('ব্লক %d: ক্যাটাগরি নির্বাচন করুন', 'prothom-news'), $i),
                'section'  => 'prothom_blocks_section',
            )));

            $wp_customize->add_setting("layout_block_{$i}", array(
                'default'           => '3col',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control("layout_block_{$i}", array(
                'label'   => sprintf(__('ব্লক %d: লেআউট স্টাইল', 'prothom-news'), $i),
                'section' => 'prothom_blocks_section',
                'type'    => 'select',
                'choices' => $layouts_options,
            ));

            $wp_customize->add_setting("count_block_{$i}", array(
                'default'           => 6,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("count_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: সংবাদের সংখ্যা', 'prothom-news'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'number',
                'input_attrs' => array('min' => 1, 'max' => 15),
            ));

            $wp_customize->add_setting("order_block_{$i}", array(
                'default'           => $i,
                'sanitize_callback' => 'absint',
            ));
            $wp_customize->add_control("order_block_{$i}", array(
                'label'       => sprintf(__('ব্লক %d: পজিশন/ক্রম নম্বর', 'prothom-news'), $i),
                'section'     => 'prothom_blocks_section',
                'type'        => 'number',
                'input_attrs' => array('min' => 1, 'max' => 15),
            ));
        }

        // Section 3: Review & Rating Settings (Full Position & Title Control)
        $wp_customize->add_section('prothom_review_section', array(
            'title'    => __('৩. রিভিউ ও রেটিং বক্স সেটিংস', 'prothom-news'),
            'panel'    => 'prothom_news_panel',
            'priority' => 25,
        ));

        // Enable Review Box Globally
        $wp_customize->add_setting('enable_global_review', array(
            'default'           => 1,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('enable_global_review', array(
            'label'    => __('রিভিউ বক্স প্রদর্শন চালু রাখুন', 'prothom-news'),
            'section'  => 'prothom_review_section',
            'type'     => 'checkbox',
        ));

        // Review Box Position
        $wp_customize->add_setting('review_position', array(
            'default'           => 'above_hero',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('review_position', array(
            'label'   => __('রিভিউ বক্সের পজিশন/স্থান', 'prothom-news'),
            'section' => 'prothom_review_section',
            'type'    => 'select',
            'choices' => array(
                'above_hero'    => 'শিরোনাম ও ফটোর মাঝামাঝি (Above Featured Photo)',
                'below_hero'    => 'ফটোর নিচে ও সংবাদের উপরে (Below Photo)',
                'below_content' => 'সংবাদের লেখার ঠিক নিচে (Below Article Text)',
            ),
        ));

        // Default Review Title
        $wp_customize->add_setting('default_review_title', array(
            'default'           => 'সামগ্রিক পর্যালোচনা ও মতামত',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('default_review_title', array(
            'label'    => __('ডিফল্ট রিভিউ শিরোনাম', 'prothom-news'),
            'section'  => 'prothom_review_section',
            'type'     => 'text',
        ));

        // Section 4: Ad Engine Manager
        $wp_customize->add_section('prothom_ads_section', array(
            'title'    => __('৪. বিজ্ঞাপন ম্যানেজার (Ad Slots)', 'prothom-news'),
            'panel'    => 'prothom_news_panel',
            'priority' => 30,
        ));

        $ad_slots = array(
            'ad_header_top'   => 'হেডার সেরা বিজ্ঞাপন (Header Top 728x90)',
            'ad_after_lead'   => 'লিড নিউজের পর বিজ্ঞাপন (After Lead 970x90)',
            'ad_lead_sidebar' => 'সাইডবার স্কাইস্ক্রেপার (Sidebar 300x250)',
            'ad_middle_home'  => 'হোমপেজ মাঝের বিজ্ঞাপন (Middle Home 728x90)',
            'ad_single_post'  => 'সংবাদের ভেতরে বিজ্ঞাপন (In-Article 300x250)',
            'ad_sticky_bottom'=> 'স্টিকি বটম মোবাইল বিজ্ঞাপন (Sticky Bottom 320x50)',
        );

        foreach ($ad_slots as $slot_key => $slot_label) {
            $wp_customize->add_setting($slot_key, array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            ));
            $wp_customize->add_control($slot_key, array(
                'label'       => esc_html($slot_label),
                'section'     => 'prothom_ads_section',
                'type'        => 'textarea',
                'description' => __('Google AdSense বা ইমেজ HTML কোড বসান।', 'prothom-news'),
            ));
        }
    }

    public static function render_ad($slot_key, $class_name = '') {
        $ad_code = get_theme_mod($slot_key, '');
        if (!empty($ad_code)) {
            echo '<div class="prothom-ad-slot ' . esc_attr($class_name) . '">';
            echo '<span class="ad-label">বিজ্ঞাপন</span>';
            echo wp_kses_post($ad_code);
            echo '</div>';
        }
    }
}

ProthomNews_Theme_Options::init();

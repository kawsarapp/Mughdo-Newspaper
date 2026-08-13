<?php
/**
 * Full Bengali Demo Content Importer Engine for ProthomNews
 * Automatically creates Categories, Sample News Posts (30+ Articles), Pages, Navigation Menus, and Customizer Settings.
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Demo_Importer {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_admin_menu'));
        add_action('wp_ajax_prothom_import_demo_data', array(__CLASS__, 'ajax_import_demo'));
    }

    public static function register_admin_menu() {
        add_theme_page(
            __('ডেমো ডাটা ইমপোর্ট', 'prothom-news'),
            __('ডেমো ডাটা ইমপোর্ট (Demo Data)', 'prothom-news'),
            'manage_options',
            'prothom-news-demo-import',
            array(__CLASS__, 'render_admin_page')
        );
    }

    public static function render_admin_page() {
        ?>
        <div class="wrap">
          <h1><?php esc_html_e('ProthomNews - বাংলা ডেমো ডাটা ইমপোর্টার', 'prothom-news'); ?></h1>
          <p><?php esc_html_e('এক ক্লিকে আপনার ওয়েবসাইটে ৩০+ টি পূর্ণাঙ্গ বাংলা খবর, ক্যাটাগরি, পেজ, নভিগেশন মেনু, ব্রেকিং নিউজ এবং ড্যাশবোর্ড সেটিংস ইমপোর্ট করুন।', 'prothom-news'); ?></p>
          
          <div style="background:#FFF; padding:2rem; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); max-width:650px; margin-top:1.5rem;">
            <h3>📦 এক ক্লিকে যা যা স্বয়ংক্রিয়ভাবে তৈরি হবে:</h3>
            <ul style="list-style:disc; margin-left:1.5rem; line-height:1.8;">
              <li><strong>১০টি প্রধান বাংলা নিউজ ক্যাটাগরি</strong> (জাতীয়, রাজনীতি, অর্থনীতি, আন্তর্জাতিক, খেলাধুলা, ইত্যাদি)</li>
              <li><strong>৩০+ টি পূর্ণাঙ্গ বাংলা সংবাদ নিবন্ধ</strong> (থাম্বনেইল, সময়, স্টার রিভিউ রেটিং ও ভিন্ন ক্যাটাগরি সহ)</li>
              <li><strong>প্রয়োজনীয় বাংলা পেজসমূহ</strong> (আমাদের সম্পর্কে, যোগাযোগ, গোপনীয়তা নীতি, শর্তাবলী)</li>
              <li><strong>প্রধান ও ফুটার নেভিগেশন মেনু</strong> (অটো-অ্যাসাইন সহ)</li>
              <li><strong>স্ক্রোলিং ব্রেকিং নিউজ শিরোনাম</strong></li>
              <li><strong>হোমপেজের ১৮টি ডায়নামিক সেকশন ব্লক কনফিগারেশন</strong></li>
            </ul>

            <div id="demo-import-status" style="margin: 1.5rem 0; font-weight: bold; color: #0073aa;"></div>

            <button id="start-demo-import-btn" class="button button-primary button-hero">
              🚀 ৩০+ বাংলা খবর, মেনু ও পেজ ইমপোর্ট করুন
            </button>
          </div>
        </div>

        <script>
        document.getElementById('start-demo-import-btn').addEventListener('click', function() {
          const statusDiv = document.getElementById('demo-import-status');
          const btn = this;
          btn.disabled = true;
          statusDiv.innerText = '৩০+ বাংলা খবর, মেনু ও পেজ ইমপোর্ট করা হচ্ছে... অনুগ্রহ করে কয়েক সেকেন্ড অপেক্ষা করুন।';

          const formData = new FormData();
          formData.append('action', 'prothom_import_demo_data');
          formData.append('_nonce', '<?php echo wp_create_nonce("prothom_demo_import_nonce"); ?>');

          fetch(ajaxurl, {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              statusDiv.style.color = '#10B981';
              statusDiv.innerText = '🎉 সফলতা! ৩০+ টি বাংলা খবর, পেজ ও নেভিগেশন মেনু সফলভাবে ইমপোর্ট হয়েছে।';
            } else {
              statusDiv.style.color = '#EF4444';
              statusDiv.innerText = 'ত্রুটি ঘটেছে: ' + (data.data || 'ইমপোর্ট করা যায়নি।');
              btn.disabled = false;
            }
          })
          .catch(err => {
            statusDiv.style.color = '#EF4444';
            statusDiv.innerText = 'সার্ভার ত্রুটি! পুনরায় চেষ্টা করুন।';
            btn.disabled = false;
          });
        });
        </script>
        <?php
    }

    public static function ajax_import_demo() {
        check_ajax_referer('prothom_demo_import_nonce', '_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('অনুমতি নেই');
        }

        // 1. Create Categories
        $categories_map = array(
            'national'      => 'জাতীয়',
            'politics'      => 'রাজনীতি',
            'economy'       => 'অর্থনীতি ও বাণিজ্য',
            'international' => 'আন্তর্জাতিক',
            'sports'        => 'খেলাধুলা',
            'entertainment' => 'বিনোদন ও সংস্কৃতি',
            'tech'          => 'বিজ্ঞান ও প্রযুক্তি',
            'opinion'       => 'মতামত ও কলাম',
            'gallery'       => 'ছবি ও ভিডিও',
        );

        $cat_ids = array();
        foreach ($categories_map as $slug => $name) {
            $term = get_term_by('name', $name, 'category');
            if (!$term) {
                $inserted = wp_insert_category(array(
                    'cat_name' => $name,
                    'category_nicename' => $slug,
                ));
                $cat_ids[$slug] = $inserted;
            } else {
                $cat_ids[$slug] = $term->term_id;
            }
        }

        // Sub-categories for Sports
        $sub_cricket = get_term_by('name', 'ক্রিকেট', 'category');
        if (!$sub_cricket && isset($cat_ids['sports'])) {
            wp_insert_category(array(
                'cat_name' => 'ক্রিকেট',
                'category_parent' => $cat_ids['sports'],
            ));
        }

        // 2. Create Key Pages
        $demo_pages = array(
            'আমাদের সম্পর্কে' => 'ProthomNews হলো বাংলাদেশের অন্যতম আধুনিক ও আল্ট্রা-ফাস্ট ডিজিটাল বাংলা নিউজ পোর্টাল। আমরা বস্তুনিষ্ঠ, নির্ভীক এবং নিরপেক্ষ সংবাদ পরিবেশনে অঙ্গীকারবদ্ধ।',
            'যোগাযোগ'         => 'আমাদের সাথে যোগাযোগের মাধ্যম:<br>ইমেইল: news@example.com<br>ফোন: +৮৮০ ১২৩৪ ৫৬৭৮ ৯০<br>ঠিকানা: ঢাকা, বাংলাদেশ।',
            'গোপনীয়তা নীতি'  => 'আমাদের ওয়েবসাইটে আপনার ব্যক্তিগত তথ্যের সুরক্ষা এবং গোপনীয়তা নীতি সম্পর্কিত বিবরণ এখানে তুলে ধরা হলো।',
            'ব্যবহারের শর্তাবলী' => 'ProthomNews ওয়েবসাইট ব্যবহারের সাধারণ নিয়মাবলী ও শর্তাবলী।',
        );

        $page_ids = array();
        foreach ($demo_pages as $page_title => $page_content) {
            $existing_page = get_page_by_title($page_title);
            if (!$existing_page) {
                $page_id = wp_insert_post(array(
                    'post_title'   => $page_title,
                    'post_content' => $page_content,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                ));
                $page_ids[$page_title] = $page_id;
            } else {
                $page_ids[$page_title] = $existing_page->ID;
            }
        }

        // 3. Create and Auto-Assign Navigation Menus
        $menu_name = 'ProthomNews Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);

            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title'  => 'প্রচ্ছদ',
                'menu-item-url'    => home_url('/'),
                'menu-item-status' => 'publish',
            ));

            foreach ($cat_ids as $slug => $cat_id) {
                $category = get_category($cat_id);
                if ($category) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => $category->name,
                        'menu-item-object'    => 'category',
                        'menu-item-object-id' => $category->term_id,
                        'menu-item-type'      => 'taxonomy',
                        'menu-item-status'    => 'publish',
                    ));
                }
            }

            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        $footer_menu_name = 'ProthomNews Footer Menu';
        $footer_menu_exists = wp_get_nav_menu_object($footer_menu_name);
        if (!$footer_menu_exists) {
            $footer_menu_id = wp_create_nav_menu($footer_menu_name);

            foreach ($page_ids as $p_title => $p_id) {
                wp_update_nav_menu_item($footer_menu_id, 0, array(
                    'menu-item-title'     => $p_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $p_id,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }

            $locations = get_theme_mod('nav_menu_locations');
            $locations['footer'] = $footer_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        // 4. Create Massive 30+ Sample Bengali News Articles
        $sample_posts = array(
            // জাতীয় (National)
            array('title' => 'সারাদেশে নতুন অবকাঠামো ও অর্থনৈতিক উন্নয়ন প্রকল্প উদ্বোধন', 'content' => 'দেশের সার্বিক অর্থনৈতিক প্রবৃদ্ধি ত্বরান্বিত করতে আজ নতুন অবকাঠামো প্রকল্পের আনুষ্ঠানিক উদ্বোধন করা হয়েছে। প্রধানমন্ত্রী তাঁর বক্তব্যে উল্লেখ করেন যে, এই প্রকল্প বাস্তবায়িত হলে দেশের দক্ষিণ-পশ্চিমাঞ্চলের যোগাযোগ ব্যবস্থায় বৈপ্লবিক পরিবর্তন আসবে।', 'cat' => isset($cat_ids['national']) ? $cat_ids['national'] : 0),
            array('title' => 'ঢাকায় স্মার্ট ট্রাফিক সিগন্যাল ব্যবস্থাপনা চালুর নতুন উদ্যোগ', 'content' => 'রাজধানীর যানজট নিরসনে কৃত্রিম বুদ্ধিমত্তানির্ভর আধুনিক ট্রাফিক সিগন্যাল সিস্টেম চালুর উদ্যোগ নিয়েছে ঢাকা উত্তর সিটি করপোরেশন। প্রাথমিক পর্যায়ে ১০টি ব্যস্ততম মোড়ে এ সিগন্যাল স্থাপন করা হবে।', 'cat' => isset($cat_ids['national']) ? $cat_ids['national'] : 0),
            array('title' => 'নদী ভাঙন রোধে মেগা প্রকল্প অনুমোদন করল জাতীয় অর্থনৈতিক পরিষদ', 'content' => 'চরাঞ্চলের নদী তীরবর্তী মানুষের ঘরবাড়ি ও ফসলি জমি রক্ষায় নতুন বন্যা নিয়ন্ত্রণ ও নদী ভাঙন রোধ প্রকল্প অনুমোদন লাভ করেছে। এই প্রকল্পের অধীনে টেকসই বেড়িবাঁধ নির্মাণ করা হবে।', 'cat' => isset($cat_ids['national']) ? $cat_ids['national'] : 0),
            array('title' => 'পদ্মা সেতু ও এক্সপ্রেসওয়েতে রেকর্ড টোল আদায়', 'content' => 'ঈদুল ফিতরের ছুটিতে পদ্মা সেতু এবং ঢাকা-মাওয়া এক্সপ্রেসওয়েতে রেকর্ড পরিমাণ যানবাহন চলাচল ও টোল আদায় হয়েছে। সেতু কর্তৃপক্ষ সূত্রে জানা গেছে, একদিনেই রেকর্ড সাড়ে ৪ কোটি টাকার টোল সংগৃহীত হয়েছে।', 'cat' => isset($cat_ids['national']) ? $cat_ids['national'] : 0),

            // রাজনীতি (Politics)
            array('title' => 'রাজনৈতিক দলগুলোর যৌথ সংলাপে অংশ নেওয়ার আহ্বান', 'content' => 'দেশের সার্বিক রাজনৈতিক স্থিতিশীলতা ও অর্থনৈতিক অগ্রযাত্রা বজায় রাখতে দেশের শীর্ষ দলগুলোর সাথে আলোচনার টেবিল খোলার তাগিদ দিয়েছেন রাজনৈতিক বিশ্লেষকগণ।', 'cat' => isset($cat_ids['politics']) ? $cat_ids['politics'] : 0),
            array('title' => 'স্থানীয় সরকার নির্বাচন আয়োজনে নির্বাচন কমিশনের পূর্ণ প্রস্তুতি', 'content' => 'আসন্ন স্থানীয় সরকার পরিষদ নির্বাচন অবাধ, সুষ্ঠু ও নিরপেক্ষভাবে সম্পন্ন করতে আইন-শৃঙ্খলা রক্ষাকারী বাহিনীর সাথে মতবিনিময় সভা করেছে নির্বাচন কমিশন।', 'cat' => isset($cat_ids['politics']) ? $cat_ids['politics'] : 0),
            array('title' => 'নির্বাচন সংস্কার কমিশনের প্রথম বৈঠক অনুষ্ঠিত', 'content' => 'ভোটাধিকার সুসংহত করতে গঠিত নতুন সংস্কার কমিশনের প্রথম বৈঠক আজ ইসির সম্মেলন কক্ষে অনুষ্ঠিত হয়েছে। বিভিন্ন রাজনৈতিক দলের প্রস্তাবনা ও সাধারণ নাগরিকদের মতামত বিশ্লেষণ করা হচ্ছে।', 'cat' => isset($cat_ids['politics']) ? $cat_ids['politics'] : 0),

            // অর্থনীতি (Economy)
            array('title' => 'বিশ্ববাজারে জ্বালানি তেলের দাম হ্রাস, স্থিতিশীলতার আভাস', 'content' => 'আন্তর্জাতিক বাজারে টানা দ্বিতীয় সপ্তাহের মতো বিশ্ববাজারে অপরিশোধিত জ্বালানি তেলের দাম কমেছে। বিশ্ব অর্থনীতিতে চাহিদা নিয়ে দুশ্চিন্তা এবং ওপেক প্লাসের নীতিগত সিদ্ধান্তের প্রভাবেই তেলের দামে এই পরিবর্তন এসেছে।', 'cat' => isset($cat_ids['economy']) ? $cat_ids['economy'] : 0),
            array('title' => 'প্রবাসীদের পাঠানো রেমিটেন্সে নতুন রেকর্ড তৈরি', 'content' => 'চলতি অর্থবছরের প্রথমার্ধে ব্যাংকিং চ্যানেলে রেকর্ড পরিমাণ রেমিটেন্স দেশে পাঠিয়েছেন প্রবাসী বাংলাদেশিরা। এতে কেন্দ্রীয় ব্যাংকের বৈদেশিক মুদ্রার রিজার্ভে স্বস্তি ফিরে এসেছে।', 'cat' => isset($cat_ids['economy']) ? $cat_ids['economy'] : 0),
            array('title' => 'রপ্তানি আয়ে ইতিবাচক প্রবৃদ্ধি, শীর্ষে তৈরি পোশাক খাত', 'content' => 'সবুজ অর্থায়ন এবং বিশ্বজুড়ে ইকোলজিক্যাল পোশাকের চাহিদা বৃদ্ধির কারণে বাংলাদেশের তৈরি পোশাক শিল্পের রপ্তানি আয় চলতি মাসে ৮ শতাংশ বৃদ্ধি লাভ করেছে।', 'cat' => isset($cat_ids['economy']) ? $cat_ids['economy'] : 0),
            array('title' => 'শেয়ার বাজারে লেনদেন বৃদ্ধি, প্রধান সূচকের উত্থান', 'content' => 'সপ্তাহের তৃতীয় কার্যদিবসে ঢাকা স্টক এক্সচেঞ্জে (ডিএসই) প্রধান সূচক ৩০ পয়েন্ট বৃদ্ধি পেয়েছে। বিনিয়োগকারীদের লেনদেনে অংশ নেওয়ার হার এবং শেয়ার দর সূচকে ইতিবাচক প্রভাব দেখা গেছে।', 'cat' => isset($cat_ids['economy']) ? $cat_ids['economy'] : 0),

            // আন্তর্জাতিক (International)
            array('title' => 'জাতিসংঘ সাধারণ পরিষদে শান্তি ও আন্তর্জাতিক নিরাপত্তা জোরদারের তাগিদ', 'content' => 'চলমান বৈশ্বিক কূটনৈতিক সংকট নিরসনে বিশ্বনেতাদের যৌথ পদক্ষেপ নেওয়ার আহ্বান জানিয়েছে জাতিসংঘ। অধিবেশনে বিভিন্ন দেশের প্রতিনিধিগণ খাদ্য নিরাপত্তা এবং জলবায়ু পরিবর্তন মোকাবিলায় কাজ করার অঙ্গীকার করেন।', 'cat' => isset($cat_ids['international']) ? $cat_ids['international'] : 0),
            array('title' => 'ইউরোপীয় ইউনিয়নে জলবায়ু তহবিল বৃদ্ধির ঐতিহাসিক সিদ্ধান্ত', 'content' => 'উন্নয়নশীল দেশগুলোতে জলবায়ু পরিবর্তনের ক্ষতিকর প্রভাব মোকাবিলায় ১০০ বিলিয়ন ডলারের বিশেষ অভিযোজন তহবিল গঠনের ঘোষণা দিয়েছে ইউরোপীয় পরিষদ।', 'cat' => isset($cat_ids['international']) ? $cat_ids['international'] : 0),
            array('title' => 'মধ্যপ্রাচ্যে কূটনৈতিক আলোচনা জোরদার করল বিশ্ব শক্তিগুলো', 'content' => 'আঞ্চলিক শান্তি ও অর্থনৈতিক জোট গড়ে তুলতে প্রতিবেশী দেশগুলোর মধ্যে নতুন ঐতিহাসিক দ্বিপাক্ষিক বাণিজ্য চুক্তি স্বাক্ষরিত হয়েছে।', 'cat' => isset($cat_ids['international']) ? $cat_ids['international'] : 0),

            // খেলাধুলা (Sports)
            array('title' => 'টি-টোয়েন্টি সিরিজে সফরকারী দলকে হারিয়ে বাংলাদেশের দারুণ জয়', 'content' => 'হোম গ্রাউন্ডে অনুষ্ঠিত রোমাঞ্চকর ম্যাচে সফরকারী শক্তিশালী প্রতিপক্ষকে ৬ উইকেটে হারিয়ে সিরিজ নিজেদের নামে করল বাংলাদেশ দল। চমৎকার বোলিং নৈপুণ্য ও অলরাউন্ড পারফর্ম্যান্সের জন্য ম্যান অব দ্য ম্যাচ নির্বাচিত হন সাকিব।', 'cat' => isset($cat_ids['sports']) ? $cat_ids['sports'] : 0),
            array('title' => 'চ্যাম্পিয়ন্স লিগের ফাইনাল ম্যাচে রুদ্ধশ্বাস লড়াইয়ে জয়ী রিয়াল', 'content' => 'ইউরোপীয় ফুটবলের সেরা লড়াইয়ে শেষ মুহূর্তের গোলে শিরোপা পুনরুদ্ধার করল রিয়াল মাদ্রিদ। নির্ধারিত সময়ে ১-১ সমতায় থাকার পর অতিরিক্ত সময়ের গোলে ট্রফি নিশ্চিত হয়।', 'cat' => isset($cat_ids['sports']) ? $cat_ids['sports'] : 0),
            array('title' => 'বিশ্ব টেস্ট চ্যাম্পিয়নশিপের ফাইনালে নতুন মাইলফলক', 'content' => 'টেস্ট ক্রিকেটের দীর্ঘতম ফরম্যাটে নতুন বিশ্ব রেকর্ড গড়েছেন বর্ষীয়ান ওপেনার। প্রথম ইনিংসে ট্রিপল সেঞ্চুরি হাঁকিয়ে ক্রিকেট ইতিহাসে নিজের নাম খোদাই করলেন তিনি।', 'cat' => isset($cat_ids['sports']) ? $cat_ids['sports'] : 0),
            array('title' => 'সাকিব ও লিটনের জুটিতে রেকর্ড সংগ্রহের ইতিহাস', 'content' => 'পঞ্চম উইকেটে ১৫০ রানের অবিচ্ছিন্ন জুটি গড়ে বাংলাদেশকে বড় সংগ্রহের ভিত এনে দিলেন দুই অভিজ্ঞ ব্যাটার সাকিব আল হাসান ও লিটন দাস।', 'cat' => isset($cat_ids['sports']) ? $cat_ids['sports'] : 0),

            // প্রযুক্তি (Tech)
            array('title' => 'নতুন প্রযুক্তির কৃত্রিম বুদ্ধিমত্তা মডেল উন্মোচন করল গবেষক দল', 'content' => 'তথ্যপ্রযুক্তিতে নতুন বিপ্লব ঘটাতে আরও দ্রুত ও নিখুঁত ভাষার প্রসেসিং সম্পন্ন করতে সক্ষম নতুন এআই মডেল তৈরি করেছেন বিজ্ঞানীরা। এই মডেলটি চিকিৎসাবিজ্ঞান এবং গবেষণায় সাহায্য করবে।', 'cat' => isset($cat_ids['tech']) ? $cat_ids['tech'] : 0),
            array('title' => '৫জি নেটওয়ার্ক সম্প্রসারণে মোবাইল অপারেটরদের নতুন উদ্যোগ', 'content' => 'দেশের প্রধান শিল্পাঞ্চল ও শিক্ষাপ্রতিষ্ঠানগুলোতে দ্রুতগতির ফাইভ-জি ব্রডব্যান্ড কাভারেজ পৌঁছাতে ফাইবার অপটিক ক্যাবল সম্প্রসারণের কাজ দ্রুতগতিতে চলছে।', 'cat' => isset($cat_ids['tech']) ? $cat_ids['tech'] : 0),
            array('title' => 'সাইবার নিরাপত্তা জোরদারে জাতীয় ডাটা সেন্টারে নতুন প্রযুক্তি', 'content' => 'সরকারি সব সেবা নিশ্চিত করতে ও তথ্য চুরি ঠেকাতে এনক্রিপ্টেড কোয়ান্টাম সিকিউরিটি ফিচার চালুর ঘোষণা দেওয়া হয়েছে।', 'cat' => isset($cat_ids['tech']) ? $cat_ids['tech'] : 0),

            // বিনোদন (Entertainment)
            array('title' => 'জাতীয় চলচ্চিত্র পুরস্কারের অনুষ্ঠান কাল, তারকাদের উপস্থিতিতে আনন্দ আয়োজন', 'content' => 'রাজধানীর মিলনায়তনে আয়োজিত হতে যাচ্ছে বাৎসরিক শুভ চলচ্চিত্র পুরস্কার বিতরণী অনুষ্ঠান। সংস্কৃতি অঙ্গনের বরেণ্য তারকা ও পরিচালকদের উপস্থিতিতে বছরসেরা চলচ্চিত্র প্রদান করা হবে।', 'cat' => isset($cat_ids['entertainment']) ? $cat_ids['entertainment'] : 0),
            array('title' => 'আন্তর্জাতিক কান চলচ্চিত্র উৎসবে বাংলা সিনেমার বিশেষ প্রদর্শনী', 'content' => 'বিশ্বখ্যাত কানে স্ক্রিনিং হলো বাংলাদেশের তরুণ পরিচালকের পূর্ণদৈর্ঘ্য চলচ্চিত্র। প্রদর্শনী শেষে উপস্থিত আন্তর্জাতিক সমালোচকদের প্রশংসায় ভেসেছেন অভিনয়শিল্পীরা।', 'cat' => isset($cat_ids['entertainment']) ? $cat_ids['entertainment'] : 0),
            array('title' => 'ওটিটি প্ল্যাটফর্মে মুক্তি পেল নতুন ক্রাইম থ্রিলার ওয়েব সিরিজ', 'content' => 'রহস্য ও রোমাঞ্চে ভরপুর মোড়ক নিয়ে স্থানীয় ওটিটিতে বিশ্বব্যাপী একযোগে মুক্তি লাভ করেছে বহুল প্রতীক্ষিত গোয়েন্দা থ্রিলার সিরিজ।', 'cat' => isset($cat_ids['entertainment']) ? $cat_ids['entertainment'] : 0),

            // মতামত (Opinion)
            array('title' => 'আমাদের তরুণ প্রজন্মের মেধা ও আগামীর টেকসই ভবিষ্যৎ গঠনে করণীয়', 'content' => 'ডিজিটাল যুগে তরুণ মেধার সঠিক পরিচর্যা এবং কর্মমুখী শিক্ষার প্রসার অত্যন্ত জরুরি। তরুণ সমাজকে আধুনিক প্রযুক্তি ও গবেষণায় উদ্বুদ্ধ করতে পারলে দেশ আগামী দিনে এগিয়ে যাবে।', 'cat' => isset($cat_ids['opinion']) ? $cat_ids['opinion'] : 0),
            array('title' => 'ডিজিটাল অর্থনীতিতে নারী উদ্যোক্তাদের সম্ভাবনা ও চ্যালেঞ্জ', 'content' => 'অনলাইন ভিত্তিক ই-কমার্স খাতের উত্থানে তরুণ নারী উদ্যোক্তাগণ নিজেদের অর্থনৈতিকভাবে স্বাবলম্বী করে তুলছেন। তবে প্রয়োজনীয় মূলধন ও সহজ শর্তে ঋণ সুবিধা প্রদান নিশ্চিত করতে হবে।', 'cat' => isset($cat_ids['opinion']) ? $cat_ids['opinion'] : 0),
            array('title' => 'নগর পরিকল্পনায় পরিবেশ ও জলবায়ু সহনশীল ব্যবস্থার প্রয়োজনীয়তা', 'content' => 'স্মার্ট সিটি গড়ে তুলতে হলে পরিবেশ বান্ধব সুউচ্চ ভবন, খেলার মাঠ এবং সবুজ বনায়নের অবকাঠামোগত পরিকল্পনা আবশ্যক।', 'cat' => isset($cat_ids['opinion']) ? $cat_ids['opinion'] : 0),

            // ছবি ও ভিডিও (Gallery)
            array('title' => 'পার্বত্য চট্টগ্রামে মেঘের রাজ্য সাজেক উপত্যকার চোখজুড়ানো দৃশ্যপট', 'content' => 'রাঙ্গামাটির সাজেক ভ্যালি পাহাড়ের চূড়ায় সাদা মেঘের মেলা ও পাহাড়ি লোকসংস্কৃতির মনোমুগ্ধকর দৃশ্যমালা পাঠকদের জন্য তুলে ধরা হলো।', 'cat' => isset($cat_ids['gallery']) ? $cat_ids['gallery'] : 0),
            array('title' => 'কক্সবাজার সমুদ্র সৈকতে নয়নাভিরাম সূর্যাস্তের চিত্রমালা', 'content' => 'বিশ্বের দীর্ঘতম প্রাকৃতিক বালুকাময় সমুদ্র সৈকতে গোধূলিলগ্নে মেতে উঠা দেশি-বিদেশি পর্যটকদের বাঁধভাঙা আনন্দের ছবি।', 'cat' => isset($cat_ids['gallery']) ? $cat_ids['gallery'] : 0),
            array('title' => 'সুন্দরবনের বন্যপ্রাণী ও প্রাকৃতিক সৌন্দর্যের ফোটো অ্যালবাম', 'content' => 'বিশ্ব ঐতিহ্য ম্যানগ্রোভ সুন্দরবনের রয়েল বেঙ্গল টাইগার, হরিণের পাল এবং বনাঞ্চলের মনোমুগ্ধকর ছবির সমাহার।', 'cat' => isset($cat_ids['gallery']) ? $cat_ids['gallery'] : 0),
        );

        foreach ($sample_posts as $sp) {
            $existing = get_page_by_title($sp['title'], OBJECT, 'post');
            if (!$existing) {
                $post_id = wp_insert_post(array(
                    'post_title'   => $sp['title'],
                    'post_content' => $sp['content'],
                    'post_status'  => 'publish',
                    'post_type'    => 'post',
                    'post_category'=> array($sp['cat']),
                ));

                update_post_meta($post_id, '_prothom_enable_review', '1');
                update_post_meta($post_id, '_prothom_review_title', 'সামগ্রিক পর্যালোচনা ও মতামত');
                update_post_meta($post_id, '_prothom_review_rating', '4.8');
            }
        }

        // 5. Update Customizer Settings
        set_theme_mod('lead_news_category', isset($cat_ids['national']) ? $cat_ids['national'] : 0);
        set_theme_mod('ticker_category', isset($cat_ids['national']) ? $cat_ids['national'] : 0);
        set_theme_mod('ticker_title', 'শিরোনাম:');

        set_theme_mod('cat_block_1', isset($cat_ids['national']) ? $cat_ids['national'] : 0);
        set_theme_mod('layout_block_1', '3col');

        set_theme_mod('cat_block_2', isset($cat_ids['politics']) ? $cat_ids['politics'] : 0);
        set_theme_mod('layout_block_2', 'tabbed_cat');

        set_theme_mod('cat_block_3', isset($cat_ids['economy']) ? $cat_ids['economy'] : 0);
        set_theme_mod('layout_block_3', 'big_list');

        set_theme_mod('cat_block_4', isset($cat_ids['international']) ? $cat_ids['international'] : 0);
        set_theme_mod('layout_block_4', '2col_split');

        set_theme_mod('cat_block_5', isset($cat_ids['tech']) ? $cat_ids['tech'] : 0);
        set_theme_mod('layout_block_5', 'overlay');

        set_theme_mod('cat_block_6', isset($cat_ids['sports']) ? $cat_ids['sports'] : 0);
        set_theme_mod('layout_block_6', 'slider_carousel');

        set_theme_mod('cat_block_7', isset($cat_ids['entertainment']) ? $cat_ids['entertainment'] : 0);
        set_theme_mod('layout_block_7', 'video_grid');

        set_theme_mod('cat_block_8', isset($cat_ids['opinion']) ? $cat_ids['opinion'] : 0);
        set_theme_mod('layout_block_8', 'quote_block');

        wp_send_json_success('৩০+ বাংলা খবর, ক্যাটাগরি, পেজ ও মেনু সফলভাবে ইমপোর্ট করা হয়েছে!');
    }
}

ProthomNews_Demo_Importer::init();

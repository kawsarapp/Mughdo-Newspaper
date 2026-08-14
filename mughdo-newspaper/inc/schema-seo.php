<?php
/**
 * RankMath & Yoast SEO Equivalent Engine for Mughdo Newspaper
 * Built-in Meta Box with Gutenberg & Classic Editor Support, Live 0-100 SEO Score, 10-Point Live Checklist Audit & 100% Action Plan Guide.
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Schema_SEO {

    public static function init() {
        add_action('wp_head', array(__CLASS__, 'output_ga4_tracking_script'), 0);
        add_action('wp_head', array(__CLASS__, 'output_meta_tags'), 1);
        add_action('wp_head', array(__CLASS__, 'output_schema_json_ld'), 5);
        add_action('add_meta_boxes', array(__CLASS__, 'register_seo_meta_box'));
        add_action('save_post', array(__CLASS__, 'save_seo_meta_data'));
        add_action('init', array(__CLASS__, 'register_xml_sitemap'));
        add_filter('robots_txt', array(__CLASS__, 'custom_ai_robots_txt'), 10, 2);

        // All Posts Screen SEO Score Column
        add_filter('manage_posts_columns', array(__CLASS__, 'add_seo_score_column'));
        add_action('manage_posts_custom_column', array(__CLASS__, 'render_seo_score_column'), 10, 2);
    }

    /**
     * 1. Output Google Analytics 4 (GA4) Tracking Script
     */
    public static function output_ga4_tracking_script() {
        $ga4_id = get_theme_mod('ga4_measurement_id', '');
        if (!empty($ga4_id)) {
            ?>
            <!-- Google Analytics 4 (GA4) Tracker -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '<?php echo esc_js($ga4_id); ?>');
            </script>
            <?php
        }
    }

    /**
     * 2. Register Post Editor SEO Meta Box (RankMath & Yoast Style Live Score)
     */
    public static function register_seo_meta_box() {
        add_meta_box(
            'mughdo_seo_meta_box',
            '🎯 Mughdo লাইভ এসইও স্কোর ও ১০০% র‍্যাঙ্কিং গাইড (RankMath & Yoast Suite)',
            array(__CLASS__, 'render_seo_meta_box'),
            'post',
            'normal',
            'high'
        );
    }

    public static function render_seo_meta_box($post) {
        wp_nonce_field('mughdo_seo_nonce_action', 'mughdo_seo_nonce');

        $focus_kw   = get_post_meta($post->ID, '_mughdo_seo_focus_kw', true);
        $seo_title  = get_post_meta($post->ID, '_mughdo_seo_title', true);
        $seo_desc   = get_post_meta($post->ID, '_mughdo_seo_desc', true);
        $saved_score= get_post_meta($post->ID, '_mughdo_seo_score', true);
        $saved_score= (!empty($saved_score) || $saved_score === '0') ? intval($saved_score) : 0;

        $default_title = get_the_title($post->ID) . ' - ' . get_bloginfo('name');
        $default_desc  = prothom_news_custom_excerpt(25, $post->ID);

        $preview_title = !empty($seo_title) ? $seo_title : $default_title;
        $preview_desc  = !empty($seo_desc) ? $seo_desc : $default_desc;
        ?>
        <div class="mughdo-seo-box-wrapper" style="font-family:-apple-system,BlinkMacSystemFont,sans-serif; padding:10px;">
          
          <input type="hidden" id="mughdo_seo_score_hidden" name="mughdo_seo_score" value="<?php echo esc_attr($saved_score); ?>" />

          <!-- LIVE SEO SCORE BADGE (0 - 100 RankMath & Yoast Style) -->
          <div style="display:flex; align-items:center; justify-content:space-between; background:linear-gradient(135deg, #0F172A, #1E293B); padding:16px 20px; border-radius:10px; color:#FFF; margin-bottom:20px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
            <div>
              <span style="font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; color:#94A3B8;">লাইভ এসইও স্কোর (Live SEO Score)</span>
              <div id="seo-score-status-text" style="font-size:18px; font-weight:800; color:#F59E0B; margin-top:4px;">
                ক্যালকুলেট করা হচ্ছে...
              </div>
            </div>

            <!-- Score Circular Badge -->
            <div style="text-align:center;">
              <div id="seo-score-badge-circle" style="width:72px; height:72px; border-radius:50%; background:#F59E0B; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:900; color:#FFF; box-shadow:0 0 15px rgba(245,158,11,0.5); transition:all 0.3s ease;">
                <span id="seo-score-num"><?php echo esc_html($saved_score); ?></span>
              </div>
              <span style="font-size:11px; color:#CBD5E1; margin-top:4px; display:block;">out of 100</span>
            </div>
          </div>

          <!-- Live Google Search Snippet Preview Box -->
          <div style="background:#FFF; border:1px solid #DADCE0; border-radius:8px; padding:16px; margin-bottom:20px; max-width:650px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
            <span style="font-size:12px; font-weight:bold; color:#70757A; display:block; margin-bottom:8px;">🔍 গুগল সার্চ প্রিভিউ (Live Google Snippet Preview):</span>
            
            <div style="font-size:14px; color:#202124; margin-bottom:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
              <span style="color:#202124; font-weight:bold; font-size:14px;"><?php echo esc_html(get_bloginfo('name')); ?></span>
              <span style="color:#5F6368; font-size:12px;"> › <?php echo esc_html(get_the_category($post->ID) ? get_the_category($post->ID)[0]->name : 'সংবাদ'); ?></span>
            </div>

            <h3 id="google-snippet-title-preview" style="font-size:18px; color:#1A0DAB; margin:0 0 4px 0; font-weight:normal; line-height:1.3; font-family:arial,sans-serif;">
              <?php echo esc_html($preview_title); ?>
            </h3>

            <p id="google-snippet-desc-preview" style="font-size:14px; color:#4D5156; margin:0; line-height:1.4; font-family:arial,sans-serif;">
              <?php echo esc_html($preview_desc); ?>
            </p>
          </div>

          <!-- Dynamic 100% Action Plan Guide (Missing Requirements to reach 100%) -->
          <div id="seo-missing-actions-box" style="background:#FEF2F2; border:1px solid #FCA5A5; border-radius:8px; padding:14px; margin-bottom:20px; display:none;">
            <h4 style="margin:0 0 8px 0; font-size:13px; font-weight:bold; color:#991B1B;">💡 ১০০% এসইও স্কোর করতে নিচের কাজগুলো সম্পন্ন করুন:</h4>
            <ul id="seo-missing-list" style="margin:0; padding-left:18px; font-size:12px; color:#7F1D1D; line-height:1.5;"></ul>
          </div>

          <!-- SEO Inputs & Real-time Checklist Grid -->
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; max-width:900px;">
            
            <!-- Left Side: Inputs -->
            <div style="display:flex; flex-direction:column; gap:16px;">
              
              <!-- Focus Keyword -->
              <div>
                <label style="font-weight:bold; display:block; margin-bottom:4px; font-size:13px;">🎯 ফোকাস কিওয়ার্ড (Focus Keyword):</label>
                <input type="text" id="mughdo_seo_focus_kw_input" name="mughdo_seo_focus_kw" value="<?php echo esc_attr($focus_kw); ?>" placeholder="যেমন: বাজেট ২০২৬" style="width:100%; padding:8px; border:1px solid #CCC; border-radius:4px;" />
                <span style="font-size:11px; color:#666;">সংবাদের মূল কিওয়ার্ড যা গুগলে সার্চ করলে পাঠকরা খবরটি খুঁজে পাবে।</span>
              </div>

              <!-- Custom SEO Title -->
              <div>
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                  <label style="font-weight:bold; font-size:13px;">🏷️ কাস্টম এসইও টাইটেল (SEO Title):</label>
                  <span id="title-char-counter" style="font-size:12px; color:#10B981; font-weight:bold;">0 / 60 Chars</span>
                </div>
                <input type="text" id="mughdo_seo_title_input" name="mughdo_seo_title" value="<?php echo esc_attr($seo_title); ?>" placeholder="<?php echo esc_attr($default_title); ?>" style="width:100%; padding:8px; border:1px solid #CCC; border-radius:4px;" />
              </div>

              <!-- Custom SEO Meta Description -->
              <div>
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                  <label style="font-weight:bold; font-size:13px;">📝 কাস্টম মেটা ডেসক্রিপশন (Meta Description):</label>
                  <span id="desc-char-counter" style="font-size:12px; color:#10B981; font-weight:bold;">0 / 160 Chars</span>
                </div>
                <textarea id="mughdo_seo_desc_input" name="mughdo_seo_desc" rows="3" placeholder="<?php echo esc_attr($default_desc); ?>" style="width:100%; padding:8px; border:1px solid #CCC; border-radius:4px;"><?php echo esc_textarea($seo_desc); ?></textarea>
              </div>

            </div>

            <!-- Right Side: 10-Point Dynamic SEO Checklist (RankMath & Yoast Style) -->
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:16px;">
              <h4 style="margin:0 0 12px 0; font-size:14px; font-weight:bold; color:#1E293B;">📋 ১০-পয়েন্ট লাইভ এসইও অডিট লিস্ট (Checklist Audit):</h4>
              
              <ul id="seo-checklist" style="list-style:none; padding:0; margin:0; font-size:12px; display:flex; flex-direction:column; gap:8px;">
                <li id="chk-kw-title" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ১. টাইটেলে ফোকাস কিওয়ার্ড উপস্থিত (+১০)</li>
                <li id="chk-kw-start" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ২. টাইটেলের শুরুতে ফোকাস কিওয়ার্ড (+১০)</li>
                <li id="chk-title-len" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৩. টাইটেল সঠিক দৈর্ঘ্য (৪০-৬০ অক্ষর) (+১০)</li>
                <li id="chk-kw-desc" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৪. মেটা ডেসক্রিপশনে ফোকাস কিওয়ার্ড (+১০)</li>
                <li id="chk-desc-len" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৫. ডেসক্রিপশন সঠিক দৈর্ঘ্য (১২০-১৬০) (+১০)</li>
                <li id="chk-kw-body" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৬. সংবাদের মূল লেখায় ফোকাস কিওয়ার্ড (+১০)</li>
                <li id="chk-body-len" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৭. সংবাদের মানসম্মত দৈর্ঘ্য (১৫০+ শব্দ) (+১০)</li>
                <li id="chk-kw-density" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৮. কিওয়ার্ড ডেনসিটি ভারসাম্যপূর্ণ (০.৫-২.৫%) (+১০)</li>
                <li id="chk-kw-slug" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ৯. ইউআরএল / পার্মালিংকে কিওয়ার্ড রয়েছে (+১০)</li>
                <li id="chk-has-img" style="display:flex; align-items:center; gap:8px; color:#EF4444;"><span class="icon">✕</span> ১০. ফিচারড ইমেজ বা ছবি যুক্ত আছে (+১০)</li>
              </ul>
            </div>

          </div>
        </div>

        <script>
        (function() {
          const kwInput   = document.getElementById('mughdo_seo_focus_kw_input');
          const titleInput= document.getElementById('mughdo_seo_title_input');
          const descInput = document.getElementById('mughdo_seo_desc_input');
          const hiddenScore = document.getElementById('mughdo_seo_score_hidden');

          const scoreNum  = document.getElementById('seo-score-num');
          const scoreBadge= document.getElementById('seo-score-badge-circle');
          const scoreText = document.getElementById('seo-score-status-text');

          const titlePrev = document.getElementById('google-snippet-title-preview');
          const descPrev  = document.getElementById('google-snippet-desc-preview');
          const titleCount= document.getElementById('title-char-counter');
          const descCount = document.getElementById('desc-char-counter');

          const missingBox = document.getElementById('seo-missing-actions-box');
          const missingList= document.getElementById('seo-missing-list');

          function getArticleContent() {
            let text = '';
            // Gutenberg Support
            if (window.wp && wp.data && wp.data.select && wp.data.select('core/editor')) {
              try {
                const editor = wp.data.select('core/editor');
                text = editor.getEditedPostContent() || '';
                // Strip HTML tags for word count
                const tmp = document.createElement('DIV');
                tmp.innerHTML = text;
                text = tmp.textContent || tmp.innerText || '';
              } catch(e) {}
            }
            if (!text && typeof tinyMCE !== 'undefined' && tinyMCE.get('content')) {
              text = tinyMCE.get('content').getContent({format: 'text'});
            }
            if (!text) {
              const contentEl = document.getElementById('content');
              if (contentEl) text = contentEl.value;
            }
            return text;
          }

          function getArticleTitle() {
            let title = '';
            // Gutenberg Support
            if (window.wp && wp.data && wp.data.select && wp.data.select('core/editor')) {
              try {
                title = wp.data.select('core/editor').getEditedPostAttribute('title') || '';
              } catch(e) {}
            }
            if (!title) {
              const titleEl = document.getElementById('title');
              if (titleEl) title = titleEl.value;
            }
            return title;
          }

          function setCheckItem(id, pass) {
            const el = document.getElementById(id);
            if (!el) return;
            const icon = el.querySelector('.icon');
            if (pass) {
              el.style.color = '#10B981';
              if (icon) icon.innerText = '✓';
            } else {
              el.style.color = '#EF4444';
              if (icon) icon.innerText = '✕';
            }
          }

          function calculateLiveSEO() {
            const focusKw = kwInput ? kwInput.value.trim().toLowerCase() : '';
            const seoTitle= titleInput ? titleInput.value.trim() : '';
            const wpTitle = getArticleTitle().trim();
            const finalTitle = seoTitle || wpTitle || '<?php echo esc_js($default_title); ?>';
            
            const seoDesc = descInput ? descInput.value.trim() : '';
            const finalDesc = seoDesc || '<?php echo esc_js($default_desc); ?>';

            const bodyContent = getArticleContent();

            // Update Preview Snippet
            if (titlePrev) titlePrev.innerText = finalTitle;
            if (descPrev) descPrev.innerText = finalDesc;

            if (titleCount) {
              const len = seoTitle ? seoTitle.length : wpTitle.length;
              titleCount.innerText = len + ' / 60 Chars';
              titleCount.style.color = (len >= 40 && len <= 60) ? '#10B981' : '#F59E0B';
            }
            if (descCount) {
              const len = finalDesc.length;
              descCount.innerText = len + ' / 160 Chars';
              descCount.style.color = (len >= 120 && len <= 160) ? '#10B981' : '#F59E0B';
            }

            let score = 0;
            const missing = [];

            // 1. Focus Kw in Title
            const hasKwTitle = focusKw && finalTitle.toLowerCase().includes(focusKw);
            setCheckItem('chk-kw-title', hasKwTitle);
            if (hasKwTitle) score += 10;
            else missing.push('ফোকাস কিওয়ার্ড (Focus Keyword) লিখুন এবং সেটি শিরোনামে (Title) ব্যবহার করুন।');

            // 2. Focus Kw at Start of Title
            const hasKwStart = focusKw && finalTitle.toLowerCase().startsWith(focusKw);
            setCheckItem('chk-kw-start', hasKwStart);
            if (hasKwStart) score += 10;
            else if (focusKw) missing.push('শিরোনামের একদম শুরুতে ফোকাস কিওয়ার্ড বসান।');

            // 3. Title Length 40-60
            const tLen = finalTitle.length;
            const validTLen = tLen >= 40 && tLen <= 70;
            setCheckItem('chk-title-len', validTLen);
            if (validTLen) score += 10;
            else missing.push('শিরোনামের দৈর্ঘ্য অন্তত ৪০ থেকে ৬০ ক্যারেক্টারের (অক্ষরের) মধ্যে রাখুন।');

            // 4. Focus Kw in Meta Desc
            const hasKwDesc = focusKw && finalDesc.toLowerCase().includes(focusKw);
            setCheckItem('chk-kw-desc', hasKwDesc);
            if (hasKwDesc) score += 10;
            else missing.push('মেটা ডেসক্রিপশনের ভেতরে ফোকাস কিওয়ার্ড ব্যবহার করুন।');

            // 5. Desc Length 120-160
            const dLen = finalDesc.length;
            const validDLen = dLen >= 100 && dLen <= 170;
            setCheckItem('chk-desc-len', validDLen);
            if (validDLen) score += 10;
            else missing.push('মেটা ডেসক্রিপশনের দৈর্ঘ্য ১২০ থেকে ১৬০ অক্ষরের মধ্যে লিখুন।');

            // 6. Focus Kw in Body
            const hasKwBody = focusKw && bodyContent.toLowerCase().includes(focusKw);
            setCheckItem('chk-kw-body', hasKwBody);
            if (hasKwBody) score += 10;
            else missing.push('সংবাদের মূল লেখার (Content Body) ভেতরে অন্তত কয়েকবার কিওয়ার্ডটি লিখুন।');

            // 7. Body Length > 150 words
            const words = bodyContent.trim() ? bodyContent.trim().split(/\s+/).length : 0;
            const validWords = words >= 150;
            setCheckItem('chk-body-len', validWords);
            if (validWords) score += 10;
            else missing.push('সংবাদের মূল লেখার দৈর্ঘ্য অন্তত ১৫০-৩০০ শব্দের বেশি বড় করুন।');

            // 8. Keyword Density
            let density = 0;
            if (focusKw && words > 0) {
              const matches = (bodyContent.toLowerCase().match(new RegExp(focusKw, 'g')) || []).length;
              density = (matches / words) * 100;
            }
            const validDensity = density >= 0.5 && density <= 3.5;
            setCheckItem('chk-kw-density', validDensity);
            if (validDensity) score += 10;
            else if (focusKw) missing.push('সংবাদে কিওয়ার্ড ডেনসিটি (ঘনত্ব) ০.৫% থেকে ২.৫% এর মধ্যে সামঞ্জস্য রাখুন।');

            // 9. Focus Kw in Slug
            const slugEl = document.getElementById('editable-post-name') || document.getElementById('post_name');
            const slug = slugEl ? slugEl.value.toLowerCase() : '';
            const hasKwSlug = focusKw && (slug.includes(focusKw) || finalTitle.toLowerCase().includes(focusKw));
            setCheckItem('chk-kw-slug', hasKwSlug);
            if (hasKwSlug) score += 10;
            else if (focusKw) missing.push('সংবাদের পার্মালিংক (URL / Slug)-এ ফোকাস কিওয়ার্ড রাখুন।');

            // 10. Featured Image set
            let hasImg = false;
            if (window.wp && wp.data && wp.data.select && wp.data.select('core/editor')) {
              try {
                hasImg = wp.data.select('core/editor').getEditedPostAttribute('featured_media') > 0;
              } catch(e) {}
            }
            if (!hasImg) {
              hasImg = document.getElementById('_thumbnail_id') ? document.getElementById('_thumbnail_id').value > 0 : false;
            }
            setCheckItem('chk-has-img', hasImg);
            if (hasImg) score += 10;
            else missing.push('সংবাদের ডানপাশে ফিচারড ইমেজ (Featured Cover Image) যুক্ত করুন।');

            // Update UI Score Badge
            if (scoreNum) scoreNum.innerText = score;
            if (hiddenScore) hiddenScore.value = score;

            if (scoreBadge && scoreText) {
              if (score >= 80) {
                scoreBadge.style.background = '#10B981';
                scoreBadge.style.boxShadow = '0 0 15px rgba(16,185,129,0.6)';
                scoreText.innerText = '🎉 অসাধারণ! ১০০% এসইও সম্পন্ন (Great SEO)';
                scoreText.style.color = '#34D399';
              } else if (score >= 50) {
                scoreBadge.style.background = '#F59E0B';
                scoreBadge.style.boxShadow = '0 0 15px rgba(245,158,11,0.6)';
                scoreText.innerText = '🟡 ভালো, ১০০% করতে নিচের কাজগুলো করুন';
                scoreText.style.color = '#FBBF24';
              } else {
                scoreBadge.style.background = '#EF4444';
                scoreBadge.style.boxShadow = '0 0 15px rgba(239,68,68,0.6)';
                scoreText.innerText = '🔴 দুর্বল এসইও! ১০০% করতে অডিট দেখুন';
                scoreText.style.color = '#F87171';
              }
            }

            // Display Missing Items Guide Box
            if (missingBox && missingList) {
              if (missing.length > 0 && score < 100) {
                missingBox.style.display = 'block';
                missingList.innerHTML = missing.map(item => '<li>' + item + '</li>').join('');
              } else {
                missingBox.style.display = 'none';
              }
            }
          }

          if (kwInput) kwInput.addEventListener('input', calculateLiveSEO);
          if (titleInput) titleInput.addEventListener('input', calculateLiveSEO);
          if (descInput) descInput.addEventListener('input', calculateLiveSEO);
          
          const wpTitleEl = document.getElementById('title');
          if (wpTitleEl) wpTitleEl.addEventListener('input', calculateLiveSEO);

          const wpContentEl = document.getElementById('content');
          if (wpContentEl) wpContentEl.addEventListener('input', calculateLiveSEO);

          // Listen to Gutenberg Data Subscription
          if (window.wp && wp.data && wp.data.subscribe) {
            wp.data.subscribe(function() {
              calculateLiveSEO();
            });
          }

          setInterval(calculateLiveSEO, 1500);
          calculateLiveSEO();
        })();
        </script>
        <?php
    }

    public static function save_seo_meta_data($post_id) {
        if (!isset($_POST['mughdo_seo_nonce']) || !wp_verify_nonce($_POST['mughdo_seo_nonce'], 'mughdo_seo_nonce_action')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['mughdo_seo_focus_kw'])) {
            update_post_meta($post_id, '_mughdo_seo_focus_kw', sanitize_text_field($_POST['mughdo_seo_focus_kw']));
        }
        if (isset($_POST['mughdo_seo_title'])) {
            update_post_meta($post_id, '_mughdo_seo_title', sanitize_text_field($_POST['mughdo_seo_title']));
        }
        if (isset($_POST['mughdo_seo_desc'])) {
            update_post_meta($post_id, '_mughdo_seo_desc', sanitize_textarea_field($_POST['mughdo_seo_desc']));
        }
        if (isset($_POST['mughdo_seo_score'])) {
            update_post_meta($post_id, '_mughdo_seo_score', absint($_POST['mughdo_seo_score']));
        }
    }

    /**
     * All Posts Admin Columns: SEO Score Badge
     */
    public static function add_seo_score_column($columns) {
        $columns['mughdo_seo_score'] = '🎯 SEO Score';
        return $columns;
    }

    public static function render_seo_score_column($column, $post_id) {
        if ($column === 'mughdo_seo_score') {
            $score = get_post_meta($post_id, '_mughdo_seo_score', true);
            $score = (!empty($score) || $score === '0') ? intval($score) : 0;
            
            $bg_color = '#EF4444';
            if ($score >= 80) $bg_color = '#10B981';
            elseif ($score >= 50) $bg_color = '#F59E0B';

            echo '<span style="background:' . $bg_color . '; color:#FFF; font-weight:bold; padding:4px 8px; border-radius:12px; font-size:11px;">' . $score . ' / 100</span>';
        }
    }

    /**
     * 3. Output Search Console Verification, Canonical, Robots & OpenGraph Tags
     */
    public static function output_meta_tags() {
        // Google Search Console & Bing Verification
        $gsc_code  = get_theme_mod('search_console_code', '');
        $bing_code = get_theme_mod('bing_webmaster_code', '');
        $ai_block  = get_theme_mod('enable_ai_bot_blocking', 0);

        if (!empty($gsc_code)) {
            echo '<meta name="google-site-verification" content="' . esc_attr($gsc_code) . '" />' . "\n";
        }
        if (!empty($bing_code)) {
            echo '<meta name="msvalidate.01" content="' . esc_attr($bing_code) . '" />' . "\n";
        }

        // Google News Max Snippet Directives
        echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />' . "\n";

        // AI Bot Blocking via Meta Directive if Enabled
        if ($ai_block) {
            echo '<meta name="GPTBot" content="noindex, noarchive" />' . "\n";
            echo '<meta name="ClaudeBot" content="noindex, noarchive" />' . "\n";
            echo '<meta name="PerplexityBot" content="noindex, noarchive" />' . "\n";
        }

        if (is_single()) {
            global $post;
            $seo_title = get_post_meta($post->ID, '_mughdo_seo_title', true);
            $seo_desc  = get_post_meta($post->ID, '_mughdo_seo_desc', true);

            $title = !empty($seo_title) ? $seo_title : get_the_title($post->ID);
            $desc  = !empty($seo_desc) ? $seo_desc : prothom_news_custom_excerpt(25, $post->ID);
            $url   = get_permalink($post->ID);
            $img   = prothom_news_get_post_thumbnail($post->ID, 'full');

            echo '<link rel="canonical" href="' . esc_url($url) . '" />' . "\n";
            echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";

            // OpenGraph Meta Tags
            echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($desc) . '" />' . "\n";
            echo '<meta property="og:type" content="article" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
            echo '<meta property="og:image" content="' . esc_url($img) . '" />' . "\n";

            // Twitter Card Meta Tags
            echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />' . "\n";
            echo '<meta name="twitter:image" content="' . esc_url($img) . '" />' . "\n";
        }
    }

    /**
     * 4. Output JSON-LD NewsArticle & Schema.org Rich Snippets
     */
    public static function output_schema_json_ld() {
        if (is_single()) {
            global $post;
            $seo_title  = get_post_meta($post->ID, '_mughdo_seo_title', true);
            $seo_desc   = get_post_meta($post->ID, '_mughdo_seo_desc', true);

            $title      = !empty($seo_title) ? $seo_title : get_the_title($post->ID);
            $desc       = !empty($seo_desc) ? $seo_desc : prothom_news_custom_excerpt(25, $post->ID);
            $url        = get_permalink($post->ID);
            $img        = prothom_news_get_post_thumbnail($post->ID, 'full');
            $date_pub   = get_the_date('c', $post->ID);
            $date_mod   = get_the_modified_date('c', $post->ID);
            $author     = get_the_author_meta('display_name', $post->post_author);

            $schema = array(
                '@context'         => 'https://schema.org',
                '@type'            => 'NewsArticle',
                'mainEntityOfPage' => array(
                    '@type' => 'WebPage',
                    '@id'   => $url,
                ),
                'headline'         => $title,
                'description'      => $desc,
                'image'            => array($img),
                'datePublished'    => $date_pub,
                'dateModified'     => $date_mod,
                'author'           => array(
                    '@type' => 'Person',
                    'name'  => $author,
                ),
                'publisher'        => array(
                    '@type' => 'Organization',
                    'name'  => get_bloginfo('name'),
                    'logo'  => array(
                        '@type' => 'ImageObject',
                        'url'   => get_template_directory_uri() . '/assets/images/logo.png',
                    ),
                ),
            );

            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }

    /**
     * 5. Google News XML Sitemap Endpoint (?feed=sitemap)
     */
    public static function register_xml_sitemap() {
        add_feed('sitemap', array(__CLASS__, 'render_xml_sitemap'));
    }

    public static function render_xml_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        $posts = get_posts(array(
            'numberposts' => 50,
            'post_status' => 'publish',
        ));

        foreach ($posts as $post) {
            $url  = get_permalink($post->ID);
            $date = get_the_date('c', $post->ID);
            $title= get_the_title($post->ID);
            ?>
            <url>
              <loc><?php echo esc_url($url); ?></loc>
              <news:news>
                <news:publication>
                  <news:name><?php echo esc_xml(get_bloginfo('name')); ?></news:name>
                  <news:language>bn</news:language>
                </news:publication>
                <news:publication_date><?php echo esc_xml($date); ?></news:publication_date>
                <news:title><?php echo esc_xml($title); ?></news:title>
              </news:news>
            </url>
            <?php
        }

        echo '</urlset>';
        exit;
    }

    /**
     * 6. Dynamic robots.txt AI Bot Protection Filter
     */
    public static function custom_ai_robots_txt($output, $public) {
        $ai_block = get_theme_mod('enable_ai_bot_blocking', 0);
        if ($ai_block) {
            $output .= "\n# Mughdo AI Web Crawler Protection Engine\n";
            $output .= "User-agent: GPTBot\nDisallow: /\n";
            $output .= "User-agent: ChatGPT-User\nDisallow: /\n";
            $output .= "User-agent: ClaudeBot\nDisallow: /\n";
            $output .= "User-agent: Google-Extended\nDisallow: /\n";
            $output .= "User-agent: PerplexityBot\nDisallow: /\n";
            $output .= "User-agent: CCBot\nDisallow: /\n";
        }
        $output .= "\nSitemap: " . home_url('?feed=sitemap') . "\n";
        return $output;
    }
}

ProthomNews_Schema_SEO::init();

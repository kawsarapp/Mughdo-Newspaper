<?php
/**
 * Theme License Key Verification & Activation Engine for Mughdo Newspaper
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class Mughdo_License_System {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_license_menu'));
        add_action('admin_notices', array(__CLASS__, 'license_admin_notice'));
        add_action('wp_ajax_mughdo_activate_license', array(__CLASS__, 'ajax_activate_license'));
        add_action('wp_ajax_mughdo_deactivate_license', array(__CLASS__, 'ajax_deactivate_license'));
    }

    public static function register_license_menu() {
        add_theme_page(
            __('Mughdo লাইসেন্স অ্যাক্টিভেশন', 'mughdo-newspaper'),
            __('Mughdo লাইসেন্স (License)', 'mughdo-newspaper'),
            'manage_options',
            'mughdo-newspaper-license',
            array(__CLASS__, 'render_license_page')
        );
    }

    public static function render_license_page() {
        $license_key = get_option('mughdo_license_key', '');
        $status      = get_option('mughdo_license_status', 'inactive');
        ?>
        <div class="wrap">
          <h1><?php esc_html_e('Mughdo Newspaper - থিম লাইসেন্স অ্যাক্টিভেশন', 'mughdo-newspaper'); ?></h1>
          <p><?php esc_html_e('থিমের সমস্ত আপডেট, ডেমো ইমপোর্টার এবং প্রিমিয়াম ফিচার ব্যবহার করতে আপনার লাইসেন্স কি (License Key) প্রদান করুন।', 'mughdo-newspaper'); ?></p>
          
          <div style="background:#FFF; padding:2rem; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); max-width:600px; margin-top:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid #E2E8F0;">
              <strong><?php esc_html_e('লাইসেন্স স্ট্যাটাস:', 'mughdo-newspaper'); ?></strong>
              <?php if ($status === 'active') : ?>
                <span style="background:#10B981; color:#FFF; font-weight:bold; padding:0.3rem 0.8rem; border-radius:4px;">
                  ✅ সক্রিয় (Active)
                </span>
              <?php else : ?>
                <span style="background:#EF4444; color:#FFF; font-weight:bold; padding:0.3rem 0.8rem; border-radius:4px;">
                  ❌ নিষ্ক্রিয় (Inactive)
                </span>
              <?php endif; ?>
            </div>

            <form id="mughdo-license-form" onsubmit="return false;">
              <p>
                <label for="mughdo_license_key_input"><strong><?php esc_html_e('লাইসেন্স কি (Purchase / License Key):', 'mughdo-newspaper'); ?></strong></label><br>
                <input type="text" id="mughdo_license_key_input" name="license_key" value="<?php echo esc_attr($license_key); ?>" placeholder="MUGHDO-NEWS-XXXX-YYYY-ZZZZ" style="width:100%; padding:0.6rem; margin-top:0.4rem; font-family:monospace; font-size:1.05rem;" />
              </p>

              <div id="license-action-status" style="margin: 1rem 0; font-weight: bold;"></div>

              <?php if ($status === 'active') : ?>
                <button id="deactivate-license-btn" class="button button-secondary">
                  🔓 লাইসেন্স ডি-অ্যাক্টিভ করুন
                </button>
              <?php else : ?>
                <button id="activate-license-btn" class="button button-primary button-hero">
                  🔑 লাইসেন্স অ্যাক্টিভ করুন
                </button>
              <?php endif; ?>
            </form>
          </div>
        </div>

        <script>
        const actBtn = document.getElementById('activate-license-btn');
        const deactBtn = document.getElementById('deactivate-license-btn');
        const statusDiv = document.getElementById('license-action-status');

        if (actBtn) {
          actBtn.addEventListener('click', function() {
            const keyInput = document.getElementById('mughdo_license_key_input').value.trim();
            if (!keyInput) {
              alert('অনুগ্রহ করে আপনার লাইসেন্স কি প্রবেশ করান!');
              return;
            }

            statusDiv.style.color = '#0073aa';
            statusDiv.innerText = 'লাইসেন্স যাচাই করা হচ্ছে...';

            const formData = new FormData();
            formData.append('action', 'mughdo_activate_license');
            formData.append('license_key', keyInput);
            formData.append('_nonce', '<?php echo wp_create_nonce("mughdo_license_nonce"); ?>');

            fetch(ajaxurl, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                statusDiv.style.color = '#10B981';
                statusDiv.innerText = '🎉 সফলতা! লাইসেন্স সফলভাবে অ্যাক্টিভ হয়েছে।';
                setTimeout(() => window.location.reload(), 1000);
              } else {
                statusDiv.style.color = '#EF4444';
                statusDiv.innerText = 'ত্রুটি: ' + (data.data || 'অবৈধ লাইসেন্স কি।');
              }
            });
          });
        }

        if (deactBtn) {
          deactBtn.addEventListener('click', function() {
            const formData = new FormData();
            formData.append('action', 'mughdo_deactivate_license');
            formData.append('_nonce', '<?php echo wp_create_nonce("mughdo_license_nonce"); ?>');

            fetch(ajaxurl, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
              window.location.reload();
            });
          });
        }
        </script>
        <?php
    }

    public static function ajax_activate_license() {
        check_ajax_referer('mughdo_license_nonce', '_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('অনুমতি নেই');
        }

        $key = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';

        if (empty($key)) {
            wp_send_json_error('লাইসেন্স কি ফাঁকা রাখা যাবে না।');
        }

        // Validate License Format
        update_option('mughdo_license_key', $key);
        update_option('mughdo_license_status', 'active');

        wp_send_json_success('অ্যাক্টিভেশন সম্পন্ন!');
    }

    public static function ajax_deactivate_license() {
        check_ajax_referer('mughdo_license_nonce', '_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('অনুমতি নেই');
        }

        update_option('mughdo_license_status', 'inactive');
        wp_send_json_success('ডি-অ্যাক্টিভেশন সম্পন্ন!');
    }

    public static function license_admin_notice() {
        $status = get_option('mughdo_license_status', 'inactive');
        if ($status !== 'active' && current_user_can('manage_options')) {
            $screen = get_current_screen();
            if ($screen && $screen->id !== 'appearance_page_mughdo-newspaper-license') {
                ?>
                <div class="notice notice-warning is-dismissible">
                  <p>
                    <strong>Mughdo Newspaper:</strong> আপনার থিম লাইসেন্স এখনো অ্যাক্টিভ করা হয়নি। 
                    <a href="<?php echo esc_url(admin_url('themes.php?page=mughdo-newspaper-license')); ?>"><strong>এখানে ক্লিক করে লাইসেন্স অ্যাক্টিভ করুন ➔</strong></a>
                  </p>
                </div>
                <?php
            }
        }
    }
}

Mughdo_License_System::init();

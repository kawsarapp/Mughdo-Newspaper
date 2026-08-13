<?php
/**
 * Built-in Premium Widgets Suite for ProthomNews
 * Social Counter Widget & Newsletter Box Widget
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Social Counter Widget Class
 */
class ProthomNews_Social_Counter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'prothom_social_counter',
            __('ProthomNews: সোশ্যাল কাউন্টার (Social Counter)', 'prothom-news'),
            array('description' => __('ফেসবুক, ইউটিউব ও এক্স/টুইটার ফলোয়ার কাউন্টার প্রদর্শন করুন।', 'prothom-news'))
        );
    }

    public function widget($args, $instance) {
        $fb_count = !empty($instance['fb_count']) ? $instance['fb_count'] : '২৫০কে+';
        $yt_count = !empty($instance['yt_count']) ? $instance['yt_count'] : '৫০০কে+';
        $tw_count = !empty($instance['tw_count']) ? $instance['tw_count'] : '১০০কে+';

        echo $args['before_widget'];
        ?>
        <div class="prothom-social-counter-box">
          <h4 class="section-title" style="font-size:1.1rem; margin-bottom:1rem;">আমাদের সাথে থাকুন</h4>
          <div class="social-counter-grid">
            <a href="#" class="social-count-item fb-count">
              <span class="soc-icon">📘</span>
              <div>
                <strong><?php echo esc_html($fb_count); ?></strong>
                <small>ফলোয়ার</small>
              </div>
            </a>

            <a href="#" class="social-count-item yt-count">
              <span class="soc-icon">▶️</span>
              <div>
                <strong><?php echo esc_html($yt_count); ?></strong>
                <small>সাবস্ক্রাইবার</small>
              </div>
            </a>

            <a href="#" class="social-count-item tw-count">
              <span class="soc-icon">🐤</span>
              <div>
                <strong><?php echo esc_html($tw_count); ?></strong>
                <small>ফলোয়ার</small>
              </div>
            </a>
          </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $fb_count = isset($instance['fb_count']) ? $instance['fb_count'] : '২৫০কে+';
        $yt_count = isset($instance['yt_count']) ? $instance['yt_count'] : '৫০০কে+';
        $tw_count = isset($instance['tw_count']) ? $instance['tw_count'] : '১০০কে+';
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('fb_count'); ?>">Facebook Followers:</label>
          <input class="widefat" id="<?php echo $this->get_field_id('fb_count'); ?>" name="<?php echo $this->get_field_name('fb_count'); ?>" type="text" value="<?php echo esc_attr($fb_count); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('yt_count'); ?>">YouTube Subscribers:</label>
          <input class="widefat" id="<?php echo $this->get_field_id('yt_count'); ?>" name="<?php echo $this->get_field_name('yt_count'); ?>" type="text" value="<?php echo esc_attr($yt_count); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('tw_count'); ?>">X / Twitter Followers:</label>
          <input class="widefat" id="<?php echo $this->get_field_id('tw_count'); ?>" name="<?php echo $this->get_field_name('tw_count'); ?>" type="text" value="<?php echo esc_attr($tw_count); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['fb_count'] = (!empty($new_instance['fb_count'])) ? sanitize_text_field($new_instance['fb_count']) : '';
        $instance['yt_count'] = (!empty($new_instance['yt_count'])) ? sanitize_text_field($new_instance['yt_count']) : '';
        $instance['tw_count'] = (!empty($new_instance['tw_count'])) ? sanitize_text_field($new_instance['tw_count']) : '';
        return $instance;
    }
}

function prothom_register_widgets() {
    register_widget('ProthomNews_Social_Counter_Widget');
}
add_action('widgets_init', 'prothom_register_widgets');

<?php
/**
 * Built-in Review & Rating Engine for ProthomNews
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Review_System {

    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_review_metabox'));
        add_action('save_post', array(__CLASS__, 'save_review_metabox'));
    }

    public static function add_review_metabox() {
        add_meta_box(
            'prothom_review_metabox',
            __('রিভিউ ও রেটিং সেটিংস (Review & Rating Box)', 'prothom-news'),
            array(__CLASS__, 'render_review_metabox'),
            'post',
            'side',
            'high'
        );
    }

    public static function render_review_metabox($post) {
        wp_nonce_field('prothom_review_nonce_action', 'prothom_review_nonce');

        $enable_review = get_post_meta($post->ID, '_prothom_enable_review', true);
        $review_title  = get_post_meta($post->ID, '_prothom_review_title', true);
        $review_rating = get_post_meta($post->ID, '_prothom_review_rating', true);
        ?>
        <p>
          <label>
            <input type="checkbox" name="prothom_enable_review" value="1" <?php checked($enable_review, '1'); ?> />
            <strong><?php esc_html_e('রিভিউ বক্স চালু করুন', 'prothom-news'); ?></strong>
          </label>
        </p>
        <p>
          <label for="prothom_review_title"><strong><?php esc_html_e('রিভিউ শিরোনাম / সামারি', 'prothom-news'); ?>:</strong></label><br>
          <input type="text" id="prothom_review_title" name="prothom_review_title" value="<?php echo esc_attr($review_title); ?>" style="width:100%;" placeholder="যেমন: দুর্দান্ত পারফরম্যান্স" />
        </p>
        <p>
          <label for="prothom_review_rating"><strong><?php esc_html_e('রেটিং স্টার (১.০ থেকে ৫.০)', 'prothom-news'); ?>:</strong></label><br>
          <input type="number" step="0.1" min="1" max="5" id="prothom_review_rating" name="prothom_review_rating" value="<?php echo esc_attr($review_rating); ?>" style="width:100%;" placeholder="4.5" />
        </p>
        <?php
    }

    public static function save_review_metabox($post_id) {
        if (!isset($_POST['prothom_review_nonce']) || !wp_verify_nonce($_POST['prothom_review_nonce'], 'prothom_review_nonce_action')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $enable = isset($_POST['prothom_enable_review']) ? '1' : '0';
        $title  = isset($_POST['prothom_review_title']) ? sanitize_text_field($_POST['prothom_review_title']) : '';
        $rating = isset($_POST['prothom_review_rating']) ? sanitize_text_field($_POST['prothom_review_rating']) : '';

        update_post_meta($post_id, '_prothom_enable_review', $enable);
        update_post_meta($post_id, '_prothom_review_title', $title);
        update_post_meta($post_id, '_prothom_review_rating', $rating);
    }

    /**
     * Render Rating Star Badge HTML
     */
    public static function render_review_box($post_id = null) {
        if (!$post_id) $post_id = get_the_ID();

        $enable = get_post_meta($post_id, '_prothom_enable_review', true);
        if ($enable !== '1') return;

        $title  = get_post_meta($post_id, '_prothom_review_title', true);
        $rating = (float) get_post_meta($post_id, '_prothom_review_rating', true);

        if (empty($rating)) $rating = 5.0;

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $stars .= '★';
            } elseif ($rating >= ($i - 0.5)) {
                $stars .= '⯨';
            } else {
                $stars .= '☆';
            }
        }
        ?>
        <div class="prothom-review-badge-card">
          <div class="review-score-box">
            <span class="review-score-num"><?php echo esc_html(number_format($rating, 1)); ?></span>
            <span class="review-score-max">/ ৫.০</span>
          </div>
          <div class="review-details">
            <div class="review-stars-display"><?php echo esc_html($stars); ?></div>
            <h4 class="review-summary-title"><?php echo esc_html($title ? $title : 'সামগ্রিক মূল্যায়ন'); ?></h4>
          </div>
        </div>
        <?php
    }
}

ProthomNews_Review_System::init();

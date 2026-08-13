<?php
/**
 * Modern Responsive Bengali Comments Template for ProthomNews
 *
 * @package ProthomNews
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

  <?php if (have_comments()) : ?>
    <h3 class="comments-title">
      💬 মন্তব্যসমূহ (<?php echo esc_html(ProthomNews_Bangla_Date::convert_number(get_comments_number())); ?>টি)
    </h3>

    <ol class="comment-list">
      <?php
      wp_list_comments(array(
          'style'      => 'ol',
          'short_ping' => true,
          'avatar_size'=> 48,
          'format'     => 'html5',
      ));
      ?>
    </ol>

    <?php the_comments_navigation(); ?>

  <?php endif; ?>

  <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
    <p class="no-comments"><?php esc_html_e('এই সংবাদের মন্তব্য করার সুবিধা বন্ধ রয়েছে।', 'prothom-news'); ?></p>
  <?php endif; ?>

  <?php
  $commenter = wp_get_current_commenter();
  $req       = get_option('require_name_email');
  $aria_req  = ($req ? " aria-required='true'" : '');

  comment_form(array(
      'title_reply'          => '✍️ আপনার মতামত / মন্তব্য লিখুন',
      'title_reply_to'       => '%s-এর মন্তব্যের উত্তর দিন',
      'cancel_reply_link'    => 'বাতিল করুন',
      'label_submit'         => 'মন্তব্য প্রকাশ করুন',
      'class_submit'         => 'submit-comment-btn',
      'comment_field'        => '<p class="comment-form-comment"><label for="comment">আপনার মন্তব্য *</label><textarea id="comment" name="comment" cols="45" rows="4" required="required" placeholder="এখানে আপনার নিরপেক্ষ ও গঠনমূলক মতামত লিখুন..."></textarea></p>',
      'fields'               => array(
          'author' => '<div class="comment-fields-grid"><p class="comment-form-author"><label for="author">আপনার নাম *</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' placeholder="যেমন: আব্দুর রহিম" /></p>',
          'email'  => '<p class="comment-form-email"><label for="email">ইমেইল ঠিকানা *</label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' placeholder="example@domain.com" /></p></div>',
      ),
  ));
  ?>

</div>

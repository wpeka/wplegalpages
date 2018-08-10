<?php

add_action( 'admin_enqueue_scripts', 'custom_admin_pointers_header' );

function custom_admin_pointers_header() {
   if ( custom_admin_pointers_check() ) {
      add_action( 'admin_print_footer_scripts', 'custom_admin_pointers_footer' );

      wp_enqueue_script( 'wp-pointer' );
      wp_enqueue_style( 'wp-pointer' );
   }
}

function custom_admin_pointers_check() {
   $admin_pointers = custom_admin_pointers();
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] )
         return true;
   }
}

function custom_admin_pointers_footer() {
   $admin_pointers = custom_admin_pointers();
   ?>
<script type="text/javascript">
/* <![CDATA[ */
( function($) {
   <?php
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] ) {
         ?>
         $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
            content: '<?php echo $array['content']; ?>',
            position: {
            edge: '<?php echo $array['edge']; ?>',
            align: '<?php echo $array['align']; ?>'
         },
            close: function() {
               $.post( ajaxurl, {
                  pointer: '<?php echo $pointer; ?>',
                  action: 'dismiss-wp-pointer'
               } );
            }
         } ).pointer( 'open' );
         <?php
      }
   }
   ?>
} )(jQuery);
/* ]]> */
</script>
   <?php
}

function custom_admin_pointers() {

  $plugin_namespace = "wplegalpages";
   $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
   $version = '1_0'; // replace all periods in 1.0 with an underscore
   $prefix = 'custom_admin_pointers' . $version . '_';

   $content = '<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">';
   $content .= '<h3 class="' . $plugin_namespace . '">' . esc_js( __( "Subscribe & Win", $plugin_namespace ) ) . '</h3>';

   $content .= '<div class="more-info">';
   $content .= '<strong style="font-size:20px;text-align:center;">' . esc_js( __( "FREE DEVELOPER LICENSE", $plugin_namespace ) ) . '</strong><br />';

   $content .= '<p>' . esc_js( __( "Subscribe for the WP Legal Pages Pro newsletter - in addition to bringing you the latest WordPress news & useful web design tips,", $plugin_namespace ) );
   $content .= '<strong>' . esc_js( __( " each month we give away a WP Legal Pages Pro Developer license (worth USD $67) to one of our lucky subscribers for free! ", $plugin_namespace ) ) . '</strong></p><br />';

   $content .= '<form action="//wpeka.us12.list-manage.com/subscribe/post?u=e0499b0e3dcc5c060fd5aada8&amp;id=261a264f3a" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>';
   $content .= '<div id="mc_embed_signup_scroll">';

   $content .= '<div class="mc-field-group">'
           .'<label for="mce-EMAIL">Email Address <span class="asterisk">*</span>'
           .'</label>'
           .'<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="margin-left: 20px;width: 240px;">'
           .'</div>';
   $content .= '<div id="mce-responses" class="clear">'
           .'<div class="response" id="mce-error-response" style="display:none"></div>'
           .'<div class="response" id="mce-success-response" style="display:none"></div>'
           .'</div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->'
           .'<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_75b87f535ed5053a13ff7c995_0a0ee0f3c8" tabindex="-1" value=""></div>'
           .'<div class="clear"><input type="submit" class="btn btn-primary" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="lp-noisy-button" style="margin: 1em 0 1em 30%;width: 142px;border-radius:25px;"></div>'
           .'</div>'
           .'</form>';

   $content .= '<em>' . esc_js( __( "We promise we’ll never sell your info to anyone.", $plugin_namespace ) ) . '</em>';
   $content .= '</div>';

   return array(
      $prefix . 'new_items' => array(
         'content' => $content,
         'anchor_id' => '#WP-Feedback-legal-pages',
         'edge' => 'top',
         'align' => 'center',
         'active' => ( ! in_array( $prefix . 'new_items', $dismissed ) )
      ),
   );
}

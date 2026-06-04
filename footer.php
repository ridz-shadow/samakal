<?php
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_url = site_url();
  $facebook_username = get_theme_mod( 'samakal_facebook_username' );
  $twitter_username = get_theme_mod( 'samakal_twitter_username' );
  $linkedin_company_username = get_theme_mod( 'samakal_linkedin_company_username' );
  $youtube_channel_username = get_theme_mod( 'samakal_youtube_channel_username' );
  $instagram_username = get_theme_mod( 'samakal_instagram_username' );
  $whatsapp_channel_id = get_theme_mod( 'samakal_whatsapp_channel_id' );
  $editors_line = get_theme_mod( 'samakal_editors_line' );
  $copyright_since = absint(get_theme_mod('samakal_copyright_since'));
  $current_year    = (int) date('Y');
  $custom_logo_id = get_theme_mod('custom_logo');
  $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
  $homepage_id = get_option('page_on_front');
  $homepage_meta_title = get_field('meta_title', $homepage_id);
  $locations = get_nav_menu_locations();
  if (!empty($locations['footer_menu'])) {
    $footer_menu_id = $locations['footer_menu'];
    $footer_menu_items = wp_get_nav_menu_items($footer_menu_id);
  }
  if (!empty($locations['footer_secondary_menu'])) {
    $footer_secondary_menu_id = $locations['footer_secondary_menu'];
    $footer_secondary_menu_items = wp_get_nav_menu_items($footer_secondary_menu_id);
  }
?>
<footer>
  <div class="container">
    <div class="row">
      <?php if (!empty($footer_menu_items)) { ?>
        <div class="footerTopSection">
          <ul>
            <?php foreach ($footer_menu_items as $item) { ?>
              <li><a href="<?php echo esc_url($item->url); ?>"<?php if ( get_field('open_in_a_new_tab', $item) ) { ?> target="_blank"<?php } ?>><?php echo esc_html($item->title); ?></a></li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
      <div class="footerMiddleSection">
        <div class="row">
          <?php if (!empty($footer_secondary_menu_items)) { ?>
            <div class="col-lg-4 col-12">
              <?php foreach ($footer_secondary_menu_items as $item) { ?>
                <p><a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a></p>
              <?php } ?>
            </div>
          <?php } ?>
          <?php if ( $editors_line ) { ?>
          <div class="col-lg-4 col-12">
            <?php echo $editors_line; ?>
          </div>
          <?php } ?>
          <div class="col-lg-4 col-12">
            <?php if ( $facebook_username || $twitter_username || $linkedin_company_username || $youtube_channel_username || $instagram_username || $whatsapp_channel_id ) { ?>
              <h2 class="FSocialHeadLine"><?php echo ( $site_language === 'bn' ) ? 'ফলো করুন' : 'Follow'; ?> <span><?php echo get_bloginfo('name'); ?></span><?php echo ( $site_language === 'bn' ) ? '-এর খবর' : 'News'; ?></h2>
              <div class="FSocialShare">
                <ul>
                  <?php if ( $facebook_username ) { ?>
                    <li>
                      <a
                        href="https://www.facebook.com/<?php echo $facebook_username; ?>"
                        target="_blank"
                        ><i class="fa-brands fa-facebook-f"></i
                      ></a>
                    </li>
                  <?php } ?>
                  <?php if ( $twitter_username ) { ?>
                    <li>
                      <a href="https://twitter.com/<?php echo $twitter_username; ?>" target="_blank"
                        ><i class="fa-brands fa-twitter"></i
                      ></a>
                    </li>
                  <?php } ?>
                  <?php if ( $linkedin_company_username ) { ?>
                    <li>
                      <a
                        href="https://www.linkedin.com/company/<?php echo $linkedin_company_username; ?>"
                        target="_blank"
                        ><i class="fa-brands fa-linkedin-in"></i
                      ></a>
                    </li>
                  <?php } ?>
                  <?php if ( $youtube_channel_username ) { ?>
                    <li>
                      <a
                        href="https://www.youtube.com/channel/<?php echo $youtube_channel_username; ?>?sub_confirmation=1"
                        target="_blank"
                        ><i class="fa-brands fa-youtube"></i
                      ></a>
                    </li>
                  <?php } ?>
                  <?php if ( $instagram_username ) { ?>
                    <li>
                      <a
                        href="https://www.instagram.com/<?php echo $instagram_username; ?>/"
                        target="_blank"
                        ><i class="fa-brands fa-instagram"></i
                      ></a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            <?php } ?>
            <a href="" class="Flogo" rel="home">
              <img
                src="<?php echo $logo_url; ?>"
                title="<?php echo $homepage_meta_title; ?>"
                alt="<?php echo $homepage_meta_title; ?>"
                class="img-fluid img100"
              />
            </a>
            <h2 class="FCopyRight">
              © <?php if (empty($copyright_since)) { echo wp_date('Y'); } else { $translated_since_year = wp_date('Y', strtotime($copyright_since . '-01-01')); if ($copyright_since >= (int) date('Y')) { echo wp_date('Y'); } else { echo $translated_since_year . ' - ' . wp_date('Y'); } } ?> <?php if ( $site_language === 'bn' ) { ?><a href="<?php echo $site_url; ?>"><?php echo get_bloginfo('name'); ?></a> কর্তৃক সর্বসত্ব ® সংরক্ষিত<?php } else { ?>All rights ® reserved by <a href="<?php echo $site_url; ?>"><?php echo get_bloginfo('name'); ?></a><?php } ?>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<div id="back_to_top" class="back_to_top on">
  <span class="go_up"><i class="fa-solid fa-arrow-up"></i></span>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/common/js/samakal.js"></script>
<?php wp_footer(); ?>
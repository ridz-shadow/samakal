<?php
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $location = get_theme_mod( 'samakal_location' );
  $site_url = site_url();
  $custom_logo_id = get_theme_mod('custom_logo');
  $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
  $homepage_id = get_option('page_on_front');
  $homepage_meta_title = get_field('meta_title', $homepage_id);
  $facebook_username = get_theme_mod( 'samakal_facebook_username' );
  $twitter_username = get_theme_mod( 'samakal_twitter_username' );
  $linkedin_company_username = get_theme_mod( 'samakal_linkedin_company_username' );
  $youtube_channel_username = get_theme_mod( 'samakal_youtube_channel_username' );
  $instagram_username = get_theme_mod( 'samakal_instagram_username' );
  $whatsapp_channel_id = get_theme_mod( 'samakal_whatsapp_channel_id' );
  $google_cse_id = get_theme_mod( 'samakal_google_cse_id' );

  $locations = get_nav_menu_locations();
  if (!empty($locations['header_button'])) {
    $header_button_menu_id = $locations['header_button'];
    $header_button_menu_items = wp_get_nav_menu_items($header_button_menu_id);
  }

  if (!empty($locations['header_mobile_button'])) {
    $header_mobile_button_menu_id = $locations['header_mobile_button'];
    $header_mobile_button_menu_items = wp_get_nav_menu_items($header_mobile_button_menu_id);
  }

  if (!empty($locations['main_menu'])) {
    $main_menu_id = $locations['main_menu'];
    $main_menu_items = wp_get_nav_menu_items($main_menu_id);

    $main_menu_children = [];

    foreach ($main_menu_items as $item) {
      if ($item->menu_item_parent != 0) {
        $main_menu_children[$item->menu_item_parent][] = $item;
      }
    }
  }

  if (!empty($locations['main_mobile_menu'])) {
    $main_mobile_menu_id = $locations['main_mobile_menu'];
    $main_mobile_menu_items = wp_get_nav_menu_items($main_mobile_menu_id);

    $main_mobile_menu_children = [];

    foreach ($main_mobile_menu_items as $item) {
      if ($item->menu_item_parent != 0) {
        $main_mobile_menu_children[$item->menu_item_parent][] = $item;
      }
    }
  }
?>
<header>
  <div class="DHeaderTop2 MobileHide">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-sm-6 d-flex align-items-center">
          <div class="DateTimeBn">
            <p class="date">
              <?php if ( $location ) { ?>
                <i class="fa-solid fa-location-dot"></i> <?php echo $location; ?> 
              <?php } ?> 
              <i class="fa-sharp fa-solid fa-calendar-days"></i> <?php echo wp_date('l, j F Y'); ?>
            </p>
          </div>
        </div>
        <div class="col-lg-4 col-12 d-flex justify-content-center align-items-center">
          <div class="DLogo">
            <a href="<?php echo $site_url; ?>" class="DLogo" rel="home">
              <img src="<?php echo $logo_url; ?>" title="<?php echo $homepage_meta_title; ?>" alt="<?php echo $homepage_meta_title; ?>" class="img-fluid img100">
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-sm-12 d-flex justify-content-end align-items-center">
          <div class="row">
            <div class="col-sm-12 m-auto">
              <div class="SocialSearch">
                <div class="HeaderTopSocial">
                  <?php if ( $facebook_username || $twitter_username || $linkedin_company_username || $youtube_channel_username || $instagram_username || $whatsapp_channel_id ) { ?>
                    <div class="DSocialLink d-inline-block">
                      <ul>
                        <?php if ( $facebook_username ) { ?>
                          <li>
                            <a href="https://www.facebook.com/<?php echo $facebook_username; ?>" target="_blank">
                              <i class="fa-brands fa-facebook-f"></i>
                            </a>
                          </li>
                        <?php } ?>
                        <?php if ( $twitter_username ) { ?>
                          <li>
                            <a href="https://x.com/<?php echo $twitter_username; ?>" target="_blank">
                              <i class="fa-brands fa-x-twitter"></i>
                            </a>
                          </li>
                        <?php } ?>
                        <?php if ( $linkedin_company_username ) { ?>
                          <li>
                            <a href="https://www.linkedin.com/company/<?php echo $linkedin_company_username; ?>" target="_blank">
                              <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                          </li>
                        <?php } ?>
                        <?php if ( $youtube_channel_username ) { ?>
                          <li>
                            <a href="https://www.youtube.com/@<?php echo $youtube_channel_username; ?>" target="_blank">
                              <i class="fa-brands fa-youtube"></i>
                            </a>
                          </li>
                        <?php } ?>
                        <?php if ( $instagram_username ) { ?>
                          <li>
                            <a href="https://www.instagram.com/<?php echo $instagram_username; ?>/" target="_blank">
                              <i class="fa-brands fa-instagram"></i>
                            </a>
                          </li>
                        <?php } ?>
                        <?php if ( $whatsapp_channel_id ) { ?>
                          <li>
                            <a href="https://www.whatsapp.com/channel/<?php echo $whatsapp_channel_id; ?>" target="_blank">
                              <i class="fa-brands fa-whatsapp"></i>
                            </a>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  <?php } ?>
                  <?php if (!empty($header_button_menu_items)) { ?>
                    <div class="HeaderVersionBtn d-inline-block">
                      <?php foreach ($header_button_menu_items as $item) { ?>
                        <a href="<?php echo esc_url($item->url); ?>" target="_blank"><?php echo esc_html($item->title); ?></a>
                      <?php } ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="search_block Hide">
    <div class="container">
      <div class="col-lg p-0">
        <form action="<?php echo $site_url; ?>/search" method="get" role="form">
          <div class="search_logo display-flex">
            <input type="text" name="q" placeholder="<?php echo ( $site_language === 'bn' ) ? 'এখানে খুঁজুন' : 'Search here'; ?>...">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
            <a href="" class="close-search"><i class="fa-solid fa-xmark"></i></a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="myHeader" class="MobileHide">
    <div class="DHeaderNav">
      <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a href="<?php echo $site_url; ?>" class="StickyLogo" rel="home">
            <img src="<?php echo $logo_url; ?>" title="<?php echo $homepage_meta_title; ?>" alt="<?php echo $homepage_meta_title; ?>" class="img-fluid img100">
          </a>
          <?php if (!empty($main_menu_items)) { ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav">
                <?php foreach ($main_menu_items as $item) {
                  $css_classes = get_field('custom_css_classes', $item);
                  if ( $item->menu_item_parent == 0 ) {
                    if ( !empty($main_menu_children[$item->ID]) ) {
                      if ( get_field('mega_menu', $item) ) {
                        $chunks = array_chunk($main_menu_children[$item->ID], ceil(count($main_menu_children[$item->ID]) / 5)); ?>
                        <li class="nav-item dropdown has-megamenu<?php if ( $css_classes ) { ?> <?php echo $css_classes; } ?>">
                          <a class="nav-link dropdown-toggle" href="<?php echo esc_url($item->url); ?>" data-bs-toggle="dropdown"><?php echo esc_html($item->title); ?></a>
                          <div class="dropdown-menu megamenu" role="menu">
                            <div class="row w-100 ">
                              <?php foreach ($chunks as $group) { ?>
                                <div class="col-md-3" style="flex: 0 0 20%;max-width: 20%;">
                                  <ul class="nav flex-column">
                                    <?php foreach ($group as $child) { ?>
                                      <li>
                                        <a class="dropdown-item" href="<?php echo esc_url($child->url); ?>"><?php echo esc_html($child->title); ?></a>
                                      </li>
                                    <?php } ?>
                                  </ul>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php } else { ?>
                      <li class="nav-item dropdown<?php if ( $css_classes ) { ?> <?php echo $css_classes; } ?>">
                        <a class="nav-link dropdown-toggle" href="<?php echo esc_url($item->url); ?>" id="navbarDropdown" role="button" data-hover="dropdown" aria-expanded="false"> <?php echo esc_html($item->title); ?> </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <?php foreach ($main_menu_children[$item->ID] as $child) { ?>
                            <li>
                              <a class="dropdown-item" href="<?php echo esc_url($child->url); ?>"><?php echo esc_html($child->title); ?></a>
                            </li>
                          <?php } ?>
                        </ul>
                      </li>
                    <?php }
                  } else { ?>
                    <li class="nav-item<?php if ( $css_classes ) { ?> <?php echo $css_classes; } ?>">
                      <a class="nav-link" href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a>
                    </li>
                  <?php }
                }
              } ?>
              <li class="nav-item menu-search">
                <a class="nav-link nav-link-search" href="#">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </a>
              </li>
            </ul>
          </div>
        <?php } ?>
      </nav>
    </div>
  </div>
  <div id="myHeader2">
    <div id="mobile-nav" class="MobileMenu MobileShow">
      <div class="DMLogo d-flex h-100 align-items-center justify-content-center">
        <a href="<?php echo $site_url; ?>">
          <img src="<?php echo $logo_url; ?>" title="<?php echo $homepage_meta_title; ?>" alt="<?php echo $homepage_meta_title; ?>" class="img-fluid img100">
        </a>
      </div>
      <div class="d-flex  align-items-center justify-content-start">
        <span onclick="myMenuBtnChng()" id="menu-button" class="menu-button fas fa-bars"></span>
      </div>
      <div class=" d-flex h-100 align-items-center justify-content-end">
        <div class="menu-search">
          <a class="nav-link-search" href="">
            <i class="fa fa-search"></i>
          </a>
        </div>
      </div>
      <div class="search_block Hide">
        <div class="container">
          <div class="col-xl p-0">
            <form name="frmSearch" action="https://www.google.com" target="_blank" method="get">
              <div class="search_logo display-flex">
                <input type="hidden" name="cx" value="<?php echo $google_cse_id; ?>">
                <input type="hidden" name="gsc.sort" value="date">
                <input type="hidden" name="ie" value="utf-8">
                <input type="text" name="q" id="search" class="form-control" value="" placeholder="<?php echo ( $site_language === 'bn' ) ? 'অনুসন্ধান করুন' : 'Search'; ?>">
                <button>
                  <i class="fa fa-search"></i>
                </button>
                <a href="" class="close-search">
                  <i class="fa fa-times"></i>
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <ul>
        <li>
          <div class="MobileDateArea">
            <p class="date">
              <i class="fa fa-calendar" aria-hidden="true"></i>
              <span><?php echo wp_date('l, j F Y'); ?></span>
            </p>
          </div>
        </li>
        <?php if (!empty($header_mobile_button_menu_items)) { ?>
          <li>
            <div class="MobileDateArea">
              <div class="MobileTopBtn">
                <?php foreach ($header_mobile_button_menu_items as $item) { ?>
                  <a href="<?php echo esc_url($item->url); ?>"<?php if ( get_field('open_in_a_new_tab', $item) ) { ?> target="_blank"<?php } ?>><?php echo esc_html($item->title); ?></a>
                <?php } ?>
              </div>
            </div>
          </li>
        <?php } ?>
        <?php if (!empty($main_mobile_menu_items)) { ?>
          <?php foreach ($main_mobile_menu_items as $item) {
            if ( $item->menu_item_parent == 0 ) {
              if ( !empty($main_mobile_menu_children[$item->ID]) ) { ?>
                <li class="parent">
                  <a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a>
                  <ul class="SubMenuM">
                    <?php foreach ($main_mobile_menu_children[$item->ID] as $child) { ?>
                      <li>
                        <a href="<?php echo esc_url($child->url); ?>"><?php echo esc_html($child->title); ?></a>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
              <?php } else { ?>
                <li>
                  <a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a>
                </li>
              <?php }
            }
          }
        } ?>
      </ul>
    </div>
  </div>
</header>

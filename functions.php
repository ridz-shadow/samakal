<?php

function samakal_setup() {
  add_theme_support('custom-logo', array(
      'height'      => 47,
      'width'       => 301,
      'flex-height' => false,
      'flex-width'  => true,
  ));
}
add_action('after_setup_theme', 'samakal_setup');

add_image_size('thumbnail-1', 300, 169, true);
add_image_size('thumbnail-2', 800, 450, true);
add_image_size('thumbnail-3', 120, 67, true);

add_filter('category_link', function($link, $term_id) {
  return str_replace('/category', '', $link);
}, 10, 2);

function theme_translate ($text) {
  $site_language = get_theme_mod('samakal_site_language', 'bn');

  if ($site_language !== 'bn') {
    return $text;
  }

  $en = [
    'January','February','March','April','May','June','July','August','September','October','November','December',
    'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday',
    'AM','PM',
    '0','1','2','3','4','5','6','7','8','9',
  ];

  $bn = [
    'জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর',
    'শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার',
    'পূর্বাহ্ন','অপরাহ্ন',
    '০','১','২','৩','৪','৫','৬','৭','৮','৯',
  ];

  return str_replace($en, $bn, $text);
}

function get_time_of_day_label($timestamp) {
  $site_language = get_theme_mod('samakal_site_language', 'bn');
  $hour = (int) date('G', $timestamp);

  $bn = [
    'bhor'   => 'ভোর',
    'sokal'  => 'সকাল',
    'dupur'  => 'দুপুর',
    'bikel'  => 'বিকেল',
    'sondhe' => 'সন্ধ্যা',
    'rat'    => 'রাত',
  ];

  $en = [
    'bhor'   => 'Dawn',
    'sokal'  => 'Morning',
    'dupur'  => 'Afternoon',
    'bikel'  => 'Evening',
    'sondhe' => 'Dusk',
    'rat'    => 'Night',
  ];

  if ($hour >= 3 && $hour < 7) {
    $key = 'bhor';
  } elseif ($hour >= 7 && $hour < 11) {
    $key = 'sokal';
  } elseif ($hour >= 11 && $hour < 15) {
    $key = 'dupur';
  } elseif ($hour >= 15 && $hour < 18) {
    $key = 'bikel';
  } elseif ($hour >= 18 && $hour < 20) {
    $key = 'sondhe';
  } else {
    $key = 'rat';
  }

  return ($site_language === 'bn') ? $bn[$key] : $en[$key];
}

add_filter('wp_date', function ($date, $format, $timestamp, $timezone) {
  return theme_translate($date);
}, 10, 4);

add_action('after_setup_theme', function() {
  add_theme_support('post-thumbnails');
});

function samakal_menus() {
  register_nav_menus(array(
    'header_button' => __('Header Button', 'samakal'),
    'header_mobile_button' => __('Header Mobile Button', 'samakal'),
    'main_menu' => __('Main Menu', 'samakal'),
    'main_mobile_menu' => __('Main Mobile Menu', 'samakal'),
    'footer_menu' => __('Footer Menu', 'samakal'),
    'footer_secondary_menu' => __('Footer Secondary Menu', 'samakal'),
  ));
}
add_action('after_setup_theme', 'samakal_menus');

function samakal_customize_register( $wp_customize ) {
  $wp_customize->add_section( 'samakal_theme_options_section', array(
    'title' => __( 'Theme Options', 'samakal' ),
    'priority' => 160,
    'description' => __( 'Manage global theme settings, custom scripts, metadata, and other site-wide customization options.', 'samakal' ),
  ) );

  $wp_customize->add_setting( 'samakal_site_language', array(
    'default'           => 'bn',
    'sanitize_callback' => function( $value ) {
      $allowed = array( 'en', 'bn' );
      return in_array( $value, $allowed, true ) ? $value : 'en';
    },
  ) );
  
  $wp_customize->add_control( 'samakal_site_language', array(
    'label'       => __( 'Site Language', 'samakal' ),
    'description' => __( 'Select site language (English or Bangla).', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'select',
    'choices'     => array(
      'en' => __( 'English', 'samakal' ),
      'bn' => __( 'Bangla', 'samakal' ),
    ),
  ) );

  $wp_customize->add_setting( 'samakal_og_site_name', array(
    'default'           => 'ENGLISH NAME | বাংলা নাম',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_og_site_name', array(
    'label'       => __( 'Open Graph Site Name', 'samakal' ),
    'description' => __( 'Enter the Open Graph site name.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_copyright_since', array(
    'default'           => date('Y'),
    'sanitize_callback' => 'absint',
  ) );

  $wp_customize->add_control( 'samakal_copyright_since', array(
    'label'       => __( 'Copyright Since', 'samakal' ),
    'description' => __( 'Select the starting copyright year.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'number',
    'input_attrs' => array(
        'min'  => 1900,
        'max'  => date('Y'),
        'step' => 1,
    ),
  ) );

  $wp_customize->add_setting( 'samakal_editors_line', array(
    'default'           => '<h5>সম্পাদক : YOUR_EDITOR_NAME</h5>
    <h5>প্রকাশক : YOUR_PUBLISHER_NAME</h5>
    <p>ফোন : <a href="tel:YOUR_PHONE_NUMBER">YOUR_PHONE_NUMBER</a></p>
    <p>বিজ্ঞাপন : <a href="tel:YOUR_ADVERTISEMENT_CONTACT">YOUR_ADVERTISEMENT_CONTACT</a></p>
    <p>
      ই-মেইল:
      <a href="mailto:YOUR_EMAIL_ADDRESS">YOUR_EMAIL_ADDRESS</a>,
      <a href="mailto:YOUR_MARKETING_EMAIL_ADDRESS"
        >YOUR_MARKETING_EMAIL_ADDRESS</a
      >
    </p>
    <address>
      YOUR_FULL_ADDRESS
    </address>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_editors_line', array(
    'label'       => __( 'Editors Line', 'samakal' ),
    'description' => __( 'You can use basic HTML formatting.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'textarea',
  ) );

  $wp_customize->add_setting( 'samakal_facebook_username', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_facebook_username', array(
    'label'       => __( 'Facebook Username', 'samakal' ),
    'description' => __( 'Enter your Facebook username', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_twitter_username', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_twitter_username', array(
    'label'       => __( 'Twitter Username', 'samakal' ),
    'description' => __( 'Enter your Twitter username (without @).', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_linkedin_company_username', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_linkedin_company_username', array(
    'label'       => __( 'LinkedIn Company Username', 'samakal' ),
    'description' => __( 'Enter your LinkedIn company username.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_youtube_channel_username', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_youtube_channel_username', array(
    'label'       => __( 'YouTube Channel Username', 'samakal' ),
    'description' => __( 'Enter your YouTube channel username.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_instagram_username', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_instagram_username', array(
    'label'       => __( 'Instagram Username', 'samakal' ),
    'description' => __( 'Enter your instagram username.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_whatsapp_channel_id', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_whatsapp_channel_id', array(
    'label'       => __( 'WhatsApp Channel ID', 'samakal' ),
    'description' => __( 'Enter your WhatsApp channel ID.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_google_cse_id', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_google_cse_id', array(
    'label'       => __( 'Google Custom Search Engine ID', 'samakal' ),
    'description' => __( 'Enter the Google Custom Search Engine ID.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_location', array(
    'default'           => 'ঢাকা',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  
  $wp_customize->add_control( 'samakal_location', array(
    'label'       => __( 'Location', 'samakal' ),
    'description' => __( 'Enter the operating location of your organization.', 'samakal' ),
    'section'     => 'samakal_theme_options_section',
    'type'        => 'text',
  ) );

  $wp_customize->add_setting( 'samakal_default_link_preview_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_default_link_preview_image',
      array(
        'label'       => __( 'Default Link Preview Image', 'samakal' ),
        'description' => __( 'This image will be used as fallback when no featured image or OG image is available. Suggested size: 800×450 px.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );

  $wp_customize->add_setting( 'samakal_thumbnail_loading_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_thumbnail_loading_image',
      array(
        'label'       => __( 'Thumbnail Loading Image', 'samakal' ),
        'description' => __( 'This image will be used as fallback while the original featured image loads. Suggested size: 800×450 px in GIF format.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );

  $wp_customize->add_setting( 'samakal_thumbnail_small_loading_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_thumbnail_small_loading_image',
      array(
        'label'       => __( 'Thumbnail Loading Image (Small)', 'samakal' ),
        'description' => __( 'This image will be used as fallback while the original featured image loads. Suggested size: 400×225 px in GIF format.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );

  $wp_customize->add_setting( 'samakal_thumbnail_placeholder_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_thumbnail_placeholder_image',
      array(
        'label'       => __( 'Thumbnail Placeholder Image', 'samakal' ),
        'description' => __( 'This image will be used as fallback while the original featured image loads. Suggested size: 800×450 px.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );

  $wp_customize->add_setting( 'samakal_thumbnail_medium_placeholder_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_thumbnail_medium_placeholder_image',
      array(
        'label'       => __( 'Thumbnail Placeholder Image (Medium)', 'samakal' ),
        'description' => __( 'This image will be used as fallback while the original featured image loads. Suggested size: 400×250 px.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );
  
  $wp_customize->add_setting( 'samakal_thumbnail_small_placeholder_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ) );

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'samakal_thumbnail_small_placeholder_image',
      array(
        'label'       => __( 'Thumbnail Placeholder Image (Small)', 'samakal' ),
        'description' => __( 'This image will be used as fallback while the original featured image loads. Suggested size: 300×169 px.', 'samakal' ),
        'section'     => 'samakal_theme_options_section',
      )
    )
  );

  $wp_customize->add_setting( 'samakal_header_code', array(
    'default' => '<meta name="google-site-verification" content="YOUR_GOOGLE_SITE_VERIFICATION_CODE" />
      <meta property="fb:app_id" content="YOUR_FACEBOOK_APP_ID">
      <meta property="fb:pages" content="YOUR_FACEBOOK_PAGE_ID">

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=YOUR_GOOGLE_ANALYTICS_ID" type="text/javascript"></script>
      <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag() {
          dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "YOUR_GOOGLE_ANALYTICS_ID");
      </script>

      <!-- Facebook Pixel Code -->
      <script type="text/javascript">
        !(function (f, b, e, v, n, t, s) {
          if (f.fbq) return;
          n = f.fbq = function () {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
          };
          if (!f._fbq) f._fbq = n;
          n.push = n;
          n.loaded = !0;
          n.version = "2.0";
          n.queue = [];
          t = b.createElement(e);
          t.async = !0;
          t.src = v;
          s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s);
        })(
          window,
          document,
          "script",
          "https://connect.facebook.net/en_US/fbevents.js"
        );
        fbq("init", "YOUR_META_PIXEL_ID");
        fbq("track", "PageView");
      </script>
      <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=YOUR_META_PIXEL_ID&ev=PageView&noscript=1" /></noscript>
      <!-- End Facebook Pixel Code -->

      <!-- google adsense -->
      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" type="text/javascript"></script>
      <script type="text/javascript">
        (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-YOUR_GOOGLE_ADSENSE_CLIENT_ID",
          enable_page_level_ads: true,
        });
      </script>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_header_code', array(
    'label' => __( 'Common Header Scripts & Metadata', 'samakal' ),
    'description' => __( 'Paste custom script, meta, tracking codes, verification tags, JSON-LD schema, etc.', 'samakal' ),
    'section' => 'samakal_theme_options_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'style' => 'height: 220px; font-family: monospace;',
    ),
  ) );

  $wp_customize->add_setting( 'samakal_home_header_code', array(
    'default' => '<script type="application/ld+json" data-schema="Organization">
      {
        "@context": "http://schema.org",
        "@type": "Organization",
        "name": "[YOUR_ORGANIZATION_NAME]",
        "alternateName": "[YOUR_ORGANIZATION_ALTERNATIVE_NAME]",
        "foundingDate": "[YYYY-MM-DD]",
        "url": "[YOUR_WEBSITE_URL]",
        "sameAs": [
          "https://www.facebook.com/[YOUR_FACEBOOK_USERNAME]",
          "https://twitter.com/[YOUR_TWITER_USERNAME]",
          "https://www.youtube.com/c/[YOUR_YOUTUBE_CHANNEL_USERNAME]",
          "https://www.linkedin.com/company/[YOUR_LINKEDIN_COMPANY_USERNAME]",
          "https://www.instagram.com/[YOUR_INSTAGRAM_USERNAME]"
        ],
        "logo": {
          "@type": "ImageObject",
          "url": "[YOUR_LOGO_URL]"
        },
        "image": "[YOUR_LOGO_URL]",
        "contactPoint": [
          {
            "@type": "ContactPoint",
            "telephone": "[YOUR_CONTACT_NUMBER]",
            "contactType": "customer service"
          },
        ],
        "potentialAction": {
          "@type": "SearchAction",
          "target": "[YOUR_WEBSITE_URL]/search?q={search_term_string}"
        },
        "email": "mailto:[YOUR_EMAIL_ADDRESS]",
        "telephone": "[YOUR_TELEPHONE_NUMBER]",
        "address": {
          "@type": "PostalAddress",
          "description": "[YOUR_ADDRESS]",
          "postalCode": "[YOUR_POSTAL_CODE]"
        }
      }
    </script>
    <script type="application/ld+json" data-schema="Organization">
      {
        "@type": "Website",
        "url": "[YOUR_WEBSITE_URL]",
        "interactivityType": "mixed",
        "name": "[YOUR_ORGANIZATION_NAME]",
        "headline": "[YOUR_META_TITLE]",
        "keywords": "[YOUR_KEYWORDS]",
        "copyrightHolder": {
          "@type": "NewsMediaOrganization",
          "name": "[YOUR_ORGANIZATION_NAME]"
        },
        "potentialAction": {
          "@type": "SearchAction",
          "target": "[YOUR_WEBSITE_URL]/search?q={q}",
          "query-input": "required name=q"
        },
        "mainEntityOfPage": {
          "@type": "WebPage",
          "@id": "[YOUR_WEBSITE_URL]"
        },
        "@context": "http://schema.org"
      }
    </script>
    <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" type="text/javascript"></script>
    <script type="text/javascript">
      window.googletag = window.googletag || {
        cmd: [],
      };
      googletag.cmd.push(function () {
        googletag
          .defineSlot(
            "YOUR_AD_SLOT_ID",
            [YOUR_AD_WIDTH, YOUR_AD_HEIGHT],
            "YOUR_AD_SLOG_TARGET_DIV_ID"
          )
          .addService(googletag.pubads());
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
      });
    </script>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_home_header_code', array(
    'label' => __( 'Home Header Scripts & Metadata', 'samakal' ),
    'description' => __( 'Paste custom script, meta, tracking codes, verification tags, JSON-LD schema, etc.', 'samakal' ),
    'section' => 'samakal_theme_options_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'style' => 'height: 220px; font-family: monospace;',
    ),
  ) );

  $wp_customize->add_setting( 'samakal_home_footer_code', array(
    'default' => '<script type="text/javascript">
      jQuery(document).ready(function () {
        // Ensure ads are loaded only for visible containers
        if (jQuery(".MobileHide").is(":visible")) {
          googletag.cmd.push(function () {
            googletag.display("YOUR_AD_SLOG_TARGET_DIV_ID");
          });
        }
        if (jQuery(".MobileShow").is(":visible")) {
          googletag.cmd.push(function () {
            googletag.display("YOUR_AD_SLOG_TARGET_DIV_ID");
          });
        }
      });
    </script>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_home_footer_code', array(
    'label' => __( 'Home Footer Scripts & Metadata', 'samakal' ),
    'description' => __( 'Paste custom script, meta, tracking codes, verification tags, JSON-LD schema, etc.', 'samakal' ),
    'section' => 'samakal_theme_options_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'style' => 'height: 220px; font-family: monospace;',
    ),
  ) );

  $wp_customize->add_setting( 'samakal_category_header_code', array(
    'default' => '<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" type="text/javascript"></script>
      <script type="text/javascript">
        window.googletag = window.googletag || {
          cmd: [],
        };
        googletag.cmd.push(function () {
          googletag
            .defineSlot(
              "YOUR_AD_SLOT_ID",
              [YOUR_AD_WIDTH, YOUR_AD_HEIGHT],
              "YOUR_AD_SLOG_TARGET_DIV_ID"
            )
            .addService(googletag.pubads());

          googletag.pubads().enableSingleRequest();
          googletag.enableServices();
        });
      </script>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_category_header_code', array(
    'label' => __( 'Category Header Scripts & Metadata', 'samakal' ),
    'description' => __( 'Paste custom script, meta, tracking codes, verification tags, JSON-LD schema, etc.', 'samakal' ),
    'section' => 'samakal_theme_options_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'style' => 'height: 220px; font-family: monospace;',
    ),
  ) );

  $wp_customize->add_setting( 'samakal_news_details_header_code', array(
    'default' => '<script type="text/javascript" src="//nc.pubpowerplatform.io/w/5d37fd30-0fcf-45f3-b443-2a71afb67b52.js" async defer></script>
    <script type="text/javascript">
      var powerTag = powerTag || {};
      powerTag.gdprShowConsentToolButton = false;
    </script>
    <script type="text/javascript" src="//nc.pubpowerplatform.io/ata/adv/5d37fd30-0fcf-45f3-b443-2a71afb67b52.js" async defer></script>
    <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" type="text/javascript"></script>
    <script type="text/javascript">
      window.googletag = window.googletag || {
        cmd: [],
      };
      googletag.cmd.push(function () {
        googletag
          .defineSlot(
            "YOUR_AD_SLOT_ID",
            [YOUR_AD_WIDTH, YOUR_AD_HEIGHT],
            "YOUR_AD_SLOG_TARGET_DIV_ID"
          )
          .addService(googletag.pubads());
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
      });
    </script>',
    'sanitize_callback' => null,
  ) );

  $wp_customize->add_control( 'samakal_news_details_header_code', array(
    'label' => __( 'Post Details Header Scripts & Metadata', 'samakal' ),
    'description' => __( 'Paste custom script, meta, tracking codes, verification tags, JSON-LD schema, etc.', 'samakal' ),
    'section' => 'samakal_theme_options_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'style' => 'height: 220px; font-family: monospace;',
    ),
  ) );
}
add_action( 'customize_register', 'samakal_customize_register' );

function samakal_output_header_code() {
  $header_code = get_theme_mod( 'samakal_header_code' );

  if ( ! empty( $header_code ) ) {
    echo "\n" . $header_code . "\n";
  }

  if ( is_front_page() ) {
    $home_header_code = get_theme_mod( 'samakal_home_header_code' );

    if ( ! empty( $home_header_code ) ) {
      echo "\n" . $home_header_code . "\n";
    }
  }

  if ( is_category() ) {
    $category_header_code = get_theme_mod( 'samakal_category_header_code' );

    if ( ! empty( $category_header_code ) ) {
      echo "\n" . $category_header_code . "\n";
    }
  }

  if ( is_single() ) {
    $news_details_header_code = get_theme_mod( 'samakal_news_details_header_code' );

    if ( ! empty( $news_details_header_code ) ) {
      echo "\n" . $news_details_header_code . "\n";
    }
  }
}
add_action( 'wp_head', 'samakal_output_header_code', 99 );

function samakal_output_footer_code() {
  if ( is_front_page() ) {
    $home_footer_code = get_theme_mod( 'samakal_home_footer_code' );

    if ( ! empty( $home_footer_code ) ) {
      echo "\n" . $home_footer_code . "\n";
    }
  }
}
add_action( 'wp_footer', 'samakal_output_footer_code', 99 );


add_filter('post_link_category', function ($category, $categories, $post) {
  $primary_category_id = get_post_meta(
    $post->ID,
    'spc_primary_category',
    true
  );
  if ($primary_category_id) {
    $primary_category = get_term(
      $primary_category_id,
      'category'
    );

    if (! $primary_category || is_wp_error($primary_category)) {
      $primary_category = $category;
    }
  } else {
    $primary_category = $category;
  }

  $primary_category->parent = 0;
  $custom_slug = get_field('custom_slug', $primary_category);
  $primary_category->slug = $custom_slug ? $custom_slug : $primary_category->slug;

  return $primary_category;
}, 10, 3);

function register_widget_areas() {
  register_sidebar(array(
    'name'          => __('Home Leaderboard 1 (Desktop)'),
    'id'            => 'home-leaderboard-1-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Home Leaderboard 1 (Mobile)'),
    'id'            => 'home-leaderboard-1-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home After Lead (Mobile)'),
    'id'            => 'home-after-lead-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Right Side 1 (Desktop)'),
    'id'            => 'home-right-side-1-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Right Side 1 (Mobile)'),
    'id'            => 'home-right-side-1-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Right Side 2'),
    'id'            => 'home-right-side-2',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Leaderboard 2 (Desktop)'),
    'id'            => 'home-leaderboard-2-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 1 (Mobile)'),
    'id'            => 'home-sidebar-1-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 2 (Mobile)'),
    'id'            => 'home-sidebar-2-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Leaderboard 3 (Desktop)'),
    'id'            => 'home-leaderboard-3-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 3 (Mobile)'),
    'id'            => 'home-sidebar-3-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Leaderboard 4 (Desktop)'),
    'id'            => 'home-leaderboard-4-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 4 (mobile)'),
    'id'            => 'home-sidebar-4-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 5 (mobile)'),
    'id'            => 'home-sidebar-5-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Leaderboard 5'),
    'id'            => 'home-leaderboard-5',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 6 (Mobile)'),
    'id'            => 'home-sidebar-6-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Leaderboard 6 (Desktop)'),
    'id'            => 'home-leaderboard-6-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 7 (Desktop)'),
    'id'            => 'home-sidebar-7-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 8 (Desktop)'),
    'id'            => 'home-sidebar-8-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 9 (Desktop)'),
    'id'            => 'home-sidebar-9-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 10 (Desktop)'),
    'id'            => 'home-sidebar-10-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
  
  register_sidebar(array(
    'name'          => __('Home Sidebar 11'),
    'id'            => 'home-sidebar-11',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Home Sticky Footer (Desktop)'),
    'id'            => 'home-sticky-footer-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Home Sticky Footer (Mobile)'),
    'id'            => 'home-sticky-footer-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));




  register_sidebar(array(
    'name'          => __('Category Leaderboard (Desktop)'),
    'id'            => 'category-leaderboard-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Leaderboard (Mobile)'),
    'id'            => 'category-leaderboard-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Right Side (Desktop'),
    'id'            => 'category-right-side-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Right Side (Mobile'),
    'id'            => 'category-right-side-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Leaderboard 2 (Desktop)'),
    'id'            => 'category-leaderboard-2-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Leaderboard 2 (Mobile)'),
    'id'            => 'category-leaderboard-2-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Subnews 1 (Left)'),
    'id'            => 'category-subnews-1-left',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Category Subnews 2 (Left)'),
    'id'            => 'category-subnews-2-left',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Right Side 1'),
    'id'            => 'video-right-side-1',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Right Side 2'),
    'id'            => 'video-right-side-2',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Leaderboard'),
    'id'            => 'video-leaderboard',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Division Leaderboard'),
    'id'            => 'division-leaderboard',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Division Right Side'),
    'id'            => 'division-right-side',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Category Right Side 1'),
    'id'            => 'video-category-right-side-1',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Category Right Side 2'),
    'id'            => 'video-category-right-side-2',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('Video Category Leaderboard'),
    'id'            => 'video-category-leaderboard',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Leaderboard (Desktop)'),
    'id'            => 'news-details-leaderboard-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Leaderboard (Mobile)'),
    'id'            => 'news-details-leaderboard-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Before Image (Mobile)'),
    'id'            => 'news-details-before-image-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details After Image (Mobile)'),
    'id'            => 'news-details-after-image-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Inside Body (Desktop)'),
    'id'            => 'news-details-inside-body-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Inside Body (Mobile)'),
    'id'            => 'news-details-inside-body-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Inside Body 2 (Desktop)'),
    'id'            => 'news-details-inside-body-2-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Inside Body 2 (Mobile)'),
    'id'            => 'news-details-inside-body-2-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details After Body (Desktop)'),
    'id'            => 'news-details-after-body-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details After Body (Mobile)'),
    'id'            => 'news-details-after-body-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Right Side 1 (Desktop)'),
    'id'            => 'news-details-right-side-1-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Right Side 2 (Desktop)'),
    'id'            => 'news-details-right-side-2-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Right Side 3 (Desktop)'),
    'id'            => 'news-details-right-side-3-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details After Latest News'),
    'id'            => 'news-details-after-latest-news',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Sticky Footer (Desktop)'),
    'id'            => 'news-details-sticky-footer-desktop',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));

  register_sidebar(array(
    'name'          => __('News Details Sticky Footer (Mobile)'),
    'id'            => 'news-details-sticky-footer-mobile',
    'description'   => __('Add widgets here.'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => '',
  ));
}
add_action('widgets_init', 'register_widget_areas');

add_action('rest_api_init', function () {
  register_rest_field('post', 'thumbnail_urls', array(
    'get_callback' => function($post) {
      if (!has_post_thumbnail($post['id'])) {
        return array();
      }
      $thumbnail_id = get_post_thumbnail_id($post['id']);
      $sizes = get_intermediate_image_sizes();
      $sizes[] = 'full';
      $images = array();
      foreach ($sizes as $size) {
        $image = wp_get_attachment_image_src($thumbnail_id, $size);
        if ($image) {
          $images[$size] = array(
            'url'    => $image[0],
            'width'  => $image[1],
            'height' => $image[2],
          );
        }
      }
      return $images;
    },
    'schema' => null,
  ));
});

add_theme_support('page-attributes');

add_filter('post_type_link', function ($url, $post) {
  if ($post->post_type !== 'video') {
      return $url;
  }

  $pages = get_pages([
    'meta_key'   => '_wp_page_template',
    'meta_value' => 'page-video.php',
    'number'     => 1,
  ]);

  $base_slug = !empty($pages) ? $pages[0]->post_name : 'video';

  $terms = get_the_terms($post->ID, 'video-category');

  $category_slug = 'uncategorized';

  if (!empty($terms) && !is_wp_error($terms)) {
    $category_slug = $terms[0]->slug;
  }

  return home_url("/{$base_slug}/{$category_slug}/{$post->ID}");
}, 10, 2);

add_action('init', function () {
  add_rewrite_rule(
    '^([^/]+)/([^/]+)/([0-9]+)/?$',
    'index.php?post_type=video&p=$matches[3]',
    'top'
  );
});

function theme_customize_register( $wp_customize ) {
  $wp_customize->add_section( 'home_section', array(
    'title'       => __( 'Home' ),
    'priority'    => 30,
    'description' => __( 'Customize Home page settings here.' ),
  ) );

  $categories = get_categories(array(
    'hide_empty' => false,
  ));

  $collections = get_terms(array(
    'taxonomy'   => 'collection',
    'hide_empty' => false,
  ));

  $choices = array();
  
  if (!is_wp_error($collections)) {
    foreach ($collections as $term) {
      $choices['col_' . $term->term_id] = '[Collection] ' . $term->name;
    }
  }

  if (!is_wp_error($categories)) {
    foreach ($categories as $cat) {
      $choices['cat_' . $cat->term_id] = $cat->name;
    }
  }

  for ($i = 1; $i <= 20; $i++) {
    $setting_id = "section_$i";

    $wp_customize->add_setting( $setting_id, array(
      'default'           => '',
      'sanitize_callback' => function($value) {
        if (preg_match('/^(cat|col)_\d+$/', $value)) {
          return $value;
        }
        return '';
      },
    ) );

    $wp_customize->add_control( $setting_id, array(
      'label'   => "Section $i",
      'section' => 'home_section',
      'type'    => 'select',
      'choices' => $choices,
    ) );
  }
}

add_action( 'customize_register', 'theme_customize_register' );

function set_post_views($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);

  if($count==''){
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '1');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}

function get_descendant_category_ids($category_id) {
  $ids = get_term_children($category_id, 'category');

  if (is_wp_error($ids)) {
    return (string) $category_id;
  }

  $ids[] = (int) $category_id;

  return implode(',', array_unique($ids));
}


add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_6a07f76ecece8',
	'title' => 'Additional Headline',
	'fields' => array(
		array(
			'key' => 'field_6a07f76bc065f',
			'label' => 'Shoulder',
			'name' => 'shoulder',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'Shoulder of the news shown above the headline.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a07f7b3c0660',
			'label' => 'Subheadline',
			'name' => 'subheadline',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'Subheadline of the news shown below the headline.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a095651322d1',
	'title' => 'Category Meta',
	'fields' => array(
		array(
			'key' => 'field_6a0abec5f4292',
			'label' => 'Custom Title',
			'name' => 'custom_title',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a10ad16374c6',
			'label' => 'Custom Slug',
			'name' => 'custom_slug',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a095652df1ff',
			'label' => 'Meta Title',
			'name' => 'meta_title',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a095679df200',
			'label' => 'Meta Description',
			'name' => 'meta_description',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a095692df201',
			'label' => 'Keywords',
			'name' => 'keywords',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a0956a1df202',
			'label' => 'Link Preview Image',
			'name' => 'link_preview_image',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'allow_in_bindings' => 0,
			'preview_size' => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			),
		),
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'video-category',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a07fc3b20c54',
	'title' => 'Collection Items',
	'fields' => array(
		array(
			'key' => 'field_6a07fc375fdd2',
			'label' => 'Items',
			'name' => 'items',
			'aria-label' => '',
			'type' => 'relationship',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'post',
			),
			'post_status' => array(
				0 => 'publish',
			),
			'taxonomy' => '',
			'filters' => array(
				0 => 'search',
				1 => 'post_type',
				2 => 'taxonomy',
			),
			'return_format' => 'object',
			'min' => '',
			'max' => '',
			'allow_in_bindings' => 0,
			'elements' => '',
			'bidirectional' => 0,
			'bidirectional_target' => array(
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'collection',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => 'DO NOT TOUCH',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a097926d936a',
	'title' => 'Header Button & Footer Menu',
	'fields' => array(
		array(
			'key' => 'field_6a0979279aed2',
			'label' => 'Open in a new tab',
			'name' => 'open_in_a_new_tab',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'allow_in_bindings' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'location/header_button',
			),
		),
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'location/header_mobile_button',
			),
		),
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'location/footer_menu',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a0ac439e8122',
	'title' => 'Main Menu',
	'fields' => array(
		array(
			'key' => 'field_6a0ac465b18c8',
			'label' => 'Custom CSS Classes',
			'name' => 'custom_css_classes',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a0ac43cb18c7',
			'label' => 'Mega Menu',
			'name' => 'mega_menu',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'allow_in_bindings' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'location/main_menu',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a084697682b9',
	'title' => 'Post, Video & Page Meta',
	'fields' => array(
		array(
			'key' => 'field_6a0846996fd0e',
			'label' => 'Meta Title',
			'name' => 'meta_title',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a0846ac6fd0f',
			'label' => 'Meta Description',
			'name' => 'meta_description',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a0846c66fd10',
			'label' => 'Keywords',
			'name' => 'keywords',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6a0846d56fd11',
			'label' => 'Link Preview Image',
			'name' => 'link_preview_image',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'allow_in_bindings' => 0,
			'preview_size' => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'video',
			),
		),
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'post_tag',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a07f95aa6e14',
	'title' => 'Source',
	'fields' => array(
		array(
			'key' => 'field_6a07f957e83c0',
			'label' => 'Reporter',
			'name' => 'reporter',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
		0 => 'author',
	),
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a104c15c7f3f',
	'title' => 'Video Collection Items',
	'fields' => array(
		array(
			'key' => 'field_6a104c15c9cc6',
			'label' => 'Items',
			'name' => 'items',
			'aria-label' => '',
			'type' => 'relationship',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'video',
			),
			'post_status' => array(
				0 => 'publish',
			),
			'taxonomy' => '',
			'filters' => array(
				0 => 'search',
				1 => 'post_type',
				2 => 'taxonomy',
			),
			'return_format' => 'id',
			'min' => '',
			'max' => 30,
			'allow_in_bindings' => 1,
			'elements' => array(
				0 => 'featured_image',
			),
			'bidirectional' => 0,
			'bidirectional_target' => array(
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'video-collection',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_6a0947be086bf',
	'title' => 'Video Embed',
	'fields' => array(
		array(
			'key' => 'field_6a103064318e3',
			'label' => 'Youtube Video ID',
			'name' => 'youtube_video_id',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 1,
			'placeholder' => '',
			'prepend' => 'https://www.youtube.com/watch?v=',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'video',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => '',
	'allow_ai_access' => false,
	'ai_description' => '',
) );
} );

add_action( 'init', function() {
	register_taxonomy( 'video-category', array(
	0 => 'video',
), array(
	'labels' => array(
		'name' => 'Categories',
		'singular_name' => 'Category',
		'menu_name' => 'Categories',
		'all_items' => 'All Categories',
		'edit_item' => 'Edit Category',
		'view_item' => 'View Category',
		'update_item' => 'Update Category',
		'add_new_item' => 'Add New Category',
		'new_item_name' => 'New Category Name',
		'parent_item' => 'Parent Category',
		'parent_item_colon' => 'Parent Category:',
		'search_items' => 'Search Categories',
		'not_found' => 'No categories found',
		'no_terms' => 'No categories',
		'filter_by_item' => 'Filter by category',
		'items_list_navigation' => 'Categories list navigation',
		'items_list' => 'Categories list',
		'back_to_items' => '← Go to categories',
		'item_link' => 'Category Link',
		'item_link_description' => 'A link to a category',
	),
	'public' => true,
	'hierarchical' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
	'rewrite' => array(
		'slug' => 'video-gallery',
		'hierarchical' => true,
	),
) );

	register_taxonomy( 'collection', array(
	0 => 'post',
), array(
	'labels' => array(
		'name' => 'Collections',
		'singular_name' => 'Collection',
		'menu_name' => 'Collections',
		'all_items' => 'All Collections',
		'edit_item' => 'Edit Collection',
		'view_item' => 'View Collection',
		'update_item' => 'Update Collection',
		'add_new_item' => 'Add New Collection',
		'new_item_name' => 'New Collection Name',
		'search_items' => 'Search Collections',
		'popular_items' => 'Popular Collections',
		'separate_items_with_commas' => 'Separate collections with commas',
		'add_or_remove_items' => 'Add or remove collections',
		'choose_from_most_used' => 'Choose from the most used collections',
		'not_found' => 'No collections found',
		'no_terms' => 'No collections',
		'items_list_navigation' => 'Collections list navigation',
		'items_list' => 'Collections list',
		'back_to_items' => '← Go to collections',
		'item_link' => 'Collection Link',
		'item_link_description' => 'A link to a collection',
	),
	'public' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
	'meta_box_cb' => false,
	'rewrite' => false,
) );

	register_taxonomy( 'video-collection', array(
	0 => 'video',
), array(
	'labels' => array(
		'name' => 'Collections',
		'singular_name' => 'Collection',
		'menu_name' => 'Collections',
		'all_items' => 'All Collections',
		'edit_item' => 'Edit Collection',
		'view_item' => 'View Collection',
		'update_item' => 'Update Collection',
		'add_new_item' => 'Add New Collection',
		'new_item_name' => 'New Collection Name',
		'search_items' => 'Search Collections',
		'popular_items' => 'Popular Collections',
		'separate_items_with_commas' => 'Separate collections with commas',
		'add_or_remove_items' => 'Add or remove collections',
		'choose_from_most_used' => 'Choose from the most used collections',
		'not_found' => 'No collections found',
		'no_terms' => 'No collections',
		'items_list_navigation' => 'Collections list navigation',
		'items_list' => 'Collections list',
		'back_to_items' => '← Go to collections',
		'item_link' => 'Collection Link',
		'item_link_description' => 'A link to a collection',
	),
	'public' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
	'meta_box_cb' => false,
	'rewrite' => false,
	'sort' => true,
) );
} );

add_action( 'init', function() {
	register_post_type( 'video', array(
	'labels' => array(
		'name' => 'Videos',
		'singular_name' => 'Video',
		'menu_name' => 'Videos',
		'all_items' => 'All Videos',
		'edit_item' => 'Edit Video',
		'view_item' => 'View Video',
		'view_items' => 'View Videos',
		'add_new_item' => 'Add New Video',
		'add_new' => 'Add New Video',
		'new_item' => 'New Video',
		'parent_item_colon' => 'Parent Video:',
		'search_items' => 'Search Videos',
		'not_found' => 'No videos found',
		'not_found_in_trash' => 'No videos found in Trash',
		'archives' => 'Video Archives',
		'attributes' => 'Video Attributes',
		'insert_into_item' => 'Insert into video',
		'uploaded_to_this_item' => 'Uploaded to this video',
		'filter_items_list' => 'Filter videos list',
		'filter_by_date' => 'Filter videos by date',
		'items_list_navigation' => 'Videos list navigation',
		'items_list' => 'Videos list',
		'item_published' => 'Video published.',
		'item_published_privately' => 'Video published privately.',
		'item_reverted_to_draft' => 'Video reverted to draft.',
		'item_scheduled' => 'Video scheduled.',
		'item_updated' => 'Video updated.',
		'item_link' => 'Video Link',
		'item_link_description' => 'A link to a video.',
	),
	'public' => true,
	'show_in_rest' => true,
	'rest_base' => 'videos',
	'menu_position' => 4,
	'menu_icon' => 'dashicons-video-alt3',
	'supports' => array(
		0 => 'title',
		1 => 'editor',
	),
	'taxonomies' => array(
		0 => 'video-category',
	),
	'delete_with_user' => false,
) );
} );


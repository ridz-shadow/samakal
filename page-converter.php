<?php
  /* Template Name: Converter */
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $site_url = get_site_url();
  $admin_email = get_option('admin_email');
  $custom_logo_id = get_theme_mod('custom_logo');
  $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
  $homepage_id = get_option('page_on_front');
  $homepage_meta_title = get_field('meta_title', $homepage_id);
  $og_site_name = get_theme_mod( 'samakal_og_site_name', $site_name );
  $page_title = get_the_title();
  $homepage_id = get_option('page_on_front');
  $homepage_keywords = get_field('keywords', $homepage_id);
  $meta_title = get_field('meta_title');
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$page_title} - {$site_name}" : "{$page_title} – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$page_title} - {$site_name}" : "{$page_title} – {$site_name}";
  }
  $keywords = get_field('keywords');
  if(!$keywords && $homepage_keywords) {
    $keywords = $homepage_keywords;
  }
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
?>
<!doctype html>
<html lang="<?php echo $site_language; ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <?php if ( $meta_title ) { ?>
      <title><?php echo $meta_title; ?></title>
    <?php } ?>
    <?php if ( $meta_description ) { ?>
      <meta name="description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <?php if ( $keywords ) { ?>
      <meta name="keywords" content="<?php echo $keywords; ?>">
    <?php } ?>
    <meta name="author" content="<?php echo $site_name; ?>">
    <meta name="Developer" content="<?php echo $site_name; ?> IT Team">
    <meta name="resource-type" content="document">
    <meta name="contact" content="<?php echo $admin_email; ?>">
    <meta name="copyright" content="Copyright (c) <?php echo $current_year; ?>. All Rights &reg; Reserved by <?php echo $site_url; ?>">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="googlebot-news" content="index, follow">
    <meta name="msnbot" content="index, follow">
    <meta property="fb:app_id" content="">
    <meta property="og:site_name" content="<?php echo $og_site_name; ?>">
    <?php if ( $meta_title ) { ?>
      <meta property="og:title" content="<?php echo $meta_title; ?>">
    <?php } ?>
    <?php if ( $meta_description ) { ?>
      <meta property="og:description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <meta property="og:url" content="<?php echo $url; ?>">
    <meta property="og:type" content="article">
    <?php if ( $link_preview_image || $default_link_preview_image ) { ?>
      <meta property="og:image" content="<?php echo esc_url( $link_preview_image ? $link_preview_image : $default_link_preview_image ); ?>">
    <?php } ?>
    <meta property="og:locale" content="<?php echo ( $site_language === 'bn' ) ? 'bn_BD' : 'en_US'; ?>">
    <?php if ( $link_preview_image || $default_link_preview_image ) { ?>
      <link rel="image_src" href="<?php echo esc_url( $link_preview_image ? $link_preview_image : $default_link_preview_image ); ?>">
    <?php } ?>
    <link rel="canonical" href="<?php echo $url; ?>">
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
    <link type="image/x-icon" rel="icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/common/css/SolaimanLipiEMM.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/common/css/Kiron.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/converter/css/samakal.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/49434536cd.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/bijoy2uni.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/uni2bijoy.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/common.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/layout.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/js.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/js1.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/converter/js/count.js"></script>
    <?php wp_head(); ?>
  </head>
  <body>
    <div class="container mb-5">
      <div class="row">
        <div class="col-sm-12" align="center">
          <div class="DLogo">
            <a href="<?php echo $site_url; ?>" class="DLogo" rel="home"><img src="<?php echo $logo_url; ?>" title="<?php echo $homepage_meta_title; ?>" alt="<?php echo $homepage_meta_title; ?>" class="img-fluid img100"></a>
          </div>
          <p><?php echo ( $site_language === 'bn' ) ? 'বাংলা ফন্ট কনভার্টার' : 'Bengali Font Converter'; ?> - <?php echo $site_name; ?></p>
          <p class="pMessage"><?php echo ( $site_language === 'bn' ) ? 'ইউনিকোড থেকে বিজয় কনভার্টার' : 'Unicode to Bijoy Converter'; ?> - <?php echo parse_url($site_url, PHP_URL_HOST); ?></p>
        </div>
      </div>
      <form name="frmConverter" action="/converter" method="post">
        <div class="row">
          <div class="col-sm-12 col-sm-offset-1 DMT18">
            <textarea class="inpUnicode" onKeyPress="return KeyBoardPress(event);" id=EDT onKeyDown="return KeyBoardDown(event);" name="textarea" onBlur="InputLengthCheck();" onKeyUp="InputLengthCheck();" autofocus="autofocus" value="" placeholder="<?php echo ( $site_language === 'bn' ) ? 'ইউনিকোড কি-বোর্ডের লেখা এখানে পেস্ট করুন' : 'Paste the text from the Unicode keyboard here'; ?>"></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-sm-offset-1 DMT18 text-center">
            <div class="DConvertButton">
              <button type="button" class="btnBijoy btn btn-primary" onClick="ConvertToTextArea('CONVERTEDT');" alt="Unicode to Bijoy" name="btnConvertToAscii">
              <span class="fa fa-arrow-down" aria-hidden="true"></span> <?php echo ( $site_language === 'bn' ) ? 'ইউনিকোড থেকে বিজয়' : 'Unicode to Bijoy'; ?> </button>
              <button type="button" class="btnUnicode btn btn-success" onClick="ConvertFromTextArea('CONVERTEDT');" alt="Bijoy to Unicode" name="btnAsciiToConvert">
              <span class="fa fa-arrow-up" aria-hidden="true"></span> <?php echo ( $site_language === 'bn' ) ? 'বিজয় থেকে ইউনিকোড' : 'Bijoy to Unicode'; ?> </button>
              <button type="reset" class="btnReset btn btn-danger" alt="Delete" name="btnClear">
              <span class="fa fa-refresh" aria-hidden="true"></span><?php echo ( $site_language === 'bn' ) ? 'মুছে ফেলুন' : 'Clear'; ?></button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <input readonly type="hidden" name="CharsTyped" size="2"
              style="font-weight:bold;border:0 solid #2D69AE;color:#808080;text-align:left;">
            <input readonly type="hidden" name="WordsTyped" size="3"
              style="font-weight:bold;border:1px solid #2D69AE;color:#808080;text-align:right;">
            <input readonly type="hidden" name="CharsLeft" size="8">
            <input readonly type="hidden" name="WordsLeft" size="8">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 DMT18">
            <textarea class="inpBijoy" id="CONVERTEDT" autofocus value=""
              placeholder="<?php echo ( $site_language === 'bn' ) ? 'বিজয় কি-বোর্ডের লেখা এখানে পেস্ট করুন' : 'Paste the text from the Bijoy keyboard here'; ?>"></textarea>
          </div>
        </div>
      </form>
      <div
        style="background-color: #f4f4f4;padding: 20px;border-radius: 10px;margin-top: 20px;border: 1px solid #e18b20;">
        <h4><?php echo ( $site_language === 'bn' ) ? 'ইউনিকোড ফন্ট কী?' : 'What is Unicode font?'; ?></h4>
        <p><?php echo ( $site_language === 'bn' ) ? 'ইউনিকোড ফন্ট হলো এমন একটি অক্ষরের ধারাবাহিকতা যা কোনো নির্দিষ্ট নেটিভ ফরম্যাটে এনকোড করা থাকে। যদি আপনি অভ্র (Avro) টুলে বাংলা ফন্ট লিখেন, তাহলে সেটি ডিফল্টভাবে ইউনিকোড ফরম্যাটে তৈরি হয়।' : 'Unicode font is a sequence of characters encoded using some native format. If you write Bangla font in the Avro tool, by default it has been created in Unicode format.'; ?>
        </p>
        <h4><?php echo ( $site_language === 'bn' ) ? $site_name . ' বাংলা কনভার্টারের বৈশিষ্ট্যসমূহ' : 'Features of ' . $site_name . ' Bangla Converter'; ?></h4>
        <p><?php echo ( $site_language === 'bn' ) ? $site_name . ' বাংলা কনভার্টার বাংলা ভাষার একটি সুপরিচিত ওয়েব টুল। এর কিছু বিশেষ বৈশিষ্ট্য নিচে দেওয়া হলো:' : $site_name . ' Bangla Converter is a well-known web tool in the Bangla language. It has the following unique features:'; ?>
        </p>
        <ul>
          <li><?php echo ( $site_language === 'bn' ) ? 'যেকোনো ব্রাউজারে ব্যবহারযোগ্য' : 'Compatible in any browser'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'অভ্র ফন্ট থেকে বিজয় ফন্টে রূপান্তর' : 'Avro font to Bijoy font'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'বিজয় ফন্ট থেকে ইউনিকোড ফন্টে রূপান্তর' : 'Bijoy font to Unicode font'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'সম্পূর্ণ বিনামূল্যে ব্যবহারযোগ্য' : 'Absolutely free to use'; ?></li>
        </ul>
        <h4><?php echo ( $site_language === 'bn' ) ? 'এই টুলটি কীভাবে ব্যবহার করবেন' : 'How to use this tool'; ?></h4>
        <p><?php echo ( $site_language === 'bn' ) ? 'নিচের ধাপগুলো অনুসরণ করে আপনি সহজেই আপনার প্রয়োজনীয় ফন্ট রুপান্তর করতে পারবেন।' : 'Following this step, you can easily convert your desire font.'; ?></p>
        <ul>
          <li><?php echo ( $site_language === 'bn' ) ? 'ধাপ-১: আপনার ইউনিকোড বা বিজয় ফন্ট কপি করুন' : 'Step-1: Copy your Unicode or Bijoy font'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'ধাপ-২: একটি ফাঁকা বক্সে সেটি পেস্ট করুন' : 'Step-2: Paste your font in a blank panel'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'ধাপ-৩: রুপান্তর করতে "ইউনিকোড থেকে বিজয়" অথবা "বিজয় থেকে ইউনিকোড" বাটনে ক্লিক করুন।' : 'Step-3: Click on the "Unicode to Bijoy" or "Bijoy to Unicode" button to convert.'; ?></li>
          <li><?php echo ( $site_language === 'bn' ) ? 'ধাপ-৪: শেষ ধাপে আপনি আপনার ফলাফল পেয়ে যাবেন।' : "Step-4: Finally you'll get your result."; ?></li>
        </ul>
      </div>
    </div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/frontend/common/js/samakal.js"></script>
    <?php wp_footer(); ?>
  </body>
</html>
</html>
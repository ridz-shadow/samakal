<?php
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $admin_email = get_option('admin_email');
  $current_year = date('Y');
  $site_url = get_site_url();
  $og_site_name = get_theme_mod( 'samakal_og_site_name', $site_name );
  $twitter_username = get_theme_mod( 'samakal_twitter_username' );
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<meta name="author" content="<?php echo $site_name; ?>">
<meta name="Developer" content="<?php echo $site_name; ?> IT Team">
<meta name="resource-type" content="document">
<meta name="contact" content="<?php echo $admin_email; ?>">
<meta name="copyright" content="Copyright (c) <?php echo $current_year; ?>. All Rights &reg; Reserved by <?php echo $site_url; ?>">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="googlebot-news" content="index, follow">
<meta name="msnbot" content="index, follow">
<?php if ( $og_site_name ) { ?>
  <meta property="og:site_name" content="<?php echo $og_site_name; ?>">
<?php } ?>
<meta property="og:type" content="article">
<meta property="og:locale" content="<?php echo ( $site_language === 'bn' ) ? 'bn_BD' : 'en_US'; ?>">
<meta name="twitter:domain" content="<?php echo $site_url; ?>" />
<meta name="twitter:card" content="summary_large_image">
<?php if ( $og_site_name ) { ?>
  <meta name="twitter:site" content="<?php echo $og_site_name; ?>">
<?php } ?>
<?php if ( $twitter_username ) { ?>
  <meta name="twitter:site" content="@<?php echo $twitter_username; ?>" />
<?php } ?>
<link type="image/x-icon" rel="shortcut icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
<link type="image/x-icon" rel="icon" href="<?php echo esc_url(get_site_icon_url()); ?>">
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo esc_url(get_site_icon_url(57)); ?>">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url(get_site_icon_url(60)); ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url(get_site_icon_url(72)); ?>">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url(get_site_icon_url(76)); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url(get_site_icon_url(114)); ?>">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url(get_site_icon_url(120)); ?>">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url(get_site_icon_url(144)); ?>">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url(get_site_icon_url(152)); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_site_icon_url(180)); ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url(get_site_icon_url(192)); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_site_icon_url(32)); ?>">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url(get_site_icon_url(96)); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(get_site_icon_url(16)); ?>">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/common/css/SolaimanLipi.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/common/css/Kiron.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/frontend/common/css/samakal.css">
<?php wp_head(); ?>

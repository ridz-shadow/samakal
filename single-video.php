<?php
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_url = site_url();
  $url = esc_url( get_permalink() );
  $meta_title = get_field('meta_title');
  $meta_description = get_field('meta_description');
  $keywords = get_field('keywords');
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
  $youtube_video_id = get_field('youtube_video_id');

  $homepage_id = get_option('page_on_front');
  $homepage_keywords = get_field('keywords', $homepage_id);


  $categories = get_terms( array(
    'taxonomy'   => 'video-category',
    'parent'     => 0,
    'hide_empty' => false,
  ) ) ?: array();

  $page = get_posts([
    'post_type'   => 'page',
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'page-video.php',
    'numberposts' => 1
  ]);

  if ($page) {
    $videos = $page[0];
  }

  $video_categories = wp_get_post_terms(get_the_ID(), 'video-category');
  
  if ($video_categories) {
    $video_category = $video_categories[0];

    $video_category_ids = wp_list_pluck($video_categories, 'term_id');

    $related = new WP_Query([
      'post_type'      => 'video',
      'posts_per_page' => 4,
      'post__not_in'   => [get_the_ID()],
      'tax_query' => [
        [
          'taxonomy' => 'video-category',
          'field'    => 'term_id',
          'terms'    => $video_category_ids,
        ]
      ]
    ]);
  }
?>

<!doctype html> 
<html lang="<?php echo $site_language; ?>">
  <head>
    <?php include get_template_directory() . '/meta.php'; ?>
    <title><?php echo $meta_title ? $meta_title : get_the_title(); ?></title>
    <meta http-equiv="refresh" content="">
    <meta name="description" content="<?php echo $meta_description ? $meta_description : get_the_title() . " - " . get_bloginfo('name'); ?>">
    <meta name="keywords" content="<?php echo $keywords ? $keywords : $homepage_keywords; ?>">
    <meta property="og:title" content="<?php echo $meta_title ? $meta_title : get_the_title() . " - " . get_bloginfo('name'); ?>">
    <meta property="og:description" content="<?php echo $meta_description ? $meta_description : get_the_title() . " - " . get_bloginfo('name'); ?>">
    <meta property="og:url" content="<?php echo $url; ?>">
    <meta property="og:image" content="http://img.youtube.com/vi/<?php echo $youtube_video_id; ?>/maxresdefault.jpg">
    <meta name="twitter:title" content="<?php echo $meta_title ? $meta_title : get_the_title() . " - " . get_bloginfo('name'); ?>">
    <meta name="twitter:description" content="<?php echo $meta_description ? $meta_description : get_the_title() . " - " . get_bloginfo('name'); ?>">
    <meta name="twitter:image" content="http://img.youtube.com/vi/<?php echo $youtube_video_id; ?>/maxresdefault.jpg">
    <link rel="image_src" href="https://img.youtube.com/vi/<?php echo $youtube_video_id; ?>/maxresdefault.jpg">
    <link rel="canonical" href="<?php echo $url; ?>">
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <div class="container">
        <div class="category-area">
          <div class="heading-title">
            <?php if($videos) { ?>
              <a href="<?php echo get_permalink($videos->ID); ?>">
                <h1><?php echo $videos->post_title; ?> <i class="fa-solid fa-angles-right"></i></h1>
              </a>
              <?php if ($video_category->name) { ?>
                <span><?php echo $video_category->name; ?></span>
              <?php } ?>
            <?php } else if ($video_category->name) { ?>
              <h1><?php echo $video_category->name; ?></h1>
            <?php } ?>
            <input class="d-none" id="vidcatSlug" value="international">
          </div>
          <?php if ( $categories && ! is_wp_error( $categories ) ) { ?>
            <div class="sub-category-area">
              <ul class="sub-category">
                <?php foreach ( $categories as $cat ) { ?>
                  <li class="sub-list"><a href="<?php echo get_term_link($cat); ?>"><?php echo $cat->name; ?></a></li>
                <?php } ?>
              </ul>
            </div>
          <?php } ?>
        </div>
        <div class="DVideoGalleryTopSec">
          <div class="row">
            <div class="col-lg-9">
              <div class="embed-responsive embed-responsive-16by9 mt-3">
                <iframe width="100%" class="embed-responsive-item"
                  src="https://www.youtube.com/embed/<?php echo $youtube_video_id; ?>"
                  frameborder="0"></iframe>
              </div>
              <div class="DVideoDetailsTitle my-3">
                <h1><?php the_title(); ?></h1>
              </div>
              <div class="DSocialTop">
                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                  <a class="a2a_button_facebook"></a>
                  <a class="a2a_button_x"></a>
                  <a class="a2a_button_whatsapp"></a>
                  <a class="a2a_button_facebook_messenger"></a>
                  <a class="a2a_button_linkedin"></a>
                  <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                </div>
                <script async src="https://static.addtoany.com/menu/page.js" type="text/javascript"></script>
              </div>
            </div>
            <div class="col-lg-3">
            </div>
          </div>
        </div>
        
        <?php if ($related->have_posts()) { ?>
          <div class="DVideoGalleryCatSec">
            <div class="row">
              <div class="col-lg-12">
                <div class="SectionTitle mb-3 mt-4">
                  <a href="">
                    <h3><?php echo ( $site_language === 'bn' ) ? 'আরও দেখুন' : 'See more'; ?> <i class="fa-solid fa-angles-right"></i></h3>
                  </a>
                </div>
              </div>
            </div>
            <div class="row">
              <?php while ($related->have_posts()) : $related->the_post(); ?>
                <div class="col-lg-3 d-flex">
                  <div class="DVideoCatList align-self-stretch">
                    <a href="<?php the_permalink(); ?>">
                      <div class="row">
                        <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                          <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg" class="img-fluid img100" alt="<?php the_title(); ?>" title="৪ <?php the_title(); ?>"></picture>
                          <span class="play-btn-big"><i class="fas fa-play"></i></span>
                        </div>
                        <div class="col-lg-12 col-sm-8 col-7">
                          <div class="Desc">
                            <h3 class="Title"><?php the_title(); ?></h3>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </main>
    <?php get_footer(); ?>
  </body>
</html>
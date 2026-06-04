<?php 
  $category = get_queried_object();
  $site_url = site_url();
  $site_name = get_bloginfo('name');
  $url = esc_url( get_permalink() );
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $homepage_id = get_option('page_on_front');
  $homepage_keywords = get_field('keywords', $homepage_id);
  $meta_title = get_field('meta_title');
  আন্তর্জাতিক ক্যাটাগরীর ভিডিও - সমকাল
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$category->name} ক্যাটাগরীর ভিডিও - {$site_name}" : "Videos from the {$category->name} category – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$category->name} ক্যাটাগরীর ভিডিও - {$site_name}" : "Videos from the {$category->name} category – {$site_name}";
  }
  $keywords = get_field('keywords');
  if(!$keywords && $homepage_keywords) {
    $keywords = $homepage_keywords;
  }
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );

  $categories = get_terms( array(
    'taxonomy'   => 'video-category',
    'parent'     => 0,
    'hide_empty' => false,
  ) ) ?: array();

  $args = [
    'post_type'      => 'video',
    'posts_per_page' => 11,
    'tax_query'      => [
      [
        'taxonomy' => 'video-category',
        'field'    => 'term_id',
        'terms'    => $category->term_id,
      ]
    ],
  ];

  $posts = new WP_Query($args);  

  $page = get_posts([
    'post_type'   => 'page',
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'page-video.php',
    'numberposts' => 1
  ]);

  if ($page) {
    $videos = $page[0];
  }
?>
<!doctype html>
<html lang="<?php echo $site_language; ?>">
  <head>
    <?php include get_template_directory() . '/meta.php'; ?>
    <?php if ( $meta_title ) { ?>
      <title><?php echo $meta_title; ?></title>
    <?php } ?>
    <meta http-equiv="refresh" content="">
    <?php if ( $meta_description ) { ?>
      <meta name="description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <?php if ( $keywords ) { ?>
      <meta name="keywords" content="<?php echo $keywords; ?>">
    <?php } ?>
    <?php if ( $meta_title ) { ?>
      <meta property="og:title" content="<?php echo $meta_title; ?>">
    <?php } ?>
    <?php if ( $meta_description ) { ?>
      <meta property="og:description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <meta property="og:url" content="<?php echo $url; ?>">
    <?php if ( $link_preview_image || $default_link_preview_image ) { ?>
      <meta property="og:image" content="<?php echo esc_url( $link_preview_image ? $link_preview_image : $default_link_preview_image ); ?>">
    <?php } ?>
    <?php if ( $meta_title ) { ?>
      <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <?php } ?>
    <?php if ( $meta_description ) { ?>
      <meta name="twitter:description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <?php if ( $link_preview_image || $default_link_preview_image ) { ?>
      <meta name="twitter:image" content="<?php echo esc_url( $link_preview_image ? $link_preview_image : $default_link_preview_image ); ?>">
      <link rel="image_src" href="<?php echo esc_url( $link_preview_image ? $link_preview_image : $default_link_preview_image ); ?>">
    <?php } ?>
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
              <span><?php echo $category->name; ?></span>
            <?php } else { ?>
              <h1><?php echo $category->name; ?></h1>
            <?php } ?>
            <input class="d-none" id="vidcatSlug" value="<?php echo $category->term_id; ?>">
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
        <?php if ($posts->have_posts() || is_active_sidebar('video-category-right-side-1') || is_active_sidebar('video-category-right-side-2') ) : ?>
          <?php if ($posts->have_posts() || is_active_sidebar('video-category-right-side-1') || is_active_sidebar('video-category-right-side-2') || is_active_sidebar('video-category-leaderboard') ) { ?>
            <div class="DVideoGalleryTopSec">
              <?php if ($posts->have_posts() || is_active_sidebar('video-category-right-side-1') || is_active_sidebar('video-category-right-side-2') ) { ?>
                <div class="row">
                  <?php if( $posts->have_posts() ) { ?>
                    <div class="col-lg-9">
                      <div class="row">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                          <?php if ($posts->current_post === 0) { ?>
                            <div class="col-lg-8 col-12 d-flex">
                              <div class="DVideoGalleryTop align-self-stretch">
                                <a href="<?php the_permalink(); ?>">
                                  <div class="videoIcon">
                                    <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg"
                                      class="img-fluid img100" alt="" title=""></picture>
                                    <span class="play-btn-big"><i class="fas fa-play"></i></span>
                                  </div>
                                  <div class="Desc">
                                    <div class="NewsTitle">
                                      <h3 class="Title"><?php the_title(); ?></h3>
                                      <div class="Brief">
                                        <p><?php the_excerpt(); ?></p>
                                      </div>
                                    </div>
                                  </div>
                                </a>
                              </div>
                            </div>
                          <?php } ?>
                        <?php endwhile; ?>
                        <?php if ( $posts->post_count > 1) { ?>
                          <div class="col-lg-4 col-12 d-flex">
                            <div class="DVideoGTop2ListSec align-self-stretch">
                              <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                                <?php if ($posts->current_post > 0 && $posts->current_post < 3) { ?>
                                  <div class="DVideoGTop2List align-self-stretch">
                                    <a href="<?php the_permalink(); ?>">
                                      <div class="row">
                                        <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                                          <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg"
                                            class="img-fluid img100" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"></picture>
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
                                <?php } ?>
                              <?php endwhile; ?>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if (is_active_sidebar('video-category-right-side-1') || is_active_sidebar('video-category-right-side-2')) { ?>
                    <div class="col-lg-3">
                      <?php if (is_active_sidebar('video-category-right-side-1')) : ?>
                        <div class="DRightSideAdd mt-3">
                          <?php dynamic_sidebar('video-category-right-side-1'); ?>
                        </div>
                      <?php endif; ?>
                      <?php if (is_active_sidebar('video-category-right-side-1')) : ?>
                        <div class="DRightSideAdd mt-3">
                          <?php dynamic_sidebar('video-category-right-side-1'); ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
              <?php if (is_active_sidebar('video-category-leaderboard')) : ?>
                <div class="row">
                  <div class="col-12">
                    <div class="DHomeAdd970X90 d-flex justify-content-center my-4">
                      <?php dynamic_sidebar('video-category-leaderboard'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php } ?>
          <?php if ($posts->post_count > 3) { ?>
            <div class="DVideoGalleryCatSec" id="videocategoryContentList">
              <div class="row" id="data-wrapper">
                <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                  <?php if ($posts->current_post > 2) { ?>
                    <div class="col-lg-3 d-flex">
                      <div class="DVideoCatList align-self-stretch">
                        <a href="<?php the_permalink(); ?>">
                          <div class="row">
                            <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                              <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg"
                                class="img-fluid img100" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"></picture>
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
                  <?php } ?>
                <?php endwhile; ?>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="read-more-btn d-flex justify-content-center">
                    <a type="button" class="load-more-data"><?php echo ( $site_language === 'bn' ) ? 'আরও' : 'Load more'; ?></a>
                  </div>
                  <div class="auto-load text-center" style="display: none;">
                    <svg version="1.1" id="L9" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                      <path fill="#000" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                        <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                      </path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php endif; ?>
      </div>
    </main>
    <?php get_footer(); ?>
    <script type="text/javascript">
      $(document).ready(function () {
        var slug = $("#vidcatSlug").val();
        // var posCatIDs = $("#posCatID").val();

        var ENDPOINT = "/wp-json/wp/v2/videos";

        var page = 1;

        $(".load-more-data").click(function () {
          page++;
          infinteLoadMore(page);
        });

        function infinteLoadMore(page) {
          $.ajax({
            url: ENDPOINT + "?video-category=" + slug + "&page=" + page + "&per_page=8",
            datatype: "html",
            type: "GET",
            beforeSend: function () {
              $(".auto-load").show();
            },
          })
            .done(function (response) {
              $(".auto-load").hide();
              let htmlToAppend = '';
              $.each(response, function (index, post) {
                htmlToAppend += `<div class="col-lg-3 d-flex">
                  <div class="DVideoCatList align-self-stretch">
                    <a href="${post.link}">
                      <div class="row">
                        <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                          <picture><img src="https://img.youtube.com/vi/${post.acf.youtube_video_id}/maxresdefault.jpg"
                            class="img-fluid img100" alt="${post.title.rendered}" title="${post.title.rendered}"></picture>
                          <span class="play-btn-big"><i class="fas fa-play"></i></span>
                        </div>
                        <div class="col-lg-12 col-sm-8 col-7">
                          <div class="Desc">
                            <h3 class="Title">${post.title.rendered}</h3>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>`
              });
              $("#data-wrapper").append(htmlToAppend);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
              let response = jqXHR.responseText;
              try {
                response = JSON.parse(response);
                if (response.code == "rest_post_invalid_page_number") {
                  $(".auto-load").html(
                    "<small class='text-warning'><?php echo ( $site_language === 'bn' ) ? 'দুঃখিত! এই ক্যাটাগরিতে আর কোন তথ্য সংরক্ষিত নেই' : 'Sorry! There is no more information stored in this category'; ?></small>"
                  );
                  $(".read-more-btn").addClass("d-none");
                  return;
                }
                throw new Error("Unhandled API error");
              } catch {
                console.log("Server error occured");
              }
            });
        }
      });
      
    </script>
  </body>
</html>
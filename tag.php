<?php
  $tag = get_queried_object();
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $site_url = site_url();
  $url = esc_url( get_permalink() );
  $meta_title = get_field('meta_title');
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$tag->name} ট্যাগের সকল খবর - {$site_name}" : "All news from the {$tag->name} tag – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$tag->name} ট্যাগের সকল খবর - {$site_name}" : "All news from the {$tag->name} tag – {$site_name}";
  }
  $keywords = get_field('keywords');
  if(!$keywords) {
    $keywords = "{$site_name}, {$tag->name}";
  }
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
  $args = [
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'tag_id'            => $tag->term_id,
  ];
  $posts = new WP_Query($args);

  $latest_args = [
    'post_type'      => 'post',
    'posts_per_page' => 15,
  ];
  $latest_posts = new WP_Query($latest_args);
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
    <script type="application/ld+json">
      {
      	"@context": "https://schema.org/",
      	"@type": "BreadcrumbList",
      	"itemListElement": [
      		{
      			"@type": "ListItem",
      			"position": 1,
      			"name": "Home",
      			"item": "<?php echo $site_url; ?>"
      		},
      		{
      			"@type": "ListItem",
      			"position": 2,
      			"name": "<?php echo $tag->name; ?>",
      			"item": "<?php echo $url; ?>"
      		}
      	]
      }
    </script>
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <div class="container">
        <div class="category-area">
          <div class="CatSubList-area">
            <div class="row">
              <div class="col-lg-3">
                <div class="DTagDesc">
                  <h1><?php echo $tag->name; ?></h1>
                  <input class="d-none" id="TagName" value="<?php echo $tag->name; ?>">
                  <input class="d-none" id="partition" value="<?php echo $tag->term_id; ?>">
                  <p></p>
                  <div class="DSocialTop mt-3">
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
              </div>
              <?php if ($posts->have_posts()) : ?>
                <div class="col-lg-6">
                  <div id="data-wrapper">
                    <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                      <div class="CatListNews">
                        <a href="<?php the_permalink(); ?>">
                          <div class="row d-flex justify-content-end">
                            <div class="col-md-6 col-6">
                              <div class="CatListhead">
                                <h3><?php the_title(); ?></h3>
                              </div>
                              <div class="ListDesc">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                              <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?></span>
                            </div>
                            <div class="col-md-6 col-6">
                              <div class="img-box">
                                <picture>
                                  <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100 rounded">
                                </picture>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endwhile; ?>
                  </div>
                  <div class="read-more-btn d-flex justify-content-center ">
                    <a type="button" class="load-more-data"><?php echo ( $site_language === 'bn' ) ? 'আরও' : 'Load more'; ?></a>
                  </div>
                  <div class="auto-load text-center" style="display: none;">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="60"
                      viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                      <path fill="#000"
                        d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                        <animateTransform attributeName="transform" attributeType="XML" type="rotate"
                          dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                      </path>
                    </svg>
                  </div>
                </div>
              <?php endif; ?>
              <?php if ($latest_posts->have_posts()) : ?>
                <div class="col-lg-3">
                  <div class="DLatestNewsSec">
                    <div class="DTitleStyle">
                      <h3><i class="fa-solid fa-circle-half-stroke"></i><?php echo ( $site_language === 'bn' ) ? 'সর্বশেষ' : 'Latest'; ?></h3>
                    </div>
                    <div class="DLatestNewsList">
                      <ul>
                        <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                          <li>
                            <a href="<?php the_permalink(); ?>">
                              <div class="d-flex  align-items-center">
                                <div class="d-flex h-100 align-items-center"><span class="Counter"><?php echo theme_translate($latest_posts->current_post + 1); ?>.</span></div>
                                <p class="Title"><?php the_title(); ?></p>
                              </div>
                            </a>
                          </li>
                        <?php endwhile; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>e
        </div>
      </div>
    </main>
    <?php get_footer(); ?>
    <script type="text/javascript">
      $(document).ready(function () {
        var TagName = $("#TagName").val();
        var partition = $("#partition").val();
        var ENDPOINT = "/wp-json/wp/v2/posts";

        var page = 1;

        $(".load-more-data").click(function () {
          page++;
          infinteLoadMore(page);
        });

        function infinteLoadMore(page) {
          $.ajax({
            url: ENDPOINT + "?tags=" + partition + "&page=" + page,
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
                htmlToAppend += `<div class="CatListNews">
                  <a href="${post.link}">
                    <div class="row d-flex justify-content-end">
                      <div class="col-md-6 col-6">
                        <div class="CatListhead">
                          <h3>${post.title.rendered}</h3>
                        </div>
                        <div class="ListDesc">
                          <p>${post.excerpt.rendered.replace(/(<([^>]+)>)/gi, "")}</p>
                        </div>
                        <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> ${new Date(post.date).toLocaleDateString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { day: 'numeric', month: 'long', year: 'numeric' })} | ${new Date(post.date).toLocaleTimeString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { hour: '2-digit', minute: '2-digit', hour12: false })}</span>
                      </div>
                      <div class="col-md-6 col-6">
                        <div class="img-box">
                          <picture>
                            <img src="${post.thumbnail_urls['thumbnail-1'].url}" alt="${post.title.rendered}" title="${post.title.rendered}" class="img-fluid img100 rounded">
                          </picture>
                        </div>
                      </div>
                    </div>
                  </a>
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
                    "<p class='text-warning my-4'><?php echo ( $site_language === 'bn' ) ? 'দুঃখিত! এই ট্যাগ সম্পর্কিত আর কোন তথ্য সংরক্ষিত নেই' : 'Sorry! No more information related to this tag has been stored.'; ?></p>"
                  );
                  $(".read-more-btn").addClass("d-none");
                  return;
                }
                throw new Error("Unhandled API error");
              } catch {
                console.log("Server error occured");
                $(".auto-load").html(
                  "<p class='text-warning my-4'><?php echo ( $site_language === 'bn' ) ? 'দুঃখিত! এই ট্যাগ সম্পর্কিত আর কোন তথ্য সংরক্ষিত নেই' : 'Sorry! No more information related to this tag has been stored.'; ?></p>"
                );
                $(".read-more-btn").addClass("d-none");
                return;
              }
            });
        }
      });
    </script>
  </body>
</html>
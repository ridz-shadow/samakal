<?php 
  $category = get_queried_object();
  if ($category->parent) {
    $parent = get_category($category->parent);
    if ($parent && $parent->slug === 'whole-country') {
      include locate_template('division.php');
      exit;
    }
    if ($parent && $parent->parent) {
      $grandparent = get_category($parent->parent);
      if ($grandparent && $grandparent->slug === 'whole-country') {
        include locate_template('district.php');
        exit;
      }
    }
  }
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $site_url = site_url();
  $url = esc_url( get_permalink() );
  $meta_title = get_field('meta_title');
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$category->name} বিভাগের সকল খবর - {$site_name}" : "All news from the {$category->name} sector – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$category->name} বিভাগের সকল খবর - {$site_name}" : "All news from the {$category->name} sector – {$site_name}";
  }
  $keywords = get_field('keywords');
  if(!$keywords) {
    $keywords = "{$site_name}, {$category->name}";
  }
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
  $thumbnail_placeholder_image = get_theme_mod( 'samakal_thumbnail_placeholder_image' );
  $thumbnail_medium_placeholder_image = get_theme_mod( 'samakal_thumbnail_medium_placeholder_image' );
  $category_chain = [];
  if ( $category && ! is_wp_error($category) ) {
    $current = $category;
    while ( $current && $current->parent != 0 ) {
      $parent = get_term($current->parent, 'category');
      if ( $parent && ! is_wp_error($parent) ) {
        $category_chain[] = $parent;
        $current = $parent;
      } else {
        break;
      }
    }
    $category_chain = array_reverse($category_chain);
    $category_chain[] = $category;
  }

  $children = get_categories([
    'taxonomy'   => 'category',
    'hide_empty' => false,
    'parent'     => $category->term_id,
  ]);

  if (empty($children) && count($category_chain) > 1) {
    $children = get_categories([
      'taxonomy'   => 'category',
      'hide_empty' => false,
      'parent'     => $category_chain[count($category_chain) - 2]->term_id,
    ]);
  } else {
    foreach ($children as $key => $division) {
      $districts = get_categories([
        'taxonomy'   => 'category',
        'hide_empty' => false,
        'parent'     => $division->term_id,
      ]);
      $children[$key]->districts = $districts;
    }
  }

  $args = [
    'post_type'      => 'post',
    'posts_per_page' => 15,
    'cat'            => $category->term_id,
  ];

  $posts = new WP_Query($args);
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
    <style>
      body {
        overflow-y: hidden
      }
    </style>
    <?php if ( ! empty($category_chain) ) : ?>
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
            <?php foreach ( $category_chain as $index => $cat ) : ?>
            {
              "@type": "ListItem",
              "position": <?php echo (int) ($index + 2); ?>,
              "name": "<?php echo esc_html($cat->name); ?>",
              "item": "<?php echo get_category_link($cat->term_id); ?>"
            }<?php if ( ! ($index === count($category_chain) - 1) ) : ?>,<?php endif; ?>
            <?php endforeach; ?>
          ]
        }
      </script>
    <?php endif; ?>
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <?php if (is_active_sidebar('category-leaderboard-desktop') || is_active_sidebar('category-leaderboard-mobile')) { ?>
        <div class="container">
          <?php if (is_active_sidebar('category-leaderboard-desktop')) : ?>
            <div class="row MobileHide">
              <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                  <?php dynamic_sidebar('category-leaderboard-desktop'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('category-leaderboard-mobile')) : ?>
            <div class="row MobileShow">
              <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                  <?php dynamic_sidebar('category-leaderboard-mobile'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php } ?>
      <div class="container">
        <div class="category-area">
          <?php if ( ! empty($children && $category->slug === 'whole-country' ) ) : ?>
            <div class="sub-category-area mb-4">
              <div class="division-cat">
                <nav>
                  <ul>
                    <?php foreach ( $children as $division ) : ?>
                      <li class="dropdown">
                        <a href="<?php echo get_category_link($division->term_id); ?>" class="btn dropdown-toggle"
                          type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                          aria-expanded="false"><?php echo esc_html($division->name); ?></a>
                        <?php if ( ! empty($division->districts) ) : ?>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <?php foreach ( $division->districts as $district ) : ?>
                              <li><a class="dropdown-item"
                              href="<?php echo get_category_link($district->term_id); ?>"><?php echo esc_html($district->name); ?></a></li>
                            <?php endforeach; ?>
                          </ul>
                        <?php endif; ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </nav>
              </div>
            </div>
          <?php endif; ?>
          <div class="heading-title">
            <?php if ( ! empty($category_chain) ) : ?>
              <?php foreach ( $category_chain as $index => $cat ) : ?>
                <?php $custom_title = get_field('custom_title', $cat) ? get_field('custom_title', $cat) : $cat -> name; ?>
                <?php if ( $index === 0 ) { ?>
                  <a href="<?php echo get_category_link($cat->term_id); ?>">
                    <h1><?php echo esc_html(count($category_chain) === 1 ? $custom_title : $cat->name); ?><?php if (count($category_chain) > 1) { ?> <i class="fa-solid fa-angles-right"></i><?php } ?></h1>
                  </a>
                <?php } else if ( ($index + 1) !== count($category_chain) ) { ?>
                  <a href="<?php echo get_category_link($cat->term_id); ?>">
                    <h2><?php echo esc_html($cat->name); ?> <i class="fa-solid fa-angles-right"></i></H2>
                  </a>
                <?php } else { ?>
                  <span><?php echo esc_html($custom_title); ?></span>
                <?php } ?>
              <?php endforeach; ?>
            <?php endif; ?>
            <input class="d-none" id="catSlug" value="<?php echo $category->slug; ?>">
            <input class="d-none" id="posCatID" value="<?php echo get_descendant_category_ids($category->term_id); ?>">
          </div>
          <?php if ( ! empty($children) ) : ?>
            <div class="sub-category-area">
              <ul class="sub-category">
                <?php foreach ( $children as $cat ) : ?>
                  <li class="sub-list">
                    <a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if ($posts->have_posts()) : ?>
            <div class="category-lead">
              <div class="row">
                <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                  <?php if ($posts->current_post === 0) { ?>
                    <div class="col-md-9">
                      <div class="DCatLead">
                        <a
                          href="<?php the_permalink(); ?>">
                          <div class="row">
                            <div class="col-md-7">
                              <div
                                class="img-box ">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>"
                                    src="<?php echo $thumbnail_placeholder_image; ?>"
                                    alt="<?php the_title(); ?>"
                                    title="<?php the_title(); ?>"
                                    class="img-fluid img100 rounded">
                                </picture>
                              </div>
                            </div>
                            <div class="col-md-5 ">
                              <div class="DCatLeadTitle">
                                <h1>
                                  <?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?>
                                </h1>
                                <p class="CatDesc"><?php the_excerpt(); ?></p>
                              </div>
                              <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?></span>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php } ?>
                <?php endwhile; ?>
                <?php if ( is_active_sidebar('category-right-side-desktop') || is_active_sidebar('category-right-side-mobile') ) { ?>
                  <div class="col-md-3">
                    <?php if (is_active_sidebar('category-right-side-desktop')) : ?>
                      <div class="DRightSideAdd MobileHide">
                        <?php dynamic_sidebar('category-right-side-desktop'); ?>
                      </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('category-right-side-mobile')) : ?>
                      <div class="DRightSideAdd MobileShow text-center">
                        <?php dynamic_sidebar('category-right-side-mobile'); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php } ?>
              </div>
            </div>
            <?php if ($posts->post_count > 1) { ?>
              <!-- category cards -->
              <div class="category-card-area">
                <div class="row">
                  <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                    <?php if ($posts->current_post > 0 && $posts->current_post < 5) { ?>
                      <div class="col-md-3 d-flex">
                        <div class="Catcards align-items-stretch">
                          <a
                            href="<?php the_permalink(); ?>">
                            <div class="img-box ">
                              <picture>
                                <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>"
                                  src="<?php echo $thumbnail_medium_placeholder_image; ?>"
                                  alt="<?php the_title(); ?>"
                                  title="<?php the_title(); ?>" class="img-fluid img100">
                              </picture>
                            </div>
                            <div class="CatCardTitle">
                              <h3>
                                <?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?>
                              </h3>
                            </div>
                            <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?></span>
                          </a>
                        </div>
                      </div>
                    <?php } ?>
                  <?php endwhile; ?>
                </div>
              </div>
              <!-- category cards end -->
            <?php } ?>
            <?php if (is_active_sidebar('category-leaderboard-2-desktop') || is_active_sidebar('category-leaderboard-2-mobile')) : ?>
              <!-- ad area desktop-->
              <div class="ad-area d-flex justify-content-center">
                <?php if (is_active_sidebar('category-leaderboard-2-desktop')) : ?>
                  <div class="row MobileHide">
                    <div class="col-md-12">
                      <?php dynamic_sidebar('category-leaderboard-2-desktop'); ?>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('category-leaderboard-2-mobile')) : ?>
                  <div class="row MobileShow">
                    <div class="col-md-12">
                      <?php dynamic_sidebar('category-leaderboard-2-mobile'); ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              <!-- ad area end -->
            <?php endif; ?>
            <?php if (($posts->post_count > 5) || is_active_sidebar('category-subnews-1-left') || is_active_sidebar('category-subnews-2-left') || is_active_sidebar('category-subnews-1-right') || is_active_sidebar('category-subnews-2-right')) { ?>
              <!-- category subnews   -->
              <div class="CatSubList-area">
                <div class="row">
                  <?php if (is_active_sidebar('category-subnews-1-left') || is_active_sidebar('category-subnews-2-left')) : ?>
                    <div class="col-lg-3">
                      <?php if (is_active_sidebar('category-subnews-1-left')) : ?>
                        <div class="DRightSideAdd mt-3 mb-4">
                          <?php dynamic_sidebar('category-subnews-1-left'); ?>
                        </div>
                      <?php endif; ?>
                      <?php if (is_active_sidebar('category-subnews-2-left')) : ?>
                      <div class="DRightSideAdd">
                        <?php dynamic_sidebar('category-subnews-2-left'); ?>
                      </div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                  <?php if ($posts->post_count > 5) { ?>
                    <div class="col-lg-6" id="categoryContentList">
                      <div id="data-wrapper">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                          <?php if ($posts->current_post > 4) { ?>
                            <div class="CatListNews">
                              <a href="<?php the_permalink(); ?>">
                                <div class="row d-flex justify-content-end">
                                  <div class="col-md-6 col-6">
                                    <div class="CatListhead">
                                      <h3><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                    </div>
                                    <div class="ListDesc">
                                      <p><?php the_excerpt(); ?></p>
                                    </div>
                                    <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?></span>
                                  </div>
                                  <div class="col-md-6 col-6">
                                    <div class="img-box ">
                                      <picture>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100 rounded">
                                      </picture>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          <?php } ?>
                        <?php endwhile; ?>
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
                      <div class="read-more-btn d-flex justify-content-center ">
                        <a type="button" class="load-more-data"><?php echo ( $site_language === 'bn' ) ? 'আরও' : 'Load more'; ?></a>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if (is_active_sidebar('category-subnews-1-right') || is_active_sidebar('category-subnews-2-right')) : ?>
                    <div class="col-lg-3">
                      <?php if (is_active_sidebar('category-subnews-1-right')) : ?>
                        <div class="DRightSideAdd mt-3 mb-4">
                          <?php dynamic_sidebar('category-subnews-1-right'); ?>
                        </div>
                      <?php endif; ?>
                      <?php if (is_active_sidebar('category-subnews-2-right')) : ?>
                      <div class="DRightSideAdd">
                        <?php dynamic_sidebar('category-subnews-2-right'); ?>
                      </div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- category subnews  end -->
            <?php } ?>
          <?php endif; ?>
        </div>
      </div>
    </main>
    <?php get_footer(); ?>
    <script type="text/javascript">
      $(document).ready(function () {
        var slug = $("#catSlug").val();
        var posCatIDs = $("#posCatID").val();

        var ENDPOINT = "/wp-json/wp/v2/posts";
        var redirectUrl = "/archive";
        var page = 1;

        $(".load-more-data").click(function () {
          page++;
          infinteLoadMore(page);
        });

        let isLoading = false;
        let noError = true;

        $(window).scroll(function () {
          if (!isLoading && noError) {
            if (
              $(window).scrollTop() + $(window).height() >=
              $(document).height() - 100
            ) {
              page++;
              infinteLoadMore(page); // for ajax request
              console.log(page);
            }
          }
        });

        function infinteLoadMore(page) {
          isLoading = true;
          $.ajax({
            url: ENDPOINT + "?categories=" + posCatIDs + "&page=" + page,
            datatype: "html",
            type: "GET",
            beforeSend: function () {
              $(".auto-load").show();
            },
          })
            .done(function (response) {
              isLoading = false;
              $(".auto-load").hide();
              let htmlToAppend = '';
              $.each(response, function (index, post) {
                htmlToAppend += `<div class="CatListNews">
                  <a href="${post.link}">
                    <div class="row d-flex justify-content-end">
                      <div class="col-md-6 col-6">
                        <div class="CatListhead">
                          <h3>${post.shoulder ? `<span class="subHeading">${post.shoulder} / </span>` : ''}${post.title.rendered}</h3>
                        </div>
                        <div class="ListDesc">
                          <p>${post.excerpt.rendered.replace(/(<([^>]+)>)/gi, "")}</p>
                        </div>
                        <span class="publishTime"><?php echo ( $site_language === 'bn' ) ? 'আপডেটঃ' : 'Update:'; ?> ${new Date(post.date).toLocaleDateString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { day: 'numeric', month: 'long', year: 'numeric' })} | ${new Date(post.date).toLocaleTimeString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { hour: '2-digit', minute: '2-digit', hour12: false })}</span>
                      </div>
                      <div class="col-md-6 col-6">
                        <div class="img-box ">
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
              isLoading = false;
              let response = jqXHR.responseText;
              try {
                response = JSON.parse(response);
                if (response.code == "rest_post_invalid_page_number") {
                  noError = false;
                  $(".auto-load").html(
                    "<a href=" +
                      redirectUrl +
                      "><small class='btn btn-sm d-inline-block bg-warning mb-4 mt-4'><?php echo ( $site_language === 'bn' ) ? 'এই ক্যাটাগরিতে আরও খবর পেতে আর্কাইভ দেখুন' : 'To get more news in this category, view the archive'; ?></small></a>"
                  );
                  $(".read-more-btn").addClass("d-none");
                  return;
                }
                throw new Error("Unhandled API error");
              } catch {
                noError = false;
                console.log("Server error occured");
              }
            });
        }
      });
    </script>
    <script type="text/javascript">
      $(window).load(function() {
          $("main img").each(function(index) {
              $(this).attr('src', $(this).attr('data-src'))
          });
      });
    </script>
  </body>
</html>
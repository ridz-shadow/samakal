<?php 
  $category = get_queried_object();
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $url = esc_url( get_permalink() );
  $meta_title = get_field('meta_title');
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$category->name} জেলার সকল খবর - {$site_name}" : "All news from the {$category->name} district – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$category->name} জেলার সকল খবর - {$site_name}" : "All news from the {$category->name} district – {$site_name}";
  }
  $keywords = get_field('keywords');
  if(!$keywords) {
    $keywords = "{$site_name}, {$category->name}";
  }
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );

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
    'parent'     => $category_chain[count($category_chain) - 3]->term_id,
  ]);

  if ( ! empty($children) ) {
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
    'posts_per_page' => 10,
    'cat'            => $category->term_id,
  ];

  $posts = new WP_Query($args);

  $latest_args = [
    'post_type'      => 'post',
    'posts_per_page' => 15,
  ];
  $latest_posts = new WP_Query($latest_args);
?>
<!doctype html>
<html lang="bn">
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
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <div class="container">
        <div class="category-area mb-5">
          <div class="row">
            <div class="col-lg-12">
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
                  <input class="d-none" id="district" value="<?php echo get_descendant_category_ids($category->term_id); ?>">
                <?php endif; ?>
              </div>
              <?php if ( ! empty($children ) ) : ?>
                <div class="sub-category-area">
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
            </div>
          </div>
          <div class="row">
            <?php if ($posts->have_posts()) : ?>
              <div class="col-lg-9">
                <div class="DivisionNewsWrapp">
                  <div >
                    <div class="row" id="data-wrapper">
                      <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                        <div class="col-md-6 col-sm-12">
                          <div class="SDivision-panel">
                            <div class="DistrictListNews">
                              <a href="<?php the_permalink(); ?>">
                                <div class="row gx-3">
                                  <div class="col-lg-5 col-sm-4 col-5">
                                    <div class="SDImgWrapp">
                                      <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100 rounded">
                                    </div>
                                  </div>
                                  <div class="col-lg-7 col-sm-8 col-7  d-flex align-content-between flex-wrap">
                                    <div class="Desc">
                                      <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                    </div>
                                    <span class="DateTime"><?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?></span>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        </div>
                      <?php endwhile; ?>
                    </div>
                    <div class="read-more-btn d-flex justify-content-center ">
                      <a type="button" class="load-more-data"><?php echo ( $site_language === 'bn' ) ? 'আরও' : 'Load more'; ?></a>
                    </div>
                    <div class="auto-load text-center" style="display: none;">
                      <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                          <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                      </svg>
                    </div>
                  </div>
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
        </div>
      </div>
    </main>
    <?php get_footer(); ?>
    <script type="text/javascript">
      $(document).ready(function () {
        var district = $("#district").val();
        var ENDPOINT = "/wp-json/wp/v2/posts";
        var page = 1;
        $(".load-more-data").click(function () {
          page++;
          infinteLoadMore(page);
        });

        function infinteLoadMore(page) {
          $.ajax({
            url: ENDPOINT + "?categories=" + district + "&page=" + page,
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
                htmlToAppend += `<div class="col-md-6 col-sm-12">
                  <div class="SDivision-panel">
                    <div class="DistrictListNews">
                      <a href="${post.link}">
                        <div class="row gx-3">
                          <div class="col-lg-5 col-sm-4 col-5">
                            <div class="SDImgWrapp">
                              <img src="${post.thumbnail_urls['thumbnail-1'].url}" alt="${post.title.rendered}" title="${post.title.rendered}" class="img-fluid img100 rounded">
                            </div>
                          </div>
                          <div class="col-lg-7 col-sm-8 col-7  d-flex align-content-between flex-wrap">
                            <div class="Desc">
                              <h3 class="Title">${post.shoulder ? `<span class="subHeading">${post.shoulder} / </span>` : ''}${post.title.rendered}</h3>
                            </div>
                            <span class="DateTime">${new Date(post.date).toLocaleDateString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { day: 'numeric', month: 'long', year: 'numeric' })} | ${new Date(post.date).toLocaleTimeString('<?php echo ( $site_language === 'bn' ) ? 'bn-BD' : 'en-US:'; ?>', { hour: '2-digit', minute: '2-digit', hour12: false })}</span>
                          </div>
                        </div>
                      </a>
                    </div>
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
                    "<small class='text-warning'><?php echo ( $site_language === 'bn' ) ? 'দুঃখিত! এই জেলা সম্পর্কিত আর কোন তথ্য সংরক্ষিত নেই' : 'Sorry! No more information related to this district is stored.'; ?></small>"
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
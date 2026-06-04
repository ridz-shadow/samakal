<?php
  set_post_views(get_the_ID());
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_url = site_url();
  $url = esc_url( get_permalink() );
  $twitter_username = get_theme_mod( 'samakal_twitter_username' );
  $meta_title = get_field('meta_title');
  $meta_description = get_field('meta_description');
  $keywords = get_field('keywords');
  $link_preview_image = get_field('link_preview_image');
  $featured_image = get_the_post_thumbnail_url(get_the_ID(), "thumbnail-2");
  $caption = wp_get_attachment_caption(get_post_thumbnail_id());
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
  $featured_image_with_backup = $link_preview_image ? $link_preview_image : ($featured_image ? $featured_image : $default_link_preview_image);

  $categories = wp_get_post_categories(get_the_ID());
  if ($categories) {
    $related = new WP_Query(array(
      'category__in'   => $categories,
      'post__not_in'   => array(get_the_ID()),
      'posts_per_page' => 4,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ));
  }

  $post_tags = get_the_tags();

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
      $primary_category = get_term($categories[0], 'category');
    }
  } else {
    $primary_category = get_term($categories[0], 'category');
  }

  $category_chain = [];
  if ( $primary_category && ! is_wp_error($primary_category) ) {
    $current = $primary_category;
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
    $category_chain[] = $primary_category;
  }

  $custom_logo_id = get_theme_mod('custom_logo');
  $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

  $shoulder = get_field('shoulder');
  $subheadline = get_field('subheadline');

  $thumbnail_loading_image = get_theme_mod( 'samakal_thumbnail_loading_image' );
  $thumbnail_small_loading_image = get_theme_mod( 'samakal_thumbnail_small_loading_image' );

  $latest_args = [
    'post_type'      => 'post',
    'posts_per_page' => 8,
  ];
  $latest_posts = new WP_Query($latest_args);

  $page = get_posts([
    'post_type'   => 'page',
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'page-latest.php',
    'numberposts' => 1
  ]);

  if ($page) {
    $latest = $page[0];
  }
?>
<!doctype html>
<html lang="<?php echo $site_language; ?>">
  <head>
    <?php include get_template_directory() . '/meta.php'; ?>
    <title><?php echo $meta_title ? $meta_title : get_the_title(); ?></title>
    <meta http-equiv="refresh" content="">
    <meta name="description" content="<?php echo $meta_description ? $meta_description : get_the_excerpt(); ?>">
    <?php if ($keywords) { ?>
      <meta name="keywords" content="<?php echo $keywords; ?>">
    <?php } ?>
    <meta property="og:title" content="<?php echo $meta_title ? $meta_title : get_the_title(); ?>">
    <meta property="og:description" content="<?php echo $meta_description ? $meta_description : get_the_excerpt(); ?>">
    <meta property="og:url" content="<?php echo $url; ?>">
    <meta property="og:image" content="<?php echo $featured_image_with_backup; ?>">
    <meta name="twitter:title" content="<?php echo $meta_title ? $meta_title : get_the_title(); ?>">
    <meta name="twitter:description" content="<?php echo $meta_description ? $meta_description : get_the_excerpt(); ?>">
    <meta name="twitter:url" content="<?php echo $url; ?>">
    <meta name="twitter:image" content="<?php echo $featured_image_with_backup; ?>">
    <meta name="twitter:creator" content="@<?php echo $twitter_username; ?>">
    <link rel="image_src" href="<?php echo $featured_image_with_backup; ?>">
    <link rel="canonical" href="<?php echo $url; ?>">
    <style>
      div#contentDetails img {
      max-width: 100%;
      }
      div#contentDetails p>a {
      color: blue;
      }
      .dCaption p {
      margin-top: 5px;
      font-size: 14px !important;
      font-style: italic;
      }
      .DModalAddSec {
      margin-top: 5px;
      }
      .DModalAddSec img {
      border: 1px solid #555;
      }
      .CrossBtn {
      position: absolute;
      top: 0;
      right: 0;
      font-size: 30px;
      font-weight: bold;
      text-decoration: none;
      color: #fff !important;
      background-color: #ff8c00;
      width: 30px;
      height: 30px;
      opacity: 1;
      border-radius: 0;
      text-align: center;
      line-height: 30px;
      cursor: pointer;
      }
      .dateAndTime p {
      font-size: 12px;
      }
      .DNewsImg {
      position: relative;
      }
      .detol {
      bottom: 25px !important;
      }
      /*sticky footer ads*/
      .T4Tutorials {
      position: fixed;
      bottom: 0px;
      height: auto;
      left: 0;
      right: 0;
      background: #f0f0f082;
      transition: opacity 500ms;
      visibility: visible;
      opacity: 1;
      z-index: 9999;
      }
      .T4Tutorials:target {
      visibility: hidden;
      opacity: 0;
      display: none
      }
      .T4Tutorials_UP {
      margin: 0 auto;
      padding: 0px;
      background: #fff;
      border-radius: 5px;
      width: 72%;
      position: relative;
      }
      .T4Tutorials_UP h2 {
      margin-top: 0;
      color: green;
      font-family: Tahoma, Arial, sans-serif;
      }
      .T4Tutorials_UP .Exit {
      position: absolute;
      top: -20px;
      right: -15px;
      font-size: 30px;
      font-weight: bold;
      text-decoration: none;
      color: #fff;
      background-color: #e80000;
      width: 30px;
      height: 30px;
      opacity: 0.6;
      border-radius: 50%;
      text-align: center;
      line-height: 30px;
      cursor: pointer;
      }
      .T4Tutorials_UP .Exit:hover {
      color: #fff;
      background-color: #e80000;
      opacity: 1;
      }
      .T4Tutorials_UP .Main_Content {
      max-height: 30%;
      overflow: auto;
      }
      @media  screen and (max-width:991px) {
      .DModalAddSec {
      margin-top: 100px;
      }
      .CrossBtn {
      font-size: 30px;
      width: 30px;
      height: 30px;
      line-height: 30px;
      top: 0;
      right: 0;
      border-radius: 0;
      }
      .dNewsDesc img {
      width: 100% !important;
      }
      .T4Tutorials {
      height: auto;
      bottom: 0px;
      }
      .T4Tutorials_UP {
      width: 75%;
      margin: 0 auto;
      }
      }
      .DModalAddSec .modal-content {
      width: max-content;
      }
      @media (min-width: 992px) {
      .DModalAddSec .modal-lg,
      .modal-xl {
      max-width: max-content;
      }
      }
      @media (min-width: 320px) {
      .DModalAddSec .modal-dialog {
      max-width: max-content;
      margin: 5rem auto;
      }
      }
      /* fullscreen public html ad overlay */
      .ad-fullscreen {
      position: fixed;
      inset: 0;
      z-index: 10000;
      }
      .ad-fullscreen__backdrop {
      position: absolute;
      inset: 0;
      background: transparent;
      }
      .ad-fullscreen__frame {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      border: 0;
      background: transparent;
      }
    </style>
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
            },
          <?php endforeach; ?>
          {
      			"@type": "ListItem",
      			"position": <?php echo (count($category_chain) + 2); ?>,
      			"name": "<?php the_title(); ?>",
      			"item": "<?php echo $url; ?>"
      		}
      	]
      }
    </script>
    <script type="application/ld+json">
      {
      	"@context": "https://schema.org",
      	"@type": "NewsArticle",
      	"url" : "<?php echo $url; ?>",
      	"articleBody" : "<?php the_excerpt(); ?>",
      	"articleSection" : "<?php echo $primary_category->name; ?>",
      	"keywords" : "<?php echo $meta_keywords; ?>",
      	"mainEntityOfPage":{
      		"@type":"WebPage",
      		"name" : "<?php the_title(); ?>",
      		"@id":"<?php echo $url; ?>"
      	},
      	"headline": "<?php the_title(); ?>",
      	"image":{
      		"@type": "ImageObject",
      		"url": "<?php echo $featured_image; ?>",
      		"height": 800,
      		"width": 450
      	},
      	"datePublished": "<?php echo get_the_date('Y-m-d H:i:s'); ?>",
      	"dateModified": "<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>",
      	"author":{
      		"@type": "Person",
      		"name": "<?php the_title(); ?>"
      	},
      	"publisher":{
      		"@type": "Organization",
      		"name": "<?php echo get_bloginfo('name'); ?>",
      		"logo":{
      			"@type": "ImageObject",
      			"url": "<?php echo  $logo[0]; ?>",
      			"width": <?php echo  $logo[1]; ?>,
      			"height": <?php echo  $logo[2]; ?>
      		}
      	},
      	"description": "<?php the_excerpt(); ?>"
      }
    </script>
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <?php if (is_active_sidebar('news-details-leaderboard-desktop') || is_active_sidebar('news-details-leaderboard-mobile')) : ?>
        <div class="container">
          <?php if (is_active_sidebar('news-details-leaderboard-desktop')) : ?>
            <div class="row MobileHide">
              <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                  <?php dynamic_sidebar('news-details-leaderboard-desktop'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('news-details-leaderboard-mobile')) : ?>
            <div class="row MobileShow">
              <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                  <?php dynamic_sidebar('news-details-leaderboard-mobile'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <section class="details-page">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="breadcrumb-wrap">
                <nav aria-label="breadcrumb" class="breadcrumbs large-font">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $site_url; ?>" role="button" tabindex="0"><i class="fas fa-home"></i></a></li>
                    <?php foreach ( $category_chain as $index => $cat ) : ?>
                      <li class="breadcrumb-item"><a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></a></li>
                    <?php endforeach; ?>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
          <div class="row detailsBodyRowGutter">
            <div class="col-lg-9">
              <div class="dheading">
                <?php if($shoulder) { ?>
                  <h2 class="DShoulder"><?php echo $shoulder; ?></h2>
                <?php } ?>
                <h1><?php the_title(); ?></h1>
                <?php if($subheadline) { ?>
                  <h2 class="DsubHead"><?php echo $subheadline; ?></h2>
                <?php } ?>
              </div>
              <?php if (is_active_sidebar('news-details-before-image-mobile')) : ?>
                <div class="row MobileShow">
                  <div class="col-12">
                    <div class="d-flex justify-content-center mt-4 mb-2">
                      <?php dynamic_sidebar('news-details-before-image-mobile'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <div class="DNewsImg">
                <img class="img-fluid w-100" id="adf-overlay"
                  data-src="<?php echo $featured_image; ?>"
                  src="<?php echo $thumbnail_loading_image; ?>"
                  alt="<?php the_title(); ?>"
                  title="<?php the_title(); ?>">
                <?php if($caption) { ?><p><?php echo $caption; ?></p><?php } ?>
              </div>
              <?php if (is_active_sidebar('news-details-after-image-mobile')) : ?>
                <div class="row MobileShow">
                  <div class="col-12">
                    <div class="d-flex justify-content-center mt-4">
                      <?php dynamic_sidebar('news-details-after-image-mobile'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <div class="row">
                <div class="col-lg-6 d-flex">
                  <div class="align-self-stretch justify-content-center">
                    <div class="writter">
                      <p><?php echo get_field('reporter'); ?></p>
                    </div>
                    <div class="dateAndTime">
                      <p><i class="fa-regular fa-clock"></i> <?php echo ( $site_language === 'bn' ) ? 'প্রকাশ' : 'Published'; ?>:
                        <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?>                                     
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-flex">
                  <div class="sharethis-wrap d-flex align-self-stretch justify-content-center">
                    <!-- AddToAny BEGIN -->
                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                      <a class="a2a_button_facebook"></a>
                      <a class="a2a_button_x"></a>
                      <a class="a2a_button_whatsapp"></a>
                      <a class="a2a_button_linkedin"></a>
                      <a class="a2a_button_telegram"></a>
                      <a class="a2a_button_facebook_messenger"></a>
                      <a class="a2a_button_email"></a>
                      <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                    </div>
                    <script async src="https://static.addtoany.com/menu/page.js" type="text/javascript"></script>
                    <!-- AddToAny END -->
                  </div>
                </div>
                <div class="col-lg-2 d-flex">
                  <div class="DTextZoom d-flex align-self-stretch justify-content-end">
                    <button id="btnDecrease">-</button>
                    <button id="btnOriginal"><?php echo ( $site_language === 'bn' ) ? 'অ' : 'A'; ?></button>
                    <button id="btnIncrease">+</button>
                  </div>
                </div>
              </div>
              <div class="dNewsDesc" id="contentDetails">
                <?php the_content(); ?>
              </div>
              <?php if (is_active_sidebar('news-details-inside-body-desktop') || is_active_sidebar('news-details-inside-body-mobile')) : ?>
                <div class="DContentAdd">
                  <?php if (is_active_sidebar('news-details-inside-body-desktop')) : ?>
                    <div class="row mt-3 mb-3 MobileHide">
                      <div class="col-md-12">
                        <div class="DetailsAdd d-flex justify-content-center">
                          <?php dynamic_sidebar('news-details-inside-body-desktop'); ?>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php if (is_active_sidebar('news-details-inside-body-mobile')) : ?>
                    <div class="row mt-3 mb-3 MobileShow">
                      <div class="col-md-12">
                        <div class="DetailsAdd d-flex justify-content-center">
                          <?php dynamic_sidebar('news-details-inside-body-mobile'); ?>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-inside-body-2-mobile') || is_active_sidebar('news-details-inside-body-2-desktop')) : ?>
                <div class="DContentAdd2">
                  <?php if (is_active_sidebar('news-details-inside-body-2-mobile')) : ?>
                    <div class="row mt-3 mb-3 MobileShow">
                      <div class="col-md-12">
                        <div class="DetailsAdd d-flex justify-content-center">
                          <?php dynamic_sidebar('news-details-inside-body-2-mobile'); ?>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php if (is_active_sidebar('news-details-inside-body-2-desktop')) : ?>
                    <div class="row mt-3 mb-3 MobileHide">
                      <div class="col-md-12">
                        <div class="DetailsAdd d-flex justify-content-center">
                          <?php dynamic_sidebar('news-details-inside-body-2-desktop'); ?>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <?php if(!empty($post_tags)) { ?>
                <div class="tagArea">
                  <ul>
                    <li><?php echo ( $site_language === 'bn' ) ? 'বিষয়' : 'Topic'; ?> :</li>
                    <?php foreach ( $post_tags as $tag ) { ?>
                    <li>
                      <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              <?php } ?>
              <div class="sharethis-wrap mt-4">
                <div class="sharethis-inline-share-buttons"></div>
              </div>
              <?php if ($related && $related->have_posts()) { ?>
                <section class="CatNewsListArea">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="catSectionHeading">
                        <h2><?php echo ( $site_language === 'bn' ) ? 'আরও পড়ুন' : 'Read more'; ?></h2>
                      </div>
                    </div>
                  </div>
                  <div class="CatNewsListWrap">
                    <div class="row gx-5">
                      <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <div class="col-lg-6 CatBr">
                          <a
                            href="<?php the_permalink(); ?>">
                            <div class="CatNewsListContent">
                              <div class="row">
                                <div class="col-lg-5 col-5">
                                  <div class="CatNewsListImg">
                                    <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>"
                                      src="<?php echo $thumbnail_small_loading_image; ?>"
                                      alt="<?php the_title(); ?>"
                                      title="<?php the_title(); ?>" class="img-fluid">
                                  </div>
                                </div>
                                <div class="col-lg-7 col-7">
                                  <div class="CatNewsLisText">
                                    <div class="Desc">
                                      <h3 class="Title2"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                      <span class="PublishTime"><i
                                        class="fa-regular fa-clock"></i>
                                        <?php echo ( $site_language === 'bn' ) ? 'আপডেট' : 'Update'; ?> <?php echo wp_date('j F Y', get_post_time('U')); ?> | <?php echo wp_date('H:i', get_post_time('U')); ?>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                  </div>
                </section>
              <?php } ?>
              <?php if (is_active_sidebar('news-details-after-body-desktop')) : ?>
                <div class="row MobileHide">
                  <div class="col-12">
                    <div class="d-flex justify-content-center mb-5">
                      <?php dynamic_sidebar('news-details-after-body-desktop'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-after-body-mobile')) : ?>
                <div class="row MobileShow">
                  <div class="col-12">
                    <div class="d-flex justify-content-center mb-5">
                      <?php dynamic_sidebar('news-details-after-body-mobile'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="col-lg-3">
              <?php if (is_active_sidebar('news-details-right-side-1-desktop')) : ?>
                <div class="dAddImg-wrap">
                  <div class="DRightSideAdd MobileHide mt-4">
                    <?php dynamic_sidebar('news-details-right-side-1-desktop'); ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-right-side-2-desktop')) : ?>
                <div class="dAddImg-wrap mt-4 MobileHide">
                  <div class="DRightSideAdd">
                    <?php dynamic_sidebar('news-details-right-side-2-desktop'); ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-right-side-3-desktop')) : ?>
                <div class="DRightSideAdd mt-4 mb-2 MobileHide">
                  <?php dynamic_sidebar('news-details-right-side-3-desktop'); ?>
                </div>
              <?php endif; ?>
              <?php if ($latest_posts->have_posts()) : ?>
                <div class="DlastNews">
                  <div class="dlastHead">
                    <a href="<?php echo get_permalink($latest->ID); ?>">
                      <h3><?php echo ( $site_language === 'bn' ) ? 'সর্বশেষ' : 'Latest'; ?></h3>
                    </a>
                  </div>
                  <div class="dAllListWrap">
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                      <div class="DlastNews-list">
                        <a
                          href="<?php the_permalink(); ?>">
                          <div class="row gx-2">
                            <div class="col-5">
                              <div class="dLastNewsImg">
                                <img class="img-fluid"
                                  data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-3'); ?>"
                                  src="<?php echo $thumbnail_small_loading_image; ?>"
                                  alt="<?php the_title(); ?>"
                                  title="<?php the_title(); ?>">
                              </div>
                            </div>
                            <div class="col-7">
                              <div class="dLastNewsText">
                                <h5><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h5>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endwhile; ?>
                  </div>
                  <div class="DreadMoreBtn">
                    <a href="<?php echo get_permalink($latest->ID); ?>"><?php echo ( $site_language === 'bn' ) ? 'আরও পড়ুন' : 'Read more'; ?> <i
                      class="fas fa-angle-double-right"></i></a>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-after-latest-news')) : ?>
                <div class="row">
                  <div class="col-12">
                    <div class="d-flex justify-content-center my-3">
                      <?php dynamic_sidebar('news-details-after-latest-news'); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
      <?php if(is_active_sidebar('news-details-sticky-footer-desktop') || is_active_sidebar('news-details-sticky-footer-mobile')) : ?>
        <div id="T4Tutorials_UP11" class="T4Tutorials">
          <div class="T4Tutorials_UP">
            <a class="Exit" id="T4Tutorials_UP11_Close">×</a>
            <div class="Main_Main_Content">
              <?php if (is_active_sidebar('news-details-sticky-footer-desktop')) : ?>
                <div class="AdvertClass MobileHide text-center">
                  <?php dynamic_sidebar('news-details-sticky-footer-desktop'); ?>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('news-details-sticky-footer-mobile')) : ?>
                <div class="AdvertClass MobileShow text-center">
                  <?php dynamic_sidebar('news-details-sticky-footer-mobile'); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </main>
    <!--Modal Code Welcome Advert-->
    <?php get_footer(); ?>
    <script type="text/javascript">
      var ContentID = '352102';
      if (ContentID == '239727') {
        $(document).ready(function() {
          $(document).on("contextmenu", function(e) {
            return false;
          });
          $('body').on('selectstart dragstart', function(e) {
            e.preventDefault();
            return false;
          });
        });
      }
    </script>
    <?php if(is_active_sidebar('news-details-sticky-footer-desktop') || is_active_sidebar('news-details-sticky-footer-mobile')) : ?>
      <script type="text/javascript">
        // Footer Sticky Ads
        jQuery(document).ready(function(e) {
          jQuery('#T4Tutorials_UP11_Close').click(function() {
            jQuery('#T4Tutorials_UP11').hide();
          })
        });
      </script>
    <?php endif; ?>
    <script type="text/javascript">
      $(window).load(function() {
          $("main img").each(function(index) {
              $(this).attr('src', $(this).attr('data-src'))
          });
      });
    </script>
    <script type="text/javascript">
      $(function() {
        $("#btnIncrease").click(function() {
          $(".dNewsDesc").children().each(function() {
            var size = parseInt($(this).css("font-size"));
            size = size + 1 + "px";
            $(this).css({
                'font-size': size
            });
          });
        });
      });
      $(function() {
        $("#btnOriginal").click(function() {
          $(".dNewsDesc").children().each(function() {
            $(this).css({
                'font-size': '20px'
            });
          });
        });
      });
      $(function() {
        $("#btnDecrease").click(function() {
          $(".dNewsDesc").children().each(function() {
            var size = parseInt($(this).css("font-size"));
            size = size - 1 + "px";
            $(this).css({
              'font-size': size
            });
          });
        });
      });
      $(".DContentAdd").insertAfter($("#contentDetails p:nth-child(2)"));
      $(".DContentAdd2").insertAfter($("#contentDetails p:nth-child(5)"));
      $(".DContentAdd3").insertAfter($("#contentDetails p:nth-child(4)"));
      $('#contentDetails img').each(function() {
        var float = '';
        var data = $(this).attr('alt');
    
        if ($(this).attr('style')) {
          var style = $(this).attr('style').split(';');
          $.each(style, function(index, value) {
            if (value.indexOf("float") >= 0) {
              float = value;
            }
          });
        }
        if (float != '') {
          if (data.length > 100) {
            $(this).wrap("<div class='dCaption' style='" + float + "'></div>")
              .parent('.dCaption')
              .append('<p class="text-justify img-caption">' + data + '</p>');
            $(document).ready(function() {
              $("#contentDetails:not(.writer_div)");
            });
          } else {
            $(this).wrap("<div class='dCaption' style='" + float + "'></div>")
              .parent('.dCaption')
              .append('<p class="text-center img-caption">' + data + '</p>');
          }
        } else {
          // console.log(float);
          if (data.length > 100) {
            $(this).wrap("<div class='dCaption'></div>")
              .parent('.dCaption')
              .append('<p class="text-justify img-caption">' + data + '</p>');
          } else {
            $(this).wrap("<div class='dCaption'></div>")
              .parent('.dCaption')
              .append('<p class="text-center img-caption">' + data + '</p>');
          }
        }
      });
    </script>
    <!--Modal script Code Welcome Advert-->
  </body>
</html>
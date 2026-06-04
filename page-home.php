<?php 
  /* Template Name: Home */
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $site_url = site_url();
  $url = esc_url( get_permalink() );
  $page_title = get_the_title();
  $meta_title = get_field('meta_title');
  if(!$meta_title) {
    $meta_title = ( $site_language === 'bn' ) ? "{$page_title} - {$site_name}" : "{$page_title} – {$site_name}";
  }
  $meta_description = get_field('meta_description');
  if(!$meta_description) {
    $meta_description = ( $site_language === 'bn' ) ? "{$page_title} - {$site_name}" : "{$page_title} – {$site_name}";
  }
  $keywords = get_field('keywords');
  $link_preview_image = get_field('link_preview_image');
  $default_link_preview_image = get_theme_mod( 'samakal_default_link_preview_image' );
  $site_name = get_bloginfo('name');

  $sections = null;

  for ($i = 1; $i <= 20; $i++) {
    $id = get_theme_mod('section_' . $i);

    if ($id && str_starts_with($id, 'cat_')) {
      $category_id = (int) str_replace('cat_', '', $id);
      $category = get_category($category_id);
  
      if ($category && !is_wp_error($category)) {
        $posts = get_posts([
          'post_type'      => 'post',
          'posts_per_page' => 11,
          'cat'            => $category_id,
        ]);

        $sections['section_' . $i] = [
          'name' => get_field('custom_title', $category)
            ? get_field('custom_title', $category)
            : $category->name,
          'url' => get_category_link($category->term_id),
          'posts'    => $posts,
        ];
      }
    } else if ($id && str_starts_with($id, 'col_')) {
      $collection_id = (int) str_replace('col_', '', $id);
      $collection = get_term($collection_id, 'collection');
  
      if ($collection && !is_wp_error($collection)) {
        $posts = get_field('items', $collection);

        $sections['section_' . $i] = [
          'name' => $collection->name,
          'posts'    => $posts,
        ];
      }
    }
  }

  $videos_page = get_posts([
    'post_type'   => 'page',
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'page-video.php',
    'numberposts' => 1
  ]);

  if ($videos_page) {
    $videos_page = $videos_page[0];
  }

  $videos_args = [
    'post_type'      => 'video',
    'posts_per_page' => 7,
  ];

  $videos = new WP_Query($videos_args);

  $thumbnail_medium_placeholder_image = get_theme_mod( 'samakal_thumbnail_medium_placeholder_image' );  $thumbnail_small_placeholder_image = get_theme_mod( 'samakal_thumbnail_small_placeholder_image' );

  $latest_args = [
    'post_type'      => 'post',
    'posts_per_page' => 15,
  ];
  $latest_posts = new WP_Query($latest_args);

  $latest_page = get_posts([
    'post_type'   => 'page',
    'meta_key'    => '_wp_page_template',
    'meta_value'  => 'page-latest.php',
    'numberposts' => 1
  ]);

  if ($latest_page) {
    $latest_page = $latest_page[0];
  }

  $popular_posts = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'meta_key'       => 'post_views_count',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'date_query'     => array(
      array(
        'after' => '7 days ago'
      )
    )                        
  ) );


  $date = date('d-m-Y');

  $url = "https://api.aladhan.com/v1/timingsByCity/{$date}?city=Dhaka&country=BD";

  $response = wp_remote_get($url);

  if (!is_wp_error($response)) {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
  
    $prayer_times = $data['data']['timings'] ?? false;
  }
?>
<!doctype html>
<html lang="<?php echo $site_language; ?>">
  <head>
    <?php include get_template_directory() . '/meta.php'; ?>
    <?php if ( $meta_title ) { ?>
      <title><?php echo $meta_title; ?></title>
    <?php } ?>
    <meta http-equiv="refresh" content="600">
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
        display: none;
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
      /* Election Results Styling */
      .ElectionWrapper {
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        border: 1px solid #e1e1e1;
        overflow: hidden;
      }
      .ElectionHeader {
        background-color: #f78022;
        padding: 15px 20px;
        text-align: center;
      }
      .ElectionHeader h2 {
        color: #111;
        margin: 0;
        font-size: 24px;
        font-weight: 700;
      }
      .ElectionSummaryBar {
        background-color: #fff9da;
        padding: 10px 20px;
        border-bottom: 1px solid #f78022;
        display: flex;
        justify-content: center;
        gap: 30px;
        align-items: center;
        flex-wrap: wrap;
      }
      .SummaryItem {
        font-size: 16px;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .SummaryItem strong {
        font-size: 20px;
        color: #000;
      }
      .ElectionBody {
        padding: 20px;
      }
      .PartiesGrid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
      }
      .PartyCard {
        background: #fff;
        border: 1px solid #eee;
        border-top: 3px solid #f78022;
        border-radius: 6px;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
      }
      .PartyName {
        color: #333;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
        min-height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1.2;
      }
      .PartyCount {
        display: inline-block;
        background: transparent;
        color: #f78022;
        padding: 2px 12px;
        border-radius: 4px;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
      }
      .ReferendumSection {
        background: #fff;
        border-top: 1px solid #eee;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
      }
      .ReferendumTitle {
        text-align: left;
        color: #111;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        white-space: nowrap;
      }
      .ReferendumGraphContainer {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
      }
      .ReferendumBarContainer {
        width: 100%;
        height: 12px;
        background: #e9ecef;
        border-radius: 6px;
        overflow: hidden;
        display: flex;
      }
      .ReferendumBarYes {
        background-color: #28a745;
        height: 100%;
      }
      .ReferendumBarNo {
        background-color: #dc3545;
        height: 100%;
      }
      .ReferendumLabels {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        line-height: 1;
      }
      .RefLabelYes {
        color: #1f1f1f;
      }
      .RefLabelNo {
        color: #9b9b9b;
      }
      @media screen and (max-width: 768px) {
        .ReferendumSection {
          flex-direction: column;
          align-items: flex-start;
          gap: 10px;
        }
        .ReferendumGraphContainer {
          width: 100%;
        }
      }
      @media screen and (max-width: 768px) {
        .PartiesGrid {
          grid-template-columns: repeat(3, 1fr);
        }
        .ElectionHeader h2 {
          font-size: 20px;
        }
      }
      @media screen and (max-width: 480px) {
        .PartiesGrid {
          grid-template-columns: repeat(2, 1fr);
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
          }
        ]
      }      
    </script>
    
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <div class="container">
        <?php if (is_active_sidebar('home-leaderboard-1-desktop')) : ?>
          <div class="row MobileHide">
            <div class="col-12">
              <div class="d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-leaderboard-1-desktop'); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if (is_active_sidebar('home-leaderboard-1-mobile')) : ?>
          <div class="row MobileShow">
            <div class="col-12">
              <div class="d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-leaderboard-1-mobile'); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="DTopNewsSection mt-5">
          <div class="row">
            <?php if(isset($sections['section_1'])) : ?>
              <?php if (!empty($sections['section_1']['posts'])) : ?>
                <div class="col-lg-9">
                    <div class="row">
                      <div class="col-lg-8 col-12 border-right-inner mt-3">
                        <?php setup_postdata($post = $sections['section_1']['posts'][0]); ?>
                          <div class="DHomeTopLead">
                            <a href="<?php the_permalink(); ?>">
                              <div class="row">
                                <div class="col-lg-7">
                                  <div class="DImgZoomBlock">
                                    <picture>
                                      <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                    </picture>
                                  </div>
                                </div>
                                <div class="col-lg-5 order-lg-first">
                                  <div class="Desc">
                                    <h1 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading fw-bold"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h1>
                                    <div class="Brief">
                                      <p><?php the_excerpt(); ?></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        <?php wp_reset_postdata(); ?>
                        <?php if (count($sections['section_1']['posts']) > 1) : ?>
                          <div class="DHomeLeadList3Sec">
                            <div class="row">
                              <?php foreach (array_slice($sections['section_1']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                                <div class="col-lg-6 col-12 d-flex border-right-inner">
                                  <div class="DHomeLeadList3 align-self-stretch">
                                    <a href="<?php the_permalink(); ?>">
                                      <div class="row">
                                        <div class="col-lg-5 col-5">
                                          <div class="DImgZoomBlock">
                                            <picture>
                                              <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                            </picture>
                                          </div>
                                        </div>
                                        <div class="col-lg-7 col-7">
                                          <div class="Desc">
                                            <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                          </div>
                                        </div>
                                      </div>
                                    </a>
                                  </div>
                                </div>
                              <?php endforeach; wp_reset_postdata(); ?>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                      <?php if (count($sections['section_1']['posts']) > 5) : ?>
                        <div class="col-lg-4 col-12 mt-3 border-right-inner">
                          <div class="DTopLeadNews2">
                            <?php foreach (array_slice($sections['section_1']['posts'], 5, 4) as $post) : setup_postdata($post); ?>
                              <div class="DHomeLeadList3 align-self-stretch">
                                <a href="<?php the_permalink(); ?>">
                                  <div class="row">
                                    <div class="col-lg-7 col-7">
                                      <div class="Desc">
                                        <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-5">
                                      <div class="DImgZoomBlock">
                                        <picture>
                                          <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                        </picture>
                                      </div>
                                    </div>
                                  </div>
                                </a>
                              </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                          </div>
                        </div>
                      <?php endif; ?>
                      <?php if (is_active_sidebar('home-after-lead-mobile')) : ?>
                        <div class="col-12 MobileShow">
                          <div class="d-flex justify-content-center mt-3">
                            <?php dynamic_sidebar('home-after-lead-mobile'); ?>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php if (count($sections['section_1']['posts']) > 9) : ?>
                    <div class="leadTop3 mt-2">
                      <div class="row">
                        <?php foreach (array_slice($sections['section_1']['posts'], 9, 3) as $post) : setup_postdata($post); ?>
                          <div class="col-lg-4 d-flex border-right-inner">
                            <div class="leadTop3-wrap align-self-stretch">
                              <a href="<?php the_permalink(); ?>">
                                <div class="row">
                                  <div class="col-lg-12 col-5 medium-video-icon">
                                    <picture>
                                      <img class="img-fluid img100" data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                                    </picture>
                                  </div>
                                  <div class="col-lg-12 col-7">
                                    <div class="Desc">
                                      <h3 class="Title2"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <div class="col-lg-3 col-12 mt-3">
              <?php if (is_active_sidebar('home-right-side-1-desktop')) : ?>
                <div class="DRightSideAdd MobileHide">
                  <?php dynamic_sidebar('home-right-side-1-desktop'); ?>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('home-right-side-1-mobile')) : ?>
                <div class="DRightSideAdd MobileShow">
                  <?php dynamic_sidebar('home-right-side-1-mobile'); ?>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('home-right-side-2')) : ?>
                <div class="DRightSideAdd my-2">
                  <?php dynamic_sidebar('home-right-side-2'); ?>
                </div>
              <?php endif; ?>
              <?php if(isset($sections['section_2'])) : ?>
                <div class="DEditorsPicksSec mt-3">
                  <div class="DTitleStyle">
                    <?php if ($sections['section_2']['url']) { ?>
                      <a href="<?php echo $sections['section_2']['url']; ?>">
                        <h3><i class="fa-solid fa-circle-half-stroke"></i><?php echo $sections['section_2']['name']; ?></h3>
                      </a>
                    <?php } else { ?>
                      <h3><i class="fa-solid fa-circle-half-stroke"></i><?php echo $sections['section_2']['name']; ?></h3>
                    <?php } ?>
                  </div>
                  <?php if (!empty($sections['section_2']['posts'])) : ?>
                    <?php foreach (array_slice($sections['section_2']['posts'], 0, 2) as $post) : setup_postdata($post); ?>
                      <div class="DEditorialListItem">
                        <a href="<?php the_permalink(); ?>">
                          <div class="row">
                            <div class="col-lg-7 col-7">
                              <div class="Desc">
                                <p class="WriterName"><i class="fa fa-edit" aria-hidden="true"></i><?php echo get_field('reporter'); ?></p>
                                <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                              </div>
                            </div>
                            <div class="col-lg-5 col-5 ">
                              <picture>
                                <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100 ImgRatio">
                              </picture>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php if (is_active_sidebar('home-leaderboard-2-desktop')) : ?>
        <div class="container">
          <div class="row MobileHide">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-4 mb-2">
                <?php dynamic_sidebar('home-leaderboard-2-desktop'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <section class="container">
        <div class="row">
          <?php if(isset($sections['section_3'])) : ?>
            <div class="col-lg-8 col-12">
              <?php if ($sections['section_3']['url']) { ?>
                <a href="<?php echo $sections['section_3']['url']; ?>">
                  <h2 class="SectionName"><?php echo $sections['section_3']['name']; ?></h2>
                </a>
              <?php } else { ?>
                <h2 class="SectionName"><?php echo $sections['section_3']['name']; ?></h2>
              <?php } ?>
              <?php if (!empty($sections['section_3']['posts'])) : ?>
                <div class="row">
                  <?php setup_postdata($post = $sections['section_3']['posts'][0]); ?>
                    <div class="col-lg-6 col-12">
                      <div class="SpecialEventTop">
                        <a href="<?php the_permalink(); ?>">
                          <div class="DImgZoomBlock .medium-video-icon">
                            <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>"  alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                            </picture>
                            <div class="card-video-img"></div>
                          </div>
                          <div class="Desc">
                            <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                            <div class="Brief">
                              <p><?php the_excerpt(); ?></p>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (count($sections['section_3']['posts']) > 1) : ?>
                    <div class="col-lg-6 col-12">
                      <?php foreach (array_slice($sections['section_3']['posts'], 1, 3) as $post) : setup_postdata($post); ?>
                        <div class="SpecialEventList">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-lg-5 col-5">
                                <div class="DImgZoomBlock">
                                  <picture>
                                    <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                  </picture>
                                </div>
                              </div>
                              <div class="col-lg-7 col-7">
                                <div class="Desc">
                                  <div class="CatNameSP"><?php echo (($cat_id = (int) get_post_meta(get_the_ID(), 'spc_primary_category', true)) > 0 && ($cat = get_category($cat_id)) && !is_wp_error($cat)) ? $cat->name : (($cats = get_the_category()) ? ($cats[0]->name ?? '') : ''); ?></div>
                                  <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?>
                                  </h2>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('home-sidebar-1-mobile')) : ?>
            <div class="col-12 MobileShow">
              <div class="d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-sidebar-1-mobile'); ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($latest_posts->have_posts()) : ?>
            <div class="col-lg-4 col-12">
              <div class="DLatestNewsSec">
                <a href="<?php echo get_permalink($latest_page->ID); ?>">
                  <div class="DTitleStyle">
                    <h3><i class="fa-solid fa-circle-half-stroke"></i><?php echo ( $site_language === 'bn' ) ? 'সর্বশেষ' : 'Latest'; ?></h3>
                  </div>
                </a>
                <div class="DLatestNewsList">
                  <ul>
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); $i = 1; ?>
                      <li>
                        <a href="<?php the_permalink(); ?>">
                          <div class="d-flex  align-items-center">
                            <div class="d-flex h-100 align-items-center"><span class="Counter"><?php echo theme_translate($i); ?>.</span></div>
                            <p class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></p>
                          </div>
                        </a>
                      </li>
                    <?php $i++; endwhile; ?>
                  </ul>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>
      <?php if (is_active_sidebar('home-sidebar-2-mobile')) : ?>
        <div class="row MobileShow">
          <div class="col-12">
            <div class="d-flex justify-content-center mt-3">
              <?php dynamic_sidebar('home-sidebar-2-mobile'); ?>
            </div>
          </div>
        </div>      
      <?php endif; ?>
      <?php if (is_active_sidebar('home-leaderboard-3-desktop')) : ?>
        <div class="container">
          <div class="row MobileHide">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-leaderboard-3-desktop'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <section class="container MT-60">
        <div class="row">
          <?php if(isset($sections['section_4'])) : ?>
            <div class="col-lg-6 col-12 Politics">
              <?php if ($sections['section_4']['url']) { ?>
                <a href="<?php echo $sections['section_4']['url']; ?>">
                  <h2 class="SectionName"><?php echo $sections['section_4']['name']; ?></h2>
                </a>
              <?php } else { ?>
                <h2 class="SectionName"><?php echo $sections['section_4']['name']; ?></h2>
              <?php } ?>
              <?php if (!empty($sections['section_5']['posts'])) : ?>
                <div class="row">
                  <?php setup_postdata($post = $sections['section_4']['posts'][0]); ?>
                    <div class="col-lg-6 col-12">
                      <div class="DCategory4Top">
                        <a href="<?php the_permalink(); ?>">
                          <div class="DImgZoomBlock ">
                            <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"> </picture>
                          </div>
                          <div class="Desc">
                            <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                            <div class="Brief">
                              <p><?php the_excerpt(); ?></p>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (count($sections['section_4']['posts']) > 1) : ?>
                    <div class="col-lg-6 col-12">
                      <?php foreach (array_slice($sections['section_4']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                        <div class="DCategory5NewsList">
                          <a href="<?php the_permalink(); ?> ">
                            <div class="row">
                              <div class="col-lg-7 col-7">
                                <div class="Desc">
                                  <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                </div>
                              </div>
                              <div class="col-lg-5 col-5">
                                <div class="DImgZoomBlock ">
                                  <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('home-sidebar-3-mobile')) : ?>
            <div class="row MobileShow">
              <div class="col-12">
                <div class="d-flex justify-content-center mt-3">
                  <?php dynamic_sidebar('home-sidebar-3-mobile'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if(isset($sections['section_5'])) : ?>
            <div class="col-lg-6 col-12 Capital">
              <?php if ($sections['section_5']['url']) { ?>
                <a href="<?php echo $sections['section_5']['url']; ?>">
                  <h2 class="SectionName"><?php echo $sections['section_5']['name']; ?></h2>
                </a>
              <?php } else { ?>
                <h2 class="SectionName"><?php echo $sections['section_5']['name']; ?></h2>
              <?php } ?>
              <?php if (!empty($sections['section_5']['posts'])) : ?>
                <?php setup_postdata($post = $sections['section_5']['posts'][0]); ?>
                  <div class="WritersSectionTop">
                    <a href="<?php the_permalink(); ?>">
                      <div class="row">
                        <div class="col-lg-6 col-12">
                          <div class="DImgZoomBlock ">
                            <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                          </div>
                        </div>
                        <div class="col-lg-6 col-12">
                          <div class="Desc">
                            <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                            <div class="Brief">
                              <p><?php the_excerpt(); ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                <?php wp_reset_postdata(); ?>
                <?php if (count($sections['section_5']['posts']) > 1) : ?>
                  <div class="row">
                    <?php foreach (array_slice($sections['section_5']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                      <div class="col-lg-6 col-12 d-flex">
                        <div class="DCategory6NewsList MT-25">
                          <a href="<?php the_permalink(); ?>" class="align-self-stretch">
                            <div class="row">
                              <div class="col-lg-5 col-5">
                                <div class="DImgZoomBlock ">
                                  <picture>
                                    <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>"
                                      alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                  </picture>
                                </div>
                              </div>
                              <div class="col-lg-7 col-7">
                                <div class="Desc">
                                  <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
      <?php if (is_active_sidebar('home-leaderboard-4-desktop')) : ?>
        <div class="container">
          <div class="row MobileHide">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-4">
                <?php dynamic_sidebar('home-leaderboard-4-desktop'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if(isset($sections['section_6'])) : ?>
        <section class="container MT-60">
          <?php if ($sections['section_6']['url']) { ?>
            <a href="<?php echo $sections['section_6']['url']; ?>">
              <h2 class="SectionName"><?php echo $sections['section_6']['name']; ?></h2>
            </a>
          <?php } else { ?>
            <h2 class="SectionName"><?php echo $sections['section_6']['name']; ?></h2>
          <?php } ?>
          <?php if (!empty($sections['section_6']['posts'])) : ?>
            <div class="row"> 
              <?php setup_postdata($post = $sections['section_6']['posts'][0]); ?>
                <div class="col-lg-5 col-12">
                  <div class="DSportsTopNews">
                    <a href="<?php the_permalink(); ?>">
                      <div class="DImgZoomBlock">
                        <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"> </picture>
                      </div>
                      <div class="Desc">
                        <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                        <div class="Brief">
                          <p><?php the_excerpt(); ?></p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              <?php wp_reset_postdata(); ?>
              <?php if (count($sections['section_6']['posts']) > 1) : ?>
                <div class="col-lg-7 col-12 order-lg-first">
                  <div class="row">
                    <?php foreach (array_slice($sections['section_6']['posts'], 1, 6) as $post) : setup_postdata($post); ?>
                      <div class="col-lg-4 col-12 d-flex">
                        <div class="DSportsList align-self-stretch">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-lg-12 col-5">
                                <div class="DImgZoomBlock ">
                                  <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                                </div>
                              </div>
                              <div class="col-lg-12 col-7">
                                <div class="Desc">
                                  <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </section>
      <?php endif; ?>
      <?php if (is_active_sidebar('home-sidebar-4-mobile')) : ?>
        <div class="container">
          <div class="row MobileShow">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-sidebar-4-mobile'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($videos->have_posts()) : ?>
        <div class="DVideoSecBG">
          <div class="container">
            <div class="SectionTitle SpTitle">
              <a href="<?php echo get_permalink($videos_page->ID); ?>">
                <h3><?php echo $videos_page->post_title; ?></h3>
              </a>
            </div>
            <div class="DVideoGallery">
              <div class="row">
                <?php while ($videos->have_posts()) : $videos->the_post(); if ($videos->current_post < 3) : ?>
                  <div class="col-lg-4 col-12 d-flex">
                    <div class="DTopVideo2 align-self-stretch">
                      <a href="<?php the_permalink(); ?>">
                        <div class="DImgBlock">
                          <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"></picture>
                          <div class="card-video-img transition"></div>
                        </div>
                      </a>
                    </div>
                  </div>
                <?php endif; endwhile; ?>
              </div>
              <?php if ( $videos->post_count > 3) : ?>
                <div class="VideoSlider">
                  <div class="row">
                    <?php while ($videos->have_posts()) : $videos->the_post(); if ($videos->current_post > 2 && $videos->current_post < 7) : ?>
                      <div class="col-lg-3 col-6 mb-4">
                        <div class="DVideoGalleryTop3List">
                          <a href="<?php the_permalink(); ?>">
                            <div class="DImgBlock">
                              <img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                              <div class="card-video-img transition"></div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endif; endwhile; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if (is_active_sidebar('home-sidebar-5-mobile')) : ?>
        <div class="container">
          <div class="row MobileShow">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-3">
                <?php dynamic_sidebar('home-sidebar-5-mobile'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="container">
        <div class="row">
          <?php if(isset($sections['section_7'])) : ?>
            <div class="col-lg-9 border-right-inner1 MT-25">
              <div class="SectionTitle">
                <?php if ($sections['section_7']['url']) { ?>
                  <a href="<?php echo $sections['section_7']['url']; ?>">
                    <h3><?php echo $sections['section_7']['name']; ?></h3>
                  </a>
                <?php } else { ?>
                  <h3><?php echo $sections['section_7']['name']; ?></h3>
                <?php } ?>
              </div>
              <?php if (!empty($sections['section_7']['posts'])) : ?>
                <div class="DBangladesh">
                  <div class="row">
                    <?php foreach (array_slice($sections['section_7']['posts'], 0, 3) as $post) : setup_postdata($post); ?>
                      <div class="col-lg-4 d-flex">
                        <div class="DBangladeshList align-self-stretch">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-lg-12 col-5">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>"
                                    alt="<?php the_title(); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" title="<?php the_title(); ?>" class="img-fluid">
                                </picture>
                              </div>
                              <div class="col-lg-12 col-7">
                                <div class="Desc">
                                  <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if(isset($sections['section_8'])) : ?>
            <div class="col-lg-3 MT-25">
              <div class="SectionTitle">
                <?php if ($sections['section_8']['url']) { ?>
                  <a href="<?php echo $sections['section_8']['url']; ?>">
                    <h3><?php echo $sections['section_8']['name']; ?></h3>
                  </a>
                  <?php } else { ?>
                    <h3><?php echo $sections['section_8']['name']; ?></h3>
                <?php } ?>
              </div>
              <?php if (!empty($sections['section_8']['posts'])) : ?>
                <div class="DInvestigation">
                  <?php foreach (array_slice($sections['section_8']['posts'], 0, 3) as $post) : setup_postdata($post); ?>
                    <div class="DInvestigationList">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-5 col-5 ">
                            <picture>
                              <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-3'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid leadMedia">
                            </picture>
                          </div>
                          <div class="col-lg-7 col-7">
                            <h5 class="Title TitleSM"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?>
                            </h5>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php endforeach; wp_reset_postdata(); ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>        
        <?php if (is_active_sidebar('home-leaderboard-5')) : ?>
          <div class="col-12">
            <div class="d-flex justify-content-center">
              <?php dynamic_sidebar('home-leaderboard-5'); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <?php if (is_active_sidebar('home-sidebar-6-mobile')) : ?>
        <div class="row mt-5 MobileShow">
          <div class="col-12">
            <div class="d-flex justify-content-center">
              <?php dynamic_sidebar('home-sidebar-6-mobile'); ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="container">
        <div class="row">
          <?php if(isset($sections['section_9'])) : ?>
            <div class="col-lg-9 col-12">
              <div class="PoliticsSection">
                <div class="row mb-3">
                  <div class="col-lg-12">
                    <div class="SectionTitle">
                      <h3>
                        <?php if ($sections['section_9']['url']) { ?>
                        <a href="<?php echo $sections['section_9']['url']; ?>"><span class="ColorBox"></span><?php echo $sections['section_9']['name']; ?></a>
                        <?php } else { ?>
                          <span class="ColorBox"></span><?php echo $sections['section_9']['name']; ?>
                        <?php } ?>
                      </h3>
                    </div>
                  </div>
                </div>
                <?php if (!empty($sections['section_9']['posts'])) : ?>
                  <div class="row">
                    <?php setup_postdata($post = $sections['section_9']['posts'][0]); ?>
                      <div class="col-lg-6 col-12 border-right-inner rowresize" style="flex:0 0 49%;max-width: 49%;">
                        <div class="DPoliticsTopNews">
                          <a href="<?php the_permalink(); ?>">
                            <div class="DImgZoomBlock">
                              <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                            </div>
                            <div class="Desc">
                              <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php wp_reset_postdata(); ?>
                    <?php if (count($sections['section_9']['posts']) > 1) : ?>
                      <div class="col-lg-3 col-12 border-right-inner rowresize" style="flex:0 0 21%;max-width: 21%;">
                        <?php foreach (array_slice($sections['section_9']['posts'], 1, 2) as $post) : setup_postdata($post); ?>
                          <div class="DPoliticsTop2News">
                            <a href="<?php the_permalink(); ?>">
                              <div class="DImgZoomBlock">
                                <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                              </div>
                              <div class="Desc">
                                <h4 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h4>
                              </div>
                            </a>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    <?php endif; ?>
                    <?php if (count($sections['section_9']['posts']) > 3) : ?>
                      <div class="col-lg-4 col-12 rowresize" style="flex:0 0 30%;max-width: 30%;">
                        <div class="DPoliticsTop3News">
                          <?php foreach (array_slice($sections['section_9']['posts'], 3, 5) as $post) : setup_postdata($post); ?>
                            <div class="DPoliticsTop3List">
                              <a href="<?php the_permalink(); ?>">
                                <div class="Desc">
                                  <h4 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h4>
                                </div>
                              </a>
                            </div>
                          <?php endforeach; wp_reset_postdata(); ?>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if ( $popular_posts->have_posts() ) : ?>
            <div class="col-lg-3 col-12">
              <div class="DPopularSec">
                <div class="SectionTitle">
                  <a href="">
                    <h3><?php echo ( $site_language === 'bn' ) ? 'সর্বাধিক পঠিত' : 'Most read'; ?></h3>
                  </a>
                </div>
                <div class="DPopularNews">
                  <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                    <div class="DPopularNewsList">
                      <a href="popular_posts">
                        <div class="row">
                          <div class="col-lg-5 col-5 ">
                            <picture>
                              <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-3'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid leadMedia">
                            </picture>
                          </div>
                          <div class="col-lg-7 col-7">
                            <h5 class="Title TitleSM"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h5>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php endwhile; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?php if (is_active_sidebar('home-leaderboard-6-desktop')) : ?>
        <div class="container">
          <div class="row MobileHide">
            <div class="col-12">
              <div class="DHomeAdd970X90 d-flex justify-content-center mt-4">
                <?php dynamic_sidebar('home-leaderboard-6-desktop'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if(isset($sections['section_10']) || isset($sections['section_11'])) : ?>
        <section class="container MT-20">
          <div class="row">
            <?php if(isset($sections['section_10'])) : ?>
              <div class="col-lg-6 col-12">
                <?php if ($sections['section_10']['url']) { ?>
                  <a href="<?php echo $sections['section_10']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_10']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_10']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_10']['posts'])) : ?>
                  <?php setup_postdata($post = $sections['section_10']['posts'][0]); ?>
                    <div class="DHealthCatStyle">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="DImgZoomBlock ">
                              <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                            </div>
                          </div>
                          <div class="col-lg-6 order-lg-first">
                            <div class="Desc">
                              <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (!empty($sections['section_10']['posts']) && count($sections['section_10']['posts']) > 1) : ?>
                    <div class="DHealthCatStyleSec">
                      <div class="row">
                        <?php foreach (array_slice($sections['section_10']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                          <div class="col-lg-6 col-12 d-flex">
                            <div class="DHealthCatList4 align-self-stretch">
                              <a href="<?php the_permalink(); ?>">
                                <div class="row">
                                  <div class="col-lg-5 col-5">
                                    <div class="DImgZoomBlock ">
                                      <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                                    </div>
                                  </div>
                                  <div class="col-lg-7 col-7">
                                    <div class="Desc">
                                      <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if(isset($sections['section_11'])) : ?>
              <div class="col-lg-6 col-12">
                <?php if ($sections['section_11']['url']) { ?>
                  <a href="<?php echo $sections['section_11']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_11']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_11']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_11']['posts'])) : ?>
                  <?php setup_postdata($post = $sections['section_11']['posts'][0]); ?>
                    <div class="DHealthCatStyle">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="DImgZoomBlock ">
                              <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                            </div>
                          </div>
                          <div class="col-lg-6 order-lg-first">
                            <div class="Desc">
                              <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (!empty($sections['section_11']['posts']) && count($sections['section_11']['posts']) > 1) : ?>
                    <div class="DHealthCatStyleSec">
                      <div class="row">
                        <?php foreach (array_slice($sections['section_11']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                          <div class="col-lg-6 col-12 d-flex">
                            <div class="DHealthCatList4 align-self-stretch">
                              <a href="<?php the_permalink(); ?>">
                                <div class="row">
                                  <div class="col-lg-5 col-5">
                                    <div class="DImgZoomBlock ">
                                      <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                                    </div>
                                  </div>
                                  <div class="col-lg-7 col-7">
                                    <div class="Desc">
                                      <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
      <?php if(isset($sections['section_12'])) : ?>
        <section class="SpecialSectionBG MT-60">
          <div class="container">
            <?php if ($sections['section_12']['url']) { ?>
              <a href="<?php echo $sections['section_12']['url']; ?>">
                <h2 class="SectionName2"><?php echo $sections['section_12']['name']; ?></h2>
              </a>
            <?php } else { ?>
              <h2 class="SectionName2"><?php echo $sections['section_12']['name']; ?></h2>
            <?php } ?>
            <?php if (!empty($sections['section_12']['posts'])) : ?>
              <div class="SpecialSection2">
                <div class="row">
                  <?php foreach (array_slice($sections['section_12']['posts'], 0, 2) as $post) : setup_postdata($post); ?>
                    <div class="col-lg-4 col-sm-12 border-right-inner2">
                      <div class="DEconomicsTop">
                        <a href="<?php the_permalink(); ?>">
                          <div class="Imgresize ">
                            <picture><img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
                              class="img-fluid img100"></picture>
                          </div>
                          <div class="Desc">
                            <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                            <div class="Brief">
                              <p><?php the_excerpt(); ?></p>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php endforeach; wp_reset_postdata(); ?>
                  <?php if (!empty($sections['section_12']['posts']) && count($sections['section_12']['posts']) > 2) : ?>
                    <div class="col-lg-4 col-sm-12">
                      <div class="DEconomicsList">
                        <?php foreach (array_slice($sections['section_12']['posts'], 2, 3) as $post) : setup_postdata($post); ?>
                          <div class="DEconomicsListItem">
                            <a href="<?php the_permalink(); ?>">
                              <div class="DImgBlock">
                                <div class="Imgresize ">
                                  <picture>
                                    <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
                                      class="img-fluid img100">
                                  </picture>
                                </div>
                              </div>
                              <div class="DetailsBlock">
                                <div class="Desc">
                                  <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                </div>
                              </div>
                            </a>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
      <div class="container">
        <div class="row MobileHide">
          <?php if (is_active_sidebar('home-sidebar-7-desktop')) : ?>
            <div class="col-lg-4">
              <div class="DHomeAdd300X250 d-flex justify-content-center mt-5">
                <?php dynamic_sidebar('home-sidebar-7-desktop'); ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('home-sidebar-8-desktop')) : ?>
            <div class="col-lg-4">
              <div class="DHomeAdd300X250 d-flex justify-content-center mt-5">
                <?php dynamic_sidebar('home-sidebar-8-desktop'); ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('home-sidebar-8-desktop')) : ?>
            <div class="col-lg-4">
              <div class="DHomeAdd300X250 d-flex justify-content-center mt-5">
                <?php dynamic_sidebar('home-sidebar-8-desktop'); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?php if(isset($sections['section_13']) || isset($sections['section_14'])) : ?>
        <div class="EntertainmentBG">
          <div class="container">
            <?php if(isset($sections['section_13'])) : ?>
              <div class="SectionTitle">
                <?php if ($sections['section_13']['url']) { ?>
                  <a href="<?php echo $sections['section_13']['url']; ?>">
                    <h3><?php echo $sections['section_13']['name']; ?></h3>
                  </a>
                <?php } else { ?>
                  <h3><?php echo $sections['section_13']['name']; ?></h3>
                <?php } ?>
              </div>
              <?php if (!empty($sections['section_13']['posts'])) : ?>
                <div class="DEntertainment">
                  <div class="row">
                    <div class="col-lg-8 col-12 border-right-inner1">
                      <?php setup_postdata($post = $sections['section_13']['posts'][0]); ?>
                        <div class="DEntertainmentTop">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-lg-8 col-12">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                </picture>
                              </div>
                              <div class="col-lg-4 col-12">
                                <div class="Desc">
                                  <h3 class="Title BGTitle FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                  <div class="Brief">
                                    <p><?php the_excerpt(); ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      <?php wp_reset_postdata(); ?>
                      <?php if (!empty($sections['section_13']['posts']) && count($sections['section_13']['posts']) > 1) : ?>
                        <div class="DEnterTop2">
                          <div class="row">
                            <?php foreach (array_slice($sections['section_13']['posts'], 1, 3) as $post) : setup_postdata($post); ?>
                              <div class="col-lg-4 col-12 d-flex">
                                <div class="DEnterList2 align-self-stretch">
                                  <a href="<?php the_permalink(); ?>">
                                    <picture>
                                      <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                    </picture>
                                    <div class="Desc">
                                      <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                    <?php if ((!empty($sections['section_13']['posts']) && count($sections['section_13']['posts']) > 4) || is_active_sidebar('home-sidebar-10-desktop')) : ?>
                      <div class="col-lg-4 col-12">
                        <?php if (!empty($sections['section_13']['posts']) && count($sections['section_13']['posts']) > 4) : ?>
                          <div class="DEntertainmentList">
                            <?php foreach (array_slice($sections['section_13']['posts'], 4, 3) as $post) : setup_postdata($post); ?>
                              <div class="DEntertainmentListItem">
                                <a href="<?php the_permalink(); ?>">
                                  <div class="row">
                                    <div class="col-lg-5 col-5">
                                      <picture>
                                        <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-3'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                      </picture>
                                    </div>
                                    <div class="col-lg-7 col-7">
                                      <div class="Desc">
                                        <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                      </div>
                                    </div>
                                  </div>
                                </a>
                              </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                          </div>
                        <?php endif; ?>
                        <?php if (is_active_sidebar('home-sidebar-10-desktop')) : ?>
                          <div class="DRightSideAdd mt-3 MobileHide">
                            <?php dynamic_sidebar('home-sidebar-10-desktop'); ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <?php if(isset($sections['section_14'])) : ?>
              <div class="SectionTitle mt-3">
                <?php if ($sections['section_14']['url']) { ?>
                  <a href="<?php echo $sections['section_14']['url']; ?>">
                    <h3><?php echo $sections['section_14']['name']; ?></h3>
                  </a>
                <?php } else { ?>
                  <h3><?php echo $sections['section_14']['name']; ?></h3>
                <?php } ?>
              </div>
              <?php if (!empty($sections['section_14']['posts'])) : ?>
                <div class="PhotoSliderSec">
                  <div class="row">
                    <?php foreach (array_slice($sections['section_14']['posts'], 0, 4) as $post) : setup_postdata($post); ?>
                      <div class="col-lg-3 col-6 d-flex">
                        <div class="DPhotoSliderList align-self-stretch">
                          <a href="<?php the_permalink(); ?>">
                            <div class="DImgBlock">
                              <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                              <div class="video-icon"><i class="fas fa-image"></i></div>
                            </div>
                            <div class="Desc">
                              <h2 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
      <?php if(isset($sections['section_15']) || isset($sections['section_16'])) : ?>
        <section class="container MT-60">
          <div class="row">
            <?php if(isset($sections['section_15'])) : ?>
              <div class="col-lg-6 col-12 Politics">
                <?php if ($sections['section_15']['url']) { ?>
                  <a href="<?php echo $sections['section_15']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_15']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_15']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_15']['posts'])) : ?>
                  <div class="row">
                    <?php setup_postdata($post = $sections['section_15']['posts'][0]); ?>
                      <div class="col-lg-6 col-12">
                        <div class="DCategory4Top">
                          <a href="<?php the_permalink(); ?>">
                            <div class="DImgZoomBlock ">
                              <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"> </picture>
                            </div>
                            <div class="Desc">
                              <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php wp_reset_postdata(); ?>
                    <?php if (!empty($sections['section_15']['posts']) && count($sections['section_15']['posts']) > 1) : ?>
                      <div class="col-lg-6 col-12">
                        <?php foreach (array_slice($sections['section_15']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                          <div class="DCategory5NewsList">
                            <a href="<?php the_permalink(); ?>">
                              <div class="row">
                                <div class="col-lg-7 col-7">
                                  <div class="Desc">
                                    <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                  </div>
                                </div>
                                <div class="col-lg-5 col-5">
                                  <div class="DImgZoomBlock ">
                                    <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if(isset($sections['section_16'])) : ?>
              <div class="col-lg-6 col-12 Capital">
                <?php if ($sections['section_16']['url']) { ?>
                  <a href="<?php echo $sections['section_16']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_16']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_16']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_16']['posts'])) : ?>
                  <?php setup_postdata($post = $sections['section_16']['posts'][0]); ?>
                    <div class="WritersSectionTop">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-6 col-12">
                            <div class="DImgZoomBlock ">
                              <picture> <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100"></picture>
                            </div>
                          </div>
                          <div class="col-lg-6 col-12">
                            <div class="Desc">
                              <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (!empty($sections['section_16']['posts']) && count($sections['section_16']['posts']) > 1) : ?>
                    <div class="row">
                      <?php foreach (array_slice($sections['section_16']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                        <div class="col-lg-6 col-12 d-flex">
                          <div class="DCategory6NewsList MT-25">
                            <a href="<?php the_permalink(); ?>" class="align-self-stretch">
                              <div class="row">
                                <div class="col-lg-5 col-5">
                                  <div class="DImgZoomBlock ">
                                    <picture>
                                      <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>"
                                        alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100">
                                    </picture>
                                  </div>
                                </div>
                                <div class="col-lg-7 col-7">
                                  <div class="Desc">
                                    <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        </div>
                      <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
      <div class="container">
        <div class="DLifestyleSec">
          <div class="row">
            <?php if(isset($sections['section_17'])) : ?>
              <div class="col-lg-9 border-right-inner1">
                <div class="SectionTitle">
                  <?php if ($sections['section_17']['url']) { ?>
                    <a href="<?php echo $sections['section_17']['url']; ?>">
                      <h3><?php echo $sections['section_17']['name']; ?></h3>
                    </a>
                  <?php } else { ?>
                    <h3><?php echo $sections['section_17']['name']; ?></h3>
                  <?php } ?>
                </div>
                <?php if (!empty($sections['section_17']['posts'])) : ?>
                <div class="DLifestyle">
                  <div class="row">
                    <?php setup_postdata($post = $sections['section_17']['posts'][0]); ?>
                      <div class="col-md-9">
                        <div class="DLifestyleTop1">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-md-8 ">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                </picture>
                              </div>
                              <div class="col-md-4">
                                <div class="Desc">
                                  <h3 class="Title BGTitle FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                  <div class="Brief">
                                    <p><?php the_excerpt(); ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php wp_reset_postdata(); ?>
                    <?php if (!empty($sections['section_17']['posts']) && count($sections['section_17']['posts']) > 1) : ?>
                      <?php setup_postdata($post = $sections['section_17']['posts'][1]); ?>
                        <div class="col-md-3 d-flex">
                          <div class="DLifestyleTop2 align-self-stretch">
                            <a href="<?php the_permalink(); ?>-">
                              <div class="">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                </picture>
                              </div>
                              <div class="Desc">
                                <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                              </div>
                            </a>
                          </div>
                        </div>
                      <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                    <?php if (!empty($sections['section_17']['posts']) && count($sections['section_17']['posts']) > 2) : ?>
                      <?php foreach (array_slice($sections['section_17']['posts'], 2, 4) as $post) : setup_postdata($post); ?>
                        <div class="col-md-3 d-flex col-6">
                          <div class="DLifestyleTop2 align-self-stretch">
                            <a href="<?php the_permalink(); ?>">
                              <div class="">
                                <picture>
                                  <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid">
                                </picture>
                              </div>
                              <div class="Desc">
                                <h3 class="Title"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                              </div>
                            </a>
                          </div>
                        </div>
                      <?php endforeach; wp_reset_postdata(); ?>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if(isset($prayer_times)) { ?>
              <div class="col-lg-3">
                <div class="DPrayer skeleton">
                  <div class="DPrayersBanner">
                    <a href="#"><img class="img-fluid skeleton img100"
                      src="<?php echo get_template_directory_uri(); ?>/frontend/media/common/namaz.png" alt="Namaz"
                      title="Namaz"></a>
                  </div>
                  <div class="DPrayersTime table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td><?php echo ( $site_language === 'bn' ) ? 'ফজর' : 'Fajr'; ?></td>
                          <td><?php echo theme_translate(date('g:i', strtotime($prayer_times['Fajr']))); ?> <?php echo get_time_of_day_label(strtotime($prayer_times['Fajr'])); ?></td>
                        </tr>
                        <tr>
                          <td><?php echo ( $site_language === 'bn' ) ? 'যোহর' : 'Dhuhr'; ?></td>
                          <td><?php echo theme_translate(date('g:i', strtotime($prayer_times['Dhuhr']))); ?> <?php echo get_time_of_day_label(strtotime($prayer_times['Dhuhr'])); ?> 
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo ( $site_language === 'bn' ) ? 'আছর' : 'Asr'; ?></td>
                          <td><?php echo theme_translate(date('g:i', strtotime($prayer_times['Asr']))); ?> <?php echo get_time_of_day_label(strtotime($prayer_times['Asr'])); ?>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo ( $site_language === 'bn' ) ? 'মাগরিব' : 'Maghrib'; ?></td>
                          <td><?php echo theme_translate(date('g:i', strtotime($prayer_times['Maghrib']))); ?> <?php echo get_time_of_day_label(strtotime($prayer_times['Maghrib'])); ?>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo ( $site_language === 'bn' ) ? 'ইশা' : 'Isha'; ?></td>
                          <td><?php echo theme_translate(date('g:i', strtotime($prayer_times['Isha']))); ?> <?php echo get_time_of_day_label(strtotime($prayer_times['Isha'])); ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <p><?php echo ( $site_language === 'bn' ) ? 'ঢাকা' : 'Dhaka'; ?>, <?php echo wp_date( 'l, j F Y', current_time('timestamp') ) ?></p>
                  </div>
                </div>
                <?php if (is_active_sidebar('home-sidebar-11')) : ?>
                  <div class="DRightSideAdd mt-3">
                    <?php dynamic_sidebar('home-sidebar-11'); ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php if(isset($sections['section_18']) || isset($sections['section_19'])) : ?>
        <section class="container MT-60">
          <div class="row">
            <?php if(isset($sections['section_18'])) : ?>
              <div class="col-lg-6 col-12 Politics">
                <?php if ($sections['section_18']['url']) { ?>
                  <a href="<?php echo $sections['section_18']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_18']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_18']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_18']['posts'])) : ?>
                <div class="row">
                  <?php setup_postdata($post = $sections['section_18']['posts'][0]); ?>
                    <div class="col-lg-6 col-12">
                      <div class="DCategory4Top">
                        <a href="<?php the_permalink(); ?>">
                          <div class="DImgZoomBlock ">
                            <picture>
                              <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
                                class="img-fluid img100">
                            </picture>
                          </div>
                          <div class="Desc">
                            <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php wp_reset_postdata(); ?>
                  <?php if (!empty($sections['section_18']['posts']) && count($sections['section_18']['posts']) > 1) : ?>
                    <div class="col-lg-6 col-12">
                      <?php foreach (array_slice($sections['section_18']['posts'], 1, 3) as $post) : setup_postdata($post); ?>
                        <div class="DCategory5NewsList">
                          <a href="<?php the_permalink(); ?>">
                            <div class="row">
                              <div class="col-lg-7 col-7">
                                <div class="Desc">
                                  <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                </div>
                              </div>
                              <div class="col-lg-5 col-5">
                                <div class="DImgZoomBlock ">
                                  <picture>
                                    <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>"
                                      title="<?php the_title(); ?>" class="img-fluid img100">
                                  </picture>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if(isset($sections['section_19'])) : ?>
              <div class="col-lg-6 col-12 Politics">
                <?php if ($sections['section_19']['url']) { ?>
                  <a href="<?php echo $sections['section_19']['url']; ?>">
                    <h2 class="SectionName"><?php echo $sections['section_19']['name']; ?></h2>
                  </a>
                <?php } else { ?>
                  <h2 class="SectionName"><?php echo $sections['section_19']['name']; ?></h2>
                <?php } ?>
                <?php if (!empty($sections['section_19']['posts'])) : ?>
                  <div class="row">
                    <?php setup_postdata($post = $sections['section_19']['posts'][0]); ?>
                      <div class="col-lg-6 col-12">
                        <div class="DCategory4Top">
                          <a href="<?php the_permalink(); ?>">
                            <div class="DImgZoomBlock ">
                              <picture>
                                <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_medium_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
                                  class="img-fluid img100">
                              </picture>
                            </div>
                            <div class="Desc">
                              <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php wp_reset_postdata(); ?>
                    <?php if (!empty($sections['section_19']['posts']) && count($sections['section_19']['posts']) > 1) : ?>
                      <div class="col-lg-6 col-12">
                        <?php foreach (array_slice($sections['section_19']['posts'], 1, 3) as $post) : setup_postdata($post); ?>
                          <div class="DCategory5NewsList">
                            <a href="<?php the_permalink(); ?>">
                              <div class="row">
                                <div class="col-lg-7 col-7">
                                  <div class="Desc">
                                    <h2 class="Title FW700"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h2>
                                  </div>
                                </div>
                                <div class="col-lg-5 col-5">
                                  <div class="DImgZoomBlock ">
                                    <picture>
                                      <img data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>"
                                        title="<?php the_title(); ?>" class="img-fluid img100">
                                    </picture>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
      <?php if(isset($sections['section_20'])) : ?>
        <section class="video-area">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="SectionTitle mb-3 mt-4">
                  <?php if ($sections['section_20']['url']) { ?>
                    <a href="<?php echo $sections['section_20']['url']; ?>">
                      <h3><?php echo $sections['section_20']['name']; ?></h3>
                    </a>
                  <?php } else { ?>
                    <h3><?php echo $sections['section_20']['name']; ?></h3>
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php if (!empty($sections['section_20']['posts'])) : ?>
              <div class="row">
                <?php setup_postdata($post = $sections['section_20']['posts'][0]); ?>
                  <div class="col-lg-6">
                    <div class="video-big">
                      <a href="<?php the_permalink(); ?>">
                        <div class="video-img-wrap">
                          <div class="video-overlay"></div>
                          <picture>
                            <img class="img-fluid w-100 h-100" data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-2'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                          </picture>
                        </div>
                        <div class="Desc">
                          <h3 class="Title2"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                        </div>
                        <div class="video-icon"><i class="fas fa-image"></i></div>
                      </a>
                    </div>
                  </div>
                <?php wp_reset_postdata(); ?>
                <?php if (!empty($sections['section_20']['posts']) && count($sections['section_20']['posts']) > 1) : ?>
                  <div class="col-lg-6">
                    <div class="video-middel">
                      <div class="row gx-2">
                        <?php foreach (array_slice($sections['section_20']['posts'], 1, 4) as $post) : setup_postdata($post); ?>
                          <div class="col-lg-6">
                            <div class="video-middel-items">
                              <a href="<?php the_permalink(); ?>">
                                <div class="video-img-wrap">
                                  <div class="video-overlay"></div>
                                  <picture>
                                    <img class="img-fluid w-100 h-100" data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" src="<?php echo $thumbnail_small_placeholder_image; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                                  </picture>
                                </div>
                                <div class="Desc">
                                  <h3 class="Title2"><?php if ( get_field('shoulder') ) { ?><span class="subHeading"><?php echo get_field('shoulder'); ?> / </span><?php } the_title(); ?></h3>
                                </div>
                                <div class="video-icon"><i class="fas fa-image"></i></div>
                              </a>
                            </div>
                          </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
      <?php if (is_active_sidebar('home-sticky-footer-desktop') || is_active_sidebar('home-sticky-footer-mobile')) : ?>
        <div id="T4Tutorials_UP11" class="T4Tutorials">
          <div class="T4Tutorials_UP">
            <a class="Exit" id="T4Tutorials_UP11_Close">×</a>
            <div class="Main_Main_Content">
              <?php if (is_active_sidebar('home-sticky-footer-desktop')) : ?>
                <!-- Desktop Ad -->
                <div class="AdvertClass MobileHide text-center">
                  <?php dynamic_sidebar('home-sticky-footer-desktop'); ?>
                </div>
              <?php endif; ?>
              <?php if (is_active_sidebar('home-sticky-footer-mobile')) : ?>
                <!-- Mobile Ad -->
                <div class="AdvertClass MobileShow text-center">
                  <?php dynamic_sidebar('home-sticky-footer-mobile'); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </main>
    <!-- 14 Jan 26: Code is now more cleaner -->
    <!--Modal Code Welcome Advert-->
    <?php get_footer(); ?>
    <script type="text/javascript">
      // Footer Sticky Ads
      jQuery(document).ready(function () {
        // Close button functionality
        jQuery("#T4Tutorials_UP11_Close").click(function () {
          jQuery("#T4Tutorials_UP11").hide();
        });      
      });
    </script>
    <script type="text/javascript">
      $(window).load(function () {
        $("main img").each(function (index) {
          $(this).attr("src", $(this).attr("data-src"));
        });
      });
    </script>
  </body>
</html>
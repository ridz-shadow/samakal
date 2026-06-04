<?php 
  /* Template Name: Video */
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_name = get_bloginfo('name');
  $site_url = site_url();
  $url = esc_url( get_permalink() );
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
  $categories = get_terms( array(
    'taxonomy'   => 'video-category',
    'parent'     => 0,
    'hide_empty' => false,
  ) ) ?: array();

  $query = new WP_Query([
    'post_type'      => 'video-collection',
    'title'          => 'Lead',
    'posts_per_page' => 1,
  ]);

  $lead = get_term_by('slug', 'lead', 'video-collection');

  if ($lead && !is_wp_error($lead)) {
    $lead_posts = get_field('items', $lead);
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
          <a href="<?php echo $url; ?>">
            <h1><?php echo $page_title; ?></h1>
          </a>
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
      <?php if ( ( $lead_posts && count ( $lead_posts ) > 0 ) || is_active_sidebar('video-right-side-1') || is_active_sidebar('video-right-side-2') || is_active_sidebar('video-leaderboard') ) { ?>
        <div class="DVideoGalleryTopSec">
          <?php if ( ( $lead_posts && count ( $lead_posts ) > 0 ) || is_active_sidebar('video-right-side-1') || is_active_sidebar('video-right-side-2') ) { ?>
            <div class="row">
              <?php if ( $lead_posts && count ( $lead_posts ) > 0 ) { ?>
                <div class="col-lg-9">
                  <div class="row">
                    <?php foreach (array_slice($lead_posts, 0, 1) as $post) { ?>
                      <div class="col-lg-8 col-12 d-flex">
                        <div class="DVideoGalleryTop align-self-stretch">
                          <a href="<?php echo get_permalink($post); ?>">
                            <div class="videoIcon">
                              <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id', $post); ?>/maxresdefault.jpg" class="img-fluid img100" alt="" title=""></picture>
                              <span class="play-btn-big"><i class="fas fa-play"></i></span>
                            </div>
                            <div class="Desc">
                              <div class="NewsTitle">
                                <h3 class="Title"><?php echo get_the_title($post); ?></h3>
                                <div class="Brief">
                                  <p><?php echo get_the_excerpt($post); ?></p>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php } ?>
                    <?php if ( count($lead_posts) > 1 ) { ?>
                      <div class="col-lg-4 col-12 d-flex">
                        <div class="DVideoGTop2ListSec align-self-stretch">
                          <?php foreach (array_slice($lead_posts, 1, 2) as $post) { ?>
                            <div class="DVideoGTop2List align-self-stretch">
                              <a href="<?php echo get_permalink($post); ?>">
                                <div class="row">
                                  <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                                    <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id', $post); ?>/maxresdefault.jpg" class="img-fluid img100" alt="কিশোরগঞ্জে ডুবে থাকা ধানের জন্য কৃষকের লড়াই" title="কিশোরগঞ্জে ডুবে থাকা ধানের জন্য কৃষকের লড়াই"></picture>
                                    <span class="play-btn-big"><i class="fas fa-play"></i></span>
                                  </div>
                                  <div class="col-lg-12 col-sm-8 col-7">
                                    <div class="Desc">
                                      <h3 class="Title"><?php echo get_the_title($post); ?></h3>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <?php if (is_active_sidebar('video-right-side-1') || is_active_sidebar('video-right-side-2')) { ?>
              <div class="col-lg-3">
                <?php if (is_active_sidebar('video-right-side-1')) : ?>
                  <div class="DRightSideAdd mt-3">
                    <?php dynamic_sidebar('video-right-side-1'); ?>
                  </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('video-right-side-2')) : ?>
                  <div class="DRightSideAdd mt-3">
                    <?php dynamic_sidebar('video-right-side-2'); ?>
                  </div>
                <?php endif; ?>
              </div>
              <?php } ?>
            </div>
          <?php } ?>
          <?php if (is_active_sidebar('video-leaderboard')) : ?>
            <div class="row">
              <div class="col-12">
                <div class="DHomeAdd970X90 d-flex justify-content-center my-4">
                  <?php dynamic_sidebar('video-leaderboard'); ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
        </div>
      <?php } ?>
      <?php if ( $categories && ! is_wp_error( $categories ) ) { foreach ( $categories as $cat ) { ?>
        <div class="DVideoGalleryCatSec">
          <div class="row">
            <div class="col-lg-12">
              <div class="SectionTitle mb-3 mt-4">
                <a href="<?php echo get_term_link($cat); ?>">
                  <h3><?php echo $cat->name; ?> <i class="fa-solid fa-angles-right"></i></h3>
                </a>
              </div>
            </div>
          </div>
          <?php 
            $args = [
              'post_type'      => 'video',
              'posts_per_page' => 7,
              'tax_query'      => [
                [
                  'taxonomy' => 'video-category',
                  'field'    => 'term_id',
                  'terms'    => $cat->term_id,
                ]
              ],
            ];
          
            $posts = new WP_Query($args);            
          ?>
          <?php if ($posts->have_posts()) : ?>
            <div class="row">
              <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                <?php if ($posts->current_post === 0) { ?>
                  <div class="col-lg-6 d-flex">
                    <div class="DVideoCatTop align-self-stretch">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-8 videoIcon">
                            <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg" class="img-fluid img100" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"></picture>
                            <span class="play-btn-big"><i class="fas fa-play"></i></span>
                          </div>
                          <div class="col-lg-4">
                            <div class="Desc">
                              <h3 class="Title"><?php the_title(); ?></h3>
                              <div class="Brief">
                                <p><?php the_excerpt(); ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="col-lg-3 d-flex">
                    <div class="DVideoCatListTop2 align-self-stretch">
                      <a href="<?php the_permalink(); ?>">
                        <div class="row">
                          <div class="col-lg-12 col-sm-4 col-5 videoIcon">
                            <picture><img src="https://img.youtube.com/vi/<?php echo get_field('youtube_video_id'); ?>/maxresdefault.jpg" class="img-fluid img100" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"></picture>
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
          <?php endif; ?>
        </div>
      <?php } } ?>
    </main>
    <?php get_footer(); ?>
  </body>
</html>
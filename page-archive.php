<?php 
  /* Template Name: Archive */
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
  $categories = get_categories( array(
    'parent'     => 0,
    'hide_empty' => false,
  ) ) ?: array();
  $paged = max(1, intval($_GET['Page'] ?? 1));
  $from_date = $_GET['from_date'] ?? '';
  $to_date   = $_GET['to_date'] ?? '';
  $category  = $_GET['Category'] ?? '';

  $args = [
    'post_type'      => 'post',
    'posts_per_page' => 20,
    'paged'          => $paged,
  ];

  if (!empty($category) && is_numeric($category) && intval($category) > 0) {
    $args['cat'] = intval($category);
  }

  $date_query = [];

  if (!empty($from_date) && strtotime($from_date)) {
      $date_query['after'] = date('Y-m-d', strtotime($from_date));
  }

  if (!empty($to_date) && strtotime($to_date)) {
      $date_query['before'] = date('Y-m-d', strtotime($to_date));
  }

  if (!empty($date_query)) {
      $date_query['inclusive'] = true;
      $args['date_query'] = [$date_query];
  }

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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
      .pagePagination .page-item.active .page-link {
        background: yellow;
        color: #000;
        margin: 0 2px;
      }

      .pagePagination .page-item .page-link {
        color: #000;
        margin: 0 2px;
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
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Archive",
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
        <div class="row">
          <div class="col-lg-12">
            <div class="heading-title mt-4 my-3">
              <a href="<?php echo $url; ?>">
                <h1><?php echo $page_title; ?></h1>
              </a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="DArchivesSec">
              <form name="frmArchives" action="" method="GET" class="form">
                <div class="form-group clearfix">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?php echo $from_date; ?>" id="from_date" name="from_date"
                        placeholder="<?php echo ( $site_language === 'bn' ) ? 'তারিখ হতে' : 'From date'; ?>:" autocomplete="off">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" id="to_date" value="<?php echo $to_date; ?>" name="to_date" class="form-control"
                        placeholder="<?php echo ( $site_language === 'bn' ) ? 'তারিখ পর্যন্ত' : 'To date'; ?>:" autocomplete="off">
                    </div>
                    <div class="col-sm-4">
                      <select id="CategoryID" name="Category" class="form-control cboCatName">
                        <option value=""><?php echo ( $site_language === 'bn' ) ? 'সব ক্যাটাগরি' : 'All categories'; ?></option>
                        <?php if ( $categories && ! is_wp_error( $categories ) ) { foreach ( $categories as $cat ) { ?>
                          <option value="<?php echo $cat->term_id; ?>"<?php if($category == $cat->term_id) { ?> selected<?php } ?>><?php echo $cat->name; ?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 offset-lg-4 col-12">
                    <div id="btnDiv" class="d-grid gap-2 mt-4 mb-4">
                      <button id="archive_search" type="submit" class="btn btn-danger btn-lg btn-block ButtonBG"><?php echo ( $site_language === 'bn' ) ? 'খুঁজুন' : 'Search'; ?></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php if ($posts->have_posts()) : ?>
        <div class="DArchivePageSec">
          <div class="row" id="data-wrapper">
            <?php while ($posts->have_posts()) : $posts->the_post(); ?>
              <div class="col-lg-6">
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
                        <div class="img-box">
                          <picture>
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-1'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid img100 rounded">
                          </picture>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
        <?php 
          function build_archive_url($page, $url) {
            $params = $_GET;
            $params = array_filter($params, function ($value) {
                return $value !== null && $value !== '';
            });
            $params['Page'] = $page;
            return esc_url(add_query_arg($params, $url));
          }
          $total = $posts->max_num_pages;
          
          function build_pagination($total, $paged) {
            $pages = [];
        
            if ($total <= 0) return [];
        
            if ($total <= 10) {
              return range(1, $total);
            }
        
            $pages[] = 1;
            $pages[] = 2;
        
            if ($paged <= 5) {
              $pages = array_merge($pages, range(3, 8));
              $pages[] = '...';
              $pages[] = $total - 1;
              $pages[] = $total;
              return $pages;
            }
        
            if ($paged >= $total - 4) {
              $pages[] = '...';
              $pages = array_merge($pages, range($total - 7, $total));
              return $pages;
            }
        
            $pages[] = '...';
        
            for ($i = $paged - 2; $i <= $paged + 2; $i++) {
              if ($i > 2 && $i < $total - 1) {
                $pages[] = $i;
              }
            }
        
            $pages[] = '...';
            $pages[] = $total - 1;
            $pages[] = $total;
        
            return $pages;
          }

          $pagination = build_pagination($total, $paged);
        ?>
        <div class="row">
          <div class="col-lg-12 pagePagination mt-5 mb-5">
            <div class="d-flex justify-content-center">
              <nav>
                <ul class="pagination">
                  <?php if ( $paged > 1 ) { ?>
                    <li class="page-item">
                      <a class="page-link" href="<?php echo build_archive_url($paged - 1, $url); ?>" rel="prev" aria-label="« Previous">‹</a>
                    </li>
                  <?php } else { ?>
                    <li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Previous">
                      <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                  <?php } ?>
                  <?php foreach ($pagination as $p) {
                    if ($p === '...') { ?>
                      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                    <?php } else if ($p == $paged) { ?>
                      <li class="page-item active" aria-current="page"><span class="page-link"><?php echo $p; ?></span></li>
                    <?php } else { ?>
                    <li class="page-item"><a class="page-link" href="<?php echo build_archive_url($p, $url); ?>"><?php echo $p; ?></a></li>
                    <?php }
                  } ?>
                  <?php if ( $paged < $total ) { ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo build_archive_url($paged + 1, $url); ?>" rel="next" aria-label="Next &raquo;">&rsaquo;</a>
                  </li>
                  <?php } else { ?>
                    <li class="page-item disabled" aria-disabled="true" aria-label="Next »">
                      <span class="page-link" aria-hidden="true">›</span>
                  </li>
                  <?php } ?>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        <?php wp_reset_postdata(); endif; ?>
      </div>
    </main>
    <?php get_footer(); ?>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" type="text/javascript"></script>
    <script type="text/javascript">
      $('#from_date').datepicker({
      	uiLibrary: 'bootstrap4',
      	dateFormat: 'yy-mm-dd',
      	maxDate: '0'
      
      });
      $('#to_date').datepicker({
      	uiLibrary: 'bootstrap4',
      	dateFormat: 'yy-mm-dd',
      	maxDate: '0'
      });
    </script>
  </body>
</html>
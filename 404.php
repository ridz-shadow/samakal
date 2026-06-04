<?php
  $site_language = get_theme_mod( 'samakal_site_language', 'bn' );
  $site_url = site_url();
  $previous_url = wp_get_referer();
  if (!$previous_url) {
    $previous_url = home_url();
  }
  $homepage_id = get_option('page_on_front');
  $homepage_meta_title = get_field('meta_title', $homepage_id);
?>
<!doctype html>
<html lang="<?php echo $site_language; ?>">
  <head>
    <?php include get_template_directory() . '/meta.php'; ?>
    <?php if ( $homepage_meta_title ) { ?>
      <title><?php echo $homepage_meta_title; ?></title>
    <?php } ?>
    <meta http-equiv="refresh" content="">
  </head>
  <body>
    <?php get_header(); ?>
    <main>
      <div class="container">
        <div class="row my-3">
          <div class="ErrorBody col-lg-8 offset-lg-2">
            <div class="Errors"><?php echo ( $site_language === 'bn' ) ? '৪' : '4'; ?><span><?php echo ( $site_language === 'bn' ) ? '০' : '0'; ?></span><?php echo ( $site_language === 'bn' ) ? '৪' : '4'; ?></div>
            <h1 class="ErrorHeader"><?php echo ( $site_language === 'bn' ) ? 'পাওয়া যায়নি' : 'Not found'; ?> </h1>
            <div class="row mt-4">
              <div class="col-6">
                <div class="read-more-btn d-flex justify-content-start">
                  <a href="<?php echo $previous_url; ?>"><?php echo ( $site_language === 'bn' ) ? 'আগের পেজে ফিরে যান' : 'Go back to previous page'; ?></a>
                </div>
              </div>
              <div class="col-6">
                <div class="read-more-btn d-flex justify-content-end">
                  <a href="<?php echo $site_url; ?>"><?php echo ( $site_language === 'bn' ) ? 'প্রচ্ছদে ফিরে যান' : 'Go back to home'; ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php get_footer(); ?>
    <script type="text/javascript">
      $(function () {
        $("#btnIncrease").click(function () {
          $(".dNewsDesc")
            .children()
            .each(function () {
              var size = parseInt($(this).css("font-size"));
              size = size + 1 + "px";
              $(this).css({ "font-size": size });
            });
        });
      });
      $(function () {
        $("#btnOriginal").click(function () {
          $(".dNewsDesc")
            .children()
            .each(function () {
              $(this).css({ "font-size": "20px" });
            });
        });
      });
      $(function () {
        $("#btnDecrease").click(function () {
          $(".dNewsDesc")
            .children()
            .each(function () {
              var size = parseInt($(this).css("font-size"));
              size = size - 1 + "px";
              $(this).css({ "font-size": size });
            });
        });
      });

      $(".DContentAdd").insertAfter($("#contentDetails p:nth-child(2)"));
      $(".DContentAdd2").insertAfter($("#contentDetails p:nth-child(5)"));
    </script>
  </body>
</html>
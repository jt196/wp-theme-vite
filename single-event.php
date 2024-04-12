<?php 

    get_header();

    while(have_posts()){
        the_post(); ?>
        
        <div>
      <div style="background-image: url(<?php echo get_theme_file_uri('/assets/img/ocean.jpg') ?>"></div>
      <div>
        <h1><?php the_title(); ?></h1>
        <div>
          <p>TODO subheader.</p>
        </div>
      </div>
    </div>
    <div>
    <div>
        <p>
        <a href="<?php echo get_post_type_archive_link('event') ?>">
        <i aria-hidden="true"></i> Events </a>
        <span><?php the_title(); ?></span>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content()    ?>
    </div>
    </div>

        <?php
    }  

    get_footer();
?>
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
        <a href="<?php echo site_url('/blog') ?>">
        <i aria-hidden="true"></i> Blog Home </a>
        <span>Posted by <?php the_author_posts_link(); ?> on <?php the_time('j.n.y'); ?> in <?php echo get_the_category_list(', '); ?></span>
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
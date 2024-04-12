<?php 

    get_header(); ?>

    <div>
      <div style="background-image: url(<?php echo get_theme_file_uri('/assets/img/ocean.jpg') ?>">
            <div>
                <h1>
                    All Programs
                </h1>
                <div>
                    <p>See all programs</p>
                </div>
            </div>
        </div>

        <div class="container">
        <ul>
            <?php
            while(have_posts()) {
                the_post(); ?> 
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
                <?php }
            echo paginate_links();
            ?>
        </ul>
        </div>
    
    <?php get_footer();
?>
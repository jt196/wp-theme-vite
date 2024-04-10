<?php get_header() ?>

<?php 

    get_header(); ?>

    <div>
      <div style="background-image: url(<?php echo get_theme_file_uri('/assets/img/ocean.jpg') ?>">
            <div>
                <h1>Welcome to the blog!</h1>
                    <div">
                        <p>Keep up with the news, babes.</p>
                    </div>
            </div>
        </div>

        <div class="container container--narrow page-section">
        
        <?php
            while(have_posts()) {
                the_post(); ?>
                <div>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="metabox">
                        <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('j.n.y'); ?> in <?php echo get_the_category_list(', '); ?></p>
                    </div>
                    <div class="generic-content">
                        <?php the_excerpt(); ?>
                        <button>
                            <a href="<?php the_permalink(); ?>">Continue reading &raquo;</a>
                        </button>
                    </div>
                </div>
            <?php }
            echo paginate_links();
        ?>
        </div>
    
    <?php get_footer();
?>

<?php get_footer() ?>
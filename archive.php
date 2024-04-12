<?php 

    get_header(); 
    pageBanner(array(
        'title' => get_the_archive_title(),
        'subtitle' => get_the_archive_description()
    ))
    
    ?>

    <div>
        <div class="container">

        <?php
            while(have_posts()) {
                the_post(); ?>
                <div>
                    <h2>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="metabox">
                        <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('j.n.y'); ?> in <?php echo get_the_category_list(', '); ?></p>
                    </div>
                    <div class="generic-content">
                        <?php the_excerpt(); ?>
                        <button>
                            <a href="<?php the_permalink(); ?>">Continue reading &raquo;</a>
                        </button>
                    </div>
            <?php }
            echo paginate_links();
        ?>
        </div>
    
    <?php get_footer();
?>
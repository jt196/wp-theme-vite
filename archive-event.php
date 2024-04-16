<?php 

    get_header(); 
    pageBanner(array(
        'title' => 'All Events',
        'subtitle' => 'See what is going on in our world'
    ))?>

    <div>
        <div class="container">
        
        <?php
            while(have_posts()) {
                the_post(); 
                get_template_part('template-parts/content-event');
                ?>
                <div>
                    <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p><?php echo wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink(); ?>">Read more</a></p>
                </div>
            <?php }
            echo paginate_links();
        ?>
        <button>
            <a href="<?php echo site_url('/past-events') ?>">Past Events</a>
        </button>
        </div>
    
    <?php get_footer();
?>
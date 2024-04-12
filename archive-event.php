<?php 

    get_header(); ?>

    <div>
      <div style="background-image: url(<?php echo get_theme_file_uri('/assets/img/ocean.jpg') ?>">
            <div>
                <h1>
                    All Events
                </h1>
                <div>
                    <p>See all events</p>
                </div>
            </div>
        </div>

        <div class="container">
        
        <?php
            while(have_posts()) {
                the_post(); 
                $eventDate = new DateTime(get_field('event_date'));
                $eventMonth = $eventDate->format('M');
                $eventDay = $eventDate->format('d');
                ?>
                <div>
                    <a href="<?php the_permalink(); ?>">
                    <span><?php echo $eventMonth; ?></span>
                    <span><?php echo $eventDay; ?></span>
                    </a>
                </div>      
                <div>
                    <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p><?php echo wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink(); ?>">Read more</a></p>
                </div>
            <?php }
            echo paginate_links();
        ?>
        <a href="<?php echo site_url('/past-events') ?>">Past Events</a>
        </div>
    
    <?php get_footer();
?>
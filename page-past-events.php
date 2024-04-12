<?php 

    get_header(); ?>

    <div>
      <div style="background-image: url(<?php echo get_theme_file_uri('/assets/img/ocean.jpg') ?>">
            <div>
                <h1>
                    Past Events
                </h1>
                <div>
                    <p>See all past events</p>
                </div>
            </div>
        </div>

        <div class="container">
        
        <?php
            $today = date('Ymd');
            $pastEvents = new WP_Query(array(
                'paged' => get_query_var('paged', 1),
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                    'key' => 'event_date',
                    'compare' => '<',
                    'value' => $today,
                    'type' => 'numeric'
                    )
                )
                ));
            while($pastEvents->have_posts()) {
                $pastEvents->the_post(); 
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
            echo paginate_links(array(
                'total' => $pastEvents->max_num_pages
            ));
        ?>
        </div>
    
    <?php get_footer();
?>
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
        <a href="<?php echo get_post_type_archive_link('program') ?>">
        <i aria-hidden="true"></i> All Programs > </a>
        <span><?php the_title(); ?></span>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content()    ?>
    </div>
    <?php
        $today = date('Ymd');
        $relatedProfessors = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => 'professor',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_programs',
              'compare' => 'LIKE',
              // Wordpress saves the related programs as an array of IDs
              // so to search through the array we need to search for numbers wrapped in quotes
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));
        // var_dump($relatedProfessors->posts); 
        if ($relatedProfessors->have_posts()) {
          // print_r($relatedProfessors);
          echo '<hr>';
          echo '<h2>' . get_the_title() . ' Professors</h2>';
          while($relatedProfessors->have_posts()) {
            $relatedProfessors->the_post(); ?>
            <div class="grid">
              <div>
                <?php the_post_thumbnail();?>
              </div>
              <div>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </div>
            </div>
            </li>
          <?php }
          }
        wp_reset_postdata();
        $homepageEvents = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => 'event',
          'meta_key' => 'event_date',
          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            ),
            array(
              'key' => 'related_programs',
              'compare' => 'LIKE',
              // Wordpress saves the related programs as an array of IDs
              // so to search through the array we need to search for numbers wrapped in quotes
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));
        // var_dump($homepageEvents->posts); 
        if ($homepageEvents->have_posts()) {
          // print_r($homepageEvents);
          echo '<hr>';
          echo '<h2>Upcoming ' . get_the_title() . ' Events</h2>';
          while($homepageEvents->have_posts()) {
            $homepageEvents->the_post(); ?>
            <div class="event-summary">
              <a class="event-summary__date t-center" href="#">
                <span class="event-summary__month"><?php
                  $eventDate = new DateTime(get_field('event_date'));
                  echo $eventDate->format('M')
                ?></span>
                <span class="event-summary__day"><?php echo $eventDate->format('d') ?></span>  
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p><?php if (has_excerpt()) {
                    echo get_the_excerpt();
                  } else {
                    echo wp_trim_words(get_the_content(), 18);
                    } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
              </div>
            </div>
          <?php }
          }
  
        ?>
  
      </div>
      
  
      
    <?php }
  
    get_footer();
  
  ?>
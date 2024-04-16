<?php 

    get_header();
    pageBanner(array(
        'title' => 'Programs',
        'subtitle' => 'Our programs for you to choose from'
    ));

    while(have_posts()){
        the_post(); ?>
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
            $homepageEvents->the_post();
            get_template_part('template-parts/content-event'); ?>
          <?php }
          }
  
        ?>
  
      </div>
      
  
      
    <?php }
  
    get_footer();
  
  ?>
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

    <div class="generic-content">
        <?php the_content()    ?>
    </div>
    <?php
        $relatedPrograms = get_field('related_programs');
        if($relatedPrograms){
            echo '<hr>';
            echo '<h2>Subject(s)</h2>';
            echo '<ul>';
            foreach($relatedPrograms as $program){ ?>
        <li>
            <a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program) ?></a>
        </li>
        <?php }
        echo '</ul>';
        }
        ?>
    </div>

        <?php
    }  

    get_footer();
?>





 
<?php 

    get_header();
    pageBanner();

    while(have_posts()){
        the_post(); ?>
        
    <div>
        <p>
        <a href="<?php echo get_post_type_archive_link('event') ?>">
        <i aria-hidden="true"></i> All Events > </a>
        <span><?php the_title(); ?></span>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content()    ?>
    </div>
    <?php
        $relatedPrograms = get_field('related_programs');
        if($relatedPrograms){
            echo '<hr>';
            echo '<h2>Related Programs</h2>';
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





 
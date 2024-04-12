<?php 

    get_header(); 
    pageBanner(array(
        'title' => 'All Programs',
        'subtitle' => 'See all programs'
    ))?>

    <div>
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
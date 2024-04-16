<?php get_header(); ?>

<div class="align-centre frontpage-banner">
    <h1>Welcome!</h1>
    <h2>We think you&rsquo;ll like it here.</h2>
    <h3>Why don't you check out the major you're interested in?</h3>
    <button>Find Your Major</button>
</div>

<div class="grid">
  <div>
    <h2>Upcoming Events</h2>
    <?php
        $today = date('Ymd');
        $homepageEvents = new WP_Query(array(
          'posts_per_page' => 2,
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
            )
          )
        ));

        while($homepageEvents->have_posts()) {
          $homepageEvents->the_post(); 
          get_template_part('template-parts/content', 'event');
          ?>
        <?php } wp_reset_postdata();
        ?>
 
 <button>
  <a href="<?php echo get_post_type_archive_link('event') ?>">View All Events</a>
 </button>
  </div>
  <div>
    <h2>From Our Blogs</h2>
    <?php
        $homepagePosts = new WP_Query(array(
          'posts_per_page' => 2
        ));

        while($homepagePosts->have_posts()) {
          $homepagePosts->the_post(); ?>
          <div>
            <a href="<?php the_permalink(); ?>">
              <span><?php the_time('M'); ?></span>
              <span><?php the_time('d '); ?></span>
            </a>
            <div>
              <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php if(has_excerpt()){
                echo get_the_excerpt();
              } else {
                echo wp_trim_words(get_the_content(), 18);
              } ?> <a href="<?php the_permalink(); ?>">Read more</a></p>
            </div>
          </div> 
        <?php } wp_reset_postdata();
        ?>
  </div>
</div>
<div id="svelte-slider"></div>

<?php get_footer(); ?>
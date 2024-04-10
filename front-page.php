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

          <div>
            <a href="#">
              <span>Mar</span>
              <span>25</span>
            </a>
            <div>
              <h5><a href="#">Poetry in the 100</a></h5>
              <p>Bring poems you&rsquo;ve wrote to the 100 building this Tuesday for an open mic and snacks. <a href="#">Learn more</a></p>
            </div>
          </div>
          <div>
            <a href="#">
              <span>Apr</span>
              <span>02</span>
            </a>
            <div>
              <h5><a href="#">Quad Picnic Party</a></h5>
              <p>Live music, a taco truck and more can found in our third annual quad picnic day. <a href="#">Learn more</a></p>
            </div>
          </div>

          <p><a href="#">View All Events</a></p>
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
                  <h5><a href="#"><?php the_title(); ?></a></h5>
                  <p><?php echo wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink(); ?>">Read more</a></p>
                </div>
              </div> 
            <?php } wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
<div id="svelte-slider"></div>

<?php get_footer(); ?>
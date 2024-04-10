    </main>

    <footer class="container" >
        <div>
            <p>Currently in <strong><?php echo (IS_VITE_DEVELOPMENT) ? "development" : "production" ?></strong> mode.</p>
        </div>
        <div class="grid">
          <div>
            <h1>
              <a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
            </h1>
            <p><a href="<?php echo site_url('') ?>">555.555.5555</a></p>
          </div>

            <div>
              <h3>Explore</h3>
              <?php wp_nav_menu(array('theme_location' => 'footerLocation1')); ?> 
            </div>

            <div>
              <h3>Learn</h3>
              <?php wp_nav_menu(array('theme_location' => 'footerLocation2')); ?> 
            </div>
          </div>
    </footer>

<?php wp_footer() ?>
</body>
</html>
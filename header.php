<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head() ?>
</head>
<body>
<?php wp_body_open(); ?>

    <header class="container">
        <nav>
            <ul>
                <li><a href="<?php echo home_url() ?>">Logo</a></li>
            </ul>
            <ul>
            <li <?php if (is_page('about-us') or wp_get_post_parent_id(0) == 16) echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/about-us') ?>">About Us</a></li>
            <li <?php if (get_post_type() == 'program') echo 'class="current-menu-item"' ?>><a href="<?php echo get_post_type_archive_link('program') ?>">Programs</a></li>
            <li <?php if (get_post_type() == 'event' OR is_page('past-events')) echo 'class="current-menu-item"';  ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>
            <li <?php if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/blog'); ?>">Blog</a></li>

          <?php if(is_user_logged_in()) { ?>
            <li>
                <a href="<?php echo esc_url(site_url('/my-notes')); ?>">My Notes</a>
            </li>
            <button class="header-button">
                <a href="<?php echo wp_logout_url();  ?>">
                <span class="header-avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                <span>Log Out</span>
            </button>
            </a>
            <?php } else { ?>
              <button class="header-button">
                <a href="<?php echo wp_login_url(); ?>">Login</a>
              </button>
              <button class="header-button">
                <a href="<?php echo wp_registration_url(); ?>">Sign Up</a>
              </button>
             <?php } ?>
            <li><div id="svelte-search"></div></li>
            </ul>
            
        </nav>
    </header>


    <main class="container">


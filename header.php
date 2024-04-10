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
            <?php echo wp_nav_menu() ?>

        </nav>
    </header>

    
    <main class="container">
        <div id="svelte-root"></div>


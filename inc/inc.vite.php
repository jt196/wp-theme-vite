<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;  
    
/*
 * VITE & Tailwind JIT development
 * Inspired by https://github.com/andrefelipe/vite-php-setup
 *
 */

// dist subfolder - defined in vite.config.json
define('DIST_DEF', 'dist');

// defining some base urls and paths
define('DIST_URI', get_template_directory_uri() . '/' . DIST_DEF);
define('DIST_PATH', get_template_directory() . '/' . DIST_DEF);

// js enqueue settings
define('JS_DEPENDENCY', array()); // array('jquery') as example
define('JS_LOAD_IN_FOOTER', true); // load scripts in footer?

// deafult server address, port and entry point can be customized in vite.config.json
define('VITE_SERVER', 'http://localhost:3000');
define('VITE_ENTRY_POINT', '/main.js');

// enqueue hook
add_action( 'wp_enqueue_scripts', function() {
    
    if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === true) {

        // insert hmr into head for live reload
        function vite_head_module_hook() {
            echo '<script type="module" crossorigin src="' . VITE_SERVER . VITE_ENTRY_POINT . '"></script>';
        }
        add_action('wp_head', 'vite_head_module_hook');

    } else {

        // production version, 'npm run build' must be executed in order to generate assets
        // ----------

        // read manifest.json to figure out what to enqueue
        $manifest = json_decode( file_get_contents( DIST_PATH . '/manifest.json'), true );
        
        // is ok
        if (is_array($manifest)) {

            // get first key, by default is 'main.js' but it can change
            $manifest_key = array_keys($manifest);
            if (isset($manifest_key[0])) {

                // enqueue CSS files
                foreach(@$manifest[$manifest_key[0]]['css'] as $css_file) {
                    wp_enqueue_style( 'main', DIST_URI . '/' . $css_file );
                }

                // enqueue main JS file
                $js_file = @$manifest[$manifest_key[0]]['file'];
                if ( ! empty($js_file)) {
                    wp_enqueue_script( 'main', DIST_URI . '/' . $js_file, JS_DEPENDENCY, '', JS_LOAD_IN_FOOTER );
                }
            }
        }
    }
});


function university_features() {
    add_theme_support( 'title-tag' );
    add_theme_support('post-thumbnails');
    register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );
    register_nav_menu( 'footerLocation1', 'Footer Location 1' );
    register_nav_menu( 'footerLocation2', 'Footer Location 2' );
}

add_action( 'after_setup_theme', 'university_features' );

function university_adjust_queries( $query ) {
    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => date('Ymd'),
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries' );
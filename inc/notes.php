<?php

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/my-notes/', array(
        'methods' => 'GET',
        'callback' => 'get_current_user_notes'
        // Removed permission_callback for this example, ensure security as needed
    ));
});

/**
 * Retrieves the notes of the current user via the WordPress REST API.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return WP_REST_Response The response containing the user's notes.
 * @throws None
 */
function get_current_user_notes(WP_REST_Request $request) {
    if (!is_user_logged_in()) {
        // If user is not logged in, return a permission error
        return new WP_REST_Response('You must be logged in to view your notes.', 403);
    }

    $user_id = get_current_user_id(); // Get the ID of the current user

    // Log the user ID to the debug.log file
    error_log('Current User ID: ' . $user_id);

    $args = array(
        'post_type' => 'note', // Target the custom post type 'note'
        'author' => $user_id, // Filter notes by author
        'posts_per_page' => -1, // How many notes to retrieve
    );

    $query = new WP_Query($args);
    $notes = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $notes[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'content' => wp_strip_all_tags(get_the_content())
            ];
        }
    }

    wp_reset_postdata(); // Reset post data after the custom query

    return new WP_REST_Response($notes, 200); // Send the response with status code 200
}

/**
 * Enqueues the Svelte script and localizes script settings.
 */
function enqueue_svelte_script() {
    wp_enqueue_script('svelte-notes', get_template_directory_uri() . '/assets/js/scripts.js', array(), '1.0.0', true);

    wp_localize_script('svelte-notes', 'wpApiSettings', array(
        'nonce' => wp_create_nonce('wp_rest'),
        'siteUrl' => get_site_url()
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_svelte_script');

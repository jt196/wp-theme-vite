<?php

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/my-notes/', array(
        'methods' => 'GET',
        'callback' => 'get_current_user_notes'
        // Removed permission_callback for this example, ensure security as needed
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/my-notes/(?P<id>\d+)', array(
        array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => 'delete_user_note',
            'permission_callback' => function ($request) {
                return current_user_can_crud_post($request['id']);
            },
            'args' => [
                'id' => [
                    'validate_callback' => function ($param, $request, $key) {
                        return is_numeric($param);
                    }
                ],
            ],
        ),
        array(
            'methods' => WP_REST_Server::EDITABLE,  // Use EDITABLE for POST requests; you can change to CREATABLE for PUT if needed
            'callback' => 'update_user_note',
            'permission_callback' => function ($request) {
                return current_user_can_crud_post($request['id']);
            },
            'args' => [
                'id' => [
                    'validate_callback' => function ($param, $request, $key) {
                        return is_numeric($param);
                    }
                ],
            ],
        )
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/my-notes/', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'create_user_note',
        'permission_callback' => function () {
            return is_user_logged_in(); // Make sure the user is logged in
        }
    ));
});

function current_user_can_crud_post($post_id) {
    if (!is_user_logged_in()) {
        return false;
    }

    $post = get_post($post_id);
    if (is_null($post)) {
        return false;
    }

    $user_id = get_current_user_id();
    return (int) $post->post_author === $user_id;
}


function delete_user_note($request) {
    $note_id = $request['id'];
    $post = get_post($note_id);

    // Double-check the post exists and the current user is the author
    if (is_null($post) || get_current_user_id() !== (int) $post->post_author) {
        return new WP_REST_Response('Forbidden - You do not have permission to delete this note', 403);
    }

    $result = wp_delete_post($note_id, true); // True to bypass trash and permanently delete
    if (!$result) {
        return new WP_REST_Response('Error deleting note', 500);
    }

    return new WP_REST_Response('Note successfully deleted', 200);
}

function update_user_note($request) {
    $note_id = $request['id'];
    if (!current_user_can_crud_post($note_id)) {
        return new WP_Error('rest_forbidden', esc_html__('You are not allowed to edit this note.', 'your-text-domain'), array('status' => 403));
    }

    $postarr = array(
        'ID'           => $note_id,
        'post_title'   => sanitize_text_field($request->get_param('title')),
        'post_content' => sanitize_textarea_field($request->get_param('content'))
    );

    $result = wp_update_post($postarr, true);
    if (is_wp_error($result)) {
        return $result;
    }

    return new WP_REST_Response('Note updated', 200);
}

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

function create_user_note(WP_REST_Request $request) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not logged in.', array('status' => 401));
    }

    $post_data = array(
        'post_title'   => sanitize_text_field($request->get_param('title')),
        'post_content' => sanitize_textarea_field($request->get_param('content')),
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
        'post_type'    => 'note', // Assuming 'note' is a custom post type you have registered
    );

    $post_id = wp_insert_post($post_data, true);

    if (is_wp_error($post_id)) {
        return new WP_Error('rest_insert_error', 'Failed to create note.', array('status' => 500));
    }

    // Retrieve the newly created post to return its complete details
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('rest_post_error', 'Failed to retrieve note after creation.', array('status' => 500));
    }

    $response_data = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content
    );

    return new WP_REST_Response($response_data, 200);
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

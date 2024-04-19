<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults($data){
    $mainQuery = new WP_Query(array(
        'post_type' => array('professor', 'post', 'page', 'event', 'program'),
        's' => sanitize_text_field(
            $data['term']
        )
    ));

    $queryResults = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($queryResults['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
                'authorName' => get_the_author()
            ));
        } else if (get_post_type() == 'professor') {
            array_push($queryResults['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        } else if (get_post_type() == 'program') {
            array_push($queryResults['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        } else if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
              } else {
                $description = wp_trim_words(get_the_content(), 18);
                } 
            array_push($queryResults['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
    }
    // Check if any related programs to the search string exist
    if ($queryResults['programs']) {
        // Create an array of meta queries
        $programsMetaQuery = array('relation' => 'OR');
    
        foreach($queryResults['programs'] as $item) {
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }
        
        // Query for professors with related programs
        // e.g. search string 'Biology' returns professors with 'Biology' in their related programs
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array(
                'professor',
                'event'
            ),
            'meta_query' => $programsMetaQuery
        ));
    
        // Add the above related program professors to the $queryResults array
        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            if (get_post_type() == 'professor') {
                array_push($queryResults['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                ));
            };
            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                  } else {
                    $description = wp_trim_words(get_the_content(), 18);
                    } 
                array_push($queryResults['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_id(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }
        }
    
        // Filter the $queryResults array to remove duplicate professors
        $queryResults['professors'] = array_unique($queryResults['professors'], SORT_REGULAR);
        $queryResults['events'] = array_unique($queryResults['events'], SORT_REGULAR);
    }

    return $queryResults;
}
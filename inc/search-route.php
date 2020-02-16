<?php

function uni_register_search()
{
    // In routes no conflict with plugins
    register_rest_route('uni/v1', 'search', array(
        'methods' => 'GET', // OR WP_REST_SERVER::READABLE, will get 'GET' according to the server specified.
        'callback' => 'uni_search_results',

    ));
}
function uni_search_results($data)
{
    // WP automatically convert PHP array to JSON data
    $query_results = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => $data['term'], // lowercase 's' means search; http://www.mysite.com/wp-json/uni/v1/search?term=malik
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'events' => array(),
        'campuses' => array(),
        'programs' => array(),
    );

    while($query_results->have_posts())
    {
        $query_results->the_post();
        
        if(get_post_type() == 'post' OR get_post_type() == 'page')
        {
            array_push( $results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'post_type' => get_post_type(),
                'author_name' => get_the_author(),
            ));
        }
        if(get_post_type() == 'event')
        {
            
            $date = DateTime::createFromFormat('d/m/Y', get_field('event_date') );
            $formatted_month = $date->format('M');
            $formatted_day = $date->format('d');

            $description = '';
            
            if(has_excerpt())
            {
                $description = get_the_excerpt();
            }else{
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push( $results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $formatted_month,
                'day' => $formatted_day,
                'description' => $description,
            ));
        }
        if(get_post_type() == 'professor')
        {
            array_push( $results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
            ));
        }
        if(get_post_type() == 'campus')
        {
            array_push( $results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }
        if(get_post_type() == 'program')
        {
            $relatedCampuses = get_field('related_campuses');
            if($relatedCampuses)
            { 
                foreach($relatedCampuses as $campus)
                {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus),
                    ));
                }
            }
            array_push( $results['programs'], array(
                'id' => get_the_id(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }
    }


    if($results['programs'])
    {
        $programsMtaQuery = array('relation'=>'OR');
        foreach ($results['programs'] as $item) 
        {
            array_push($programsMtaQuery, 
            array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'. $item['id'] .'"',
            ));
        }
    
        $r = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programsMtaQuery,
        )); 
        while ($r->have_posts()) 
        {
            $r->the_post();
            if(get_post_type() == 'professor')
            {
                array_push( $results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
                ));
            }
            if(get_post_type() == 'event')
            {
                
                $date = DateTime::createFromFormat('d/m/Y', get_field('event_date') );
                $formatted_month = $date->format('M');
                $formatted_day = $date->format('d');
    
                $description = '';
                
                if(has_excerpt())
                {
                    $description = get_the_excerpt();
                }else{
                    $description = wp_trim_words(get_the_content(), 18);
                }
                array_push( $results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $formatted_month,
                    'day' => $formatted_day,
                    'description' => $description,
                ));
            }
        }
    }
    $results['professors'] = array_values( array_unique($results['professors'], SORT_REGULAR) );
    $results['events'] = array_values( array_unique($results['events'], SORT_REGULAR) );
    return $results;
}
add_action('rest_api_init', 'uni_register_search');

// Route for above to get data is: http://www.mysite.com/wp-json/uni/v1/search
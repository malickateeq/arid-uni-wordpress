<?php

add_action('rest_api_init', 'uni_like_routes');
function uni_like_routes()
{
    // POST / Create route
    register_rest_route('uni/v1', 'manage_like', array(
        'methods' => 'POST',
        'callback' => 'create_like',
    ));

    // DELETE / Delete route
    register_rest_route('uni/v1', 'manage_like', array(
        'methods' => 'DELETE',
        'callback' => 'delete_like',
    ));
}

function create_like($data)
{
    if( is_user_logged_in() )    // Or current_user_can('publish_post')
    {
        $professor = sanitize_text_field($data['professor_id']);
        
        $isLiked = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor ,
                )
            ),
        ));

        if($isLiked->found_posts() == 0 AND get_post_type($professor)== 'professor')
        {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'User Liked',
                'meta_input' => array(
                    'liked_professor_id' => $professor,
                ),
            ));
        }
        else
        {
            die("Invalid professor ID.");
        }
    }
    else
    {
        die('Only logged in users can create a like.');
    }
}
function delete_like($data)
{
    $likeID = sanitize_text_field($data['like']);
    if(get_current_user_id() == get_post_field('post_author', $likeID) AND get_post_type($likeID) == 'like')
    {
        wp_delete_post($likeID, true);
        return "Congrats, like deleted.";
    }
    else
    {
        die('You do not have permission to delete that.');
    }
}
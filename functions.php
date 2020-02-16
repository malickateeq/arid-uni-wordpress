<?php

require get_theme_file_path("/inc/search-route.php");

function uni_resources()
{
    wp_enqueue_script('googleMap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAVELSX6ErxUO5vgrxO_z9SHZyf_RvdP3w', NULL, 1.0, true );
    wp_enqueue_script('main-uni-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, 1.0, true );
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('style', get_stylesheet_uri());

    wp_localize_script('main-uni-js', 'uni_data', array(
        'root_url' => get_site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'uni_resources');

function uni_features()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    // args: nickname, widht, height, crop (true,false)
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);

    // PAge banner size
    add_image_size('pageBanner', 1500, 350, true);

}
add_action('after_setup_theme', 'uni_features');

function uni_adjust_queries($query)
{
    // Event archive query
    if( !is_admin() AND is_post_type_archive('event') AND $query->is_main_query() )
    {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', 
            array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numberic',
                )
            ),
        );
    }

    // To display all programs order by aplphabatically 'title'
    //  at front end                 //post type        // whennot a custom query but default URL query
    if( !is_admin() AND is_post_type_archive('program') AND $query->is_main_query() )
    {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if( !is_admin() AND is_post_type_archive('campus') AND $query->is_main_query() )
    {
        $query->set('posts_per_page', -1);
    }

}

add_action('pre_get_posts', 'uni_adjust_queries');

// Reusable functions
function page_banner($args = NULL)
{
    // PHP logic will live here
    if(!$args['title'])
    {
        $args['title'] = get_the_title();
    }
    if(!$args['subtitle'])
    {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if(!$args['image'])
    {
        if(get_field('page_banner_image'))
        {
           $args['image'] = get_field('page_banner_image')['sizes']['pageBanner'];
        }
        else
        {
            $args['image'] = get_theme_file_uri('images/ocean.jpg');
        }
    }
    ?>
    <!-- HTML Code here -->

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['image']; ?>);"></div>
        <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
            <p><?php echo $args['subtitle']; ?></p>
        </div>
        </div>  
    </div>

    <?php
}

function uni_map_key($api)
{
    $api['key'] = 'AIzaSyAVELSX6ErxUO5vgrxO_z9SHZyf_RvdP3w';
    return $api;
}
add_filter('acf/fields/google_map/api', 'uni_map_key');


// Manipulate results in REST API
// add new field in rest api result
function uni_custom_rest()
{
    register_rest_field('post', 'author_name', array(
        'get_callback' => function() {return get_the_author();}
    ));
}
add_action('rest_api_init', 'uni_custom_rest');

// Register/ Login Redirects
function redirectsSubsToFrontend()
{
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber')
    {
        wp_redirect(site_url('/'));
        show_admin_bar(false);
        exit;
    }
}
add_action('admin_init', 'redirectsSubsToFrontend');

function noAdminBarForSubs()
{
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber')
    {
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'noAdminBarForSubs');

// Cutomize login screes
add_filter('login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS()
{
    // It'll overrides the existing CSS file for login
    wp_enqueue_style('style', get_stylesheet_uri());
}

add_filter('login_headertitle', 'ourLoginTitle');
function ourLoginTitle()
{
    return get_bloginfo('name');
}
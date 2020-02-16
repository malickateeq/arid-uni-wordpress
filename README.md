# Wordpress theme development

- Playlist which I followed:
[tuts](https://www.youtube.com/playlist?list=PLpcSpRrAaOaqMA4RdhSnnNcaqOVpX7qi5)

## Setting Up Things; BASICS
1. Install XAMPP
2. Install/Extract wordpress package downloaded from wordpress.org to htdocs/www/etc
3. Intall (in browser) custom theme or pick existing.
4. Create a new DB, (file config is in: wordpress/wp-config.php)

## Create a custom theme
- Create new folder in "themes" and create following files: index.php and style.css
- Put below in style.css;
```css
    - It'll display in theme info;
    /*
    Theme Name: Learning
    Author: malik ateeq
    Author URI: https://www.facebook.com/malickateeq
    Version: 1.0
    */
```

## Wordpress functions
- If a WP function starts with get_* it'll return something and else will echo out the_*
0. Functions related to site info
```php
    // use bloginfo('') function and pass a key variable to get its values like;
    bloginfo('charset');    // site charset
    bloginfo('name');    // name of the site
    bloginfo('description');    // site description
    home_url(); // to get home url :D
    get_site_url(); //
    

```

1. Functions related to POSTS
```php
    // To get Title of a post, or any custom post
    the_title();

    // And also have a function for content
    the_content();

    // URL to that post
    the_permalink();

    // Time
    the_time('m/d/y');  // 'F j, Y g:i a'

    // The Author
    the_author();   // Author name who posted it

    // Url of Author posts
    get_author_posts_url( get_the_author_meta('ID') );  // Get author ID: get_the_author_meta('ID');

    // Categories
    $categories = get_the_category();   // get all categories in which a post is belongs to
    $separator = ', ';
    $output = '';
    if($categories){
        foreach($categories as $category){
            $output .= '<a href=" '. get_category_link($category->term_id) .' ">'. $category->cat_name . '</a>' . $separator;
        }
        echo $output;
    }
```

## Wordpress navigation menu
```php
    // List all different Pages
    wp_nav_menu();

    // To create (then get) different nav items
    $args = array('theme_location'=>'primary'); // or theme_location =>footer for footer nav
    wp_nav_menu($args);

    // Now, Regiseter above menu locations in functions.php
    register_nav_menus(array(
        'primary' => __('Primary Menu'),
        'footer' => __('Footer Menu'),
    ));

    // Now control above categories from admin panel under
    Appreance > Menus
    1. Create Menus: 'Primary Menu Links' and  'Footer Menu Links'
    2. Add pages whichever you want
    3. Set references in "Manage Locations" tab.

```

## Wordpress Header & Footer
```php
    // use below function to get header and footer 
    get_header();
    get_footer();

    // It'll look for a file name header.php and footer.php
    // create above files to override default.
```
- 'header.php' file
```php
    // Open html, doctype in header.php and close it in footer.php
    // place below function within header and footer; for plugin etc.
    wp_head();
    wp_footer();

    // And add body class in order to target different body classes
    body_class();
```

## Wordpress stylesheets/scripts etc.
- Fist way is to include them in header and the second way is include them it functions.php
- functions.php handles lots of things
```php
    // In 'functions.php' add function to install css
    function site_resources(){  // any function
        wp_enqueue_style('style', get_stylesheet_uri() );
    }

    // Run above function
    add_action('wp_enqueue_scripts', 'site_resources');
```

## Wordpress Pages Customization
- make a new page 'page.php' <!-- It'll define page template for page -->
```php
    // To check for current page
    if( is_page($page_id_here) );  // you can also pass 'slug_name' here
    // To check page_id except for URL goto
    // Pages > Click-on-the-page > : here you'll see post=some_id
    // Or we can also use Slug name instead of page_ids

    // Got to Settings > Permalinks > Here select how do you want to include in your URL's
```

- To Make a template for a specific page
    * Just make a new page and name it lik: page-page_slug_namge or page-page_id then you have it.

- To make a Template and use/assign it from admin panel.
```php
    1. Make a page with any name lets say 'special-template.php'
    2. Now in 'special-template.php' add below comment.
    /*
        Template Name: Special Layout
    */
        Your custom code for that template goes here..

    3. It will register 'Special Layout' and you can use it in Admin panel to assign to pages.
```

- Child Pages
```php
    // assign child-aprent pages from the admin panel

    wp_list_pages();    // will list all pages-subpages of the site.

    // List only subpages to a Parent Page
    $args = array(
        'child_of' => $post->ID;    // current page id
        'title_li' => '',       // Title of bullets is set NULL/Empty
    );

    // Make a function if we want to list ancester(or siblings) pages in a child page
    $args = array(
        'child_of' => get_top_ancestor_id(),    // define its functions in functions.php
        'title_li' => '',
    );
    // then
    // Get top ancestor
    function get_top_ancestor_id()
    {
        global $post;
        // return ID of parent page when in child
        if( $post->post_parent )
        {
            $ancestors = array_reverse( get_post_ancestors($post->ID) );
            return $ancestors[0];
        }
        return $post->ID;
    }

    wp_list_pages($args);

    // Access post outside just use 
    global $post;
```

## Wordpress Post Archives (Category, Author)
```php
    // Show author, category info on top of a only category view page

    // Make a new file name archive.php
    // Place your logic here for specific posts
    if( is_category() ){
        single_cat_title();
    }else if( is_tag() ){
        single_tag_title();
    }else if( is_author() ){
        echo 'Author Archives: '.get_the_author();
    }else if( is_day() ){
        echo 'Daily Archives: '.get_the_date();
    }else if( is_month() ){
        echo 'Monthly Archives: '.get_the_date('F Y');
    }else if( is_year() ){
        echo 'Yearly Archives: '.get_the_date('Y');
    }else{
        echo 'Archive';
    }

    // show piece of your posts's text
    the_excerpt();

    // to check if in archive or not
    if( is_archive() );
```

## Wordpress post excerpts:
```php
    // Add single.php file to view single post


    // Insert Read more tag in admin panel while editing/creating a post/page
    // OR insert this  
    <!-- more -->

    To edit 'Read More' text use following arg in the function;
    the_content('Read complete article >');

    // If you want to use Excerpt/Read More in all posts/pages use following instead of the_content();
    the_excerpt();  // first 55 words of the post

    // Customize Readmore
    <p>
        <?php echo get_the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>"> Read more>> </a>
    </p>

    // Customize excerpt word-count in functions.php
    function custom_excerpt_length(){
        return 25;
    }
    // add_filter('hook on event', 'default action')
    add_filter('excerpt_length', 'custom_excerpt_length');
    
    // To check if current post has excerpt
    if( $post->post_excerpt ); //OR
    if( has_excerpt(); )
```

## Wordpress featured images
- 1. Add featured image support in your theme
```php
    // Customization in functions.php file

    function my_theme_setup(){
        // Add featured image support
        add_theme_support('post-thumbnails'); // or you can just use it

        // image settings
        // add_image_size('small-thumbnail', width-px, height-px, true/false (cropping));
        add_image_size('small-thumbnail', 180, 120, true);
        add_image_size('banner-image', 920, 210, true);

    }
    add_action('after_setup_theme', 'my_theme_setup');

    // Using this featured image in the theme
    // Get the image via
    the_post_thumbnail();

    // Use custom picture size which you defined already
    the_post_thumbnail('small-thumbnail');

    // To check if a post has thumbnail
    if( has_post_thumbnail() );

```

## Wordpress search form functionality
```php
    // this method will place a search field automatically
    // It'll get the form from the file searchform.php if not exists default
    get_search_form();

    // To customize search form
    searchform.php

    // Customise search results make a file
    search.php
    <h2>Search results for: <?php the_search_query(); ?> </h2>

    // To check if you're in search
    if( is_search(); )
```

## Wordpress post formats
- aside, gallery, link, image, quote, status, video, audio, chat
- Post Formats is a theme feature.
- To use them: 1st. Enable Post Formats, 2nd: Craft different presentations

<!-- To enable Post Formats use the same function as use before for theme_setup -->

```php

    // Add Post Format Support
    // enable following three features in the array
    add_theme_support('post-formats', array('aside', 'gallery', 'link') );
    // Then you'll se post formats while adding a new post under 'Status & Visibility' > Post Format

    // Now we use above a function to get code snippets add second arg for Formats
    get_template_part('file_name', get_post_format() ); // look for file_name-{aside}.php 

```

## Wordpress Widgets: specific cotent contained
- Used for specific content modules: sidebar, nav, footer etc.
- To add widget. You'll nnot see here until activate it
    Goto Appreance > Widgets 
- Activating Widgets in a theme; Open functions.php

```php
    // Add our widget locations
    function ourWidgetsInit()
    {
        register_sidebar(
            array(
                'name' => 'Sidebar',
                'id'  => 'sidebar1'
            )
        );
        // add another here with custome html
        register_sidebar(
            array(
                'name' => 'Footer Area 1',
                'id'  => 'footer1',
                'before_widget' => '<div class="xyz">',
                'after_widget' => '</div>'
            )
        );
    }
    add_action('widgets_init', 'ourWidgetsInit');

    // Use above Widget in a php file
    // We can use this widget anywhere
    dynamic_sidebar('sidebar1');   // pass id as argument
```

## Wordpress custom static homepage
- Create two page(posts): 'Home'=>your homepage, 'Blog'=>empty only title
- Goto Settings > Reading
- Set 'Your homepage displays' to A static page
- Set Posts page: Blog
- Wordpress always looks for 'front-page.php' as your homepge
    If you need customized homepage just create a page front-page.php and add your custom HTML

## WP_Query
- Use to get posts/data of our own choice from the database
- WP always query by default on the basis of the URL but we can also use Custom query to access data
```php

    $opinionPosts = WP_Query('cat=7');  // Category whose ID is 7, u can check in the admin dashboard

    // Now we can loop through the $opinionPosts data like we did in index posts whil(have_posts()): the_post()
    if ($opinionPosts->have_posts()){ while( $opinionPosts->have_posts()) { $opinionPosts->the_post(); } } 
    // Then in HTML <h1> the_title(); </h1>

    // More abut WP_Query
    $opinionPosts = WP_Query('cat=7&posts_per_page=10'); // return 10 posts

    // Surrender the control back to the boss Wordpress; always after looping through posts
    // Use this after whenever you use posts
    // It is used to reset hijacked functions LIKE the_title(); the_content(); etc.
    wp_reset_postdata();

    // WP Custom Query
    $result = new WP_Query(array( ));
    $result = new WP_Query(array(
        'post_type' => 'page', // post
        'posts_per_page' => 2,
        // 'category_name' => 'awards',
    ));
    while( $result->have_posts() )
    {
        $result->the_post();
    }

    // Now you can use tons of functions to get data (formatted) as you like
    the_time('M'); the_title(); the_author(); the_title();

    // Whenever you use custom query always do this
    wp_reset_postdata();
```



## WordPress Customize Elements (Color Picker)
- User can customize things from the wordpress.
- Appreance > Customize

```php
    // *** WordPress Customize Elements
    // Theory
    // i. Control:  is the actual UI element which user interact with.
    // ii. Settings: What uses choses (input) stored in the DataBase 
    // iii. Sections: is just a group of ooptions

    // 0. First you must/maybe have a static HTML/CSS content then you can make it dynamic

    // 1. Customize Appreance Opotions
    function my_theme_customize_register( $wp_customize )
    {
        // 1. Create a settings
        $wp_customize->add_setting('my_theme_link_color', array(
            'default' => '#006ec3',
            'transport' => 'refresh',   // how wp update the preview of our site on change
        ));

        // 2. Create a section to wrap in above setting
        // __('') WP transalation/localization feature
        $wp_customize->add_section('my_theme_standard_colors', array(
            'title' => __('Standard Colors', 'MyTheme'), // On screen label
            'priority' => 30, // sestion position
        ));

        // 3. Create a control
        // WP has pre-built elements just use them                    // using          // name
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'my_theme_link_color_cntrol', array(
            'label' => __('Link Color', 'my_theme'), // On screen label
            // Now assign this control to a section name: 'my_theme_standard_colors'
            'section' => 'my_theme_standard_colors',
            // Assign this control a setting where user's input can be stored
            'settings' => 'my_theme_link_color', 
        )));
    }
    add_action('customize_register', 'my_theme_customize_register');
    
    // Output customize CSS on user control change
    function my_theme_customize_link_color()
    {?>
        <!-- HTML, CSS, JS etc. changes here -->
        <style type="text/css">
            a:link,
            a:visited{
                /* Get dynamic color here */
                /* place name of the setting in the argument; which is 'settings' => 'my_theme_link_color', */
                color: <?php echo get_theme_mod('my_theme_link_color'); ?> ;
            }
        </style>

    <?php }
    // When and how this function is going to run
    add_action('wp_head', 'my_theme_customize_link_color');
```

## Wordpress Pagination

- To set posts per page goto Settings >Reading > Blog pages show at most = x
- Place pagination just afte the loop 

```php
    // Fucntion to get next posts links
    next_posts_link();
    // Fucntion to get previous posts links
    previous_posts_link();

    // Or

    // WP auto query results according to the link/url
    echo paginate_links();
    // But what if we want results without url, or at any custom page

    // ** For custom pages.templates...

    // 1. Custom template

    // 2. Write a custom query
    // 3. Implement Pagination

```



## Wordpress REST API

- CRUD functionalities became easy by WP RESR APIs

```php
    // Get any data from the DB in JSON format
    https://wwww.mysite.com/wp-json/wp/v2/posts

    // You can als pass arguments from here
    /wp-json/wp/v2/posts?per_page=10

    // Create a new folder with main.js file in it
    // Use/include this file in your WP theme by resources function we've made earlier
    
        // arg1: give it a name, arg2: path;  get_template_directory_uri() == base path of the site, arg3: dependency
        // arg4: version, arg4: location to load true==just above </body>, false== in header
        wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', NULL, 1.0, true );


```
## Wordpress Ajax Request
- To check if a user is admin & logged in 
```php
    if(current_user_can('administrator'));
```
```javascript
    // Fast way to run multiple requests
    // Async code
    $.when(
        one_request,
        two_request,
    ).then(
        (one_resutlt, two_result)=>{
            // code logic here        
        }
    );


    // Ajax request
    var ourRequest = new XMLHttpRequest();
    ourRequest.open('GET', 'http://website.test/wp-json/wp/v2/posts');
    
    ourRequest.onload = function ()
    {
        if(ourRequest.status >= 200 && ourRequest.status <400 )
        {
            var data = JSON.parse(ourRequest.responseText);
            jsonToHTML(data);
        }
        else
        {
            console.log('error');
        }
    };
    ourRequest.onerror = function()
    {
        console.log('conn error');
    };
    ourRequest.send();
```

## WP Custom Post Type
- By default wordpress comes with two post types: Pages and Posts
```php
// Create a new post type
function uni_post_types()
{
    // Event Post type
    register_post_type('event', array(
        'supports' => array('title', 'editor', 'excerpt'),    // mention features
        'rewrite' => array('slug'=>'events'),
        'has_archive' => true,  // To allow Archives
        'public' => true,
        'labels' => array(      // Name on Admin Panel sidebar
                'name'=>'Events',
                'add_new_item' => 'Add New Event', // set Add new Title
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events', // Submenu title
                'singular_name' => 'Event',
            ),
        'menu_icon' => 'dashicons-calendar',  // Get from https://developer.wordpress.org/resource/dashicons/
    ));
}
add_action('init', 'uni_post_types');

// Go to Settings > Permalinks
// Just clink "Save Changes" to update permalink new structure

// To dynamically get archive link
echo get_post_type_archive_link('event');

// To register a new custom post type create a file single-my_type.php
// To show an individual post

// To show all Events in a page then what
// We need to tell WP that this custom post type supports Archives
// Create a new page in WP Admin panel named Events

// This code only exists in functions.php but when someone changes their theme eveything gone
// Although data still resides in the database..
// To overcome this issue we use Pluins

// To enable custom post types in REST API
register_post_type('event', array(
    'show_in_rest' => true,
    ...

```

### WP Custom Fields
- Add a custom field in WP Admin post type
- Don't reinvent the wheel. There are many custom fields which are already present as plugins in WP
    * Advanced Custom Fields (ACF)
    * CMB2 (Custom Metaboxes 2)
```php
    // Advanced Custom Fields (ACF) gives access to its fields by the following functions
    $name = get_field('name');
    print_r ( $name ); // to see what's init

    // Configure Google Maps API in ACF
    

```

## WP Plugins
- Create a new folder in WP/Content directory named="mu-plugins" == Must Use Plugins
- Create a new file in this dir for custom post types
```php
    // place above code here to create custom post type

```

## WP Default Qurery Manipulation
```php
    // To display all programs order by aplphabatically 'title'
    //  at front end                 //post type        // whennot a custom query but default URL query
    if( !is_admin() AND is_post_type_archive('program') AND $query->is_main_query() )
    {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
```

## WP Images Thumbnails
- On uploading WP automatically generate 5 copies with different sizes of an image
- We can also add one of our own
```php
// To enable image thumbnail
// This will enable only for post types not custom types
add_theme_support('post-thumbnails');

// To enable thumbnail for custom post type
register_post_type('professor', array(
    'supports' => array('thumbnail', ...), 
    ...

// To add custom sizes
// Generate custom sizes for future uploads
function uni_features()
{
    // args: nickname, widht, height, crop (true,false)
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
}
add_action('after_setup_theme', 'uni_features');

// To use above custom images
the_post_thumbnail_url('professorPortrait'); // for src=""
the_post_thumbnail('professorPortrait'); // directly echo/display image


// To Generate custom sizes for already uploaded images
// Plugin to generate custom image sizes
- Regenerate Thumbnails by Alex Mills
// Activate > regen thumbnail will regenerate all existing images custom sizes defined in functions.php

// Best plugin for image cropping
this_plugin() = "Manual Image Crop by Tomasz Sita";

```

## WP DRY
1. Get code via function
    - Use it when custom arguments required
    1. Create a function in function.php
    ```php
    // Reusable functions
    function page_banner($args = NULL)
    {
        // PHP logic will live here
        if(!$args['title'])
        {
            $args['title'] = 'Mt title';
        }
        ?>
        <!-- HTML Code here -->
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <?php
    }
    ```
    2. Call the function from anywhere
    ```php
        <!-- To give args -->
        page_banner(array('title'=> 'Use this title'));

        <!-- To use defaults -->
        page_banner();
    ```

2. Reducing duplicaiton via get_template_part() function
    - Use it when no custom arguments required just plain HTML/PHP etc
    1. Create a new file/folder (Organised) and place your code in it

    2. Use the file;
    ```php
        get_template_part('folder/file');   // don't specify extension here (It is slug)
        // If we need to get file dynamically 
        get_template_part('folder/content', 'event');   // will look for a file name "content-event.php"
    ```

## WP Create REST API
- Just to be organised create new folder and a file within it
- Reqiure/Include this file in functions.php
```php
    require get_theme_file_path("/inc/search-route.php");

    // Then your REST API code in search-route.php file
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
        $events = new WP_Query(array(
            'post_type' => 'professor',
            's' => $data['name'], // lowercase 's' means search; http://www.mysite.com/wp-json/uni/v1/search?name=malik
        ));
        $results = array();
        
        while($events->have_posts())
        {
            $events->the_post();
            array_push( $results, array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }

        return $results;
    }
    add_action('rest_api_init', 'uni_register_search');

    // Route for above to get data is: http://www.mysite.com/wp-json/uni/v1/search

```

## WP Roles and Permissions

- Recommended plugin: User Role Editor by Members (Justin Tadlock) 
    ![asd](https://wordpress.org/plugins/members/)
- This will let you create new custome Roles 
- Users > Roles, Add new Roles

- Include custom post types in assigning permissions
```php
// Event Post type
register_post_type('event', array(
    'capability_type' => 'event',   // 'capability_type' => 'post', is by default
    'map_meta_cap' => true, // enforce chnage
```

## WP User Registration and roles
- 1stly enable user registration: check Settings > Membership : Anyone can register
- Set new user default role to "Subscriber" or whatever you want : "Subscriber" safe side

- To sign up: mysite.com/wp-signup.php // OR assign this URL to a Sign Up button
```php
    // Login URL
    wp_login_url();
    
    // Sign Up URL
    wp_registration_url();
    
    // Sign Out URL
    wp_logout_url();
    
    // Redirect users
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber')
    {
        wp_redirect(site_url('/'));
        show_admin_bar(false);
        exit;
    }
    
```

## WP Private Posts
```javascript
// POST request data to create a new request

// On client side
// Not secure
var newPost = {
    'title': $(".new-note-title").val(),
    ...
    'status': 'private',
    // Private=Not visible publically
    // Publlish=Visible to Public
    // Draft=(default) in draft
}

// Server side Robust and more secure
// Force Note Posts to be Private
add_filter('wp_insert_post_data', 'make_note_private');
function make_note_private($data)
{
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash')
    {
        $data['post_status'] = "private";
    }
    return $data;
}

// !! It'll display title with 'Private:' at the start to fix that
str_replace("Private:", "", esc_attr(get_the_title()) );

```

## WP Post Limit For Each User

```php
// To filter html js content
if($data['post_type'] == 'note')
{
    if(count_user_posts( get_current_user_id(), 'note' ) > 4 AND !$postarr['ID'])
    {
        die("You have reached your note limit.");
    }
...

```


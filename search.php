<?php
    get_header();
    page_banner(array(
        'title' => 'Search Results',
        'subtitle' => 'You search for &ldquo;'. get_search_query() .'&rdquo;',
    ));
?>

    <div class="container container--narrow page-section">

        <?php get_search_form(); ?>

        <?php 
        while( have_posts() )
        {
            if( have_posts() )
            {
                the_post(); 
                get_template_part('template-parts/content', get_post_type() );
                echo paginate_links();
            }
            else
            {
                echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
            }
        } 
        
        ?>

    </div>

<?php
    get_footer();
?>
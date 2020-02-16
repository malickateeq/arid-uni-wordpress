<!-- This will display Event archives -->

<?php
    get_header();
    page_banner(array(
        'title' => 'Past Events',
        'subtitle' => 'Recap of our past events.',
    ));
?>

    <div class="container container--narrow page-section">
    <?php

        $today = date('Ymd');
        $result = new WP_Query(array(
            'post_type' => 'event',
            'orderby' => 'meta_value_num',
            'meta_key' => 'event_date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '<',
                    'value' => $today,
                    'type' => 'numberic',
                )
            ),
        ));

        while( $result->have_posts() )
        {
            
            $result->the_post(); ?>

            <div class="event-summary">
                <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                    <span class="event-summary__month">
                        <?php 
                            $date = DateTime::createFromFormat('d/m/Y', get_field('event_date') );
                            echo $date->format('M'); 
                        ?>
                    </span>
                    <span class="event-summary__day"><?php echo $date->format('d'); ?></span>  
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p>
                        <?php echo wp_trim_words(get_the_content(), 30); ?> 
                        <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                    </p>
                </div>
            </div>

        <?php } 
        
        echo paginate_links(array(
            'total' => $result->max_num_pages,
        ));
        ?>

    </div>

<?php
    get_footer();
?>
<!-- This will display Campus archives -->

<?php
    get_header();
    page_banner(array(
        'title' => 'Our Campuses',
        'subtitle' => 'We\'ve several conveniently located campuses.',
    ));
?>

    <div class="container container--narrow page-section">
        <div class="acf-map">
        <?php

            while( have_posts() )
            {
                
                the_post(); 
                $mapLocation = get_field('map_location');
                ?>
                <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
                    <h3> <a href="<?php the_permalink(); ?>" target="_blank"> <?php the_title(); ?> </a> </h3>
                    <?php echo $mapLocation['address'] ?>
                </div>

            <?php } ?>
        </div>
    </div>

<?php
    get_footer();
?>
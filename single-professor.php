<!-- To display individual Events -->

<?php get_header(); 

    while( have_posts() )
    {
        the_post(); 
        page_banner();
        ?>
        
        
        <div class="container container--narrow page-section">

            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="two-third">
                        <?php 
                            $like_cout = new WP_Query(array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID(),
                                    )
                                ),
                            ));
                            
                            $isLikeValue = 'no';

                            if(is_user_logged_in())
                            {
                                $isLiked = new WP_Query(array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_professor_id',
                                            'compare' => '=',
                                            'value' => get_the_ID(),
                                        )
                                    ),
                                ));
                                if($isLiked->found_posts)
                                {
                                    $isLikeValue = 'yes';
                                }
                            }
                        ?>
                        <span class="like-box" data-like="<?php echo $isLiked->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $isLikeValue; ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count"><?php echo $like_cout->found_posts; ?></span>
                        </span>
                        
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

                <?php
                    $relatedPrograms = get_field('related_programs');
                    if($relatedPrograms)
                    { ?>
                    
                        <hr class="section-break">
                        <h2 class="headline headline--medium"> Subject(s) Taught: </h2>
                        <ul class="link-list min-list">
                        <?php
                        foreach ($relatedPrograms as $program) 
                        {   ?>

                            <li> <a href="<?php echo get_the_permalink($program); ?>"> <?php echo get_the_title($program); ?> </a> </li>

                        <?php }
                    }
                ?>
                </ul>

        </div>

    <?php
    }

    get_footer();
?>

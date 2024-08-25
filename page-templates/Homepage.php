<?php
/* Template Name: Homepage*/

get_template_part('template-parts/header');
?>

<main id="main-content">
    <div class="page-container">
        <?php
        // Start the loop.
        while (have_posts()):
            the_post();
            if (has_post_thumbnail()) { ?>
                <div class="featured-image-container">
                    <?php the_post_thumbnail('full'); ?>
                    <div class="overlay-text">
                        <h1>Gearing up the ideas</h1>
                        <p> We're excited to start exploring new concepts and innovations. Our goal is to bring fresh perspectives and creative solutions to the table. Stay tuned as we dive into brainstorming sessions and develop strategies to turn our ideas into reality. </p>
                    </div>
                </div>
            <?php }

        endwhile;
        ?>
        
      
        <?php get_template_part('template-parts/feature-showcase'); ?>
        
        <!-- Portfolio section using shortcode-->
        <?php echo do_shortcode('[portfolio_grid]'); ?>

    </div>
</main>

<?php
get_template_part('template-parts/footer');
// Default footer
?>
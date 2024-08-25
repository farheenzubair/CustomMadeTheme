<?php
/* Template Name: Portfolio*/

get_template_part('template-parts/header');
get_template_part('template-parts/feature-showcase');
?>
<div class="portfolio-page">
    <div class="heading-row">
        <h2>D'SIGN IS THE SOUL</h2>
        <div class="button-row">
            <a href="#" class="button advertising-btn">Advertising</a>
            <a href="#" class="button multimedia-btn">Multimedia</a>
            <a href="#" class="button photography-btn">Photography</a>
        </div>
    </div>
    <hr class="section-divider">

    <div class="portfolio-grid">
        <?php
        
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        $args = array(
            'posts_per_page' => 15,
            'paged' => $paged,
            'meta_key' => '_thumbnail_id', 
        );
        $the_query = new WP_Query($args);

       
        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();
        ?>
                <div class="portfolio-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); 
                         ?>
                    </a>
                </div>
        <?php
            endwhile;
        else :
            echo '<p>No posts found.</p>';
        endif;

        // Reset Post Data
        wp_reset_postdata();
        ?>
    </div>

    <!-- Custom Pagination -->
    <div class="pagination">
        <?php
        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,  // Set the current page
            'format' => '?paged=%#%', // Pagination format
            'prev_text' => __('<i class="fas fa-chevron-left"></i>'), 
            'next_text' => __('<i class="fas fa-chevron-right"></i>'), 
        ));
        ?>
    </div>
</div>
<?php
get_template_part('template-parts/footer');
?>

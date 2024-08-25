<?php
get_template_part('template-parts/header');
get_template_part('template-parts/feature-showcase');
?>

<div class="archive-container">
    <header class="archive-header">
        <?php
        if (is_category()) {
            single_cat_title('<h1 class="archive-title">', '</h1>');
        } elseif (is_tag()) {
            single_tag_title('<h1 class="archive-title">', '</h1>');
        } elseif (is_author()) {
            the_post();
            echo '<h1 class="archive-title">Posts by ' . get_the_author() . '</h1>';
            rewind_posts(); 
        } elseif (is_day()) {
            echo '<h1 class="archive-title">Posts from ' . get_the_date('F j, Y') . '</h1>';
        } elseif (is_month()) {
            echo '<h1 class="archive-title">Posts from ' . get_the_date('F Y') . '</h1>';
        } elseif (is_year()) {
            echo '<h1 class="archive-title">Posts from ' . get_the_date('Y') . '</h1>';
        } elseif (is_post_type_archive()) {
            post_type_archive_title('<h1 class="archive-title">', '</h1>');
        } elseif (is_tax()) {
            // Custom taxonomy archives
            $taxonomy = get_queried_object();
            echo '<h1 class="archive-title">Posts in ' . $taxonomy->name . '</h1>';
        } else {
            echo '<h1 class="archive-title">Archives</h1>';
        }
        ?>
        <hr class="section-divider">
    </header><!-- .archive-header -->

    <div class="posts-grid">
        <?php
        // Set up the query for posts with featured images
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'posts_per_page' => 6, // Number of posts per page
            'paged' => $paged,
            'meta_key' => '_thumbnail_id', 
        );

        if (is_category()) {
            $args['cat'] = get_queried_object_id(); //get id current viewed category
        } elseif (is_tag()) {
            $args['tag_id'] = get_queried_object_id();
        } elseif (is_author()) {
            $args['author'] = get_queried_object_id();
        } elseif (is_day()) {
            $args['date_query'] = array(
                array(
                    'year'  => get_the_date('Y'),
                    'month' => get_the_date('m'),
                    'day'   => get_the_date('d'),
                ),
            );
        } elseif (is_month()) {
            $args['date_query'] = array(
                array(
                    'year'  => get_the_date('Y'),
                    'month' => get_the_date('m'),
                ),
            );
        } elseif (is_year()) {
            $args['date_query'] = array(
                array(
                    'year'  => get_the_date('Y'),
                ),
            );
        } elseif (is_tax()) {
            $taxonomy = get_queried_object();
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $taxonomy->term_id,
                ),
            );
        }

        $the_query = new WP_Query($args);

        // Loop through the posts and display featured images with other details
        if ($the_query->have_posts()):
            while ($the_query->have_posts()):
                $the_query->the_post();
                ?>
                <div class="posts-grid-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); // Adjust size as needed ?>
                    </a>
                    <h2 class="post-archive-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="post-archive-meta">
                        <span class="post-author">
                            by <span class="author-name"><?php the_author(); ?></span>
                        </span>
                        <span class="post-date">on <?php echo get_the_date(); ?></span>
                    </div>
                    <div class="post-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a class="read-more-button" href="<?php the_permalink(); ?>">Read More</a>
                </div>
                <?php
            endwhile;
        else:
            echo '<p>No posts found.</p>';
        endif;

        // Reset Post Data
        wp_reset_postdata();
        ?>
    </div><!-- .posts-grid -->

    <!-- Custom Pagination -->
    <div class="pagination">
        <?php
        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => __('<i class="fas fa-chevron-left"></i>'),
            'next_text' => __('<i class="fas fa-chevron-right"></i>'),
        ));
        ?>
    </div><!-- .pagination -->
</div><!-- .archive-container -->

<?php get_template_part('template-parts/footer'); ?>

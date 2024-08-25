<?php
get_template_part('template-parts/header');
get_template_part('template-parts/feature-showcase');
?>

<div class="blog-page-container">
    <div class="blog-content">
        <div class="blog-heading">
            <h1><?php printf(__('Search for: %s', 'wp-blog-theme'), '<span>' . get_search_query() . '</span>'); ?></h1>
            <hr class="horizontal-line">
        </div>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post(); ?>
                <div class="post-item">
                    <div class="post-header">
                        <div class="post-date-title">
                            <span class="post-date"><?php echo strtoupper(date_i18n('j M')); ?></span>
                            <span class="post-title-separator">|</span>
                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </div>
                    </div>
                    <div class="post-content">
                        <div class="post-thumbnail">
                            <?php if (has_post_thumbnail()) {
                                the_post_thumbnail('thumbnail');
                            } ?>
                        </div>
                        <div class="post-meta-content">
                            <div class="post-meta">
                                <span class="post-author">
                                    by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"
                                        class="author-name"><?php the_author(); ?></a>
                                </span>
                                <span class="post-date">
                                    on <a
                                        href="<?php echo get_day_link(get_the_date('Y'), get_the_date('m'), get_the_date('d')); ?>"><?php echo get_the_date(); ?></a>
                                </span>
                                <span
                                    class="post-comments"><?php comments_number('No Comments', '1 Comment', '% Comments'); ?></span>
                                <span class="post-title-separator">|</span>
                                <span class="post-category">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        foreach ($categories as $category) {
                                            $category_link = get_category_link($category->term_id);
                                            echo '<a href="' . esc_url($category_link) . '" class="category-title">' . esc_html($category->name) . '</a> ';
                                        }
                                    }
                                    ?>
                                </span>
                            </div>

                            <hr class="horizontal-line">
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                                <a href="<?php the_permalink(); ?>" class="read-more-button">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <!-- Pagination -->
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $wp_query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'format' => '?paged=%#%',
                    'prev_text' => __('<i class="fas fa-chevron-left"></i>'),
                    'next_text' => __('<i class="fas fa-chevron-right"></i>'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('No posts found.', 'wp-blog-theme'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</div>

<?php
get_template_part('template-parts/footer');
?>

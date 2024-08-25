<?php
get_template_part('template-parts/header');
get_template_part('template-parts/feature-showcase');
?>

<div class="single-post-wrapper">
    <div class="single-post-content">
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <div class="single-post">
                    <h1 class="single-post-title"><?php the_title(); ?></h1>
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
                            <span class="post-comments"><?php comments_number('No Comments', '1 Comment', '% Comments'); ?></span>
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
                    </div>
                    <div class="single-post-content-body">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="comments-section">
                    <?php comments_template('template-parts/comments.php'); ?>
                </div>
            <?php endwhile; else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.', 'wp-blog-theme'); ?></p>
        <?php endif; ?>
    </div>

    <aside id="secondary" class="widget-area">
        <?php
        if ( is_active_sidebar( 'single-post-sidebar' ) ) {
            dynamic_sidebar( 'single-post-sidebar' );
        }
        ?>
    </aside>
</div>

<?php
get_template_part('template-parts/footer');
?>

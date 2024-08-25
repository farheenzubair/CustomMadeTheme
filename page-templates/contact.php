<?php
/* Template Name: Contact */

get_template_part('template-parts/header');
get_template_part('template-parts/feature-showcase');
?>

<div class="contact-page-content">
    <div class="content-column">
        <h2 class="wp-block-heading">Get In Touch with Us</h2>
    </div>
    <?php the_content(); ?>
</div>

<?php
get_template_part('template-parts/footer');
?>
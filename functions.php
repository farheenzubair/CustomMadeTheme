<?php
// Register navigation menus
register_nav_menus(
    array('primary-menu' => 'Header menu')
);

// Add theme support
function wp_blog_theme_setup() {
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'wp_blog_theme_setup');

// Enqueue stylesheets
function wp_blog_theme_enqueue_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'wp_blog_theme_enqueue_styles');

// Include portfolio shortcode
require get_template_directory() . '/template-parts/portfolio-shortcode.php';

// Custom comment format
function custom_comments_format($comment, $args, $depth) {
    static $first_comment = true;
    $comment_author = get_comment_author();
    $comment_date = get_comment_date();
    $comment_time = get_comment_time();
    $comment_content = get_comment_text();
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-container">
            <?php if ($first_comment): ?>
                <h2><hr><?php _e('Comments', 'wp-blog-theme'); ?><hr></h2>
                <?php $first_comment = false; ?>
            <?php endif; ?>
            <div class="comment-meta">
                <i class="fas fa-comments"></i>
                <span class="comment-author"><?php echo $comment_author; ?></span>
                <span class="comment-said-on"><?php _e('said on', 'wp-blog-theme'); ?></span>
                <span class="comment-date"><?php echo $comment_date; ?></span>
                <span class="comment-at"><?php _e('at', 'wp-blog-theme'); ?></span>
                <span class="comment-time"><?php echo $comment_time; ?></span>
            </div>
            <div class="comment-content"><?php echo $comment_content; ?></div>
            <div class="comment-reply">
                <?php
                comment_reply_link(array_merge($args, array(
                    'reply_text' => '<i class="fas fa-reply"></i> ' . __('reply', 'wp-blog-theme'),
                    'depth' => $depth,
                    'max_depth' => $args['max_depth'],
                )));
                ?>
            </div>
        </div>
    </li>
    <?php
}

// Register sidebars
function wp_blog_theme_sidebars() {
    register_sidebar(array(
        'id' => 'blog-sidebar',
        'name' => __('Blog Sidebar'),
        'description' => __('Blog sidebar'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'id' => 'single-post-sidebar',
        'name' => __('Single Post Sidebar'),
        'description' => __('Single post sidebar'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'wp_blog_theme_sidebars');

// Register custom widget
function wp_blog_theme_custom_portfolio_widget() {
    register_widget('Featured_Images_Widget');
}
add_action('widgets_init', 'wp_blog_theme_custom_portfolio_widget');

// Define custom widget
class Featured_Images_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'featured_images_widget',
            __('Featured Images Widget', 'wp-blog-theme'),
            array('description' => __('Displays featured images of recent posts.', 'wp-blog-theme'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $query = new WP_Query(array(
            'posts_per_page' => 8,
            'meta_key' => '_thumbnail_id',
        ));

        if ($query->have_posts()) {
            echo '<ul class="featured-images-widget">';
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <li class="featured-image-item">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail('small'); ?>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('No featured images found.', 'wp-blog-theme') . '</p>';
        }
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
}

// Custom templates
function wp_blog_theme_custom_templates($template) {
    if (is_home() && !is_front_page()) {
        $new_template = locate_template('templates/index.php');
        if ($new_template) {
            return $new_template;
        }
    }

    if (is_single()) {
        $new_template = locate_template('templates/single.php');
        if ($new_template) {
            return $new_template;
        }
    }

    if (is_archive()) {
        $new_template = locate_template('templates/archive.php');
        if ($new_template) {
            return $new_template;
        }
    }

    if (is_search()) {
        $new_template = locate_template('template-parts/search.php');
        if ($new_template) {
            return $new_template;
        }
    }

    return $template;
}
add_filter('template_include', 'wp_blog_theme_custom_templates');

// Add theme settings page
function wp_blog_theme_add_admin_menu() {
    add_menu_page(
        __('Blog Theme Settings', 'wp-blog-theme'),
        __('Blog Theme Settings', 'wp-blog-theme'),
        'manage_options',
        'wp-blog-theme-settings',
        'wp_blog_theme_settings_page',
        'dashicons-admin-generic',
        100
    );
}
add_action('admin_menu', 'wp_blog_theme_add_admin_menu');

function wp_blog_theme_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Blog Theme Settings', 'wp-blog-theme'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_blog_theme_options');
            do_settings_sections('wp-blog-theme-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialize settings
function wp_blog_theme_settings_init() {
    register_setting('wp_blog_theme_options', 'wp_blog_theme_options');

    add_settings_section(
        'wp_blog_theme_section',
        __('General Settings', 'wp-blog-theme'),
        'wp_blog_theme_section_callback',
        'wp-blog-theme-settings'
    );

    add_settings_field(
        'wp_blog_theme_field_mode',
        __('Theme Mode', 'wp-blog-theme'),
        'wp_blog_theme_field_mode_callback',
        'wp-blog-theme-settings',
        'wp_blog_theme_section',
        array('label_for' => 'wp_blog_theme_field_mode')
    );

    add_settings_field(
        'wp_blog_theme_field_font_family',
        __('Font Family', 'wp-blog-theme'),
        'wp_blog_theme_field_font_family_callback',
        'wp-blog-theme-settings',
        'wp_blog_theme_section',
        array('label_for' => 'wp_blog_theme_field_font_family')
    );
}
add_action('admin_init', 'wp_blog_theme_settings_init');

// Section description
function wp_blog_theme_section_callback() {
    echo '<p>' . __('Choose the mode for your theme.', 'wp-blog-theme') . '</p>';
}

// Theme mode field
function wp_blog_theme_field_mode_callback($args) {
    $options = get_option('wp_blog_theme_options', array());
    $selected_mode = isset($options['mode']) ? $options['mode'] : 'light';
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="wp_blog_theme_options[mode]">
        <option value="light" <?php selected($selected_mode, 'light'); ?>><?php _e('Light', 'wp-blog-theme'); ?></option>
        <option value="dark" <?php selected($selected_mode, 'dark'); ?>><?php _e('Dark', 'wp-blog-theme'); ?></option>
    </select>
    <?php
}

// Font family field
function wp_blog_theme_field_font_family_callback($args) {
    $options = get_option('wp_blog_theme_options', array());
    $selected_font = isset($options['font_family']) ? $options['font_family'] : 'Verdana';
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="wp_blog_theme_options[font_family]">
        <option value="Verdana" <?php selected($selected_font, 'Verdana'); ?>><?php _e('Verdana', 'wp-blog-theme'); ?></option>
        <option value="Tahoma" <?php selected($selected_font, 'Tahoma'); ?>><?php _e('Tahoma', 'wp-blog-theme'); ?></option>
        <option value="Trebuchet MS" <?php selected($selected_font, 'Trebuchet MS'); ?>><?php _e('Trebuchet MS', 'wp-blog-theme'); ?></option>
    </select>
    <?php
}

// Apply settings
function wp_blog_theme_apply_mode() {
    $options = get_option('wp_blog_theme_options');
    $theme_mode = isset($options['mode']) ?

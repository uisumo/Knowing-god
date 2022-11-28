<?php
/**
 * Knowing God functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Knowing_God
 */
if ( ! defined( 'KNOWING_GOD_VERSION' ) ) {
    $my_theme = wp_get_theme();
    define( 'KNOWING_GOD_VERSION', $my_theme->get( 'Version' ) );
}

if ( ! function_exists( 'knowing_god_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function knowing_god_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Knowing God, use a find and replace
         * to change 'knowing-god' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'knowing-god', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * Add image sizes.
         *
         * @link https://developer.wordpress.org/reference/functions/add_image_size/
         */
        add_image_size( 'knowing-god-featured', 800, 400, true );
        add_image_size( 'knowing-god-recent', 40, 40, true );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary-menu' => esc_html__( 'Primary Menu', 'knowing-god' ),
            'footer-menu' => esc_html__( 'Footer Menu', 'knowing-god' ),
            // 'login-menu' => esc_html__( 'Login Menu', 'knowing-god' ),
        ) );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support( 'post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        ) );

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style( array( 'css/editor-style.css', knowing_god_fonts_url() ) );

        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'knowing_god_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support( 'custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ) );
    }
endif;
add_action( 'after_setup_theme', 'knowing_god_setup' );

if ( ! function_exists( 'knowing_god_fonts_url' ) ) :
    /**
     * Register Google fonts for Auto Point.
     *
     * Create your own knowing_god_fonts_url() function to override in a child theme.
     *
     * @since Auto Point 1.0.0
     *
     * @return string Google fonts URL for the theme.
     */
    function knowing_god_fonts_url() {
        $fonts_url = '';

        /*
         * Translators: If there are characters in your language that are not
         * supported by Open Sans, translate this to 'off'. Do not translate
         * into your own language.
         */
        $libre_franklin = _x( 'on', 'Open Sans font: on or off', 'twentyseventeen' );

        if ( 'off' !== $libre_franklin ) {
            $font_families = array();

            $font_families[] = 'Open Sans:300,300i,400,400i,600,600i,700';

            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return esc_url_raw( $fonts_url );
    }
endif;

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function knowing_god_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'knowing_god_content_width', 640 );
}
add_action( 'after_setup_theme', 'knowing_god_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function knowing_god_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'knowing-god' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'knowing-god' ),
        'before_widget' => '<div id="%1$s" class="card mb-4 widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="card-header widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets', 'knowing-god' ),
        'id'            => 'footer-widgets',
        'description'   => esc_html__( 'Add Footer Widgets here.', 'knowing-god' ),
        'before_widget' => '<div id="%1$s" class="col-sm-6 col-md-3 widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6 class="text-center pathway_green pb-2 widget-title">',
        'after_title'   => '</h6>',
    ) );
}
add_action( 'widgets_init', 'knowing_god_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function knowing_god_scripts() {

    // Add Bootstrap default CSS.
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );

	// Google Fonts
	wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700', false );

    // Add Modern Business CSS.
    wp_enqueue_style( 'modern-business', get_template_directory_uri() . '/css/modern-business.css' );

    // Add font awesome CSS.
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0', 'all' );

    // Add Bootstrap default CSS.
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl.carousel.min.css' );

    // Add Bootstrap default CSS.
    wp_enqueue_style( 'owl-theme-default', get_template_directory_uri() . '/css/owl.theme.default.min.css' );

    // Add jquery.fancybox CSS.
    wp_enqueue_style( 'jquery.fancybox', get_template_directory_uri() . '/css/jquery.fancybox.min.css' );

    // Add custom CSS.
    wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css' );
    // Add custom CSS.
    wp_enqueue_style( 'other', get_template_directory_uri() . '/css/other-style.css', '', time() );

    wp_enqueue_style( 'knowing-god-style', get_stylesheet_uri() );

    wp_enqueue_script( 'popper', get_template_directory_uri() . '/js/popper.min.js', array( 'jquery' ), KNOWING_GOD_VERSION, true );

    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '4.0.0', true );

    // Scripts.
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ) );

    wp_enqueue_script( 'jquery-fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js', array( 'jquery' ), '3.1.22', true );

    // wp_enqueue_script( 'knowing-god-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

    wp_enqueue_script( 'knowing-god-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), KNOWING_GOD_VERSION, true );

    //custom js
    wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0.0', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

	wp_enqueue_style( 'alertify-core', get_template_directory_uri() . '/css/alertify/themes/alertify.core.css' );
	wp_enqueue_style( 'alertify-default', get_template_directory_uri() . '/css/alertify/themes/alertify.default.css' );
	wp_enqueue_script( 'alertify', get_template_directory_uri() . '/js/alertify.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'knowing_god_scripts' );

function knowing_god_admin_style() {
        wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/css/jquery-ui.css' );

		wp_enqueue_script( 'knowing-god-admin', get_template_directory_uri() . '/js/admin.js', array( 'jquery' ), KNOWING_GOD_VERSION, true );

		wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ), time() );
}
add_action( 'admin_enqueue_scripts', 'knowing_god_admin_style' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Header Logo
 */
function knowing_god_get_header_logo() {
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
        // the_custom_logo();
        ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" rel="home" itemprop="url"><img src="<?php bloginfo('template_url'); ?>/images/logo.svg" class="img-responsive" alt="Knowing God" itemprop="logo" width="145" height="30"></a>
        <?php
    } else { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"><div class="site-title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div></a>
    <?php
    }
}

if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {

    add_filter( 'get_custom_logo', 'knowing_god_custom_logo_output', 10 );

    /**
     * Filters the custom logo output.
     *
     * @param string $html output logo.
     * @return string
     */
    function knowing_god_custom_logo_output( $html ) {
        $html = str_replace( 'custom-logo-link', 'navbar-brand', $html ); // 'a' tag.
        $html = str_replace( 'custom-logo', 'img-responsive', $html ); // 'img' tag.
        return $html;
    }
}

/**
 * Function to add 'Read more' link to the escerpt.
 *
 * @param string $more excerpt.
 * @return string
 */
function knowing_god_excerpt_more( $more ) {
    return sprintf(
    /*
    translators: %1: Permalink. %2: Post Title
    */
    '<div class="read"><a class="btn btn-success" href="%1$s" title="%2$s">' . esc_html__( 'Read More', 'knowing-god' ) . ' &rarr;</a></div>', get_permalink(), get_the_title() );
}
// add_filter( 'excerpt_more', 'knowing_god_excerpt_more' );

//Read More Button For Excerpt
function themprefix_excerpt_read_more_link($output) {
	global $post;
	return $output . sprintf(
    /*
    translators: %1: Permalink. %2: Post Title
    */
    '<div class="read"><a class="btn btn-success" href="%1$s" title="%2$s">' . esc_html__( 'Read More', 'knowing-god' ) . ' &rarr;</a></div>', get_permalink(), get_the_title() );
}
add_filter( 'get_the_excerpt', 'themprefix_excerpt_read_more_link' );


if ( ! function_exists( 'knowing_god_get_social_networks' ) ) :
    /**
     * This function returns all available social networks.
     *
     * @return array
     */
    function knowing_god_get_social_networks() {
        return array(
                'knowing_god_facebook' => array(
                    'title' =>  esc_html__( 'Facebook', 'knowing-god' ),
                    'icon' => 'fa fa-facebook',
                    'icon_sqare' => 'fa fa-facebook-square',
                    ),
                'knowing_god_linkedin' => array(
                    'title' => esc_html__( 'LinkedIn', 'knowing-god' ),
                    'icon' => 'fa fa-linkedin',
                    'icon_sqare' => 'fa fa-linkedin-square',
                    ),
                'knowing_god_pinterest' => array(
                    'title' => esc_html__( 'Pinterest', 'knowing-god' ),
                    'icon' => 'fa fa-pinterest',
                    'icon_sqare' => 'fa fa-pinterest-square',
                    ),
                'knowing_god_skype' => array(
                    'title' => esc_html__( 'Skype', 'knowing-god' ),
                    'icon' => 'fa fa-skype',
                    'icon_sqare' => 'fa fa-skype',
                    ),
                'knowing_god_twitter' => array(
                    'title' => esc_html__( 'Twitter', 'knowing-god' ),
                    'icon' => 'fa fa-twitter',
                    'icon_sqare' => 'fa fa-twitter-square',
                    ),
                'knowing_god_google-plus' => array(
                    'title' => esc_html__( 'Google Plus', 'knowing-god' ),
                    'icon' => 'fa fa-google-plus',
                    'icon_sqare' => 'fa fa-google-plus-square',
                    ),

                'knowing_god_dribbble' => array(
                    'title' => esc_html__( 'Dribbble', 'knowing-god' ),
                    'icon' => 'fa fa-dribbble',
                    'icon_sqare' => 'fa fa-dribbble-square',
                    ),
                'knowing_god_behance' => array(
                    'title' => esc_html__( 'Behance', 'knowing-god' ),
                    'icon' => 'fa fa-behance',
                    'icon_sqare' => 'fa fa-behance-square',
                    ),
                'knowing_god_deviantart' => array(
                    'title' => esc_html__( 'DeviantArt', 'knowing-god' ),
                    'icon' => 'fa fa-deviantart',
                    'icon_sqare' => 'fa fa-deviantart',
                    ),
                'knowing_god_flickr' => array(
                    'title' => esc_html__( 'Flickr', 'knowing-god' ),
                    'icon' => 'fa fa-flickr',
                    'icon_sqare' => 'fa fa-flickr',
                    ),
                'knowing_god_500px' => array(
                    'title' => esc_html__( '500px', 'knowing-god' ),
                    'icon' => 'fa fa-500px',
                    'icon_sqare' => 'fa fa-500px',
                    ),
                'knowing_god_instagram' => array(
                    'title' => esc_html__( 'Instagram', 'knowing-god' ),
                    'icon' => 'fa fa-instagram',
                    'icon_sqare' => 'fa fa-instagram-square',
                    ),


                'knowing_god_youtube' => array(
                    'title' => esc_html__( 'YouTube', 'knowing-god' ),
                    'icon' => 'fa fa-youtube',
                    'icon_sqare' => 'fa fa-youtube',
                    ),
                'knowing_god_vimeo' => array(
                    'title' => esc_html__( 'Vimeo', 'knowing-god' ),
                    'icon' => 'fa fa-vimeo',
                    'icon_sqare' => 'fa fa-vimeo',
                    ),
                'knowing_god_medium' => array(
                    'title' => esc_html__( 'Medium', 'knowing-god' ),
                    'icon' => 'fa fa-medium',
                    'icon_sqare' => 'fa fa-medium',
                    ),
                'knowing_god_tumblr' => array(
                    'title' => esc_html__( 'Tumblr', 'knowing-god' ),
                    'icon' => 'fa fa-tumblr',
                    'icon_sqare' => 'fa fa-tumblr-square',
                    ),
                'knowing_god_wordpress' => array(
                    'title' => esc_html__( 'WordPress', 'knowing-god' ),
                    'icon' => 'fa fa-wordpress',
                    'icon_sqare' => 'fa fa-wordpress',
                    ),
                'knowing_god_github' => array(
                    'title' => esc_html__( 'GitHub', 'knowing-god' ),
                    'icon' => 'fa fa-github-square',
                    'icon_sqare' => 'fa fa-github',
                    ),
                'knowing_god_bitbucket' => array(
                    'title' => esc_html__( 'Bitbucket', 'knowing-god' ),
                    'icon' => 'fa fa-bitbucket',
                    'icon_sqare' => 'fa fa-bitbucket-square',
                    ),
                'knowing_god_codepen' => array(
                    'title' => esc_html__( 'Codepen', 'knowing-god' ),
                    'icon' => 'fa fa-codepen',
                    'icon_sqare' => 'fa fa-codepen',
                    ),
                'knowing_god_mixcloud' => array(
                    'title' => esc_html__( 'Mixcloud', 'knowing-god' ),
                    'icon' => 'fa fa-mixcloud',
                    'icon_sqare' => 'fa fa-mixcloud',
                    ),
                'knowing_god_soundcloud' => array(
                    'title' => esc_html__( 'SoundCloud', 'knowing-god' ),
                    'icon' => 'fa fa-soundcloud',
                    'icon_sqare' => 'fa fa-soundcloud',
                    ),
                'knowing_god_stumbleupon' => array(
                    'title' => esc_html__( 'StumbleUpon', 'knowing-god' ),
                    'icon' => 'fa fa-stumbleupon-circle',
                    'icon_sqare' => 'fa fa-stumbleupon-circle',
                    ),
                'knowing_god_vk' => array(
                    'title' => esc_html__( 'VK', 'knowing-god' ),
                    'icon' => 'fa fa-vk',
                    'icon_sqare' => 'fa fa-vk',
                    ),
                'knowing_god_rss' => array(
                    'title' => esc_html__( 'RSS', 'knowing-god' ),
                    'icon' => 'fa fa-rss',
                    'icon_sqare' => 'fa fa-rss-square',
                    ),
                'knowing_god_envelope' => array(
                    'title' => esc_html__( 'Email', 'knowing-god' ),
                    'icon' => 'fa fa-envelope-open',
                    'icon_sqare' => 'fa fa-envelope-open',
                    ),
                'knowing_god_phone' => array(
                    'title' => esc_html__( 'Phone', 'knowing-god' ),
                    'icon' => 'fa fa-phone',
                    'icon_sqare' => 'fa fa-phone-square',
                    ),
            );
    }
endif;

/**
 * Set up the page banner for the page OR post.
 */
function knowing_god_page_banner() {
    $page_banner = get_theme_mod( 'banner-show-hide', 'show' );
    if ( function_exists( 'knowing_god_add_page_meta' ) ) {
        if ( ! is_archive() && ! is_search() && ! is_home() ) {
            $page_banner_local = get_post_meta( get_the_ID(), 'page_banner', true );
            if ( ! empty( $page_banner_local ) ) {
                $page_banner = $page_banner_local;
            }
        }
    }
    return $page_banner;
}

/**
 * Set up the Sidebar Position.
 */
function knowing_god_sidebar_position() {
    $sidebar_position = get_theme_mod( 'sidebar-position', 'right' );
    if ( function_exists( 'knowing_god_add_page_meta' ) ) {
        $sidebar_position = get_post_meta( get_the_ID(), 'sidebar_position', true );
        if ( empty( $sidebar_position ) ) {
            $sidebar_position = 'right';
        }
    }
    return $sidebar_position;
}

/**
 * Load widget output filters
 */
require_once get_template_directory() . '/inc/class-widget-output-filters.php';
Class_Widget_Output_Filters::get_instance();

if ( ! function_exists( 'knowing_god_change_default_widget' ) ) :
    function knowing_god_change_default_widget( $widget_output, $widget_type, $widget_id, $sidebar_id ) {
        // echo $widget_type;
		if ( in_array( $widget_type, array( 'categories', 'archives', 'seriestoc' ), true ) ) {
            $widget_output = preg_replace('/\(/', '<span class="badge badge-dark badge-pill">', $widget_output);
            $widget_output = preg_replace('/\)/', '</span>', $widget_output);
        } elseif ( 'tag_cloud' === $widget_type ) {
			$widget_output = preg_replace('/tag-link-count/', 'badge badge-dark badge-pill', $widget_output);
			$count = preg_replace('/\(\D\)/', '', $widget_output);
            $widget_output = preg_replace('/\(/', '', $widget_output);
			$widget_output = preg_replace('/\)/', '', $widget_output);
		}
        return $widget_output;
    }
    add_filter( 'widget_output', 'knowing_god_change_default_widget', 10, 4 );
endif;

// add_action( 'pre_get_posts',  'knowing_god_set_posts_per_page'  );
function knowing_god_set_posts_per_page( $query ) {

    if ( $query->is_category ) {
        $query->set( 'posts_per_page', 1 );
    }
  return $query;
}

/**
 * Load Additional plugin installation.
 */
require_once get_template_directory() . '/theme_plugin/plugin-activate-config.php';

require get_template_directory() . '/widgets/class-knowing-god-widget-recent-posts.php';

require get_template_directory() . '/widgets/class-knowing-god-widget-recent-courses.php';

require get_template_directory() . '/widgets/class-knowing-god-widget-search.php';
/**
 * Register theme widgets.
 */
function knowing_god_register_widgets() {
    register_widget( 'Knowing_God_Widget_Recent_Posts' );
	register_widget( 'Knowing_God_Widget_Recent_Courses' );
	// register_widget( 'Knowing_God_Widget_Search' );
}
add_action( 'widgets_init', 'knowing_god_register_widgets' );

if( ! function_exists( 'knowing_god_get_excerpt' ) ) {
    /**
     * Function to get the excerpt to display
     *
     * @since 1.0
     * @param int $from - start position.
     * @param int $count - characters to get.
     * @return string
     */
    function knowing_god_get_excerpt( $from = 0, $count = 200 ) {
      $excerpt = get_the_excerpt();
      $excerpt = strip_tags( $excerpt );
      $length = strlen( $excerpt );
      $excerpt = substr( $excerpt, $from, $count );
      $excerpt = substr( $excerpt, $from, strripos( $excerpt, " " ) );
      if($length > $count)
      $excerpt = $excerpt  .'...';
      return $excerpt;
    }
}

/**
 * Load Additional plugin installation.
 */
require_once get_template_directory() . '/theme_plugin/plugin-activate-config.php';
require_once get_template_directory() . '/admin/theme-filters.php';

/**
 * Function to add additional call for Primary Menu
 */
function knowing_god_menu_classes( $classes, $item, $args ) {
  if($args->theme_location == 'primary-menu' && $item->menu_item_parent == 0 ) {
    $classes[] = 'nav-item dropdown';
  }
  return $classes;
}
add_filter('nav_menu_css_class','knowing_god_menu_classes',1,3);

/**
 * Function to replace default class
 */
function knowing_god_submenu_class( $menu ) {
    $menu = preg_replace( '/ class="sub-menu"/','/ class="dropdown-menu" /', $menu );
    return $menu;
}
add_filter('wp_nav_menu','knowing_god_submenu_class');

// Add 'class' attribute
add_filter( 'nav_menu_link_attributes', 'knowing_god_nav_menu_link_atts', 10, 4 );
function knowing_god_nav_menu_link_atts( $atts, $item, $args, $depth ) {
    $new_atts = array();
    if($args->theme_location == 'primary-menu' && $item->menu_item_parent == 0 ) {
        $new_atts = array( 'data-toggle' => 'dropdown', 'class' => 'nav-link dropdown-toggle' );
        if ( isset( $atts['href'] ) ) {
            $new_atts['href'] = $atts['href'];
        } else if ( isset( $atts['href'] ) ) {
            $new_atts = array( 'href' => $atts['href'] );
        }
    } else {
        if ( isset( $atts['href'] ) ) {
            $new_atts['href'] = $atts['href'];
        }
    }
    return $new_atts;
}

add_action( 'wp_ajax_knowing_god_quiz_modal_confirm', 'knowing_god_quiz_modal_confirm' );
add_action( 'wp_ajax_nopriv_knowing_god_quiz_modal_confirm', 'knowing_god_quiz_modal_confirm' );

function knowing_god_quiz_modal_confirm() {
    global $wpdb;
    $post_id = $_POST['post_id'];
	$course_id = $_POST['course_id'];
	$user_id = 0;
	$wp_user_id = 0;
	if ( is_user_logged_in() ) {
		$user_id = knowing_god_get_lms_user_id();
		$wp_user_id = get_current_user_id();
	}

    $quiz_id = get_post_meta( $post_id, 'quiz_id', true );
    if ( $quiz_id > 0 ) {
        $quiz_row = $wpdb->get_row( sprintf( "SELECT * FROM quizzes WHERE id = %d", $quiz_id  ) );
        if ( $quiz_row ) :
            // Let us make this quiz as completed!!. Because there is no way to track this except here!
			$check = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %s WHERE content_id = %d AND user_id = %d AND type = "quiz" AND course_id = %d', TBL_LMS_LMSCONTENTS_TRACK, $post_id, $user_id, $course_id  ) );
			if ( empty( $check ) ) {
				$record = array(
					'content_id' => $post_id,
					'user_id' => $user_id,
					'wp_user_id' => $wp_user_id,
					'status' => 'completed',
					'type' => 'quiz',
					'course_id' => $course_id,
					'content_type' => 'post',
					'created_at' => date('Y-m-d H:i:s'),
				);
				$wpdb->insert(TBL_LMS_LMSCONTENTS_TRACK, $record );
			} else {

			}
			echo '<a class="btn btn-secondary" href="' . esc_url( knowing_god_urls( 'take-quiz' ) ) . $quiz_row->slug . '">' . __( 'Yes', 'knowing-god' ) . '</a>';
        endif;
    } else {

    }
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

add_action( 'wp_ajax_knowing_god_global_modal', 'knowing_god_global_modal' );
add_action( 'wp_ajax_nopriv_knowing_god_global_modal', 'knowing_god_global_modal' );
function knowing_god_global_modal() {
    echo $_POST['post_id'];

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

add_action( 'wp_ajax_knowing_god_global_modal_save', 'knowing_god_global_modal_save' );
add_action( 'wp_ajax_nopriv_knowing_god_global_modal_save', 'knowing_god_global_modal_save' );
function knowing_god_global_modal_save() {
	global $wpdb;

	$record = array();
	$record['slug'] = wp_generate_password(40, false);
	$record['content_id'] = $_POST['content_id'];
	$record['conten_type'] = 'post';
	if ( is_user_logged_in() ) {

	} else {
		$record['user_id'] = 0;
		$record['full_name'] = $_POST['full_name'];
		$record['email'] = $_POST['full_name'];
	}
	$record['description'] = $_POST['description'];
	$record['url'] = $_SERVER['HTTP_REFERER'];
	$record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$record['ip_address'] = knowing_god_getRealIpAddr();
	$record['type'] = 'translation';

	$wpdb->insert( 'translation_siteissues', $record );
	esc_html_e( 'We received your request we will get back to you soon', 'knowing-god' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}


function knowing_god_icon_row() {
    global $wpdb;
    $icon_row = array(
        'icon_image' => 'Icon Image',
        'file_word' => 'File Word',
        'file_ppt' => 'File PPT',
        'file_pdf' => 'File PDF',
    );
    $meta = get_post_meta( get_the_ID() );
    ?>
	<span class="icon-row">
    <?php if ( ! empty( $meta['file_pdf'] ) ) : ?>
    <a class="mr-2" href="<?php echo esc_url( $meta['file_pdf'][0] ); ?>"><span class="mr-2"><i class="fa fa-file-pdf-o"></i></span>
    </a>
    <?php else : ?>
    <span class="mr-2"><i class="fa fa-file-pdf-o"></i></span>
    <?php endif; ?>

    <?php if ( ! empty( $meta['file_ppt'] ) ) : ?>
    <a class="mr-2" href="<?php echo esc_url( $meta['file_ppt'][0] ); ?>"><span class="mr-2"><i class="fa fa-file-powerpoint-o"></i></span>
    </a>
    <?php else : ?>
    <span class="mr-2"><i class="fa fa-file-powerpoint-o"></i></span>
    <?php endif; ?>

    <?php if ( ! empty( $meta['file_word'] ) ) : ?>
    <a class="mr-2" href="<?php echo esc_url( $meta['file_word'][0] ); ?>"><span class="mr-2"><i class="fa fa-file-word-o"></i></span>
    </a>
    <?php else : ?>
    <span class="mr-2"><i class="fa fa-file-word-o"></i></span>
    <?php endif; ?>

	<a class="mr-2" href="<?php echo esc_url( comments_link() ); ?>"><span class="mr-2"><i class="fa fa-comments-o"></i></span>
    </a>


    <?php if ( ! empty( $meta['show_share_icon'] ) && $meta['show_share_icon'][0] === 'yes' ) : ?>
    <a class="mr-2" href="#shareModal" data-toggle="modal"><i class="fa fa-share"></i></a>
    <?php endif; ?>
    <?php if ( ! empty( $meta['show_globe_icon'] ) && $meta['show_globe_icon'][0] === 'yes' ) : ?>
    <!-- <a class="mr-2" href="#globalModal" data-toggle="modal"><i class="fa fa-globe"></i></a> -->
	<a class="mr-2" href="#" onclick="globalModal( <?php echo get_the_ID(); ?> )"><i class="fa fa-globe"></i></a>
    <?php endif; ?>
	
	<?php
	$post_groups = post_groups( get_the_ID() );
	if ( count( $post_groups ) > 0 ) { ?>
	<a class="mr-2" href="#" onclick="getData( <?php echo get_the_ID(); ?>, 0, 'postgroups' )"><i class="fa fa-group"></i></a>
	<?php } else {
	?>
    <span class="mr-2"><i class="fa fa-group"></i></span>
	<?php } ?>
    <?php
    if ( ! empty( $meta['quiz_id'] ) ) :
        $quiz_row = $wpdb->get_row( sprintf( "SELECT * FROM quizzes WHERE id = %d", $meta['quiz_id'][0]  ) );
		$course_id = 0;
		if ( knowing_god_get_series_id() ) {
			$course_id = knowing_god_get_series_id();
		}
        if ( $quiz_row ) :
            if ( is_user_logged_in() ) : ?>
            <a class="mr-2 quizConfirm" href="#" data-post_id="<?php echo get_the_ID(); ?>" data-course_id="<?php echo $course_id; ?>"><i class="fa fa-graduation-cap"></i></a>
            <?php else : ?>
            <a class="mr-2" href="javascript:void(0);" onclick="show_login('<?php echo $quiz_row->id; ?>')" ><i class="fa fa-graduation-cap"></i></a>
            <?php endif; ?>
        <?php else : ?>
        <span class="mr-2"><i class="fa fa-graduation-cap"></i></span>
        <?php endif;
    else :
    ?>
    <span class="mr-2"><i class="fa fa-graduation-cap"></i></span>
    <?php endif; ?>
	</span>
    <?php
}

add_action( 'login_form_logout', function () {
    global $wpdb;
	$user = wp_get_current_user();

	unset($_COOKIE['kg_user']);
	setcookie('kg_user', null, -1, '/');
	
	$wpdb->query( "UPDATE users SET is_wp_loggedin='no' WHERE id = " . knowing_god_get_lms_user_id() );

    wp_logout();

    if ( ! empty( $_REQUEST['redirect_to'] ) ) {
        $redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
    } else {
        $redirect_to = 'wp-login.php?loggedout=true';
        $requested_redirect_to = '';
    }

    /**
     * Filters the log out redirect URL.
     *
     * @since 4.2.0
     *
     * @param string  $redirect_to           The redirect destination URL.
     * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User $user                  The WP_User object for the user that's logging out.
     */
    $redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
    wp_safe_redirect( $redirect_to );
    exit;
});

define('URL_FRONTEND_LMSSERIES', get_site_url() . '/' . LMS_FOLDER .  '/lms/course/');

add_filter('get_search_form', 'knowing_god_search_form_text');

function knowing_god_search_form_text($text) {
     $text = str_replace( 'value="Search"', 'value="' . esc_html__( 'Go!', 'knowing-god' ) . '"', $text ); //set as value the text you want
     return $text;
}

//Exclude pages from WordPress Search
if ( ! is_admin() ) {
	function knowing_god_search_filter($query) {
		if ( $query->is_search ) {
			$query->set('post_type', 'post');
		}
		return $query;
	}
	add_filter('pre_get_posts','knowing_god_search_filter');
}

function knowing_god_get_user_role( $user = null ) {
	$user = $user ? new WP_User( $user ) : wp_get_current_user();
	return $user->roles ? $user->roles[0] : false;
}

/**
 * Generate custom search form
 *
 * @param string $form Form HTML.
 * @return string Modified form HTML.
 */
function knowing_god_search_form( $form ) {
    /*
	$form = '
	<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
	<div class="card-body"><div class="input-group"><input type="text" placeholder="Search for..." class="form-control" value="' . get_search_query() . '" name="s" id="s"> <span class="input-group-btn"><button type="submit" class="btn btn-secondary">'. esc_attr__( 'Go' ) .'</button></span></div></div>
    </form>';
	*/

	$form = '<form method="get" action="' . knowing_god_urls( 'search-action' ) . '">
	<div class="input-group kg-advanced-search">
  <input type="text" class="form-control" placeholder="Search …" aria-label="Search …" aria-describedby="basic-addon2" name="s" value="' . get_search_query() . '">
   <div class="btn-group">
    <button type="button" class="  kg-filter-dropdown-arrow dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
      <span class="caret"></span>
    </button>
    <div class="dropdown-menu kg-filter-dropdown-menu">
     <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="posts" checked>Posts
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="courses" checked>Courses
      </label>
    </div>
    <div class="form-check ">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="articles" checked>Articles
      </label>
    </div>
    </div>
     <button type="submit" class="btn btn-secondary">Go !</button>
  </div>
	</div>
	</form>';
    return $form;
}
add_filter( 'get_search_form', 'knowing_god_search_form' );

function knowing_god_getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

add_action( 'wp_ajax_knowing_god_siteissue_modal_save', 'knowing_god_siteissue_modal_save' );
add_action( 'wp_ajax_nopriv_knowing_god_siteissue_modal_save', 'knowing_god_siteissue_modal_save' );
function knowing_god_siteissue_modal_save() {
	global $wpdb;

	$record = array();
	$record['slug'] = wp_generate_password(40, false);
	$record['content_id'] = $_POST['content_id'];
	$record['conten_type'] = 'post';
	if ( is_user_logged_in() ) {

	} else {
		$record['user_id'] = $_POST['user_id'];
		$record['full_name'] = $_POST['full_name'];
		$record['email'] = $_POST['email'];
	}
	$record['description'] = $_POST['description'];
	if ( ! empty( $_POST['issue_url'] ) ) {
		$record['url'] = $_POST['issue_url'];
	} else {
		$record['url'] = $_SERVER['HTTP_REFERER'];
	}
	$record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$record['ip_address'] = knowing_god_getRealIpAddr();
	$record['type'] = 'siteissue';

	$wpdb->insert( 'translation_siteissues', $record );
	esc_html_e( 'We received your request we will get back to you soon', 'knowing-god' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

add_action( 'wp_ajax_knowing_god_newsletter_modal_save', 'knowing_god_newsletter_modal_save' );
add_action( 'wp_ajax_nopriv_knowing_god_newsletter_modal_save', 'knowing_god_newsletter_modal_save' );
function knowing_god_newsletter_modal_save() {
	global $wpdb;

	$check = $wpdb->get_results( 'SELECT * FROM translation_siteissues WHERE email = "' . $_POST['email'] . '" AND type="newsletter"' );

	if ( empty( $check ) ) {
		$record = array();
		$record['slug'] = wp_generate_password(40, false);
		$record['email'] = $_POST['email'];
		$record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$record['ip_address'] = knowing_god_getRealIpAddr();
		$record['type'] = 'newsletter';
		$wpdb->insert( 'translation_siteissues', $record );
	}
	esc_html_e( 'We received your request we will get back to you soon', 'knowing-god' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

/**
 * Filter to add additional attributes to navigation links
 */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args ) {
	if ( 'siteissuesModal' === $item->classes[0] ) {
		$atts['data-toggle'] = 'modal';
	}

	return $atts;
}, 10, 3 );

add_filter( 'body_class', 'knowing_god_body_class' );
function knowing_god_body_class( $classes ) {
	global $post;
	if ( ! empty( $post ) ) {
		if( has_shortcode( $post->post_content, 'knowing_god_banner') ) {
			$classes[] = 'banner-added';
		} elseif( has_shortcode( $post->post_content, 'knowing_god_easy_questions_banner') ) {
			$classes[] = 'banner-added';
		}
	}
    return $classes;
}

add_filter('post_class', 'knowing_god_post_class');
function knowing_god_post_class($classes){
	if ( is_singular() && knowing_god_has_series() ) {
		$classes[] = 'category-pathway-challenge';
	}
	return $classes;
}

// Share Content Start
add_action( 'wp_ajax_sharecontent', 'knowing_god_sharecontent' );
add_action( 'wp_ajax_nopriv_sharecontent', 'knowing_god_sharecontent' );
function knowing_god_sharecontent() {
	global $wpdb;
    $course_id = $_POST['course_id'];
	$lesson_id = $_POST['lesson_id'];
	$lesson = $wpdb->get_results( 'SELECT * FROM lmscontents WHERE id = ' . $lesson_id );

	$course = $wpdb->get_results( 'SELECT * FROM lmsseries WHERE id = ' . $course_id );
	if ( ! empty( $lesson ) ) {
		$lesson = $lesson[0];
	}
	if ( ! empty( $course ) ) {
		$course = $course[0];
	}
	ob_start();
	if ( ! empty( $lesson ) && ! empty( $course ) ) {
	?>
	<?php
	$title = $lesson->title;
	$link = $_SERVER['HTTP_REFERER'];
	$site_title = get_bloginfo( 'name' );

	$link = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $lesson->slug;

	$video_background_image = IMAGES . '900x400.png';
	if ( ! empty( $lesson->video_background_image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $lesson->video_background_image ) ) {
		$video_background_image = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $lesson->video_background_image;
	}

	?>
	<ul class="socialshare">
		<li><a href="https://twitter.com/intent/tweet?text=<?php  echo htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( $link ) . '&via=' . urlencode( $site_title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-twitter"></i></a></li>

		<li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $link ); ?>&title=<?php echo urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-facebook"></i></a></li>

		<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $link . '&amp;media=' . $video_background_image . '&description=' . urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-pinterest"></i></a></li>

		<li><a href="http://plus.google.com/share?url=<?php echo  $link; ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-google-plus"></i></a></li>
	</ul>
	<?php
	}
	$html = ob_get_clean();
	echo json_encode( array( 'html' => $html, 'title' => esc_html__( 'Share content', 'knowing-god' ) ) );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}
// Share Content End

// Translation Request Start
add_action( 'wp_ajax_translation', 'knowing_god_translation' );
add_action( 'wp_ajax_nopriv_translation', 'knowing_god_translation' );
function knowing_god_translation() {
	global $wpdb;
    $course_id = $_POST['course_id'];
	$lesson_id = $_POST['lesson_id'];
	$lesson = $wpdb->get_results( 'SELECT * FROM lmscontents WHERE id = ' . $lesson_id );

	$course = $wpdb->get_results( 'SELECT * FROM lmsseries WHERE id = ' . $course_id );
	if ( ! empty( $lesson ) ) {
		$lesson = $lesson[0];
	}
	if ( ! empty( $course ) ) {
		$course = $course[0];
	}
	ob_start();
	if ( ! empty( $lesson ) && ! empty( $course ) ) {
	?>
	<?php
	$title = $lesson->title;
	$link = $_SERVER['HTTP_REFERER'];
	$site_title = get_bloginfo( 'name' );

	$link = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $lesson->slug;

	$video_background_image = IMAGES . '900x400.png';
	if ( ! empty( $lesson->video_background_image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $lesson->video_background_image ) ) {
		$video_background_image = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $lesson->video_background_image;
	}

	?>
	<?php if( ! is_user_logged_in() ) : ?>
		<fieldset class="form-group">
			<input type="text" class="form-control" name="full_name" id="full_name" value="" placeholder="<?php esc_html_e( 'Full Name *', 'knowing-god' ); ?>" required="true">
		</fieldset>
		<fieldset class="form-group">
			<input type="email" class="form-control" name="email" id="email" value="" placeholder="<?php esc_html_e( 'Email *', 'knowing-god' ); ?>" required="true">
		</fieldset>
	<?php endif; ?>
	<input type="hidden" id="current_user_id" name="current_user_id" value="<?php echo get_current_user_id(); ?>">

	<fieldset class="form-group">
	<textarea type="text" class="form-control" name="description" id="description" value="<?php echo esc_attr( $actual_link ); ?>" placeholder="<?php esc_html_e( 'Enter your description here', 'knowing-god' ); ?>" rows="5" required="true"></textarea>
	</fieldset>
	<input type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $lesson_id; ?>">
	<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id; ?>">
	<input type="hidden" name="specific_action" id="specific_action" value="translation_save">
	<?php
	}
	$html = ob_get_clean();

	ob_start();
	?>
	<button type="button" class="btn btn-info" onclick="saveGenericForm()"><?php esc_html_e( 'Send Request', 'knowing-god' ); ?></button>
	<?php
	$footer = ob_get_clean();
	echo json_encode( array( 'html' => $html, 'footer' => $footer, 'title' => esc_html__( 'Enter Your Request Here', 'knowing-god' ) ) );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

function knowing_god_get_lms_user_id() {
	global $wpdb;
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$lms_user_id = get_user_meta( $user_id, 'lms_user_id', TRUE);
		if ( ! empty( $lms_user_id ) ) {
			$user_id = $lms_user_id;
		} else {
			// If any case it dont find LMS user id in WP DB! Let us take it from LMS DB directly
			$user = wp_get_current_user();
			if ( $user ) {
				$lms_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM users WHERE username = %s ", $user->user_login ) );
				if ( ! empty( $lms_user ) ) {
					$user_id = $lms_user[0]->id;
				}
			}

		}
	} else {
		$user_id = 0;
	}
	return $user_id;
}

add_action( 'wp_ajax_save_generic_form', 'knowing_god_save_generic_form' );
add_action( 'wp_ajax_nopriv_save_generic_form', 'knowing_god_save_generic_form' );
function knowing_god_save_generic_form() {
	global $wpdb;

	$action = $_POST['specific_action'];
	$user_id = knowing_god_get_lms_user_id();
	$wp_user_id = get_current_user_id();

	if ( 'translation_save' === $action ) {
		$record = array();
		$record['slug'] = wp_generate_password(40, false);
		$record['content_id'] = $_POST['lesson_id'];
		$record['conten_type'] = 'lesson';
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$record['user_id'] = $user_id;
			$record['full_name'] = $current_user->display_name;
			$record['email'] = $current_user->user_email;
		} else {
			$record['user_id'] = 0;
			$record['full_name'] = $_POST['full_name'];
			$record['email'] = $_POST['email'];
		}
		$record['description'] = $_POST['description'];
		$record['url'] = $_SERVER['HTTP_REFERER'];
		$record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$record['ip_address'] = knowing_god_getRealIpAddr();
		$record['type'] = 'translation';
		$record['wp_user_id'] = $wp_user_id;
		$wpdb->insert( 'translation_siteissues', $record );
		esc_html_e( 'We received your request we will get back to you soon', 'knowing-god' );
	} elseif ( 'save_comments' === $action ) {
		$record = array(
			'content_id' => $_POST['lesson_id'],
			'user_id' => $user_id,
			'comments_notes' => $_POST['description'],
			'type' => 'comments',
			'created_at' => date('Y-m-d H:i:s'),
			'wp_user_id' => $wp_user_id,
		);
		$wpdb->insert( 'lmscontents_comments', $record );
		esc_html_e( 'Your comment submitted successfully', 'knowing-god' );
	} elseif ( 'lms_track' === $action ) {
		$message = esc_html__( 'Please login to do this operation', 'knowing-god' );
		if ( is_user_logged_in() ) {
			$course_id = $_POST['course_id'];
			$user_id = knowing_god_get_lms_user_id();
			$wp_user_id = get_current_user_id();
			if ( 'text' === $_POST['type'] ) {
				$record = array(
					'content_id' => $_POST['content_id'],
					'user_id' => $user_id,
					'status' => 'completed',
					'type' => $_POST['type'],
					'course_id' => $_POST['course_id'],
					'content_type' => 'post',
					'wp_user_id' => $wp_user_id,
					'created_at' => date('Y-m-d H:i:s'),
				);
				$wpdb->insert( TBL_LMS_LMSCONTENTS_TRACK, $record );
				$message = esc_html__('Marked as completed', 'knowing-god');
			} elseif ( 'text-uncomplete' === $_POST['type'] ) {
				$wpdb->delete(TBL_LMS_LMSCONTENTS_TRACK,
					array(
						'content_id' => $_POST['content_id'],
						'user_id' => $user_id,
						'type' => 'text',
						'course_id' => $_POST['course_id'],
						'content_type' => 'post',
						'wp_user_id' => $wp_user_id,
						));
				$message = esc_html__('Marked as un complete', 'knowing-god');
			}
			$is_completed = 'no';
			if ( ! empty( $course_id ) ) {
				$check = knowing_god_get_my_courses( $user_id, array( 'course_id' => $course_id ) );

				if ( empty( $check ) ) {
					$mycourse = array(
						'user_id' => $user_id,
						'course_id' => $course_id,
						'content_type' => 'postsseries',
						'wp_user_id' => $wp_user_id,
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
					);
					$wpdb->insert( TBL_LMS_USERS_MY_COURSES, $mycourse );
				}
				
				if ( $course_id > 0 ) {
					if ( knowing_god_is_series_completed( $course_id ) ) {
						mark_as_completed_course( $course_id );
					} else {
						$wpdb->query( $wpdb->prepare('UPDATE ' . TBL_LMS_USERS_MY_COURSES . ' SET course_status="running" WHERE user_id = %d AND course_id = %d AND content_type="postsseries"', $user_id, $course_id) );
					}
				}
				
				if ( knowing_god_is_post_completed( $_POST['content_id'], $course_id ) ) {
					$is_completed = 'yes';
				}
			}
		}
		echo json_encode( array('message' => $message, 'is_completed' => $is_completed ) );
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}
// Translation Request End

function mark_as_completed_course( $course_id )
{
	global $wpdb;
	if ( is_user_logged_in() ) {
		$record = array(
			'course_status' => 'completed',
			'course_completed' => date('Y-m-d H:i:s'),
		);
		$where = array(
			'user_id' => knowing_god_get_lms_user_id(),
			'course_id' => $course_id,
			'content_type' => 'postsseries',
		);
		$wpdb->update( TBL_LMS_USERS_MY_COURSES, $record, $where );
	}
}

add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
	global $wpdb;

	unset($_COOKIE['kg_user']);
	setcookie('kg_user', null, -1, '/');
	
	$wpdb->query( "UPDATE users SET is_wp_loggedin='no' WHERE id = " . knowing_god_get_lms_user_id() );

  wp_redirect( knowing_god_urls( 'lms-logout' ) );
  exit();
}

function knowing_god_delete_user( $user_id ) {
	global $wpdb;

	$user_obj = get_userdata( $user_id );

	$username = $user_obj->user_login;

	// Let us delete User from LMS also
	$lms_user = $wpdb->get_results( 'SELECT * FROM users WHERE username = "'.$username.'"' );

	if ( ! empty( $lms_user ) ) {
		$lms_user = $lms_user[0];
		$lms_user_id = $lms_user->id;
		$wpdb->delete( 'bookmarks', array( 'user_id' => $lms_user_id ) );
		// $wpdb->delete( 'couponcodes_usage', array( 'user_id' => $lms_user_id ) );
		// $wpdb->delete( 'donations', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'examtoppers', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'feedbacks', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'lmscontents_comments', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'lmscontents_track', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'lmsgroups', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'lmsgroups_users', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'messenger_messages', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'messenger_participants', array( 'user_id' => $lms_user_id ) );

		$wpdb->delete( 'quizresults', array( 'user_id' => $lms_user_id ) );
		// $wpdb->delete( 'subscriptions', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'translation_siteissues', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'users_completed_courses', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'users_my_courses', array( 'user_id' => $lms_user_id ) );
		$wpdb->delete( 'users', array( 'id' => $lms_user_id ) );
	}
}

add_action( 'delete_user', 'knowing_god_delete_user' );

function knowing_god_prevent_duplicate_lms_user( $user_login, $user_email, $errors ) {
    global $wpdb;

	$lms_user_username = $wpdb->get_results( sprintf( 'SELECT * FROM users WHERE username = "%s"', $user_login ) );
	print_r( $lms_user_username );
	die();

	$lms_user_email = $wpdb->get_results( sprintf( 'SELECT * FROM users WHERE email = "%s"', $user_email ) );

	if ( ! empty( $lms_user_username ) ) {
        $errors->add( 'lms_user_username', '<strong>ERROR</strong>: This Username is already exists in LMS Site.' );
    }
	if ( ! empty( $lms_user_email ) ) {
        $errors->add( 'lms_user_email', '<strong>ERROR</strong>: This Email is already exists in LMS Site.' );
    }
}
// add_action( 'wpmu_signup_user', 'knowing_god_prevent_duplicate_lms_user', 10, 3 );

// Comments Start
add_action( 'wp_ajax_comments', 'knowing_god_lesson_comments' );
add_action( 'wp_ajax_nopriv_comments', 'knowing_god_lesson_comments' );
function knowing_god_lesson_comments() {
	global $wpdb;
    $course_id = $_POST['course_id'];
	$lesson_id = $_POST['lesson_id'];
	$user_id = 0;
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$lms_user_id = get_user_meta( $user_id, 'lms_user_id', TRUE);
		if ( ! empty( $lms_user_id ) ) {
			$user_id = $lms_user_id;
		} else {
			// If any case it dont find LMS user id in WP DB! Let us take it from LMS DB directly
			$user = wp_get_current_user();
			if ( $user ) {
				$lms_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM users WHERE username = %s ", $user->user_login ) );
				if ( ! empty( $lms_user ) ) {
					$user_id = $lms_user[0]->id;
				}
			}

		}
	}
	$records = $wpdb->get_results( "SELECT lmscontents_comments.*,users.image,users.name FROM lmscontents_comments
	INNER JOIN users ON users.id = lmscontents_comments.user_id WHERE user_id =  $user_id AND content_id = $lesson_id AND type='comments' ORDER BY lmscontents_comments.created_at DESC" );

	ob_start(); ?>
	<textarea class="form-control ng-pristine ng-valid ng-touched" id="description" rows="5" placeholder="<?php esc_html_e('Enter Commnets Here', 'knowing-god'); ?>" name="description" cols="50"></textarea>
	<input name="lesson_id" id="lesson_id" value="<?php echo $lesson_id; ?>" type="hidden">
	<input name="specific_action" id="specific_action" value="save_comments" type="hidden">
	<?php
	$str = '<ul class="ag-media-list"><li>No Comments</li></ul>';
	if ( ! empty( $records )) {
		$str = '<ul class="ag-media-list">';
		foreach ( $records as $record ) {
			// $image = getProfilePath($record->image);
			$image = LMS_HOST . LMS_FOLDER . '/public/uploads/users/thumbnail/' . $record->image;
			$str .= '<li class="media"><img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$record->name.'"/>&nbsp;' . $record->comments_notes . '&nbsp;<small>'.human_time_diff( strtotime( $record->created_at ), current_time('timestamp') ) .' ago</small></li>';
		}
		$str .= '</ul>';
	}
	// echo $str;
	$html = ob_get_clean();
	echo json_encode( array( 'html' => $html, 'generic_other_list' => $str, 'footer' => '<button type="button" class="btn btn-info" id="btn-comments" onclick="saveGenericForm()">Submit</button>', 'title' => esc_html__( 'Add Comments', 'knowing-god' ) ) );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

add_action( 'wp_ajax_postgroups', 'knowing_god_postgroups' );
add_action( 'wp_ajax_nopriv_postgroups', 'knowing_god_postgroups' );
function knowing_god_postgroups() {
	global $wpdb;
    $course_id = $_POST['course_id'];
	$lesson_id = $_POST['lesson_id'];
	$user_id = 0;
	$records = post_groups( $course_id );

	ob_start(); ?>
	<?php
	$str = '<table class="ag-media-list"><tr><td>No Comments</td></tr></table>';
	if ( ! empty( $records )) {
		$str = '<table class="ag-media-list" width="100%">';
		foreach ( $records as $record ) {
			// $image = getProfilePath($record->image);
			$image = LMS_HOST . LMS_FOLDER . '/public/uploads/lms/categories/default.png';
			if ( ! empty( $record->image ) ) {
				$image = LMS_HOST . LMS_FOLDER . '/public/uploads/lms/groups/thumbnail/' . $record->image;
			}
			$substr = '';
			if ( $record->total_lessons > 0 ) {
				$substr .= '<br>' . esc_html__('Lessons : ', 'knowing-god') . $record->total_lessons;
			}
			if ( $record->total_courses > 0 ) {
				$substr .= '<br>' . esc_html__('Courses : ', 'knowing-god') . $record->total_courses;
			}
			if ( $record->total_posts > 0 ) {
				$substr .= '<br>' . esc_html__('Posts : ', 'knowing-god') . $record->total_posts;
			}
			$str .= '<tr><td><span class="media"><img class="d-flex mr-3 icn-size" src="' . $image . '"   title="' . $record->title . '"/></span></td><td><a href="' . knowing_god_urls('group-dashboard') . $record->slug . '">' . $record->title . '</a></td><td>' . $substr . '</td></tr>';
		}
		$str .= '</table>';
	}
	// echo $str;
	$html = '';
	echo json_encode( array( 'html' => $str, 'generic_other_list' => '', 'footer' => '', 'title' => esc_html__( 'Post Groups', 'knowing-god' ) ) );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

add_action( 'wp_ajax_coursegroups', 'knowing_god_coursegroups' );
add_action( 'wp_ajax_nopriv_coursegroups', 'knowing_god_coursegroups' );
function knowing_god_coursegroups() {
	global $wpdb;
    $course_id = $_POST['course_id'];
	$lesson_id = $_POST['lesson_id'];
	$user_id = 0;
	$records = course_groups( $course_id );

	ob_start(); ?>
	<?php
	$str = '<table class="ag-media-list"><tr><td>No Comments</td></tr></table>';
	if ( ! empty( $records )) {
		$str = '<table class="ag-media-list" width="100%">';
		foreach ( $records as $record ) {
			// $image = getProfilePath($record->image);
			$image = LMS_HOST . LMS_FOLDER . '/public/uploads/lms/categories/default.png';
			if ( ! empty( $record->image ) ) {
				$image = LMS_HOST . LMS_FOLDER . '/public/uploads/lms/groups/thumbnail/' . $record->image;
			}
			$substr = '';
			$str .= '<tr><td><span class="media"><img class="d-flex mr-3 icn-size" src="' . $image . '"   title="' . $record->title . '"/></span></td><td><a href="' . knowing_god_urls('show-course') . $record->slug . '">' . $record->title . '</a></td><td>' . $substr . '</td></tr>';
		}
		$str .= '</table>';
	}
	// echo $str;
	$html = '';
	echo json_encode( array( 'html' => $str, 'generic_other_list' => '', 'footer' => '', 'title' => esc_html__( 'Post Groups', 'knowing-god' ) ) );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die();
    }
}

function knowing_god_the_slug( $echo=true ){
  $slug = basename( get_permalink() );
  do_action( 'before_slug', $slug );
  $slug = apply_filters( 'slug_filter', $slug );
  if( $echo ) {
	  echo $slug;
  }
  do_action('after_slug', $slug );
  return $slug;
}
// Comments End
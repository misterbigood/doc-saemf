<?php
/**
 * pacify functions and definitions
 *
 * @package pacify
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'pacify_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function pacify_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on pacify, use a find and replace
	 * to change 'pacify' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'pacify', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
//Add header image support 
add_theme_support( 'custom-header' );
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'pacify' ),
                'secondary' => __( 'Secondary Menu', 'pacify' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'pacify_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // pacify_setup
add_action( 'after_setup_theme', 'pacify_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function pacify_widgets_init() {
register_sidebar( array(
		'name'          => __( 'Sidebar', 'pacify' ),
		'id'            => 'sidebar-1',
		'description'   => (''),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
 register_sidebar( array(
'name' => __( 'Footer1', 'pacify' ),
'id' => 'footer-1',
'description' => __('Appears in the footer area', 'pacify'),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h2 class="widget-title">',
'after_title' => '</h2>',
) );
register_sidebar( array(
'name' => __( 'Footer2', 'pacify' ),
'id' => 'footer-2',
'description' => __('Appears in the footer area','pacify'),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h2 class="widget-title">',
'after_title' => '</h2>',
) );
register_sidebar( array(
'name' => __( 'Footer3', 'pacify' ),
'id' => 'footer-3',
'description' => __('Appears in the footer area','pacify'),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h2 class="widget-title">',
'after_title' => '</h2>',
) );
}
add_action( 'widgets_init', 'pacify_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function pacify_scripts() {
	wp_enqueue_style( 'pacify-style', get_stylesheet_uri() );

	wp_enqueue_script( 'pacify-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'pacify-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'pacify_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

add_theme_support( 'post-thumbnails' ); 

/*Logo Upload till here*/

/*remove dots*/
//function to replace invalid ellipsis with text linking to the post
function pacify_customize_excerpt( $more ) {
return '...';
}
add_filter('excerpt_more', 'pacify_customize_excerpt');
//*remove dots*///////

/*dynamic footer*/

/*load google fonts*/
   function pacify_load_fonts() {
            wp_register_style('pacify_googleFonts', '//fonts.googleapis.com/css?family=Lobster|Roboto:400,500|Droid+Serif');
            wp_enqueue_style( 'pacify_googleFonts');
        }
    
    add_action('wp_print_styles', 'pacify_load_fonts');

/*dynamic footer*/

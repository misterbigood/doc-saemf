<?php
/**
* Theme Setup Function
*/
function blackcolors_setup(){
	
	// Theme Support
	add_theme_support('post-thumbnails');
	add_theme_support('post-formats', array('aside', 'gallery'));
	add_theme_support('custom-background', array('default-color' => '#34495e'));
	add_theme_support('custom-header', array('default-image' => get_template_directory_uri() . '/img/logo.png', 'width' => '200px', 'height' => '200px'));
	add_theme_support('html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support('automatic-feed-links' );
	add_theme_support('title-tag');

	// Text domain
    load_theme_textdomain( 'blackcolors', get_template_directory() . '/languages' );

    // WordPress Content Width
    global $content_width;
    if ( !isset( $content_width ) ){
      $content_width = 1100;
    }
}
add_action('after_setup_theme', 'blackcolors_setup');

/**
* Customizer Options
*/
require get_template_directory() . '/inc/customizer-options.php';

/**
* Proper Theme Functions
*/
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
* Title Filter
*/
add_filter( 'wp_title', 'blackcolors_title_for_home' );
function blackcolors_title_for_home( $title )
{
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return get_bloginfo( 'name' );
  }
  return $title;
}

/**
* Header Menu
*/
function blackcolors_menu() {
  register_nav_menu('primary', __('Header Menu', 'blackcolors'));
}
add_action( 'init', 'blackcolors_menu' );


/**
* Widget Areas for footer
*/
function blackcolors_sidebar(){
  
  register_sidebars(2, array('name' => __('Footer %d', 'blackcolors'),
	  'before_widget' => '<div class="pie"><aside id="%1$s" class="widget %2$s"><ul class="xoxo">',
	  'after_widget'  => '</ul></aside></div>',
      'before_title' => '<h2 class="widgettitle">',
      'after_title' => '</h2>',)
  );

  register_sidebar( array('name' => 'Sidebar',
	  'id'            => 'sidebar-lateral',
	  'description'   => __('Menu Sidebar', 'blackcolors'),
	  'class'         => '',
	  'before_widget' => '<aside id="%1$s" class="widget %2$s"><ul class="xoxo">',
	  'after_widget'  => '</ul></aside>',
	  'before_title'  => '<h2 class="widgettitle">',
	  'after_title'   => '</h2>' )
  );

  register_sidebar( array('name' => 'Top Widget',
	  'id'            => 'breadcrumbs',
	  'description'   => __('Top Widget Area', 'blackcolors'),
	  'class'         => '',
	  'before_widget' => '<div class="top-breadcrumbs"><aside id="%1$s" class="widget %2$s"><ul class="xoxo">',
	  'after_widget'  => '</aside></div>',
	  'before_title'  => '<h2 class="widgettitle">',
	  'after_title'   => '</h2>' )
  );
}
add_action( 'widgets_init', 'blackcolors_sidebar' );

/**
* Styles and Scripts
*/
function blackcolors_scripts() {

	$minified_scripts = get_theme_mod('blackcolors_minified_scripts');
	$hide_fixedbar = get_theme_mod('blackcolors_effects');
	
	if($minified_scripts != ''){

	  wp_enqueue_style( 'blackcolors-style', get_template_directory_uri() . '/style.min.css' );
	  wp_enqueue_script( 'blackcolors-menu.js', get_template_directory_uri() . '/js/menu.min.js', array("jquery"), false, true );
	  if($hide_fixedbar != ''){
		wp_enqueue_script( 'blackcolors-effects.js', get_template_directory_uri() . '/js/effects.min.js', array("jquery"), false, true );
	  }

	}else{

	  wp_enqueue_style( 'blackcolors-style', get_stylesheet_uri() );
	  wp_enqueue_script( 'blackcolors-menu.js', get_template_directory_uri() . '/js/menu.js', array("jquery"), false, true );
	  if($hide_fixedbar != ''){
		wp_enqueue_script( 'blackcolors-effects.js', get_template_directory_uri() . '/js/effects.js', array("jquery"), false, true );
	  }
	
	}

	wp_enqueue_script( 'masonry' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/fonts/font-awesome/css/font-awesome.min.css' );

	// Fonts
	$custom_fonts = get_theme_mod('blackcolors_custom_fonts');
	if($custom_fonts == ''){

	  $body_font = esc_html(get_theme_mod('blackcolors_body_fonts'));

	  if( $body_font ) {

		if($body_font != 'Arial' or $body_font != 'Helvetica' or $body_font != 'Verdana')

		$fonts_cyrillic = get_theme_mod('blackcolors_fonts_cyrillic');
		$fonts_latin = get_theme_mod('blackcolors_fonts_latin');
		$fonts_greek = get_theme_mod('blackcolors_fonts_greek');

		if($fonts_cyrillic || $fonts_greek || $fonts_latin){

		  $body_font = $body_font . "&subset=";

		  if($fonts_cyrillic){

			$body_font = $body_font . "cyrillic-ext,";

		  }
		  if($fonts_latin){

			$body_font = $body_font . "latin,";

		  }
		  if($fonts_greek){

			$body_font = $body_font . "greek";

		  }

		}

		wp_enqueue_style( 'blackcolors-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );

	  }

	}
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'blackcolors_scripts' );

/*
* Allowed Tags for the Custom Excerpt
*/
function blackcolors_wpse_allowedtags() {
  // Add custom tags to this string
  return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
}

if ( ! function_exists( 'blackcolors_custom_wp_trim_excerpt' ) ) : 
/*
* Custom Excerpt
*/
function blackcolors_custom_wp_trim_excerpt($wpse_excerpt) {
$raw_excerpt = $wpse_excerpt;
	if ( '' == $wpse_excerpt ) {

		$wpse_excerpt = get_the_content('');
		$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
		$wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
		$wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
		$wpse_excerpt = strip_tags($wpse_excerpt, blackcolors_wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

		//Set the excerpt word count and only break after sentence is complete.
			$excerpt_word_count = 55;
			$excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
			$tokens = array();
			$excerptOutput = '';
			$count = 0;

			// Divide the string into tokens; HTML tags, or words, followed by any whitespace
			preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

			foreach ($tokens[0] as $token) { 

				if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
				// Limit reached, continue until , ; ? . or ! occur at the end
					$excerptOutput .= trim($token);
					break;
				}

				// Add words to complete sentence
				$count++;

				// Append what's left of the token
				$excerptOutput .= $token;
			}

		$wpse_excerpt = trim(force_balance_tags($excerptOutput));

		return $wpse_excerpt;   

	}
	return apply_filters('blackcolors_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
}
endif; 

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'blackcolors_custom_wp_trim_excerpt'); 
?>
<?php

/**
* Collect user saved data
* and print the top color boxes
*/
if(!function_exists('blackcolors_social_icons')):
/*
* Color Boxes
*/
function blackcolors_colorboxes(){

	$opcion = get_theme_mod("blackcolors_color_boxes");
	if($opcion != ''){
		echo '<div id="barra-superior">';
	
		$url = array();
		$url[0] = get_theme_mod("blackcolors_box1_url");
		$url[1] = get_theme_mod("blackcolors_box2_url");
		$url[2] = get_theme_mod("blackcolors_box3_url");
		$url[3] = get_theme_mod("blackcolors_box4_url");
		$url[4] = get_theme_mod("blackcolors_box5_url");
	
		$text = array();
		$text[0] = get_theme_mod("blackcolors_box1_text");
		$text[1] = get_theme_mod("blackcolors_box2_text");
		$text[2] = get_theme_mod("blackcolors_box3_text");
		$text[3] = get_theme_mod("blackcolors_box4_text");
		$text[4] = get_theme_mod("blackcolors_box5_text");
	
		$icon = array();
		$icon[0] = get_theme_mod("blackcolors_box1_icon");
		$icon[1] = get_theme_mod("blackcolors_box2_icon");
		$icon[2] = get_theme_mod("blackcolors_box3_icon");
		$icon[3] = get_theme_mod("blackcolors_box4_icon");
		$icon[4] = get_theme_mod("blackcolors_box5_icon");
	
		$icons_default = array("fa-home", "fa-envelope", "fa-user", "fa-camera", "fa-weixin");
		$color = array("#f1c40f", "#e67e22", "#e74c3c", "#3498db", "#9b59b6");
		for($i = 0; $i <=4; $i++): ?>
			<?php // Using that instead of the default option in get_theme_mod() to ensure compatibility with previous versions of the theme
			if(empty($icon[$i])){
				$icon[$i] = $icons_default[$i];
			} ?>
			<div class="caja-superior" style="background-color: <?php echo $color[$i]; ?>;">
				<div class="caja-centrado">
				<a class="enlacesuperior" href="<?php if(!empty($url[$i])){ echo esc_url($url[$i]);} ?>">
				<i class="fa <?php echo esc_attr($icon[$i]); ?>"></i>
				<div class="caja-centrado-texto"><?php if(!empty($text[$i])){ echo ent2ncr($text[$i]); } ?></div>
				</a>
				</div>
			</div>
		<?php endfor; 
		echo '</div>';
	}
}
endif;

/**
* Collect and Print the saved social icons
* saved by the user with the Customizer
*/
if(!function_exists('blackcolors_social_icons')): 
/*
* Social Icons
*/
function blackcolors_social_icons(){

	echo '<div id="social-icons">';

	$_social_services = array('fa-twitter' => 'Twitter',
									'fa-facebook' => 'Facebook',
									'fa-google-plus' => 'Google+',
									'fa-behance' => 'Behance',
									'fa-codepen' => 'Codepen',
									'fa-github' => 'GitHub',
									'fa-linkedin' => 'LinkeIn',
									'fa-skype' => 'Skype',
									'fa-youtube' => 'YouTube',
									'fa-slack' => 'Slack',
									'fa-tumblr' => 'Tumblr',
									'fa-yelp' => 'Yelp',
									'fa-instagram' => 'Instagram',
									'fa-pinterest' => 'Pinterest',
									'fa-flickr' => 'Flickr',
									'fa-stack-exchange' => 'Stack Exchange');

	foreach($_social_services as $id => $valor){
		$icon = esc_url(get_theme_mod("blackcolors_$id"));
		if(isset($icon) and !empty($icon)){
			echo "<a href='$icon' class='social-icon-round'><i class='fa $id'></i></a>";
		}
	}

	echo "</div>";
}
endif; 

if(!function_exists('blackcolors_the_post_navigation')):
/*
* Post Navigation
*/
function blackcolors_the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<!-- Post Footer -->
	<div class="post-mas entry-footer">
		 <div class="post-mas-izq">
			  <?php previous_post_link("<div class='button-pag'><i class='fa fa-arrow-circle-left'></i> %link</div>", '%title', TRUE); ?>
		 </div>
		 <div class="post-mas-der">
			  <?php next_post_link("<div class='button-pag'>%link <i class='fa fa-arrow-circle-right'></i></div>", '%title', TRUE); ?>
		 </div>
	</div>
	<?php
}
endif;


if ( ! function_exists( 'blackcolors_the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function blackcolors_the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( '%s', 'blackcolors' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( '%s', 'blackcolors' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'blackcolors' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'blackcolors' ), get_the_date( _x( 'Y', 'yearly archives date format', 'blackcolors' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'blackcolors' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'blackcolors' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'blackcolors' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'blackcolors' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'blackcolors' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'blackcolors' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'blackcolors' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'blackcolors' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'blackcolors' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

/**
* Collect and Print CSS styles
* saved by the user with the Customizer
*/
function blackcolors_my_estilos(){
    if(is_admin_bar_showing()){
      $top_superior = "28px";
    }else{
      $top_superior = "0";
    }

    /* User saved styles
    /* CSS styles for fixed bar are loaded directy in header.php, since fixed bar is not being showed in every page */
    $colortexto = esc_attr(get_theme_mod( 'blackcolors_texto_color', '#ffffff')); 	// Text Color
    $colorlinks = esc_attr(get_theme_mod( 'blackcolors_links_color', '#e67e22'));	// Links Color
    $colorhover = esc_attr(get_theme_mod( 'blackcolors_links_hover', '#f1c40f'));	// Likns Color on Hover
    $colorsocial = esc_attr(get_theme_mod( 'blackcolors_social_links', '#e67e22'));	// Social Icons Color
    $custom_fonts = get_theme_mod('blackcolors_custom_fonts');						// Fonts
    if($custom_fonts != ''){
        $body_font = '';
    }else{
        $body_font = esc_html(get_theme_mod('blackcolors_body_fonts', 'Open Sans'));
        $body_font = explode(":", $body_font);
        $body_font = "font-family: $body_font[0] ;";
    }
    $fixedbaralign = esc_attr(get_theme_mod( 'blackcolors_fixed_bar', 'right'));	// Text Align in Fixed Bar
  
    echo "<style>
    body{
        color: $colortexto ;
        $body_font
    }
    #barra-lateral{
        color: $colortexto ;
        padding-top: $top_superior ;
        text-align: $fixedbaralign ;
    }
    #barra-superior{
      top: $top_superior ; 
    }
    a, a:link, a:visited{
        color: $colorlinks ;
        text-decoration:none;
    }
    a:hover{
        color: $colorhover ;
    }
    #social-icons a, #social-icons a:visited{
        color: $colorsocial;
      }
    </style>";
}
add_action('wp_head', 'blackcolors_my_estilos');

function blackcolors_footer(){
	
	echo '<div id="creadopor">';
	
	if(is_home()){ // Print the credit links only in home. 
        printf( __( '%1$s for %2$s.', 'blackcolors' ), '<a href="http://antsanchez.com/blackcolors-wordpress-theme">Blackcolors</a>', '<a href="http://wordpress.org/">WordPress</a>' ); 
	}else{
		 printf( __( '%1$s for %2$s.', 'blackcolors' ), 'Blackcolors', 'WordPress' ); 
	}

	echo '</div>';

}
?>
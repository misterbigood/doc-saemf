<?php
/* Add customization settings and controls */
add_action('customize_register', 'blackcolors_customize');
function blackcolors_customize($wp_customize) {

    /* Fonts
	/*--------------------------------------*/
    $wp_customize->add_section(
        'blackcolors_typography',
        array(
            'title' => __('Blackcolors Fonts', 'blackcolors' ),
        )
    );

    $font_choices = 
      array(
        'Abel' => 'Abel',
        'Arial' => 'Arial',
        'Arimo:400,700,400italic,700italic' => 'Arimo',
        'Arvo:400,700,400italic,700italic' => 'Arvo',

        'Cabin:400,700' => 'Cabin',
        'Cuprum:400,400italic,700' => 'Cuprum',

        'Bitter:400,700,400italic' => 'Bitter',
        'Bree+Serif' => 'Bree Serif',

        'Dancing+Script:400,700' => 'Dancing Script',
        'Droid Sans:400,700' => 'Droid Sans',
        'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
        'Dosis:300,400,700' => 'Dosis',
      
        'Fira Sans:400,700,400italic,700italic' => 'Fira Sans',

        'Helvetica' => 'Helvetica',

        'Indie+Flower' => 'Indie Flower',
        'Inconsolata:400,700' => 'Inconsolata',

        'Josefin+Sans:400,700' => 'Josefin Sans',

        'Fjalla+One' => 'Fjalla One',

        'Karla:700,400,400italic' => 'Karla',

        'Lato:400,700,400italic,700italic' => 'Lato',
        'Lora:400,700,400italic,700italic' => 'Lora',
        'Lobster' => 'Lobster',
        'Libre+Baskerville:400,700,400italic' => 'Libre Baskerville',

        'Maven+Pro:400,700' => 'Maven Pro',
        'Merriweather:900,700' => 'Merriweather',
        'Montserrat:400,700' => 'Montserrat',
        'Muli:400,400italic' => 'Muli',

        'Noto+Sans:400,700,400italic,700italic' => 'Noto Sanas',
        'Nunito:400,300,700' => 'Nunito',
        
        'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
        'Oswald:400,700' => 'Oswald',
        'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
        'Oxygen:400,300,700' => 'Oxygen',
        
        'Pacifico' => 'Pacifico',
        'Play:400,700' => 'Play',
        'PT Sans:400,700,400italic,700italic' => 'PT Sans',
        'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
        'Playfair+Display:400,700' => 'Playfair Display',
        'Poiret+One' => 'Poiret One',

        'Raleway:400,700' => 'Raleway',
        'Roboto:400,400italic,700,700italic' => 'Roboto',
        'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
        'Roboto Slab:400,700' => 'Roboto Slab',
        'Rokkitt:400' => 'Rokkitt',

        'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
        'Slabo+27px;' => 'Slabo',
        'Shadows+Into+Light' => 'Shadows Into Light',
        'Signika:700,400' => 'Signika',

        'Titillium+Web:400,300,400italic,700' => 'Titillium Web',

        'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
        'Ubuntu+Condensed' => 'Ubuntu Condensed',

        'Verdana' => 'Verdana',
        'Vollkorn:400italic,400,700' => 'Vollkorn',

        'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
      );
    
    $wp_customize->add_setting(
      'blackcolors_body_fonts',
      array(
        'sanitize_callback' => 'blackcolors_sanitize_fonts',
      )
    );
    $wp_customize->add_control(
      'blackcolors_body_fonts',
      array(
        'type' => 'select',
        'priority'    => 1,
        'label' => __('Select your desired font.', 'blackcolors'),
        'section' => 'blackcolors_typography',
        'choices' => $font_choices
      )
    );

    // Cyrillic Fonts?
    $wp_customize->add_setting( 'blackcolors_fonts_cyrillic', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_fonts_cyrillic', array(
        'settings' => 'blackcolors_fonts_cyrillic',
        'label'    => __( 'Use Cyrillic fonts? ', 'blackcolors' ),
        'section'  => 'blackcolors_typography',
        'type'     => 'checkbox',
        'priority' => 2,
    ) ) );

    // Greek Fonts?
    $wp_customize->add_setting( 'blackcolors_fonts_greek', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_fonts_greek', array(
        'settings' => 'blackcolors_fonts_greek',
        'label'    => __( 'Use Greek fonts? ', 'blackcolors' ),
        'section'  => 'blackcolors_typography',
        'type'     => 'checkbox',
        'priority' => 3,
    ) ) );

    // Latin?
    $wp_customize->add_setting( 'blackcolors_fonts_latin', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_fonts_latin', array(
        'settings' => 'blackcolors_fonts_latin',
        'label'    => __( 'Use Latin fonts? ', 'blackcolors' ),
        'section'  => 'blackcolors_typography',
        'type'     => 'checkbox',
        'priority' => 4,
    ) ) );

    // Latin?
    $wp_customize->add_setting( 'blackcolors_custom_fonts', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_custom_fonts', array(
        'settings' => 'blackcolors_custom_fonts',
        'label'    => __( 'Let me use my own fonts. That will deactivate the theme control over the fonts', 'blackcolors' ),
        'section'  => 'blackcolors_typography',
        'type'     => 'checkbox',
        'priority' => 4,
    ) ) );
 
	/* Fixed bar background color"
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_barra_color', array(
        'default'        => '#2c3e50',
	'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_barra_color', array(
        'label'   => __('Fixed Bar Background Color', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_barra_color',
    ) ) );
    
	/* Text color
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_texto_color', array(
        'default'        => '#ffffff',
	'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_texto_color', array(
        'label'   => __('Text Color', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_texto_color',
    ) ) );
    
	/* Links color
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_links_color', array(
        'default'        => '#e67e22',
	'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_links_color', array(
        'label'   => __('Links Color', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_links_color',
    ) ) );
    
	/* Links color when hover
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_links_hover', array(
        'default'        => '#f1c40f',
	'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_links_hover', array(
        'label'   => __('Links color when mouse is hover', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_links_hover',
    ) ) );

    /* Social Links Color
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_social_links', array(
        'default'        => '#f1c40f',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_social_links', array(
        'label'   => __('Color for the social icons', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_social_links',
    ) ) );
    
	/* Galleries background color
	/*--------------------------------------*/
    $wp_customize->add_setting( 'blackcolors_galeria_color', array(
        'default'        => '#34495e',
		'sanitize_callback' => 'sanitize_hex_color',
    ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blackcolors_galeria_color', array(
        'label'   => __('Gallery Background Color', 'blackcolors'),
        'section' => 'colors',
        'settings'   => 'blackcolors_galeria_color',
    ) ) );

	/* Add Section for layout
	/*--------------------------------------*/
	$wp_customize->add_section( 'blackcolors_layout', array(
    'title'          => __( 'Blackcolors Layout', 'blackcolors' ),
	'description' => __( 'Change the main elements of the layout','blackcolors'),
	) );

    // Effects
    $wp_customize->add_setting( 'blackcolors_effects', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_effects', array(
        'settings' => 'blackcolors_effects',
        'label'    => __( 'Hide Fixed bar? ', 'blackcolors' ),
        'section'  => 'blackcolors_layout',
        'type'     => 'checkbox',
        'priority' => 2,
    ) ) );

    // Show color boxes?
    $wp_customize->add_setting( 'blackcolors_color_boxes', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_color_boxes', array(
        'settings' => 'blackcolors_color_boxes',
        'label'    => __( 'Show color boxes? ', 'blackcolors' ),
        'section'  => 'blackcolors_layout',
        'type'     => 'checkbox',
        'priority' => 3,
    ) ) );

    // Text align in fixed bar
    $wp_customize->add_setting( 'blackcolors_fixed_bar', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_fixedbaroptions'
    ) );
    $fixed_bar_options = array( 'right' => __('Right', 'blackcolors'),
                                'left' => __('Left', 'blackcolors'),
                                'center' => __('Center', 'blackcolors'));
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_fixed_bar', array(
        'settings' => 'blackcolors_fixed_bar',
        'label'    => __( 'Text align in Fixed Bar', 'blackcolors' ),
        'section'  => 'blackcolors_layout',
        'type'     => 'select',
        'choices'  => $fixed_bar_options,
        'priority' => 4,
    ) ) );

    
    /* Add Section for 404 Page
	/*--------------------------------------*/
	$wp_customize->add_section( 'blackcolors_errorpage', array(
    'title'          => __( 'Blackcolors 404', 'blackcolors' ),
	'description' => __( 'Customize your 404 Error page','blackcolors'),
	) );

    // 404 Message
    $wp_customize->add_setting( 'blackcolors_404_message', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_404_message', array(
		'settings' => 'blackcolors_404_message',
		'label'    => __( 'Write your custom 404 error message:', 'blackcolors' ),
		'section'  => 'blackcolors_errorpage',
		'type'     => 'textarea',
		'priority' => 5,
	) ) );

    // 404 Search Form
    $wp_customize->add_setting( 'blackcolors_404_search', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'blackcolors_sanitize_checkbox',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_404_search', array(
		'settings' => 'blackcolors_404_search',
		'label'    => __( 'Show search form in 404 error page?', 'blackcolors' ),
		'section'  => 'blackcolors_errorpage',
		'type'     => 'checkbox',
		'priority' => 6,
	) ) );

    /* Color Boxes
    /*-------------------------*/
    $wp_customize->add_section( 'blackcolors_boxes', array(
    'title'          => __( 'Blackcolors Boxes', 'blackcolors' ),
	'description' => __( 'Change the url, text and icons of every color box.<br>For the icons, just go to <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="blackcolors_blank">Fontawesome Icons</a>, select and copy in the respective textbox the name of the icons. For instance, to add the facebook icon, copy "fa-facebook"','blackcolors'),
	) );

	/* Text and URL for Box 1
	/*-------------------------*/
    for($i = 1; $i <= 5; $i++){
        $wp_customize->add_setting( 'blackcolors_box1_text', array(
            'type'           => 'theme_mod',
            'capability'     => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box1_text', array(
            'settings' => 'blackcolors_box1_text',
            'label'    => __( 'Text on Box 1: ', 'blackcolors' ),
            'section'  => 'blackcolors_boxes',
            'type'     => 'text',
            'priority' => 1,
        ) ) );
    
        $wp_customize->add_setting( 'blackcolors_box1_url', array(
            'type'           => 'theme_mod',
            'capability'     => 'edit_theme_options',
            'sanitize_callback' => 'esc_url',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box1_url', array(
            'settings' => 'blackcolors_box1_url',
            'label'    => __( 'URL on Box 1: ', 'blackcolors' ),
            'section'  => 'blackcolors_boxes',
            'type'     => 'text',
            'priority' => 2,
        ) ) );
    
        $wp_customize->add_setting( 'blackcolors_box1_icon', array(
            'type'           => 'theme_mod',
            'capability'     => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box1_icon', array(
            'settings' => 'blackcolors_box1_icon',
            'label'    => __( 'Icon on Box 1: ', 'blackcolors' ),
            'section'  => 'blackcolors_boxes',
            'type'     => 'text',						
            'priority' => 3,
        ) ) );
    }
	/* Text and URL for Box 2
	/*-------------------------*/
	$wp_customize->add_setting( 'blackcolors_box2_text', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box2_text', array(
		'settings' => 'blackcolors_box2_text',
		'label'    => __( 'Text on Box 2: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 4,
	) ) );

	$wp_customize->add_setting( 'blackcolors_box2_url', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box2_url', array(
		'settings' => 'blackcolors_box2_url',
		'label'    => __( 'URL on Box 2: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 5,
	) ) );

	$wp_customize->add_setting( 'blackcolors_box2_icon', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box2_icon', array(
		'settings' => 'blackcolors_box2_icon',
		'label'    => __( 'Icon on Box 2: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 6,
	) ) );

	/* Text and URL for Box 3
	/*-------------------------*/
	$wp_customize->add_setting( 'blackcolors_box3_text', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box3_text', array(
		'settings' => 'blackcolors_box3_text',
		'label'    => __( 'Text on Box 3: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'priority' => 7,
		'type'     => 'text',
	) ) );

	$wp_customize->add_setting( 'blackcolors_box3_url', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box3_url', array(
		'settings' => 'blackcolors_box3_url',
		'label'    => __( 'URL on Box 3: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 8,
	) ) );

	$wp_customize->add_setting( 'blackcolors_box3_icon', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box3_icon', array(
		'settings' => 'blackcolors_box3_icon',
		'label'    => __( 'Icon on Box 3: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 9,
	) ) );

	/* Text and URL for Box 4
	/*-------------------------*/
	$wp_customize->add_setting( 'blackcolors_box4_text', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box4_text', array(
		'settings' => 'blackcolors_box4_text',
		'label'    => __( 'Text on Box 4: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'priority' => 10,
		'type'     => 'text',
	) ) );

	$wp_customize->add_setting( 'blackcolors_box4_url', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box4_url', array(
		'settings' => 'blackcolors_box4_url',
		'label'    => __( 'URL on Box 4: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 11,
	) ) );

	$wp_customize->add_setting( 'blackcolors_box4_icon', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box4_icon', array(
		'settings' => 'blackcolors_box4_icon',
		'label'    => __( 'Icon on Box 4: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 12,
	) ) );

	/* Text and URL for Box 5
	/*-------------------------*/
	$wp_customize->add_setting( 'blackcolors_box5_text', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box5_text', array(
		'settings' => 'blackcolors_box5_text',
		'label'    => __( 'Text on Box 5: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'priority' => 13,
		'type'     => 'text',
	) ) );

	$wp_customize->add_setting( 'blackcolors_box5_url', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box5_url', array(
		'settings' => 'blackcolors_box5_url',
		'label'    => __( 'URL on Box 5: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 14,
	) ) );
    
	$wp_customize->add_setting( 'blackcolors_box5_icon', array(
		'type'           => 'theme_mod',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_box5_icon', array(
		'settings' => 'blackcolors_box5_icon',
		'label'    => __( 'Icon on Box 5: ', 'blackcolors' ),
		'section'  => 'blackcolors_boxes',
		'type'     => 'text',
		'priority' => 15,
	) ) );

    /* Add Section for Social Icons
	/*--------------------------------------*/
	$wp_customize->add_section( 'blackcolors_social_icons', array(
    'title'          => __( 'Blackcolors Social Icons', 'blackcolors' ),
    'description'    => __( 'Fill in the fields you wish to show with his correspondent url. For instance, for Twitter: https://twitter.com/your_username', 'blackcolors'),
	) );
    
    /* Add setting und control for every social icon
    /*--------------------------------------*/
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

    $a = 1;
    foreach($_social_services as $id => $valor){
        
        $wp_customize->add_setting( "blackcolors_$id", array(
            'type'           => 'theme_mod',
            'capability'     => 'edit_theme_options',
            'sanitize_callback' => 'esc_url',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "blackcolors_$id", array(
            'settings' => "blackcolors_$id",
            'label'    => $valor, // <- Translation function calls must NOT contain PHP variables. Var contains just non-translatable International Brands
            'section'  => 'blackcolors_social_icons',
            'type'     => 'text',
            'priority' => $a,
        ) ) );

        $a++;
    }

    /* Add SEO Section
	/*--------------------------------------*/
	$wp_customize->add_section( 'blackcolors_seo', array(
    'title'          => __( 'Blackcolors SEO', 'blackcolors' ),
	) );

    $wp_customize->add_setting( 'blackcolors_minified_scripts', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'blackcolors_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'blackcolors_minified_scripts', array(
        'settings' => 'blackcolors_minified_scripts',
        'label'    => __( 'Use Minified Scripts? ', 'blackcolors' ),
        'section'  => 'blackcolors_seo',
        'type'     => 'checkbox',
        'priority' => 1,
    ) ) );
}

/* Fonts sanitization
/*----------------------------------*/
function blackcolors_sanitize_fonts( $input ) {
    $valid = array(
        'Abel' => 'Abel',
        'Arial' => 'Arial',
        'Arimo:400,700,400italic,700italic' => 'Arimo',
        'Arvo:400,700,400italic,700italic' => 'Arvo',

        'Cabin:400,700' => 'Cabin',
        'Cuprum:400,400italic,700' => 'Cuprum',

        'Bitter:400,700,400italic' => 'Bitter',
        'Bree+Serif' => 'Bree Serif',

        'Dancing+Script:400,700' => 'Dancing Script',
        'Droid Sans:400,700' => 'Droid Sans',
        'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
        'Dosis:300,400,700' => 'Dosis',
      
        'Fira Sans:400,700,400italic,700italic' => 'Fira Sans',

        'Helvetica' => 'Helvetica',

        'Indie+Flower' => 'Indie Flower',
        'Inconsolata:400,700' => 'Inconsolata',

        'Josefin+Sans:400,700' => 'Josefin Sans',

        'Fjalla+One' => 'Fjalla One',

        'Karla:700,400,400italic' => 'Karla',

        'Lato:400,700,400italic,700italic' => 'Lato',
        'Lora:400,700,400italic,700italic' => 'Lora',
        'Lobster' => 'Lobster',
        'Libre+Baskerville:400,700,400italic' => 'Libre Baskerville',

        'Maven+Pro:400,700' => 'Maven Pro',
        'Merriweather:900,700' => 'Merriweather',
        'Montserrat:400,700' => 'Montserrat',
        'Muli:400,400italic' => 'Muli',

        'Noto+Sans:400,700,400italic,700italic' => 'Noto Sanas',
        'Nunito:400,300,700' => 'Nunito',
        
        'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
        'Oswald:400,700' => 'Oswald',
        'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
        'Oxygen:400,300,700' => 'Oxygen',
        
        'Pacifico' => 'Pacifico',
        'Play:400,700' => 'Play',
        'PT Sans:400,700,400italic,700italic' => 'PT Sans',
        'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
        'Playfair+Display:400,700' => 'Playfair Display',
        'Poiret+One' => 'Poiret One',

        'Raleway:400,700' => 'Raleway',
        'Roboto:400,400italic,700,700italic' => 'Roboto',
        'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
        'Roboto Slab:400,700' => 'Roboto Slab',
        'Rokkitt:400' => 'Rokkitt',

        'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
        'Slabo+27px;' => 'Slabo',
        'Shadows+Into+Light' => 'Shadows Into Light',
        'Signika:700,400' => 'Signika',

        'Titillium+Web:400,300,400italic,700' => 'Titillium Web',

        'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
        'Ubuntu+Condensed' => 'Ubuntu Condensed',

        'Verdana' => 'Verdana',
        'Vollkorn:400italic,400,700' => 'Vollkorn',

        'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

function blackcolors_sanitize_fixedbaroptions($input){
    $valid = array( 'right' => __('Right', 'blackcolors'),
                                'left' => __('Left', 'blackcolors'),
                                'center' => __('Center', 'blackcolors'));

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/* Checkbox Sanitization
/*----------------------------------*/
function blackcolors_sanitize_checkbox( $input ) {
  if ( $input == 1 ) {
    return 1;
  } else {
    return '';
  }
}

?>
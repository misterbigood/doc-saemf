<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lt IE 9]>
		<script src='<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5shiv.min.js'></script>
		<![endif]-->
		<?php wp_head(); ?>
	</head>
        
    <body <?php body_class('cuerpo'); ?> >
    
    
    <div id="main">

<?php
// Color Boxes
blackcolors_colorboxes();
?>

<!-- Fixed Side Bar -->
<?php $colorbarra = get_theme_mod( 'blackcolors_barra_color', '#2c3e50' );?>
<div id="barra-lateral" style="background-color:<?php echo esc_attr($colorbarra); ?>;">

	<header>
	<div id="barra-lateral-contenidos">

		<?php $header_color = get_header_textcolor(); ?>
	
		<!-- Header Image & Titles -->
		<?php if(get_header_image() != ''): ?> <!-- Check if there is a header image -->
			<?php if ( display_header_text() ) : ?> <!-- Check if there is text over the header -->
			<div id="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" alt="<?php get_bloginfo('name'); ?>"></a>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><div id="titulo-sobre-imagen" style="color:#<?php echo esc_attr($header_color); ?>"><h2><?php echo get_bloginfo('name'); ?></h2></div></a>
					<div id="subtitulo-sobre-imagen" style="color:#<?php echo esc_attr($header_color); ?>"><?php echo get_bloginfo('description'); ?></div>
				</div>
			<?php else: ?> <!-- if there are header image but no text over it -->
			<div id="logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" alt="<?php get_bloginfo('name'); ?>"></a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><div id="titulo-hidden" style="color:#<?php echo esc_attr($header_color); ?>"><h2><?php echo get_bloginfo('name'); ?></h2></div></a>
			</div>
			<?php endif; ?>
		<?php else: ?> <!-- if there are no header image... -->
			<div id="logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><div id="titulo" style="color:#<?php echo esc_attr($header_color); ?>"><h2><?php echo get_bloginfo('name'); ?></h2></div></a>
				<div id="subtitulo" style="color:#<?php echo esc_attr($header_color); ?>"><?php echo get_bloginfo('description'); ?></div>
			</div>
		<?php endif; ?>
		
		<!-- Head Menu -->
		<div id="open-menu"><a href="#"><i class="fa fa-bars"></i></a></div><!--Responsive Menu Link-->
		<div class="access sidebar-lateral">
		<?php
		if(has_nav_menu('primary')){
			wp_nav_menu(array( 'theme_location' => 'primary', 'container' => 'nav', 'container_class' => false, 'container_id' => 'menu-principal', 'fallback_cb' => false)); 
		}else{
			echo "<nav><div id='menu-principal'>";
				wp_nav_menu();
			echo "</div></nav>";
		}
		?>
		</div>

		<!-- Social Icons -->
		<?php blackcolors_social_icons(); ?>
		
		<div id="widget-sidebar">
			<?php dynamic_sidebar('sidebar'); ?>
		</div>

	</div><!-- End of bara-lateral-contenidos

	<!-- Trigger for Fixed Lateral Bar when hidden -->
    <div id="barra-lateral-trigger">
	<span><i class="fa fa-angle-right fa-3x"></i></a></span>
    </div>

	</header>

</div>
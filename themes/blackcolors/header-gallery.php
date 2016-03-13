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
    <?php ?>
    <body <?php body_class('cuerpo'); ?>>
    
    
    <div id="main">

<?php
// Color Boxes
blackcolors_colorboxes();
?>

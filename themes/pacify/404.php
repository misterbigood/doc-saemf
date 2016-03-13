<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package pacify
 */

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'pacify' ); ?></h1>
<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'pacify' ); ?></p>
<?php get_search_form(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>	
<?php get_footer(); ?>

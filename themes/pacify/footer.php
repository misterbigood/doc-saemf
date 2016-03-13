<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package pacify
 */
?>
</div><!-- #page -->
	</div><!-- #content -->


	<footer id="colophon" class="site-footer" role="contentinfo">
	<div id="footerwidgets">
	<div id="footer-sidebar" class="secondary">
<div id="footer-sidebar1">
<?php
if(is_active_sidebar('footer-1')){
dynamic_sidebar('footer-1');
}
?>
</div>
<div id="footer-sidebar2">
<?php
if(is_active_sidebar('footer-2')){
dynamic_sidebar('footer-2');
}
?>
</div>
<div id="footer-sidebar3">
<?php
if(is_active_sidebar('footer-3')){
dynamic_sidebar('footer-3');
}
?>
</div>
</div>
<div style="clear:both;"></div>

		<div class="site-info">
			<?php echo "Copyright " . date( 'Y' ) . " , " . get_bloginfo('name') ?>
			<span class="sep"> , </span>
			<?php echo 'Theme by <a href="http://zibeb.com/">Zibeb</a>' ?>


		</div><!-- .site-info -->
</div><!-- #footerwidgets -->

		</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>

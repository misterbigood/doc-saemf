<footer id="thefoot">
	<div id="thefoot-container">
	<?php dynamic_sidebar('Footer 1'); ?>
	<?php dynamic_sidebar('Footer 2'); ?>
	<div id="widget-footer">
		<?php dynamic_sidebar('sidebar'); ?>
	</div>
    <?php blackcolors_footer(); ?>
	</div>
</footer>

<!-- Up buttom when scrolled --> 
<div id="up_buttom">
    <a href="#"><i class="fa fa-angle-up fa-3x"></i></a>
</div>

<?php wp_footer(); ?>
</div>
</body>
</html>
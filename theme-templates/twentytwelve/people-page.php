<?php /* Template Name: People Page */
	get_header();
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'page' ); ?>
            <?php endwhile; // end of the loop. ?>
			<?php echo people_page(); ?>
			<?php comments_template( '', true ); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
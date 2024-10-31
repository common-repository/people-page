<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); 

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( 'Author Archives: %s', 'twentytwelve' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $curauth->ID ) ) . '" title="' . esc_attr( $curauth->display_name ) . '" rel="me">' . $curauth->display_name . '</a></span>' ); ?></h1>
			</header><!-- .archive-header -->

			<?php twentytwelve_content_nav( 'nav-above' ); ?>

			<?php echo people_page_author(); ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
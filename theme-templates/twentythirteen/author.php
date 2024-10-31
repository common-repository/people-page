<?php
/**
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

            <div class="author-info">
            
                    <?php
                        $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
                        echo people_page_author();
                    ?> 
                    
                </div><!-- .author-description -->
            </div><!-- .author-info -->
            
		</div><!-- #content -->
	</div><!-- #primary -->
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>
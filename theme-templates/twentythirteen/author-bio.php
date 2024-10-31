<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<div class="author-info">

        <?php
        	$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			echo people_page_author();
		?> 
        
	</div><!-- .author-description -->
</div><!-- .author-info -->
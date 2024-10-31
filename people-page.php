<?php
/*
Plugin Name: People Page
Description: Allows editors to create a "People Page" that displays a list of selected users along with links to their profiles. Plugin must be used in conjunction with a "people-page.php" template.
Version: 1.1
Author: Jethin
Author URI: 
License: GPL2
*/

// TEMPLATE SCRIPTS
function pp_style(){
	global $post;
	$postID = !empty($post->ID) ? $post->ID : false;
	$template_file = !empty($postID) ? get_post_meta($postID,'_wp_page_template',TRUE) : false;
	if( is_author() || $template_file == 'people-page.php' ){
		wp_register_style( 'pp-style',get_template_directory_uri().'/people-page.css' );
		wp_enqueue_style( 'pp-style' );
	}
}
add_action('wp_enqueue_scripts', 'pp_style');

// PEOPLE PAGE TEXT
function people_page( $excerpt_length = 420 ){

	global $post;
	$people = get_post_meta( $post->ID, 'peeps', true );
	$headingCount = 1;
	$theme_name = wp_get_theme();
	$theme_name = str_replace(' ', '', $theme_name);
	$upload_dir = wp_upload_dir();
	$peopleHTML = "<div id=\"people-page\" class=\"$theme_name\">\n\n";
	
	function do_full_text($desc){
		$text = nl2br($desc);
		return $text;
	}

	foreach($people as $id){
		if( is_numeric($id )){
			$person = get_userdata($id);
			$description = $person->description;
			$name = $person->display_name;
			$link = get_author_posts_url($id);
				
			$noPosts = count_user_posts($id);
			$postsLink = !empty($noPosts) ? '<a href="'.$link.'#posts" class="posts">posts</a>' : false;
			// excerpt?
			if(strlen($description) > $excerpt_length){
				preg_match( "/^.{1,$excerpt_length}\b/s", $description, $match) ;
				$bio = nl2br($match[0]);
				$bio = trim($bio);
				$bio .= '... <a href="'.$link.'" class="more">more</a>';
			} else{ 
				$bio = do_full_text($description);
				unset($link);
			}
			
			// return text
			$peopleHTML .= '<div id="author-'.$id.'" class="person">'."\n";
			if(!empty($person->photo)){ 
				$peopleHTML .= '<img src="'.$person->photo.'" alt="'.$name.'" class="photo" />'."\n"; 
			} elseif($person->userphoto_image_file) {
				// user photo plugin
				$peopleHTML .= '<img src="'.$upload_dir['baseurl'].'/userphoto/'.$person->userphoto_image_file.'" alt="'.$name.'" class="photo userphoto" />'."\n";
			} else {
				// gravatar
				$hash = md5( strtolower( trim($person->user_email) ) );
				$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
				$headers = @get_headers($uri);
				if ( preg_match("|200|", $headers[0]) ) {
					$peopleHTML .= get_avatar( $person->user_email, $size = '150' );
				}
			}
			
			$peopleHTML .= '<h3 class="name">';
			$peopleHTML .= isset($link) ? '<a href="'.$link.'">'.$name.'</a>' : $name;
			$peopleHTML .= "</h3>\n";
			$peopleHTML .= !empty($person->title) ? '<div class="title">'.$person->title."</div>\n" : false;
			if(!empty($postsLink) || !empty($person->user_url)){
				$peopleHTML .= '<div class="postsAndWebsite"><span class="bracket">[ </span>';
				$peopleHTML .= !empty($postsLink) ? $postsLink : false;
				if(!empty($postsLink) && !empty($person->user_url)){ $peopleHTML .= ' <span class="spacer">|</span> '; }
				$peopleHTML .= !empty($person->user_url) ? '<a href="'.$person->user_url.'" class="website">website</a>' : false;
				$peopleHTML .= '<span class="bracket"> ]</span></div>'."\n";
			}
			
			if(!empty($bio)){
				$peopleHTML .= '<p class="bio">'.$bio."</p>\n";
			}
			
			$peopleHTML .= "</div>\n\n";
		
		} else{
			if($headingCount != 1){ $peopleHTML .= "</span>\n"; }
			$peopleHTML .= '<h2 class="heading">'.$id.'</h2><span class="heading'.$headingCount.'people">'."\n\n";
			$headingCount++;
		}
	}
	
	if($headingCount > 1){ echo "</span>\n"; }
	$peopleHTML .= "</div>\n\n";
	
	return $peopleHTML;

}

// AUTHOR PAGE TEXT
function people_page_author(){
	
	$curauth = ( get_query_var('author_name') ) ? get_user_by( 'slug', get_query_var('author_name') ) : get_userdata( get_query_var('author') );
	$theme_name = wp_get_theme();
	$theme_name = str_replace( ' ', '', $theme_name );
	$upload_dir = wp_upload_dir();
	
	$text = "<div id=\"author-$curauth->ID\" class=\"pp-author $theme_name\">\n\n";
    
	$text .= '<h1 class="name">'.$curauth->display_name."</h1>\n\n";
          
	if(!empty($curauth->title)){
		$text .= '<h3 class="title">'.$curauth->title."</h3>\n\n";
	} 
            
	$text .= !empty($curauth->user_url) ? '<div class="website"><span class="bracket">[ </span><a href="'.$curauth->user_url."\">website</a><span class=\"bracket\"> ]</span></div>\n\n" : '';
	
	$text .= '<p class="bio">';
            
	if( !empty($curauth->photo) ){
		$text .= '<img src="'.$curauth->photo.'" alt="'.$curauth->display_name.'" class="photo" />';
	} elseif ($curauth->userphoto_image_file) {
		// user photo plugin
		$text .= '<img src="'.$upload_dir['baseurl'].'/userphoto/'.$curauth->userphoto_image_file.'" alt="'.$curauth->display_name.'" class="photo userphoto" />'."\n";
	} else {
		// gravatar
		$hash = md5( strtolower( trim($curauth->user_email) ) );
		$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = @get_headers($uri);
		if ( preg_match("|200|", $headers[0]) ) {
			$text .= get_avatar( $curauth->user_email, $size = '150' );
		}
	}
        
    $text .= nl2br($curauth->description) . "</p>\n\n"; 

	query_posts( "author=$curauth->ID&posts_per_page=3" );
	if (have_posts()) : 
		global $post;
		$text .= "<h2 id=\"posts-header\">Posts</h2>\n\n<div id=\"posts\">";
		while ( have_posts() ) : the_post(); 
        
			$text .= '<div class="'.implode(' ', get_post_class())."\">\n";
			if(has_post_thumbnail()) { $text .= get_the_post_thumbnail($post->ID,'thumbnail')."\n"; } 
			$text .= '<h3><a href="'.get_permalink().'">'.get_the_title()."</a></h3>\n";
			$text .= '<div class="timeAndComments">';
			$text .= '<span class="time">' . get_the_time('F j, Y') . '</span>';
			if ( comments_open() ) {
				$num_comments = get_comments_number();
				$text .= ' | <span class="comments">';
				if ( $num_comments > 1 ) { $text .= $num_comments . __(' Comments');
				} else {
					$text .= __('1 Comment');
				}
				$text .= '</span>';
			}
			$text .= "</div>\n<div class=\"categoriesAndTags\">";
			$categories = get_the_category_list(', ');
			if(!empty($categories)){
				$no_cats = substr_count($categories, ',');
				$text .= ($no_cats == 0) ? '<span class="categories">Category: ' : '<span class="categories">Categories: ';
				$text .= $categories . "</span>";
			}
			$text .= get_the_tag_list('<span class="tags"> | Tags: ', ', ', '</span>'); 
			$text .= "</div>\n";
			$text .= '<p class="excerpt">'.get_the_excerpt()."</p>\n";
			$text .= "</div>\n\n";
        
         endwhile;
		 
		 $text .= "</div>\n\n";
		 
	endif;
$text .= "</div>\n\n";
return $text;
}


// ENQUEUE ADMIN SCRIPTS
add_action( 'admin_enqueue_scripts', 'pp_enqueue' );
function pp_enqueue($hook) {
	if( $hook == 'user-edit.php' || $hook == 'profile.php' ){
		if ( !did_action( 'wp_enqueue_media' ) ){ wp_enqueue_media(); }
		wp_register_style( 'pp_admin_css', plugins_url( 'style-admin.css', __FILE__ ) );
		wp_enqueue_style( 'pp_admin_css' );
		wp_enqueue_script( 'user-edit-upload', plugins_url('/script-user-edit-upload.js', __FILE__) , array('media-upload','thickbox') );
		
	}
	elseif( $hook == 'post.php' && !empty($_GET['post']) ){
		$template_file = get_post_meta( $_GET['post'],'_wp_page_template',TRUE );
		if ($template_file == 'people-page.php') {
			wp_enqueue_script( 'pp-js', plugins_url('/script.js', __FILE__), array('jquery','jquery-ui-sortable') );
			wp_register_style( 'pp_admin_css', plugins_url( 'style-admin.css', __FILE__ ) );
			wp_enqueue_style( 'pp_admin_css' );
			add_meta_box( 'pp-meta-box', 'People', 'peoplepage_form', 'page', 'normal', 'high' );
		}
	}
}

// Print Form Fields
function peoplepage_form() {
  
  global $post_id, $blog_id;
  $nonce = wp_create_nonce('nonce-people_save');
  echo '<div id="ppActive">Active</div><div id="ppMdash"><&mdash;></div><div id="ppInactive">Inactive</div>';
  echo '<fieldset id="people"><input type="hidden" name="peoplepage_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
  
  $blogusers = get_users('blog_id='.$blog_id.'&orderby=nicename&exclude=1');
  $people = get_post_meta($post_id, 'peeps', true);
  $people = !empty($people) ? $people : array();
  echo '<ul id="peopleOn" class="connectedSortable">';
  foreach ($people as $personID) {
		if (is_numeric($personID)) {
			$user = get_userdata($personID);
			echo "<li>$user->display_name";
			echo ' <input type="hidden" name="people[]" value="'.$user->ID.'"></li>';
		} else {
			echo '<li>x<input type="text" name="people[]" value="'.$personID.'" class="header" /></li>';
		}
	}
	
	echo '</ul>';
  
  echo '<ul id="peopleOff" class="connectedSortable"><li>x<input type="text" name="people[]" class="header" value="Heading" disabled /></li>';
  foreach ($blogusers as $user) {
	  if( !in_array($user->ID, $people) ){
		  echo "<li>";
		  echo isset($user->display_name) ? $user->display_name : $user->user_login;
		  echo ' <input type="hidden" name="people[]" value="'.$user->ID.'" disabled>';
		  echo '</li>';
	  }
  }
  echo '</ul></fieldset>';
  echo '<input type="submit" value="save" id="people_ajax" class="button-primary" data-postid="'.$post_id.'" data-nonce="'.$nonce.'"> <span id="saved">saved!</span>';

}
  
// Save data
add_action('save_post', 'peoplepage_save_data');
function peoplepage_save_data($post_id) {

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
  
	if(isset($_POST['peoplepage_meta_box_nonce'])){
		if ( !wp_verify_nonce($_POST['peoplepage_meta_box_nonce'], basename(__FILE__)) )
			return;
  	}
  
	if (!current_user_can('edit_page', $post_id))
  		return;
  
	global $post_id;
	$oldArray = get_post_meta($post_id, 'peeps', true);
	$newArray = isset($_POST['people']) ? $_POST['people'] : false;
  
	if (!empty($newArray)) {
		update_post_meta($post_id, 'peeps', $newArray);
	} elseif ( !empty($oldArray) ) {
		delete_post_meta($post_id, 'peeps');
	}

}


// AJAX SAVE DATA ROUTINE
add_action('wp_ajax_people_save', 'people_save_callback');
function people_save_callback() {
	// global $wpdb; // access db
	if( !is_user_logged_in() )
		return false;
	if( !wp_verify_nonce( $_POST['_wpnonce'], 'nonce-people_save' ) )
		die();
	if ( !empty($_POST['peeps']) && !empty($_POST['postid']) ) {
		update_post_meta($_POST['postid'], 'peeps', $_POST['peeps']);
	} elseif( empty($_POST['peeps']) && !empty($_POST['postid']) ){
		delete_post_meta($_POST['postid'], 'peeps');
	}
	die();
}

// ADDITIONAL USER EDIT FIELDS
add_action( 'show_user_profile', 'pp_profile_fields' );
add_action( 'edit_user_profile', 'pp_profile_fields' );

function pp_profile_fields( $user ) { ?>
<h3>People Page Information</h3>

<table class="form-table">
	<tr>
		<th><label for="title">Title</label></th>
		<td>
        	<input type="text" class="regular-text" value="<?= esc_attr( get_the_author_meta( 'title', $user->ID ) ); ?>" id="title" name="title">
		</td>
	</tr>
    <tr>
		<th><a onclick="return false;" title="Add Photo" id="pp-photo-select" href="#"><label for="photo">Photo</label><img width="15" height="15" src="<?php echo admin_url(); ?>images/media-button.png" style="margin-left:3px;"></a></th>
		<td>
        	<input type="text" class="regular-text" value="<?= esc_attr( get_the_author_meta( 'photo', $user->ID ) ); ?>" id="pp-photo" name="photo">
            <div id="pp-photo-preview"><img src="<?= esc_attr( get_the_author_meta( 'photo', $user->ID ) ); ?>"></div>
		</td>
	</tr>
</table>
<?php }

add_action( 'personal_options_update', 'pp_save_profile_fields' );
add_action( 'edit_user_profile_update', 'pp_save_profile_fields' );

function pp_save_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_user_meta( $user_id, 'title', $_POST['title'] );
	update_user_meta( $user_id, 'photo', $_POST['photo'] );
}

// TinyMCE ON USER PROFILE'S "BIO INFO" FIELD
function biographical_info_tinymce() {
	$s = get_current_screen();
	if ( $s->base == 'profile' || $s->base == 'user-edit' && function_exists('wp_tiny_mce') ) {
		wp_enqueue_style('editor-buttons');
		add_filter( 'teeny_mce_before_init', create_function( '$a', '
			$a["height"] = "300";
			$a["width"] = "90%";
			$a["selector"] = "#description";
			$a["body_class"] = "bio_info";
			$a["toolbar1"] = "bold,italic,link,unlink,bullist,hr,forecolor,outdent,indent,removeformat,formatselect,styleselect";
			// wp_adv
			// $a["toolbar2"] = "";
			// $a["mode"] = "exact";
			// $a["wordpress_adv_hidden"] = false;
			return $a;'
		) );
		wp_tiny_mce( true );
	}
}
add_action('admin_head', 'biographical_info_tinymce');

?>
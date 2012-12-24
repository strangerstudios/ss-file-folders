<?php
	/*
	Plugin Name: SS File Folders
	Plugin URI: http://www.strangerstudios.com/wp/ss-file-folders/
	Description: Adds a [file-folder] shortcode which will show links to the parent page (../), any sub page, and any attached file.
	Version: .1
	Author: Stranger Studios
	Author URI: http://www.strangerstudios.com
	*/
	
	//shortcode function
	function ssff_shortcode_handler($atts, $content=null, $code="") {
	global $post;
	
	// $atts    ::= array of attributes
	// $content ::= text within enclosing form of shortcode element
	// $code    ::= the shortcode found, when == callback name
	// examples: [subpagelist exclude="1,2,3"]
	
	extract(shortcode_atts(array(
		'exclude' => NULL
	), $atts));
		
	// prep exclude array
	$exclude = str_replace(" ", "", $exclude);
	$exclude = explode(",", $exclude);
		
	// our return string
	$r = "";
	
	/*
		Link to Parent
	*/
	
	/*
		Link to sub folders.
	*/
		
	// get posts
	query_posts(array("post_type"=>"page", "showposts"=>-1, "orderby"=>"menu_order", "post_parent"=>$post->ID, "order"=>"ASC", "post__not_in"=>$exclude));
  
  	//to show excerpts. save the old value to revert
	global $more;
	$oldmore = $more;
	$more = 0;
  
	// the Loop					
	if ( have_posts() ) : while ( have_posts() ) : the_post();	
		$r .= '<li><a href="' . get_permalink() . '">' . the_title('','',false) . '</a></li>';            
	endwhile; endif;	
	
	//Reset Query
	wp_reset_query();

	//revert
	$more = $oldmore;
	
	/*
		Link to files.
	*/
	
	return $r;
}

add_shortcode('file-folders', 'ssff_shortcode_handler');
?>

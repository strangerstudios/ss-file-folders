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
	$r = "<ul class='ss-file-folders'>";
	
	/*
		Link to Parent
	*/	
	if(!empty($post->post_parent) && $post->post_parent != $post->ID)
		$r .= "<li><strong><a href='" . get_permalink($post->post_parent) . "'>..</a></strong></li>";
	
	/*
		Link to sub folders.
	*/
		
	//get posts
	query_posts(array("post_type"=>$post->post_type, "showposts"=>-1, "orderby"=>"post_title", "post_parent"=>$post->ID, "order"=>"ASC", "post__not_in"=>$exclude));
  
	//the Loop					
	if ( have_posts() ) : while ( have_posts() ) : the_post();	
		$r .= '<li><strong><a href="' . get_permalink() . '">' . the_title('','',false) . '</a></strong></li>';            
	endwhile; endif;	
	
	//Reset Query
	wp_reset_query();	
	
	/*
		Link to files.
	*/
	//get posts
	query_posts(array("post_type"=>"attachment", "post_status" => "inherit", "showposts"=>-1, "orderby"=>"post_title", "post_parent"=>$post->ID, "order"=>"ASC", "post__not_in"=>$exclude));
  
	//the Loop					
	if ( have_posts() ) : while ( have_posts() ) : the_post();	
		$r .= '<li><a href="' . get_the_guid() . '">' . the_title('','',false) . '</a></li>';            
	endwhile; endif;	
	
	//Reset Query
	wp_reset_query();
	
	$r .= "</ul>";
	
	return $r;
}

add_shortcode('file-folders', 'ssff_shortcode_handler');
?>

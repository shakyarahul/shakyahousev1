<?php
//functions.php

function wp_resources(){
		wp_enqueue_style('style',get_stylesheet_uri());
}
add_action('wp_enqueue_scripts','wp_resources');
//get_top_ancestor_id
function get_top_ancestor_id(){
	global $post;
	if($post->post_parent){
		$ancestor = array_reverse(get_post_ancestors($post->ID)); // returns array of the all the list of the ancestor of that pages so
		return $ancestor[0];
	}
	return $post->ID;
}
//custom excerpt length
function custom_excerpt_length(){
	return 30;
}
add_filter('excerpt_length','custom_excerpt_length');

function feature_image_setup(){
	// navigation menu register
	register_nav_menus(
	array('primary' =>__( 'Primary Menu' ),
	'secondary' =>__( 'Secondary Menu' ),
	'ternary' =>__( 'Ternary Menu' ),
	));
	//add feature image support
	add_theme_support('post-thumbnails');	
	//comments support
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	add_image_size('small-thumbnail',199,148,true);
	add_image_size('banner-image',800,630,true);
	//enabling post-formats 'aside','gallery','link'
	add_theme_support('post-formats',array('standard','aside'));
}
add_action('after_setup_theme','feature_image_setup');
function ourWidgetsInit(){
	register_sidebar(array(
			'name' => 'Side Bar',
			'id'=> 'sidebar1',
			'before_widget'=>"
			<aside class=\"widget categoryBlock normall categoriesList \">
			",
			'after_widget'=>"</aside>",
			'before_title'=>"<div class=\"recentTitle\"><h5 class=\"widget_title h5 as\">",
			'after_title'=>"</h5></div>"
		));
	register_sidebar(array(
			'name' => 'Footer Bar',
			'id'=> 'footerbar1',
			'before_widget'=>"",
			'after_widget'=>"",
			'before_title'=>"",
			'after_title'=>""
		));
	register_sidebar(array(
			'name' => 'Footer Bar 2',
			'id'=> 'footerbar2',
			'before_widget'=>"<div class=\"col-lg-3 col-md-3  col-sm-6 col-xs-12\"><div class=\"empty-space marg-lg-b35\"></div><div id=\"nav_menu-2\" class=\"widget widget_nav_menu widget-links tt-footer-list set\">",
			'after_widget'=>"</div></div>",
			'before_title'=>"<h4 class=\"tt-foooter-title h5\"><small>",
			'after_title'=>"</h4><small>"
		));
}
add_action('widgets_init','ourWidgetsInit');

// Function to get the client IP address
function getIPAddress() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');
add_action('dynamic_sidebar_before',function(){
	echo "";
});
add_action('dynamic_sidebar_after',function(){
	echo "";
});

add_filter('next_posts_link_attributes', 'posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'posts_link_attributes_2');

function posts_link_attributes_1() {
    return 'class="page-numbers"';
}
function posts_link_attributes_2() {
    return 'class="page-numbers"';
}

//including featured custom post type for wp
require_once WP_CONTENT_DIR."/themes/Consultex/inc/custom-post-type/contact.php";
require_once WP_CONTENT_DIR."/themes/Consultex/inc/custom-post-type/slideshow.php";

//require_once WP_CONTENT_DIR."/themes/consultex/inc/widgets/SocialMedias.php";
//require_once WP_CONTENT_DIR."/themes/consultex/inc/widgets/Banner.php";
//require_once WP_CONTENT_DIR."/themes/consultex/inc/widgets/FacebookPage.php";

add_action('admin_enqueue_scripts','anri_admin_custom_scripts');
function anri_admin_custom_scripts(){
	wp_enqueue_media();
	wp_register_script('admin_custom_script',get_theme_file_uri().'/js/admin_script.js',array('jquery'));
	wp_enqueue_script('admin_custom_script');
}

function hello_there_babe(){
	return "hellohellohellohellohellohello";
}
add_shortcode('get_my_code_babe','hello_there_babe');
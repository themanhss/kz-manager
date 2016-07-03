<?php
/*
 Plugin Name: Wordpress Automatic Plugin (Shared by JOJOThemes.com)
 Plugin URI: http://codecanyon.net/item/wordpress-automatic-plugin/1904470?ref=ValvePress
 Description: Wordpress automatic posts high quality articles,amazon products,clickbank products,youtube videos , eBay items,flicker images and RSS feeds posts on auto-pilot.
 Version: 3.15.0
 Author: ValvePress
 Author URI: http://codecanyon.net/user/ValvePress/portfolio?ref=ValvePress
 */

/*  Copyright 2012-2015  Wordpress Automatic  (email : sweetheatmn@gmail.com) */


/*  
 * Stylesheets & JS loading
 */
	require_once 'scripts.php';

/*
 * Creating a Custom Post Type
 */
	require_once 'post_type.php';

/*
 * Creating the admin menu
 */
	require_once 'p_menu.php';

/*
 * Settings
 */	
	require_once 'p_options.php';

/*
 * Log
 */	
	require_once 'p_log.php';
	

/*
 * Plugin functions
 */

	require_once 'p_functions.php';
	
	/*
	 * ajax handling
	 */
	require_once 'pajax.php';
	
/*
 * ads adding
 */
require_once 'p_ads.php';

/*
 * Meta Box
 */
require_once('p_meta.php');
require_once('metabox_time.php');

/*
 * Cron Schedule
 */
require_once 'automatic_schedule.php';

/*
 * clear feed cache 
 */
add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 0;' ));


/*
 * Filter the content to remove first image if active
 */
require_once 'p_content_filter.php';

/*
 * tables
 */
register_activation_hook( __FILE__, 'create_table_all' );
require_once 'p_tables.php';

//removes quick edit from custom post type list

/**
 * custom request for cron job
 */
function wp_automatic_parse_request($wp) {

	//secret word 
	$wp_automatic_secret = trim(get_option('wp_automatic_cron_secret'));
	if(trim($wp_automatic_secret) == '') $wp_automatic_secret = 'cron';
	
	// only process requests with "my-plugin=ajax-handler"
	if (array_key_exists('wp_automatic', $wp->query_vars)) {
			
		if($wp->query_vars['wp_automatic'] == $wp_automatic_secret){

			require_once('cron.php');
			exit;

		}elseif ($wp->query_vars['wp_automatic'] == 'download'){
			require_once 'downloader.php';
			exit;
		}elseif ($wp->query_vars['wp_automatic'] == 'test'){
			require_once 'test.php';
			exit;
		}
	}
}
add_action('parse_request', 'wp_automatic_parse_request');



function wp_automatic_query_vars($vars) {
	$vars[] = 'wp_automatic';
	return $vars;
}
add_filter('query_vars', 'wp_automatic_query_vars');

/*
 * support widget
 */
require_once 'widget.php';


/*
 * rating notice
 */
require_once 'rating.php';

/*
 * update notice
 */
require_once 'updated.php';

/*
 * admin edit
 */
require_once 'wp-automatic-admin-edit.php';

?>
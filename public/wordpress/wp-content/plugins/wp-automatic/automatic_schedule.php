<?php


add_filter ( 'cron_schedules', 'wp_automatic_once_a_minute' );
function wp_automatic_once_a_minute($schedules) {
	
	// Adds once weekly to the existing schedules.
	$schedules ['once_a_minute'] = array (
			'interval' => 60,
			'display' => __ ( 'once a minute' ) 
	);
	return $schedules;
}

if (! wp_next_scheduled ( 'wp_automatic_hook' )) {
	wp_schedule_event ( time (), 'once_a_minute', 'wp_automatic_hook' );
}

add_action ( 'wp_automatic_hook', 'wp_automatic_function' );
function wp_automatic_function() {
	

	 
	require_once dirname(__FILE__) .'/core.php' ;
	
	
	$gm = new wp_automatic ();
	
	
	
	$opt = get_option ( 'wp_automatic_options', array ('OPT_CRON') );
	if (in_array ( 'OPT_CRON', $opt )) {
		//$gm->log('cron call', 'cron just triggered ');
		$gm->process_campaigns (false);
	} else {
		
	}
	
	// wp_auto_spinner_the_content_filter()
}
<?php 

/* ------------------------------------------------------------------------*
 * Function Selected
* ------------------------------------------------------------------------*/
if(! function_exists('wp_automatic_opt_selected')){
	function wp_automatic_opt_selected($src,$val){

 
		
		if(stristr($src, ',')){
			//array
			$src= array_filter(explode(',', $src));
			
			 
			if(in_array($val, $src)){
				echo ' selected="selected" ';
			} 
				
			
		}else{
			if (trim($src)==trim($val)) echo ' selected="selected" ';
		}
		
		
	
	
	
	}
}

/* ------------------------------------------------------------------------*
 * Function Selected
* ------------------------------------------------------------------------*/
if(! function_exists('check_checked')){
	function check_checked($val,$arr){
		//if(! is_array($arr)) return false;
			
		if(in_array($val,$arr)){
			echo ' checked="checked" ';
		}else{

			return false;
		}
			
	}
}

function remove_quick_edit( $actions ) {
	global $post;
	if( $post->post_type == 'wp_automatic' ) {
		unset($actions['inline hide-if-no-js']);
	}
	return $actions;
}

if (is_admin()) {
	add_filter('post_row_actions','remove_quick_edit',10,2);
}
?>
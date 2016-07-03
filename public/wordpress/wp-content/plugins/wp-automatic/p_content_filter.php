<?php

add_filter('the_content', 'wp_automatic_the_content_filter');
	
function wp_automatic_the_content_filter($cnt){
		global $post;
		
		
		//fix youtube deleted rating images
		if( stristr($cnt, 'youtube.com/static/images') ){
			
			$icn_star_empty = plugins_url('images/youtube_imgs/icn_star_empty_11x11.gif' , __FILE__);
			$icn_star_half = plugins_url('images/youtube_imgs/icn_star_half_11x11.gif' , __FILE__);
			$icn_star_full = plugins_url('images/youtube_imgs/icn_star_full_11x11.gif' , __FILE__);
				
				
			$cnt = str_replace('http://gdata.youtube.com/static/images/icn_star_full_11x11.gif', $icn_star_full,$cnt );
			$cnt = str_replace('http://gdata.youtube.com/static/images/icn_star_half_11x11.gif', $icn_star_half,$cnt );
			$cnt = str_replace('http://gdata.youtube.com/static/images/icn_star_empty_11x11.gif', $icn_star_empty,$cnt );
			
		}
		
		//remove first image
		$active = get_post_meta( $post->ID ,'wp_automatic_remove_first_image' ,1) ;
		
		if($active == 'yes'){
			//return 'active remove ';
			
			return preg_replace ( '/<img [^>]*src=["|\'][^"|\']+.*?>/i', '' ,$cnt ,1 );
			
		}else{
			return $cnt;
		}
}

//link to source instead

add_filter('post_link','wp_automatic_permalink_changer');

function wp_automatic_permalink_changer($permalink ){
  
	global $post;
	 
	if (!empty($post->ID)) {

		$link_to_source = get_post_meta($post->ID, '_link_to_source', true);
		
		if ( trim($link_to_source) != '' ) {
			
			$new_permalink = get_post_meta($post->ID, 'original_link', true);
			if(trim($new_permalink) != ''  ) return $new_permalink;
			
		}
	}
	
	return $permalink;
}
 

//Canonical urls
function wp_automatic_rel_canonical_with_custom_tag_override()
{
	if( !is_singular() )
		return;

	global $wp_the_query;
	if( !$id = $wp_the_query->get_queried_object_id() )
		return;

	// check whether the current post has content in the "canonical_url" custom field
	$canonical_url = get_post_meta( $id, 'canonical_url', true );
	if( '' != $canonical_url )
	{
		// trailing slash functions copied from http://core.trac.wordpress.org/attachment/ticket/18660/canonical.6.patch
		$link = user_trailingslashit( trailingslashit( $canonical_url ) );
	}
	else
	{
		$link = get_permalink( $id );
	}
	echo "<link rel='canonical' href='" . esc_url( $link ) . "' />\n";
}

// remove the default WordPress canonical URL function
if( function_exists( 'rel_canonical' ) )
{
	remove_action( 'wp_head', 'rel_canonical' );
}
// replace the default WordPress canonical URL function with your own
add_action( 'wp_head', 'wp_automatic_rel_canonical_with_custom_tag_override' );

//Facebook videos
add_shortcode( 'fb_vid', 'wp_automatic_fbvid_shortcode_func' );


function wp_automatic_fbvid_shortcode_func( $atts ) {
	

	$cont='';

	extract( shortcode_atts( array(
	'id' => 'something',
		
	), $atts ) );
	
	return '<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-video" data-allowfullscreen="true" data-href="https://www.facebook.com/video.php?v='.$id.'&amp;set=vb.500808182&amp;type=1"><div class="fb-xfbml-parse-ignore"></div></div>';
	
}

//eBay redirect 
add_action('template_redirect', 'wp_automatic_eb_redirect_end');

function wp_automatic_eb_redirect_end(){
	
	 
	global $wp_the_query;
	if( !$id = $wp_the_query->get_queried_object_id() )
		return;

	// check whether the current post has content in the "canonical_url" custom field
	$wp_automatic_redirect_date = get_post_meta( $id, 'wp_automatic_redirect_date', true );
	 
	if(trim($wp_automatic_redirect_date) != ''){
		if( current_time('timestamp') > $wp_automatic_redirect_date ){
			
			$wp_automatic_redirect_link = get_post_meta( $id, 'wp_automatic_redirect_link', true );
			wp_redirect($wp_automatic_redirect_link,301);
			
		}
	}
 	
}
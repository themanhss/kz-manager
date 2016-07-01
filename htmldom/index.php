<?php 
	include_once('simple_html_dom.php');
		// Create DOM from URL or file
	// Create DOM from URL
$html = file_get_html('http://snkrvn.com/chuyen-muc/review/nike/');

$links = array();

// Find all links 
foreach($html->find('.post .entry-title a') as $element) {
	array_push($links, $element->href);
}

$posts = array();

foreach ($links as $key => $link) {
	
	$post_page = file_get_html($link);
	

	$posts[$key]['title'] = $post_page->find('.post h1',0)->innertext;

	$post_content = $post_page->find('.post',0)->innertext;
	var_dump($post_content);

	// die();
}

var_dump($posts);



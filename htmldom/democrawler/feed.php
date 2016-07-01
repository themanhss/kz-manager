<?php

require_once "simple_html_dom.php"; // Chèn thư viện simple_html_dom
 header("Content-Type: text/html; charset=utf-8");
$link = "http://snkrvn.com/review/snkr-viet-nam-review-su-tro-lai-cua-huyen-thoai-nike-flyknit-racer-2-0-oreo/"; // link cần lấy tin
$html = file_get_html($link); // Create DOM from URL or file
$title_pattern = ".post h1"; // Mẫu lấy phần tiêu đề
$brief_pattern = "h2.Lead"; // Mẫu lấy phần tóm tắt
$description_pattern = ".post"; // Mẫu lấy phần miêu tả
$description_pattern_delete = "h1,.post-date,.social4i,.tags-links"; // Các mẫu cần xóa trong phần miêu tả

$item=array();
// Lấy tiêu đề
foreach($html->find($title_pattern) as $element)
{
	$item['title'] = trim($element->plaintext); // Chỉ lấy phần text
}
// Lấy tóm tắt
/*foreach($html->find($brief_pattern) as $element)
{
	$item['brief'] = trim($element->plaintext); // Chỉ lấy phần text
}*/
// Lấy miêu tả
foreach($html->find($description_pattern) as $element)
{

	// Xóa các mẫu trong miêu tả
	if($description_pattern_delete){
		$arr = explode(',',$description_pattern_delete);
		for($i=0;$i<count($arr);$i++){
			foreach($element->find($arr[$i]) as $e){
				$e->outertext='';
			}
		}
	}

	$item['description'] = $element->innertext; // Lấy toàn bộ phần html

	// Find all images 
	foreach($element->find('img') as $img) {
		$img_link = $img->src;
		$img_link = strtok($img_link, '?');

		$url_arr = explode ('/', $img_link);
		$ct = count($url_arr);
		$name = $url_arr[$ct-1];

		// $name = iconv("UTF-8", "ISO-8859-1", $name);


		copy($img_link, $name);

		/*//Get the file
		$content = file_get_contents($img_link);


		//Store in the filesystem.
		$fp = fopen($name, "w");
		fwrite($fp, $content);
		fclose($fp);*/

		echo $name;
	};

	/*//Get the file
	$content = file_get_contents("http://www.google.co.in/intl/en_com/images/srpr/logo1w.png");
	//Store in the filesystem.
	$fp = fopen("image.jpg", "w");
	fwrite($fp, $content);
	fclose($fp);*/

	// Bổ sung đường dẫn ảnh
	if(isset($item['description']) and $item['description']){
		$item['description']=str_replace("/Files/","http://ngoisao.net/Files/",$item['description']);
	}
}

//var_dump($item);

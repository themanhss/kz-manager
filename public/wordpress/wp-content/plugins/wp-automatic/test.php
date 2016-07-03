<?php 

  

//curl ini
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT,20);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//curl get  
$x='error';

$q='from:cnn';

$url='https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($q);

curl_setopt($ch,CURLOPT_HTTPHEADER,array("Authorization: Bearer AAAAAAAAAAAAAAAAAAAAAAwZYQAAAAAAD8xTHtSHdF1oYm7MKqD8mWENvT0%3DHVlxqzfzq2NYywXB3u9dZhef6v8TcJkaMoiXhtRfXOjaDgW8ZO"));


curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_URL, trim($url));
 
	 $exec=curl_exec($ch);
	 $x=curl_error($ch);
	 
	 

	 
 
	 echo '<pre>';
	 $json = ( json_decode($exec) );
	 
	 print_r($json);
	 
	 
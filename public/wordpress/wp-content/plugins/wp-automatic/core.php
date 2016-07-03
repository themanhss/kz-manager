<?php
		
		// instant echo for debugging purpose
		if (! function_exists ( 'echome' )) {
			function echome($val) {
				echo str_repeat ( ' ', 1024 * 64 );
				echo $val;
			}
		}
		 
		// spintax
		require_once ('inc/class.spintax.php');
		
		// amazon
		require_once ('inc/amazon_api_class.php');
		
		// youtube
		require_once ('inc/youtube_class.php');
		
		/*
		 * ---* Auto Link Builder Class ---
		 */
		class wp_automatic {
			public $ch = '';
			public $db = '';
			public $spintax = '';
			public $plugin_url = '';
			public $wp_prefix = '';
			public $used_keyword = '';
			public $used_link ='';
			public $used_tags = '' ;
			public $duplicate_id = '';
			public $cached_file_path = '';
			public $minimum_post_timestamp = '';
			public $minimum_post_timestamp_camp = '';
			 
			
			/*
			 * ---* Class Constructor ---
			 */
			function wp_automatic() {
				// plugin url
				$siteurl = get_bloginfo ( 'url' );
				$this->plugin_url = $siteurl . '/wp-content/plugins/alb/';
				
				// db
				global $wpdb;
				$this->db = $wpdb;
				$this->wp_prefix = $wpdb->prefix;
				// $this->db->show_errors();
				@$this->db->query ( "set session wait_timeout=28800" );
				
				// curl
				$this->ch = curl_init ();
				curl_setopt ( $this->ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $this->ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $this->ch, CURLOPT_CONNECTTIMEOUT, 10 );
				curl_setopt ( $this->ch, CURLOPT_TIMEOUT, 200 );
				curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://www.bing.com/' );
				curl_setopt ( $this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8' );
				curl_setopt ( $this->ch, CURLOPT_MAXREDIRS, 20 ); // Good leeway for redirections.
				@curl_setopt ( $this->ch, CURLOPT_FOLLOWLOCATION, 1 ); // Many login forms redirect at least once.
				
			 	curl_setopt ( $this->ch, CURLOPT_COOKIEJAR, str_replace('core.php', 'cookie.txt', __FILE__) );
			 	curl_setopt ( $this->ch, CURLOPT_COOKIEJAR,  'cookie.txt' );
			  	curl_setopt ( $this->ch, CURLOPT_SSL_VERIFYPEER, false );
				
				/*
				//verbose	
				$verbose=fopen( str_replace('core.php', 'verbose.txt', __FILE__)  , 'w');
				curl_setopt($this->ch, CURLOPT_VERBOSE , 1);
				curl_setopt($this->ch, CURLOPT_STDERR,$verbose);
				*/
				
				// spintax
				$this->spintax = new Spintax ();
			}
			
			/*
			 * ---* Fetch affiliate links for specific keyword add links to db and return true ---
			 */
			function fetch_links($keyword, &$camp) {
				echo "<br>so I should now get some links from clickbank ...";
				
				// ini
				$camp_opt = unserialize ( $camp->camp_options );
				
				// using clickbank
				$clickkey = urlencode ( $keyword );
				
				// getting start
				$query = "select 	keyword_start from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' ";
				$ret = $this->db->get_results ( $query );
				
				$row = $ret [0];
				$start = $row->keyword_start;
				// check if the start = -1 this means the keyword is exhausted
				if ($start == '-1') {
					echo "<br>Keyword $keyword already exhausted and don't have any links to fetch";
					// $query="update wp_stc_links set status ='0' where keyword = '$keyword'";
					// $this->db->query($query);
					return false;
				}
				
				$sortby = $camp->camp_replace_link;
				$camp_cb_category = $camp->camp_cb_category;
				
				$clickbank = "http://www.clickbank.com/mkplSearchResult.htm?includeKeywords=$clickkey&resultsPerPage=50&firstResult=$start&sortField=$sortby&$camp_cb_category";
				echo "<br>Clickbank Remote Link:$clickbank....";
				
				// Get
				$x = 'error';
				while ( trim ( $x ) != '' ) {
					$url = $clickbank;
					curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
					$cont = curl_exec ( $this->ch );
					echo $x = curl_error ( $this->ch );
				}
				
				 
				//
				// extracting links
				/*
				 * <td class="details"> <h4> <a href="http://zzzzz.ZACNIN1.hop.clickbank.net" target="_blank">250 + Ways To Make Money</a>
				 */
				preg_match_all ( '{<td class="details">((\s)*?)<h4>((.|\s)*?)<a href="((.|\s)*?)"((.|\s)*?)>((.|\s)*?)</a>}', $cont, $matches, PREG_PATTERN_ORDER );
				
				$links = $matches [5];
				echo '<br>links found:' . count ( $links );
				$titles = $matches [9];
				
				echo '<ol>';
				for($i = 0; $i < count ( $links ); $i ++) {
					$title = addslashes ( $titles [$i] );
					
					echo '<li>' . $links [$i] . '<br>' . $titles [$i] . '</li>';
					
					if (! (in_array ( 'OPT_EXACT_MATCH', $camp_opt ) && ! stristr ( $titles [$i], $keyword ))) {
						
						// check if exists
						$query = "SELECT link_id from {$this->wp_prefix}automatic_links where link_url='$links[$i]' and link_title ='$title'";
						$res = $this->db->get_results ( $query );
						
						if (count ( $res ) == 0) {
							$query = "INSERT INTO {$this->wp_prefix}automatic_links ( link_url , link_title , link_keyword  , link_status )VALUES ( '$links[$i]', '$title', '$keyword', '0')";
							$this->db->query ( $query );
						}
					} else {
						// notvalid
						echo '<br>not valid';
					}
				}
				echo '</ol>';
				
				if (count ( $links ) > 0) {
					// increment the start with 50
					$newstart = $start + 50;
					$query = "update {$this->wp_prefix}automatic_keywords set  keyword_start = '$newstart' where keyword_name='$keyword'";
					$this->db->query ( $query );
					return true;
				} else {
					// there was no links lets deactivate
					$newstart = '-1';
					$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = '$newstart' where keyword_name='$keyword'";
					$this->db->query ( $query );
					
					// reset all links status to 0 again to circulate
					// $query="update wp_automatic_links set link_status ='0' where link_keyword = '$keyword'";
					// $this->db->query($query);
					return false;
				}
			} // end function
			
			/*
			 * ---* Function Process Campaigns ---
			 */
			function process_campaigns($cid = false) {
				$prefix = $this->db->prefix;
				
				if (trim ( $cid ) == '') {
					// last processed campaign
					$last = get_option ( 'gm_last_processed', 0 );
					
					// get all the campaigns from the db lower than the last processed
					$query = "SELECT * FROM {$this->wp_prefix}automatic_camps  where camp_id < $last ORDER BY camp_id DESC";
					$camps = $this->db->get_results ( $query );
					
					// check if results returned with id less than the last processed or not if not using regular method
					$query = "SELECT * FROM {$this->wp_prefix}automatic_camps WHERE  camp_id >= $last ORDER BY camp_id DESC";
					$camps2 = $this->db->get_results ( $query );
					
					// merging 2 arrays
					$camps = array_merge ( $camps, $camps2 );
				} else {
					
					$query = "SELECT * FROM {$this->wp_prefix}automatic_camps  where camp_id = $cid ORDER BY camp_id DESC";
					$camps = $this->db->get_results ( $query );
				}
				
				   
				
				// print_r($camps);
				
				// check if need to process camaigns or skip
				if (count ( $camps ) == 0) {
					echo '<br>No valid campaigns to process ';
					return;
				}else{
					if(trim($cid) == '')
					echo '<br>DB contains '.count($camps).' campaigns<br>';
				}
				
				// now processing each fetched campaign
				$i = 0;
				foreach ( $camps as $campaign ) {
					// reading post status
					$status = get_post_status ( $campaign->camp_id );
					// if published process
					if ($status == 'publish') {
						if ($i != 0)
							echo '<br>';
						echo "<b>Processing Campaign</b> $campaign->camp_name {  $campaign->camp_id  }";
						
						
						
						// updating the last id processed
						update_option ( 'gm_last_processed', $campaign->camp_id );
						
				
						
						//check if deserve spinning now or not
						if(trim($cid) == false){
								
							//read post every x minutes
							if( stristr($campaign->camp_general, 'a:') ) $campaign->camp_general=base64_encode($campaign->camp_general);
							$camp_general = unserialize (base64_decode( $campaign->camp_general) );
							$camp_general=array_map('stripslashes', $camp_general);
							
							if(! is_array($camp_general) || ! isset($camp_general['cg_update_every']) ){
								$camp_general = array('cg_update_every'=>60 ,'cg_update_unit'=> 1);
							}
								
							$post_every = $camp_general['cg_update_every'] * $camp_general['cg_update_unit'];
								
							echo '<br>Campaign scheduled to process every '.$post_every . ' minutes ';
							
							//get last check time 
							$last_update=get_post_meta($campaign->camp_id,'last_update',1);
							if(trim($last_update) == '') $last_update =1388692276 ;
							//echo '<br>Last updated stamp '.$last_update;
							
							$difference = $this->get_time_difference($last_update, time());
							
							echo '<br> last processing was <strong>'.$difference. '</strong> minutes ago ';
							
							if($difference > $post_every ){
								echo '<br>Campaign passed the time and eligible to be processed';
								update_post_meta($campaign->camp_id,'last_update',time());
		
								$this->log ( '<strong>Cron</strong> >> eligible waiting campaign' , $campaign->camp_name .'{'.$campaign->camp_id .'} last processing was <strong>'.$difference. '</strong> minutes ago ');
								
								//process
								$this->log ( '<strong>Cron</strong> >> Processing Campaign:' . $campaign->camp_id, $campaign->camp_name .'{'.$campaign->camp_id .'}');
								$this->process_campaign ( $campaign );
							}else{
								echo '<br>Campaign still not passed '.$post_every . ' minutes';
							}
							
							
						}else{
							//no cron just regular call
							//update last run
							update_post_meta($campaign->camp_id,'last_update',time());
							
							//process
							$this->log ( '<strong>User</strong> >> Processing Campaign:' . $campaign->camp_id, $campaign->camp_name .'{'.$campaign->camp_id .'}' );
							$this->process_campaign ( $campaign );
							
						}
						
						
						
						$i ++;
					} elseif (! $status) {
						// deleting Camp record
						$query = "delete from {$this->wp_prefix}automatic_camps where camp_id= '$campaign->camp_id'";
						$this->db->query ( $query );
						// deleting matching records for keywords
						$query = "delete from {$this->wp_prefix}automatic_keywords where keyword_camp ='$campaign->camp_id'";
						$this->db->query ( $query );
					}else{
						echo 'Campaign should be published firstly to run..';
					}
				}
			}
			
			/*
			 * ---* Processing Single Campaign Function ---
			 */
			function process_campaign($camp) {
				
				// ini
				$camp_post_every = $camp->camp_post_every;
				$wp_automatic_tw = get_option ( 'wp_automatic_tw', 400 );
				$wp_automatic_options = get_option('wp_automatic_options',array() );
				$camp_type = $camp->camp_type;
				$camp_post_custom_k = unserialize ( $camp->camp_post_custom_k );
				$camp_post_custom_v = unserialize ( $camp->camp_post_custom_v );
				
				//camp general options
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general) );
				$camp_general=array_map('stripslashes', $camp_general);
				
				// get the count of posted posts so far
				$key = 'Posted:' . $camp->camp_id;
				$query = "select count(id) as count from {$this->wp_prefix}automatic_log where action='$key'";
				$temp = $this->db->get_results ( $query );
				$temps = $temp [0];
				$posted = $temps->count;
				
				
				// if maximum reached skip
				if ($camp_post_every <= $posted) {
					echo '<br>Maximum Num Posted ';
					$this->log('Cancel Campaign', 'campaign reached maximum number of posts');
					return false;
				}
				
				// campaign options
				$camp_opt = unserialize ( $camp->camp_options );
				if (! is_array ( $camp_opt ))
					$camp_opt = array ();
					
				// reading keywords that need to be processed
				$keywords = explode ( ',', $camp->camp_keywords );
				$keywords=array_filter($keywords);
				$keywords=array_map('trim', $keywords);
				
				// set minimum item date if exists
				if(in_array('OPT_YT_DATE', $camp_opt)){
					$this->minimum_post_timestamp = strtotime($camp_general['cg_yt_dte_year'].'-'.$camp_general['cg_yt_dte_month'].'-'.$camp_general['cg_yt_dte_day'].'T00:00:00.000Z');
					$this->minimum_post_timestamp_camp = $camp->camp_id;
				}
				
				//Rotate Keywords
				if(in_array('OPT_ROTATE', $camp_opt)){
					echo '<br>Rotating Keywords Enabled';
		
					//last used keyword 
					$last_keyword = get_post_meta($camp->camp_id ,'last_keyword',1);
					
					if( ! trim($last_keyword) == ''){
						//found last keyword usage let's split
						echo '<br>Last Keyword: '.$last_keyword;
		
						//add all keywords after the last keyword
						$add = false;
						foreach ($keywords as $current_keword){
							if($add){
								//set add flag to add all coming keywords
								$rotatedKeywords[]=$current_keword;
								
							}elseif (trim($current_keword) == trim($last_keyword)) {
								$add = true; 
							} 
						}
						
					  //add all keywords before the last keyword
						foreach ($keywords as $current_keword){
							$rotatedKeywords[]=$current_keword;
							if(trim($current_keword) == trim ($last_keyword)) break ;
						
						}
						
					  //set keywords to rotated keywords
						if( count($rotatedKeywords) != 0 ) $keywords = $rotatedKeywords;
						$keywordsString = implode(',', $rotatedKeywords);
						$camp->camp_keywords=$keywordsString;
					 } 
				 }
				 
				 //Rotate feeds
				 if(in_array('OPT_ROTATE_FEEDS', $camp_opt)){
				 	echo '<br>Rotating feeds Enabled';
				 		
				 	//last used feed
				 	$last_feed = get_post_meta($camp->camp_id ,'last_feed',1);
				 	 
				 	if( ! trim($last_feed) == ''){
				 		//found last feed usage let's split
				 		echo '<br>Last feed: '.$last_feed;
				 			
				 		//add all feeds after the last feed
				 		$add = false;
				 		$feeds = explode("\n", $camp->feeds);
				 		$feeds = array_filter($feeds);
				 		
				 		foreach ($feeds as $current_feed){
				 			
				 			if($add){
				 				//set add flag to add all coming feeds
				 				$rotatedfeeds[]=$current_feed;
				 					
				 			}elseif (trim($current_feed) == trim($last_feed)) {
				 				$add = true;
				 			}
				 		}
				 		 
				 			
				 		//add all feeds before the last feed
				 		foreach ($feeds as $current_feed){
				 			$rotatedfeeds[]=$current_feed;
				 			if(trim($current_feed) == trim ($last_feed)) break ;
				 				
				 		}
		
				 	 
				 		
				 		//set feeds to rotated feeds
				 		if( count($rotatedfeeds) != 0 ) $feeds = $rotatedfeeds;
				 		$feedsString = implode("\n", $rotatedfeeds);
				 		$camp->feeds=$feedsString;
				 	}
				 }
				
				
				$post_content = $camp->camp_post_content;
				$post_title = $camp->camp_post_title;
				
				// ini content
				$abcont = '';
				if ($camp_type == 'Articles') {
					
					// proxyfy
					$this->fire_proxy ();
					
					$article = $this->articlebase_get_post ( $camp );
					$abcont = $article ['cont'];
					$title = $article ['title'];
					$source_link = $article ['source_link'];
					$img = $article;
				} elseif ($camp_type == 'Feeds') {
					// feeds posting
					echo '<br>Should get content from feeds';
					$article = $this->feeds_get_post ( $camp );
					$abcont = $article ['cont'];
					$title = $article ['title'];
					$source_link = $article ['source_link'];
					$img = $article;
				} elseif ($camp_type == 'Amazon') {
					echo '<br>Amazon product is required';
					$product = $this->amazon_get_post ( $camp );
					
					//update offer url to add to chart
		 
					if ( in_array('OPT_LINK_CHART', $camp_opt) ) {
					 
						$product['product_link'] = $product['chart_url'];
						 
					}  
				 
					$img =$product;
				 
					$abcont = $product ['offer_desc'];
					$title = $product ['offer_title'];
					$source_link = $product ['offer_url'];
					$product_img = $product ['offer_img'];
					$product_price = $product ['offer_price'];
				} elseif ($camp_type == 'Clickbank') {
					
					echo '<br>Clickbank product is required';
					$img = $product = $this->clickbank_get_post ( $camp );
					$abcont = $product ['offer_desc'];
					$title = $product ['title'];
					$source_link = $product ['offer_link'];
					$product_img = $product ['img'];
					$product_original_link = $product ['original_link'];
					
					// print_r($product);
				} elseif ($camp_type == 'Youtube') {
					echo '<br>Youtube Vid is required';
					$img = $vid = $this->youtube_get_post ( $camp );
					$abcont = $vid ['vid_desc'];
					$original_title = $vid ['vid_title'];
					$title = $vid ['vid_title'];
					$source_link = $vid ['vid_url'];
				
				} elseif($camp_type == 'Vimeo'){
				
					echo '<br>Vimeo campaign let\'s get vimeo vid';
					
					$img = $vid = $this->vimeo_get_post ( $camp );
		
					//set player width and hieght 
					
					$abcont = $vid ['vid_description'];
					$original_title = $vid ['vid_title'];
					$title = $vid ['vid_title'];
					$source_link = $vid ['vid_url'];
					
				} elseif ($camp_type == 'Flicker') {
					echo '<br>Flicker image is required';
					$img = $this->flicker_get_post ( $camp );
					
					$abcont = $img ['img_description'];
					$original_title = $img ['img_title'];
					$title = $img ['img_title'];
					$source_link = $img ['img_link'];
				} elseif ($camp_type == 'eBay') {
					echo '<br>eBay item is required';
					$img = $this->ebay_get_post ( $camp );
					
					$abcont = $img ['item_desc'];
					$original_title = $img ['item_title'];
					$title = $img ['item_title'];
					$source_link = $img ['item_link'];
				}elseif($camp_type == 'Spintax'){
					
					echo '<p>Processing spintax campaign';
					
					$abconts =$post_title . '(99999)' . $post_content;
					 
					if( in_array('OPT_TBS', $camp_opt) ){
						$abconts  = $this->spin ( $abconts );
					}
					
					
					$abconts = $this->spintax->spin ( $abconts );
					$tempz = explode ( '(99999)', $abconts );
					$post_title = $tempz [0];
					$post_content = $tempz [1];
					$title =trim($post_title);
					$img=array();
				}elseif( $camp_type == 'Facebook' ){
					
					 $img = $this->fb_get_post($camp);
					
					 $abcont = '';
					 $original_title = $img ['original_title'];
					 $title = $img ['original_title'];
					 $source_link = $img ['original_link'];
					  
				}elseif ($camp_type == 'Pinterest' ){
					
					 $img = $this->pinterest_get_post($camp);
		 
					 $abcont = $img ['pin_description'];
					 $original_title = $img ['pin_title'];
					 $title = $img ['pin_title'];
					 $source_link = $img ['pin_url'];
					  
				}elseif ($camp_type == 'Instagram' ){
					
					 $img = $this->instagram_get_post($camp);
		 
					 $abcont = $img ['item_description'];
					 $original_title = $img ['item_title'];
					 $title = $img ['item_title'];
					 $source_link = $img ['item_url'];
					  
				}elseif($camp_type == 'Twitter' ){
					
					$img = $this->twitter_get_post($camp);
					 	
					$abcont = $img ['item_description'];
					$original_title = $img ['item_title'];
					$title = $img ['item_title'];
					$source_link = $img ['item_url'];
					
					
				}
			 
				
				
				//limit the content returned
				if(in_array('OPT_LIMIT', $camp_opt)){
					echo '<br>Triming post content to '.$camp_general['cg_content_limit'] . ' chars';
					$abcont = $this->truncateHtml($abcont,$camp_general['cg_content_limit']);
				}
				
				if(in_array('OPT_LIMIT_TITLE', $camp_opt) && trim($title) != ''){
					echo '<br>Triming post title to '.$camp_general['cg_title_limit'] . ' chars';
					
					if(function_exists('mb_substr')){
						$title = mb_substr($title, 0,$camp_general['cg_title_limit']);
					}else{
						$title = substr($title, 0,$camp_general['cg_title_limit']);
						
					}
					
					$title = $this->removeEmoji($title);
					$title = $title . '...';
		
				}
				 
				
				// check if valid content fetched
				if (trim ( $title ) != '') {
					
					// validate if the content contains wanted or execluded texts
					
					$valid = true;
					
					$exact = $camp->camp_post_exact;
					$execl = $camp->camp_post_execlude;
					
					
					// validating exact
					if (trim ( $exact ) != '' & in_array('OPT_EXACT', $camp_opt)) {
						$valid = false;
						$exact = explode ( "\n", trim ( $exact ) );
						
						foreach ( $exact as $wordexact ) {
							if (trim ( $wordexact != '' )) {
								if (  preg_match ( '/\b' . trim ( $wordexact ) . '\b/iu', html_entity_decode($abcont) ) ||  preg_match ( '/\b' . trim ( $wordexact ) . '\b/iu', html_entity_decode($title) )   ) {
									
									echo '<br>Content contains the word : ' . $wordexact ;
									$valid = true;
									break;
								}else{
									echo '<br>Content don\'t contain the word : ' . $wordexact . ' try another ';
								} // match
							} // trim wordexact
						} // foreach exactword
					} // trim exact
		 			
					// validating execlude
					if ($valid == true) {
						if (trim ( $execl ) != '') {
							$execl = explode ( "\n", trim ( $execl ) );
							
							foreach ( $execl as $wordex ) {
								 
								if (trim ( $wordex != '' )) {
								
									
									if ( preg_match ( '/\b' . trim (  $wordex  ) . '\b/iu', html_entity_decode($abcont) ) || preg_match ( '/\b' . trim (  $wordex  ) . '\b/iu', (html_entity_decode($title)) )   ) {
										echo '<br>Content contains the banned word :' . $wordex . ' getting another ';
										$valid = false;
										break;
									}else{
										 
									} 
								} // trim wordexec
							} // foreach wordex
						} // trim execl
					} // valid
					  
					// if not valid process the campaign again and exit
					if ($valid == false) {
						
						//blacklisting the link so we don'g teg it again and cause a loop
						
						
						update_post_meta($camp->camp_id,'_execluded_links', get_post_meta($camp->camp_id,'_execluded_links',1).','.$source_link );
		 
						
						$this->process_campaign ( $camp );
						exit ();
					}
					
					// strip links
					if (in_array ( 'OPT_STRIP', $camp_opt )) {
						echo '<br>Striping links ';
						//$abcont = strip_tags ( $abcont, '<p><img><b><strong><br><iframe><embed><table><del><i><div>' );
						$abcont = preg_replace('{<a.*?>(.*?)</a>}', '$1', $abcont);
						
						/*
						if(in_array('OPT_STRIP_INLINE', $camp_opt)){
							echo '...striping inline links';
							$abcont = preg_replace('/https?:\/\/[^<\s]+/', '', $abcont);
						}*/
						 
						
					}
					
					
			 
					
					// translate the cotent
					if (in_array ( 'OPT_TRANSLATE', $camp_opt ) && trim ( $abcont ) != '') {
						echo '<br>Translating the post...' . $title;
						$translation = $this->gtranslate ( $title, $abcont, $camp->camp_translate_from, $camp->camp_translate_to );
						
						if (in_array ( 'OPT_TRANSLATE_TITLE', $camp_opt )) {
							$title = $translation [0];
						}
						
						$abcont = $translation [1];
						
						// check if another translation needed
						if (trim ( $camp->camp_translate_to_2 ) != 'no') {
							// another translate
							echo '<br>translating the post another time ';
							$translation = $this->gtranslate ( $title, $abcont, $camp->camp_translate_to, $camp->camp_translate_to_2 );
							
							if (in_array ( 'OPT_TRANSLATE_TITLE', $camp_opt )) {
								$title = $translation [0];
							}
							
							$abcont = $translation [1];
						}
					}
					
					// replacing general terms title and source link
					$post_content = @str_replace ( '[source_link]', $source_link, $post_content );
					$post_title = @str_replace ( '[original_title]', $title, $post_title );
					$post_content = str_replace ( '[original_title]', $title, $post_content );
					
					if ($camp_type == 'Feeds' || $camp_type == 'Articles') {
						
						 
							$post_content = str_replace ( '[matched_content]', $abcont, $post_content );
						 
						
					} elseif ($camp_type == 'Amazon') {
						
						$post_content = str_replace ( '[product_desc]', $abcont, $post_content );
						$post_content = str_replace ( '[product_img]', $product_img, $post_content );
						//$post_content = str_replace ( '[product_link]', $source_link, $post_content );
						$post_content = str_replace ( '[product_price]', $product_price, $post_content );
					} elseif ($camp_type == 'Clickbank') {
						$post_content = str_replace ( '[product_desc]', $abcont, $post_content );
						$post_content = str_replace ( '[product_img]', $product_img, $post_content );
						$post_content = str_replace ( '[product_link]', $source_link, $post_content );
						$post_content = str_replace ( '[product_original_link]', $product_original_link, $post_content );
					} elseif ($camp_type == 'Youtube') {
						
						$post_content = str_replace ( '[vid_player]', addslashes ( $vid ['vid_player'] ), $post_content );
						$post_content = str_replace ( '[vid_desc]', $abcont, $post_content );
						$post_content = str_replace ( '[vid_views]', $vid ['vid_views'], $post_content );
						$post_content = str_replace ( '[vid_rating]', $vid ['vid_rating'], $post_content );
						$post_content = str_replace ( '[vid_img]', $vid ['vid_img'], $post_content );
					}elseif($camp_type == 'eBay'){
						
						if(stristr($post_content, '[item_images]') && is_array($img['item_images']) ){
							
							$cg_eb_full_img_t = html_entity_decode( $camp_general['cg_eb_full_img_t']);
		
							$imgs = $img['item_images'];
							
							if(! stristr($cg_eb_full_img_t, '[img_src]') ) {
								$cg_eb_full_img_t = '<img src="[img_src]" class="wp_automatic_gallery" />';
							} 
							
							$contimgs='';
							foreach ($imgs as $newimg){
								$tempimg =$cg_eb_full_img_t;
								$contimgs.= str_replace('[img_src]', $newimg, $tempimg);
							}
							
							$post_content = str_replace('[item_images]', $contimgs, $post_content);
							
						}
						
						
					}elseif ($camp_type == 'Flicker'   ) {
					
						$post_content = str_replace ( '[img_description]', $abcont, $post_content );
					
					} elseif( $camp_type == 'Vimeo' ) {	
						
						//set player width and height 
						$vm_width = $camp_general['cg_vm_width'];
						$vm_height = $camp_general['cg_vm_height'];
						
						if(trim($vm_width)!= ''){
							$img['vid_embed'] = $vid['vid_embed'] = str_replace('width="560"', 'width="'.$vm_width.'"', $vid['vid_embed']);
						}
		
						if(trim($vm_height)!= ''){
							$img['vid_embed'] = $vid['vid_embed'] = str_replace('height="315"', 'height="'.$vm_height.'"', $vid['vid_embed']);
						}
		
					}elseif( $camp_type == 'Pinterest' ){ 	
						
						$post_content = str_replace ( '[pin_description]', $abcont, $post_content );
		
					}elseif( $camp_type == 'Instagram' ){
						
					}elseif( $camp_type == 'Twitter' ){
						
						
					} else {
						$post_content .= "<br>$abcont";
					}
					
					// Replacing generic tags
					if(stristr($this->used_keyword, '_')) {
						$pan=explode('_', $this->used_keyword);
						$this->used_keyword = $pan[1];
					}
					
					$post_content = str_replace('[keyword]', $this->used_keyword, $post_content);
				 
					foreach ( $img as $key => $val ) {
						
						if( ! is_array($val)){
							$post_content = str_replace ( '[' . $key . ']', $val, $post_content );
							$post_title = str_replace ( '[' . $key . ']', $val, $post_title );
						}
					
					}
					 
					
					// replacing the keywords with affiliate links
					if (in_array ( 'OPT_REPLACE', $camp_opt )) {
						foreach ( $keywords as $keyword ) {
							if (trim ( $keyword != '' )) {
								//$post_content = str_replace ( $keyword, '<a href="' . $camp->camp_replace_link . '">' . $keyword . '</a>', $post_content );
								
								$post_content = preg_replace ( '/\b'.preg_quote($keyword,'/').'\b/', '<a href="' . $camp->camp_replace_link . '">' . $keyword . '</a>', $post_content );
							}
						}
					}
					
				
					
					//replacing patterns 
					if(in_array('OPT_RGX_REPLACE', $camp_opt)){
						
						$regex_patterns = trim($camp_general['cg_regex_replace']);
						echo '<br>Replacing using REGEX';
						
						//protecting tags 
						if(in_array('OPT_RGX_REPLACE_PROTECT', $camp_opt)){
							echo '..protecting tags.';
							
							preg_match_all("/<[^<>]+>/is",$post_content,$matches,PREG_PATTERN_ORDER);
							$htmlfounds=$matches[0];
							
							 	
							//extract all fucken shortcodes
							$pattern="\[.*?\]";
							preg_match_all("/".$pattern."/s",$post_content,$matches2,PREG_PATTERN_ORDER);
							$shortcodes=$matches2[0];
							
							$htmlfounds = array_merge($htmlfounds,$shortcodes);
							
							$htmlfounds = array_filter( array_unique($htmlfounds) );
							
							 
							$i=1;
							foreach ($htmlfounds as $htmlfound){
								 
								 
								
								$post_content = str_replace($htmlfound, "[" . str_repeat('*', $i) . "]", $post_content);
								$i++;
								
							}
							
							 
						}
						
						if(stristr($regex_patterns, '|')){
							$regex_patterns_arr = explode("\n",$regex_patterns);
							
							foreach ($regex_patterns_arr as $regex_pattern){
								
								$regex_pattern = trim($regex_pattern);
								
								if(stristr($regex_pattern, '|')){
									
									$regex_pattern_parts = explode('|', $regex_pattern);
									
									$regex_pattern_search = $regex_pattern_parts[0];
									$regex_pattern_replace = $regex_pattern_parts[1];
									
									echo '<br>Replacing '.htmlentities($regex_pattern_search) .' with '.htmlentities($regex_pattern_replace);
									
									$post_content = preg_replace('{'.$regex_pattern_search.'}su', $regex_pattern_replace, $post_content);
									
									
									
								}
								
								
							}
							
						}
						
						//restore protected tags
						if( isset($htmlfounds) and count($htmlfounds) >0 ){
							
							//restoring
							$i = 1;
							foreach ($htmlfounds as $htmlfound){
								$post_content = str_replace('['.str_repeat('*', $i).']', $htmlfound, $post_content);
								$i++; 
							}
							
							
						}
						
					}
					
					
					// cache images locally ?
					if (in_array ( 'OPT_CACHE', $camp_opt )) {
						
						preg_match_all ( '/<img [^>]*src=["|\']([^"|\']+)/i', stripslashes ( $post_content ), $matches );
						
						$srcs = $matches [1];
						$srcs = array_unique ( $srcs );
						$current_host = parse_url ( home_url (), PHP_URL_HOST );
						foreach ( $srcs as $image_url ) {
							
							 
							//instantiate so we replace . note we modify image_url 
							$image_url_original = $image_url;
							
							//decode html entitiies
							$image_url = html_entity_decode($image_url);
							
							
							if(stristr($image_url, '%') ) {
								$image_url = urldecode($image_url);
							}
							
							//file name to store
							$filename = basename ( $image_url );
							 
							if(stristr($image_url ,' ')){
								$image_url = str_replace(' ', '%20', $image_url);
							} 
							
							$imghost = parse_url ( $image_url, PHP_URL_HOST );
							
							if(stristr($imghost, 'http://')){
								$imgrefer=$imghost;
							}else{
								$imgrefer = 'http://'.$imghost;
							}
							 
							
							if ($imghost != $current_host) {
								echo '<br>Caching image: ' . $image_url;
								
								
								
								// let's cache this image
								// set thumbnail
								$upload_dir = wp_upload_dir ();
		 						
								
								//curl get
								$x='error';
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($image_url));
								
								//empty referal 
								if(! in_array('OPT_CACHE_REFER_NULL', $camp_opt) ){
									curl_setopt ( $this->ch, CURLOPT_REFERER, $imgrefer );
								}else{
									curl_setopt ( $this->ch, CURLOPT_REFERER, '' );
								}
								
								 
								$image_data=curl_exec($this->ch);
								$image_data_md5 = md5($image_data);
								
								//check if already cached before
								$is_cached = $this->is_cached($image_url , $image_data_md5);
								if( $is_cached != false ){
									echo '<--already cached';
									$post_content = str_replace ( $image_url_original, $is_cached , $post_content );
									continue;
								}
								
								  
								$x=curl_error($this->ch);
						 
		
								if(trim($image_data) != ''){
									
									
									$x=curl_error($this->ch);
								 
									
									
									
									
									if (stristr ( $filename, '?' )) {
										$farr = explode ( '?', $filename );
										$filename = $farr [0];
									}
									
									if (wp_mkdir_p ( $upload_dir ['path'] ))
										$file = $upload_dir ['path'] . '/' . $filename;
									else
										$file = $upload_dir ['basedir'] . '/' . $filename;
										
										// check if same image name already exists
									
									if (file_exists ( $file )) {
										$filename = time ( 'now' ) . '_' . $filename;
										
										if (wp_mkdir_p ( $upload_dir ['path'] ))
											$file = $upload_dir ['path'] . '/' . $filename;
										else
											$file = $upload_dir ['basedir'] . '/' . $filename;
									} else {
									}
									
									 
									file_put_contents ( $file, $image_data );
									$file_link = $upload_dir ['url'] . '/' . $filename;
									
									// replace original src with new file link
									$post_content = str_replace ( $image_url_original, $file_link, $post_content );
									$this->img_cached($image_url_original, $file_link,$image_data_md5,$file);
									
									echo '<-- cached';
								}else{
									echo '<-- can not get image content '.$x;
								}
								
							
							}
						} // end foreach image
					}
					
					// replacing words that should be replaced
					$sets = stripslashes(get_option ( 'wp_automatic_replace', '' ) );
					
					
					//exit;
					$sets_arr = explode ( "\n", $sets );
					
					foreach ( $sets_arr as $set ) {
						if (trim ( $set ) != '' && stristr ( $set, '|' )) {
							
							// valid set let's replace
							$set_words = explode ( '|', $set );
							 
							
							// cleaning empty words
							$i = 0;
							foreach ( $set_words as $setword ) {
								if (trim ( $setword ) == '') {
									unset ( $set_words [$i] );
								}
								$i ++;
							}
							
							if (count ( $set_words ) > 1) {
								// word 1
								
								$word1 = trim($set_words [0]);
								
								// randomize replacing word
								$rand = rand ( 1, count ( $set_words ) - 1 );
								$replaceword = trim($set_words [$rand]);
								
								echo '<br>replacing "'.$word1.'" by "'.$replaceword.'"' ;
								
								if( in_array('OPT_REPLACE_NO_REGEX', $wp_automatic_options) ){
									
									$post_title = str_replace  (  $word1 , $replaceword, $post_title );
									$post_content = str_replace (   $word1 , $replaceword, $post_content );
									
								}else{
									
									$post_title = preg_replace ( '/\b' . trim ( preg_quote( $word1,'/') ) . '\b/iu', $replaceword, $post_title );
									$post_content = preg_replace ( '/\b' . trim (  preg_quote( $word1,'/') ) . '\b/iu', $replaceword, $post_content );
								}
								
								
							}
						}
					}
					
				 
						
					// spin the content
					if (in_array ( 'OPT_TBS', $camp_opt ) && trim ( $abcont ) != ''   || stristr($post_content, '{') || stristr($post_title, '{') )  {
						
						if($camp_type != 'Spintax'){
						
							echo '<br>Spin the content enabled';
							
							$abconts = $post_title . '(99999)' . $post_content;
							
							if (in_array ( 'OPT_TBS', $camp_opt )){
								$abconts =$this->spin ( $abconts );
							}
							
							$abconts = $this->spintax->spin ( $abconts );
							$tempz = explode ( '(99999)', $abconts );
							$post_title = $tempz [0];
							$post_content = $tempz [1];
							
						}//not spintax
					}
					
					//remove nospin tags
					$post_title = str_replace('[nospin]', '', $post_title);
					$post_title = str_replace('[/nospin]', '', $post_title);
					
					$post_content = str_replace('[nospin]', '', $post_content);
					$post_content = str_replace('[/nospin]', '', $post_content);
					
					
					
					//categories for post
					if( stristr($camp->camp_post_category, ',') ) {
						$categories = array_filter(explode(',',$camp->camp_post_category ));
					}else{
						$categories = array ($camp->camp_post_category);
					}
					
					
					//check if dummy title (notitle)
					if($post_title == '(notitle)') $post_title = '';
					
					//strip scripts
					
					//$post_content = preg_replace('{<script.*?script>}s', '', $post_content);
					
					
					
					
					// building post
					$my_post = array (
							'post_title' => strip_tags( $post_title),
							'post_content' =>  $post_content,
							'post_status' => $camp->camp_post_status,
							'post_author' => $camp->camp_post_author,
							'post_type' => $camp->camp_post_type,
							'post_category' => $categories,
							
					);
						
					
					//prepare author 
					if($camp_type == 'Feeds' && isset($img['author']) && trim($img['author']) != ''){
						echo '<br>Trying to set the post author to '.$img['author'];
						
						$author_id = $this->get_user_id_by_display_name($img['author']);
						
						if($author_id != false){
							$my_post['post_author'] = $author_id;	
						}
						
						
					}
					
					
					if (1) {
						
						kses_remove_filters ();
					}
					
					if ( $camp_type == 'Feeds' && in_array ( 'OPT_ORIGINAL_TIME', $camp_opt ) ){
						echo '<br>Setting date for the post to '.$article['wpdate'];
						$my_post['post_date']=$article['wpdate'];
					
					}
					
					if ( $camp_type == 'Instagram' && in_array ( 'OPT_IT_DATE', $camp_opt ) ){
						echo '<br>Setting date for the post to '.$img['item_created_date'];
						$my_post['post_date']=$img['item_created_date'];
					}
					
					if ( $camp_type == 'Twitter' && in_array ( 'OPT_IT_DATE', $camp_opt ) ){
						
						$item_created_at = date ( 'Y-m-d H:i:s' , strtotime( $img['item_created_at'])  );
						$item_created_at = get_date_from_gmt($item_created_at);
						
						echo '<br>Setting date for the post to '.$img['item_created_at'];
						$my_post['post_date']=$item_created_at;
						
					}
					
					if ( $camp_type == 'Youtube' && in_array ( 'OPT_YT_ORIGINAL_TIME', $camp_opt ) ){
						$realDate = date('Y-m-d H:i:s' ,$vid['vid_time'] );
						echo '<br>Setting date for the post to '. $realDate;
						$my_post['post_date']= $realDate ;  
						
					}
					
					if ( $camp_type == 'Vimeo' && in_array ( 'OPT_VM_ORIGINAL_TIME', $camp_opt ) ){
						$realDate =   $vid['vid_created_time']  ;
					 	echo '<br>Setting date for the post to '. $realDate;
						$my_post['post_date']= $realDate ;
							
					}
					
					if ( $camp_type == 'Facebook' && in_array ( 'OPT_ORIGINAL_FB_TIME', $camp_opt ) ){
						$realDate = $img['original_date']; 
						echo '<br>Setting date for the post to '. $realDate;
						$my_post['post_date']= $realDate ;
							
					}
					
					//set excerpt of amazon product post type 
					if($camp_type == 'Amazon' && $camp->camp_post_type == 'product' && in_array('OPT_AMAZON_EXCERPT', $camp_opt)){
						echo '<br>Setting product short description';
						$my_post['post_excerpt'] = $img['product_desc'];
					}
					
					//remove filter kses for security 
					remove_filter('content_save_pre', 'wp_filter_post_kses');
					
					//fixing utf8
					$my_post['post_content'] = $this->fix_utf8($my_post['post_content']);
					$my_post['post_title'] = $this->fix_utf8($my_post['post_title']);
					
					// Insert the post into the database
					$id = wp_insert_post ( $my_post );
					
					if($id == 0){
						echo '<br>Error:Post Insertion failure';
						//print_r($my_post);
					}
					
					 
					//wpml integration
					if(in_array('OPT_WPML', $camp_opt)  && function_exists('icl_object_id') ){
						include_once( WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php' );
						$language_code = $camp_general['cg_wpml_lang']; // change the language code
						echo '<br>Setting WPML language to: '.$language_code;
						wpml_update_translatable_content('post_'.$camp->camp_post_type, $id, trim($language_code) );
					}
					 
					//returning the security filter
					add_filter('content_save_pre', 'wp_filter_post_kses');
					
					//setting categories for custom post types
					if(  $camp->camp_post_type != 'post'){
						
						$customPostTaxonomies = get_object_taxonomies($camp->camp_post_type);
							
						if(count($customPostTaxonomies) > 0)
						{
							 
							foreach($customPostTaxonomies as $tax)
							{
								 
								if(is_taxonomy_hierarchical($tax)){
									echo '<br>Setting taxonomy '.$tax.' to '.$camp->camp_post_category;
									@wp_set_post_terms($id, $categories , $tax,true);
								}
							}
						}
						
						
						
					}else{
						 
					} 
					 
					//feeds category 
					if($camp_type == 'Feeds' && trim($img['cats'] != '')){
						
						add_post_meta ( $id, 'original_cats', $img['cats'] );
						
						if(in_array('OPT_ORIGINAL_CATS', $camp_opt)){
						
							echo '<br>Setting Categories to :'.$img['cats'];
							
							$cats= array_filter(explode(',', $img['cats']));
							
							if( $camp->camp_post_type == 'post' ){
								$taxonomy = 'category';
							}else{
								$taxonomy =  $camp_general['cg_camp_tax'] ;
							}
							
							$new_cats = array();
							
							//convert cats to ids
							foreach($cats as $cat_name){
								
								$cat  = get_term_by('name', $cat_name , $taxonomy);
								
								//check existence
								if($cat == false){
								
									
									//cateogry not exist create it
									$cat = wp_insert_term($cat_name, $taxonomy);
								
									if( ! is_wp_error($cat) ){
										//category id of inserted cat
										$cat_id = $cat['term_id'] ;
										$new_cats[] = $cat_id;
									}
									
								
								}else{
								
									//category already exists let's get it's id
									$cat_id = $cat->term_id ;
									$new_cats[] = $cat_id;
								}
								
								
								
							}
							
							//insert cats
							if(count($new_cats) > 0) 
							wp_set_post_terms($id,$new_cats,$taxonomy ,true);
							
						}
						
					}
					
					
					$post_id = $id;
					add_post_meta ( $id, 'original_title', $title );
					@add_post_meta ( $id, 'original_link', $source_link );
					
					//if link to source set flag
					if(in_array('OPT_LINK_SOURSE', $camp_opt)){
						add_post_meta ( $id, '_link_to_source', 'yes' );
					}
					
					//if link canonical
					if(in_array('OPT_LINK_CANONICAL', $camp_opt)){
						add_post_meta ( $id, 'canonical_url', $source_link );
					}
					 
					// add featured image
					if (in_array ( 'OPT_REPLACE', $camp_opt )) {
						foreach ( $keywords as $keyword ) {
							if (trim ( $keyword != '' )) {
								$post_content = str_replace ( $keyword, '<a href="' . $camp->camp_replace_link . '">' . $keyword . '</a>', $post_content );
							}
						}
					}
					
		
					if (in_array ( 'OPT_THUMB', $camp_opt )) {
						
						
						//if force og_img
						if(in_array('OPT_FEEDS_OG_IMG', $camp_opt) && isset($img['og_img'])){
							$srcs = array($img['og_img']) ;
						}
						
						//if youtube set thumbnail to video thum
						if($camp_type == 'Youtube' || $camp_type == 'Vimeo' ){
							//set youtube/vimeo image as featured image
							
							
							//check if maxres exists
							if( stristr( $vid['vid_img'] , 'hqdefault' ) ){
								$maxres = str_replace('hqdefault', 'maxresdefault', $vid['vid_img']) ;

								$maxhead = wp_remote_head($maxres);
								
								if(! is_wp_error($maxres) && $maxhead['response']['code'] == 200 ){
									$vid['vid_img'] = $maxres;
								}
								
							}
							
							
							$srcs=array($vid['vid_img']);
							
							echo '<br>Vid Thumb:'.$vid['vid_img'];
								
						}elseif(isset($srcs) && count($srcs) > 0 ){
							
						}else{
							// extract first image
							preg_match_all ( '/<img [^>]*src[\s]*=[\s]*["|\']([^"|\']+)/i', stripslashes ( $post_content ), $matches );
							$srcs = $matches [1];
						}
						 
						
						//may be a readability missed the image on the content get it from summary ?
						if( count($srcs) == 0 &&  $camp_type == 'Feeds' && in_array('OPT_FULL_FEED', $camp_opt) ){
							echo '<br>Featured image missing on full content searching it on feed instead';
							preg_match_all ( '/<img [^>]*src=["|\']([^"|\']+)/i', stripslashes ( $article['original_content'] ), $matches );
							$srcs = $matches [1];
							
							if(count($srcs) == 0){
								echo '<br>No image found at the feed summary';
								
								if( trim($img['og_img']) != '' ){
									echo '<br>Graph image thumb found';
									$srcs = array($img['og_img']);
								}
								
							}
							 	
						}
						
						
						
						//No featured image found let's check if random image list found
						if ( count($srcs) == 0 && in_array ( 'OPT_THUMB_LIST', $camp_opt )) {
							echo '<br>Trying to set random image as featured image';
							
							$cg_thmb_list=$camp_general['cg_thmb_list'];
							
							$cg_imgs = explode("\n", $cg_thmb_list);
							$cg_imgs = array_filter($cg_imgs);
							$cg_rand_img = trim( $cg_imgs [rand(0,count($cg_imgs)-1)]);
							
							//validate image
							if(trim( $cg_rand_img)!='' ){
								$srcs=array($cg_rand_img);
							}
							  
						}
						
						//if foce using thumb list
						if( in_array('OPT_THUMB_LIST_FORCE', $camp_opt) && in_array ( 'OPT_THUMB_LIST', $camp_opt ) ){
							
							echo '<br>Force using image from set list';
							
							$cg_thmb_list=$camp_general['cg_thmb_list'];
								
							$cg_imgs = explode("\n", $cg_thmb_list);
							$cg_imgs = array_filter($cg_imgs);
							
							
							
							$cg_rand_img = trim( $cg_imgs [rand(0,count($cg_imgs)-1)]);
		
							
							//validate image
							if(trim( $cg_rand_img)!='' ){
								$srcs=array($cg_rand_img);
							}
							
						}
						 
						//check srcs size to skip small images
						if(count($srcs) >0 && in_array('OPT_THUMB_WIDTH_CHECK', $camp_opt)){
							
							$cg_minimum_width = 0;
							$cg_minimum_width = $camp_general['cg_minimum_width'];

							if(!  (is_numeric( $cg_minimum_width ) && $cg_minimum_width > 0 ) ){
								$cg_minimum_width = 100;
							}
							
							$n=0;
							$upload_dir = wp_upload_dir ();
							
							foreach($srcs as $current_img){
								
								echo '<br>Candidate featured image: '.$current_img ;
								
							 	//curl get
								$x='error';
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($current_img));
								$image_data=curl_exec($this->ch);
								$x=curl_error($this->ch);
									
								if(trim($image_data) != ''){
									
									//let's save the file
									if (wp_mkdir_p ( $upload_dir ['path'] ))
										$file = $upload_dir ['path'] . '/' . 'temp_wp_automatic';
									else
										$file = $upload_dir ['basedir'] . '/' . 'temp_wp_automatic';
									
									file_put_contents ( $file, $image_data );
		
									$size = getimagesize($file);
									  
									if($size != false){
										
										if($size[0] >$cg_minimum_width ){
											echo '<-- Valid width is '.$size[0] . ' larget than '.$cg_minimum_width   ;
											break;
										}else{
											echo '<-- width is too low '.$size[0];
											unset($srcs[$n]);
										}
										
									}else{
										echo '<--size verification failed';
										unset($srcs[$n]);
									}
									
									
								}else{
									echo '<--no content ';
									unset($srcs[$n]);
								}
								
								$n++;
							}
						
						}		
						
						//setting the thumb
						if (count ( $srcs ) > 0) {
							
							$src = $srcs [0];
							
							$image_url = $src;
							 
							$this->log ( 'Featured image', '<a href="' . $image_url . '">' . $image_url . '</a>' );
							
							echo '<br>Featured image src: '.$image_url;
							
							// set thumbnail
							$upload_dir = wp_upload_dir ();
							
							//img host
							$imghost = parse_url ( $image_url, PHP_URL_HOST );
								
							if(stristr($imghost, 'http://')){
								$imgrefer=$imghost;
							}else{
								$imgrefer = 'http://'.$imghost;
							}
		
							//empty referal
							if( ! in_array('OPT_CACHE_REFER_NULL', $camp_opt) ){
								curl_setopt ( $this->ch, CURLOPT_REFERER, $imgrefer );
							}else{
								curl_setopt ( $this->ch, CURLOPT_REFERER, '' );
							}
							 
							//decode html entitiies
							$image_url = html_entity_decode($image_url);
								
							if(stristr($image_url, '%') ) {
								$image_url = urldecode($image_url);
							}
		
							//file name to store
							$filename = basename ( $image_url );
							
							
							if(stristr($image_url ,' ')){
								$image_url = str_replace(' ', '%20', $image_url);
							}
							
							
							if(!in_array('OPT_THUM_NELO', $camp_opt)){
							
								//get image content 
								$x='error';
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim( html_entity_decode($image_url) ) );
								$image_data=$this->curl_exec_follow($this->ch);
								$image_data_md5 = md5($image_data);
								$x=curl_error($this->ch);
								 
								if(trim($image_data) != ''){
			 						//check if already saved 
									$is_cached = $this->is_cached($image_url , $image_data_md5);
									if( $is_cached != false ){
										echo '<--already cached' ;
										$file = $this->cached_file_path;
										$guid = $is_cached; 
			 						}else{ 
									 
										
										if (stristr ( $filename, '?' )) {
											$farr = explode ( '?', $filename );
											$filename = $farr [0];
										}
										
										//pagepeeker fix
										if(stristr($image_url, 'pagepeeker')){
											$filename = md5($filename).'.jpg';
										}
										
										if (wp_mkdir_p ( $upload_dir ['path'] ))
											$file = $upload_dir ['path'] . '/' . $filename;
										else
											$file = $upload_dir ['basedir'] . '/' . $filename;
											
											// check if same image name already exists
										if (file_exists ( $file )) {
											
											 //get the current saved one to check if identical
											$already_saved_image_link=$upload_dir ['url'] . '/' . $filename;
				
											//curl get
											$x='error';
											$url=$already_saved_image_link;
											curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
											curl_setopt($this->ch, CURLOPT_URL, trim($url));
											 
											$exec=curl_exec($this->ch);
											
											 
											
											
											if(trim($exec) == trim($image_data)){
												$idential = true;
												echo '<br>Featured image already exists with same path.. using it';
											}else{
												echo '<br>Featured image exists with same path but not identical.. saving  ';
												
												$filename = time ( 'now' ) . '_' . $filename;
												
											}
											 
										}
										
										
										//saving image 
										if(! isset($idential )){
											if (wp_mkdir_p ( $upload_dir ['path'] ))
												$file = $upload_dir ['path'] . '/' . $filename;
											else
												$file = $upload_dir ['basedir'] . '/' . $filename;
											
											$f=file_put_contents ( $file, $image_data );
										}
										 
										$guid = $upload_dir['url'] . '/' . basename( $filename );
										
										$this->img_cached($image_url, $guid,$image_data_md5,$file);
									
			 						}//not cached	
									
									//atttatchment check if exists or not
									global  $wpdb;
									
									
									$query = "select * from $wpdb->posts where guid = '$guid'";
									$already_saved_attachment = $wpdb->get_row($query);
									 
									if(isset($already_saved_attachment->ID)){
										$attach_id = $already_saved_attachment->ID;
										 
									}else{
										
			
										$wp_filetype = wp_check_filetype ( $filename, null );
										
										if($wp_filetype['type'] == false){
											$wp_filetype['type'] = 'image/jpeg';
										}
										
											
										$attachment = array (
												'guid'           =>  $guid,
												'post_mime_type' => $wp_filetype ['type'],
												'post_title' => sanitize_file_name ( $filename ),
												'post_content' => '',
												'post_status' => 'inherit'
										);
										$attach_id = wp_insert_attachment ( $attachment, $file, $post_id );
										require_once (ABSPATH . 'wp-admin/includes/image.php');
										$attach_data = wp_generate_attachment_metadata ( $attach_id, $file );
										wp_update_attachment_metadata ( $attach_id, $attach_data );
										
									}
									 
			
								
									set_post_thumbnail ( $post_id, $attach_id );
									
									echo ' <-- thumbnail set successfully';
									
									//if hide first image set the custom field 
									if(in_array('OPT_THUMB_STRIP', $camp_opt)){
										update_post_meta ( $post_id, 'wp_automatic_remove_first_image', 'yes' );
									}
									
								}else{
									echo ' <-- can not get image content '.$x;
								}
							
							}else{//nelo
								//setting custom field for nelo image
								echo '<br>Setting the featured image custom field for nelio plugin'; 
								update_post_meta($id, '_nelioefi_url', $image_url);	
							}	
							
							
						} else {
							
							//currently no images in the content 
							$this->log ( 'Featured image', 'No images found to set as featured' );
				
						}
					} // thumbnails
					  
					// tags
					if (in_array ( 'OPT_TAG', $camp_opt )) {
						wp_set_post_tags ( $id, $keywords, true );
					}
					
					//youtube tags and comments
					if($camp_type == 'Youtube'   ){
						
						//tags
						if(in_array('OPT_YT_TAG', $camp_opt)){
							if(trim($this->used_tags) != ''){
								wp_set_post_tags ( $id, $this->used_tags, true );
							}	
						}
						
						
						
						//comments
						if(in_array('OPT_YT_COMMENT', $camp_opt) ){
							echo '<br>Trying to post comments';
							
							//get id
							$temp=explode('v=', $this->used_link);
							$vid_id=$temp[1] ;
							
							$wp_automatic_yt_tocken=trim(get_option('wp_automatic_yt_tocken',''));
							
							$comments_link="https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&videoId=".$vid_id."&key=$wp_automatic_yt_tocken";
							
							//curl get
							$x='error';
							$url=$comments_link;
							curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
							curl_setopt($this->ch, CURLOPT_URL, trim($url));
						 	$exec=curl_exec($this->ch);
 	 
						 	
							$x=curl_error($this->ch);
						 	
							if(trim($x) != '') echo '<br>'.$x;
							
							if(trim($exec) !=''){
								
								if( stristr($exec, 'items')){
									$comments_array=json_decode($exec);
									
									$entry=$comments_array->items ;
									
									if(count($entry) == 0){
										echo '<br>No comments found';
									}else{
										echo  '<br>Found '.count($entry). ' comment to post';
										 
										foreach($entry as $comment ){
											
											$comment = $comment->snippet->topLevelComment->snippet;
											
											$commentText= $comment->textDisplay;
											$commentAuthor= $comment->authorDisplayName;
											$commentUri= $comment->authorChannelUrl;
											
		 									$time = current_time('mysql');
											
		 									if(trim($commentText) != '' ){
												$data = array(
														'comment_post_ID' => $id,
														'comment_author' => $commentAuthor,
														'comment_author_email' => '',
														'comment_author_url' => $commentUri,
														'comment_content' => $commentText,
														'comment_type' => '',
														'comment_parent' => 0,
														 
														'comment_author_IP' => '127.0.0.1',
														'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
														'comment_date' => $time,
														'comment_approved' => 1,
												);
												
												wp_insert_comment($data);
		 									}
		 									
										}
										
									}
									
								}else{
									echo '<br>could not find comments';
								}
								
							}else{
								echo '<br>No valid comments feed';
							}
							
						}
						
					}
					
					// AFTER POST SPECIFIC
					if ($camp_type == 'Flicker') {
						if (in_array ( 'OPT_FL_TAG', $camp_opt )) {
							wp_set_post_tags ( $id, $img ['img_tags'], true );
						}
					}
					
					//After post vimeo 
					if($camp_type == 'Vimeo'){
						
						if(in_array('OPT_VM_TAG', $camp_opt)){
								
							if(trim($vid['vid_tags']) != ''){
								wp_set_post_tags ( $id, $vid['vid_tags'], true );
							}
						}
						
					}
					
					if($camp_type == 'Instagram'){
					
						if(in_array('OPT_IT_TAGS', $camp_opt)){
					
						 
							if(trim($img['item_tags']) != ''){
								
								echo '<br>Setting tags:'.$img['item_tags'];
								
								wp_set_post_tags ( $id, $img['item_tags'], true );
							}
						}
						
						
						//comments
						if(in_array('OPT_IT_COMMENT', $camp_opt) ){
							
							echo '<br>Trying to post comments';
							
							$time = current_time('mysql');
		
							$comments = $img['item_comments'];
		 						
							if(count($comments) > 0){
						
								  
									 
										echo  '<br>Found '.count($comments). ' comment to post';
											
										foreach($comments as $comment ){
												
											$commentText= $comment->text;
											$commentAuthor= $comment->from->full_name;
											
											if(trim($commentAuthor) == '') $commentAuthor= $comment->from->username;
											
											$commentAuthorID= $comment->author[0]->uri->x;
											$commentUri= "https://instagram.com/". $comment->from->username;
												
											if(in_array('OPT_IT_DATE', $camp_opt) ){
												$time = date('Y-m-d H:i:s',$comment->created_time);
											} 
											
												
											if(trim($commentText) != '' ){
												$data = array(
														'comment_post_ID' => $id,
														'comment_author' => $commentAuthor,
														'comment_author_email' => '',
														'comment_author_url' => $commentUri,
														'comment_content' => $commentText,
														'comment_type' => '',
														'comment_parent' => 0,
															
														'comment_author_IP' => '127.0.0.1',
														'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
														'comment_date' => $time,
														'comment_approved' => 1,
												);
						
												wp_insert_comment($data);
											}
						
										}
						
									 
										
							 
						
							}else{
								echo '<br>No comments found';
							}
								
						}
					
					
					
					}
					
					//After ebay 
					if(in_array('OPT_EB_REDIRECT_END', $camp_opt)){
						echo '<br>Setting expiry date: '.$img['item_end_date'];
						
						$expiry_date = strtotime( $img['item_end_date'] );
						
						add_post_meta($id, 'wp_automatic_redirect_date', $expiry_date);
						add_post_meta($id, 'wp_automatic_redirect_link', $camp_general['cg_eb_redirect_end']);
 						
					}
					
					//setting post tags
					$post_tags=array();
					
					if(in_array('OPT_ADD_TAGS',$camp_opt ) ){
						$post_tags = array_filter( explode("\n", $camp_general['cg_post_tags']));
						
						$max = $camp_general['cg_tags_limit'];
						if(! is_numeric($max)) $max= 100;
						
						if(in_array('OPT_RANDOM_TAGS', $camp_opt)  && count($post_tags) > $max ){
							
							
							
							$rand_keys = array_rand($post_tags,$max);
							
							$temp_tags = array();
							foreach ($rand_keys as $key){
								$temp_tags[] = $post_tags[$key];
							}
							
							$post_tags = $temp_tags;
								
						}
							 
					}
					
					
		
					if(in_array('OPT_ORIGINAL_TAGS', $camp_opt)){
						$new_tags = explode(',', $img['tags']);
						
						if(count($new_tags) > 0 ){
							$post_tags = array_merge($post_tags,$new_tags);
						}
						
					}
					
					
				 
					if(count($post_tags) >0){
						echo '<br>Setting ' . count($post_tags) .' post tags as tags';
						wp_set_post_tags ( $id, implode(',', $post_tags) , true );
					}
					
					//amazon woocommerce integration
					if($camp_type == 'Amazon' && $camp->camp_post_type == 'product'){
						
						$camp_post_custom_k = array_merge ( $camp_post_custom_k , array('_regular_price','_price','_visibility', '_product_url','_button_text','_product_type'));
						$camp_post_custom_v = array_merge ( $camp_post_custom_v , array('[price_numeric]','[price_numeric]','visible','[product_link]','buy now','external'));
						
						
						 
						wp_set_object_terms ($id, 'external', 'product_type');
						
						
						
					}elseif($camp_type == 'eBay' && $camp->camp_post_type == 'product'){
						
						$camp_post_custom_k = array_merge ( $camp_post_custom_k , array('_regular_price','_price','_visibility', '_product_url','_button_text','_product_type'));
						$camp_post_custom_v = array_merge ( $camp_post_custom_v , array('[item_price]','[item_price] ','visible','[item_link]','buy now','external'));
							
						wp_set_object_terms ($id, 'external', 'product_type');
						
					}elseif( $camp->camp_post_type == 'product' ){
						
							$camp_post_custom_k = array_merge ( $camp_post_custom_k , array('_visibility'));
							$camp_post_custom_v = array_merge ( $camp_post_custom_v , array('visible'));
								
							wp_set_object_terms ($id, 'external', 'product_type');
								
						
					}
					
					//TrueMag integration 
					if( ($camp_type == 'Youtube' || $camp_type == 'Vimeo')  && defined('PARENT_THEME')     ){
						
						if(PARENT_THEME =='truemag' || PARENT_THEME =='newstube' ){
						
							echo '<br>TrueMag/NewsTube theme exists adabting config..';
							$camp_post_custom_k = array_merge ( $camp_post_custom_k , array('tm_video_url'));
							$camp_post_custom_v = array_merge ( $camp_post_custom_v , array('[source_link]'));
						
						
						}
		 			}
					
		  
				 	
					//replacing tags 
					$camp_post_custom_v = implode ( '****', $camp_post_custom_v );
					foreach ( $img as $key => $val ) {
						if(! is_array($val)){
							$camp_post_custom_v = str_replace ( '[' . $key . ']', $val, $camp_post_custom_v );
						}
					}
					$camp_post_custom_v = explode ( '****', $camp_post_custom_v );
					
					
					// adding custom filds
					$in = 0;
					if (count ( $camp_post_custom_k ) > 0) {
						foreach ( $camp_post_custom_k as $key ) {
							if (trim ( $key ) != '' & trim ( $camp_post_custom_v [$in] != '' )) {
								echo '<br>Setting custom field ' . $key ;
								
								//serialized arrays
								if(is_serialized($camp_post_custom_v [$in])) $camp_post_custom_v [$in] = unserialize($camp_post_custom_v [$in]);
								
								update_post_meta ( $id, $key, $camp_post_custom_v [$in] );
							}
							
							$in ++;
						}
					}
					
					//setting post format OPT_FORMAT 
					if (in_array ( 'OPT_FORMAT', $camp_opt )) {
						echo '<br>setting post format to '.$camp_general['cg_post_format'];
						set_post_format($id, stripslashes($camp_general['cg_post_format']) );
					}elseif ( ($camp_type == 'Youtube' || $camp_type == 'Vimeo') && defined( 'PARENT_THEME')    ){
						
						if(PARENT_THEME =='truemag' || PARENT_THEME =='newstube'  ){
							echo '<br>setting post format to Video';
							set_post_format($id,  'video' );
						}
					}
					
					 
					
					if(in_array('OPT_PREVIEW_EDIT',$wp_automatic_options)){
						$plink =  get_edit_post_link(  $id );
					}else{
						$plink =  get_permalink ( $id ) ;
					}
					
					$plink = str_replace('&amp;', '&', $plink) ;
					 
					
					$display_title = get_the_title ( $id );
					
					if(trim($display_title) == '') $display_title = '(no title)';
					
					$now = date ( 'Y-m-d H:i:s' );
					echo '<br>New Post posted: <a target="_blank" class="new_post_link" time="' . $now . '" href="' .  $plink . '"> ' . $display_title . '</a>';
					$this->log ( 'Posted:' . $camp->camp_id, 'New post posted:<a href="' . $plink . '">' . get_the_title ( $id ) . '</a>' );
					exit ();
					
					print_r ( $ret );
				} // if title
			} // end function
			
			
			function fire_proxy() {
				echo '<br>Proxy Check Fired';
				$proxies = get_option ( 'wp_automatic_proxy' );
				if (stristr ( $proxies, ':' )) {
					echo '<br>Proxy Found lets try';
					// listing all proxies
					
					$proxyarr = explode ( "\n", $proxies );
					
					foreach ( $proxyarr as $proxy ) {
						if (trim ( $proxy ) != '') {
							
							if (substr_count ( $proxy, ':' ) == 3) {
								echo '<br>Private proxy found .. using authentication';
								$proxy_parts = explode ( ':', $proxy );
								
								$proxy = $proxy_parts [0] . ':' . $proxy_parts [1];
								$auth = $proxy_parts [2] . ':' . $proxy_parts [3];
								
								curl_setopt ( $this->ch, CURLOPT_PROXY, trim ( $proxy ) );
								curl_setopt ( $this->ch, CURLOPT_PROXYUSERPWD, $auth );
							} else {
								curl_setopt ( $this->ch, CURLOPT_PROXY, trim ( $proxy ) );
							}
							
							echo "<br>Trying using proxy :$proxy";
							
							curl_setopt ( $this->ch, CURLOPT_HTTPPROXYTUNNEL, 1 );
							
							curl_setopt ( $this->ch, CURLOPT_URL, 'www.bing.com/search?count=50&intlF=1&mkt=En-us&first=0&q=test' );
							// curl_setopt($this->ch, CURLOPT_URL, 'http://whatismyipaddress.com/');
							$exec = curl_exec ( $this->ch );
							
							if (curl_error ( $this->ch )) {
								echo '<br>Curl Proxy Error:' . curl_error ( $this->ch );
							} else {
								
								if (stristr ( $exec, 'It appears that you are using a Proxy' ) || stristr ( $exec, 'excessive amount of traffic' )) {
									echo '<br>Proxy working but captcha met let s skip it';
								} elseif (stristr ( $exec, 'microsoft.com' )) {
									
									// succsfull connection here
									// echo curl_exec($this->ch);
									// reordering the proxy
									$proxies = str_replace ( ' ', '', $proxies );
									$proxies = str_replace ( $proxy, '', $proxies );
									$proxies = str_replace ( "\n\n", "\n", $proxies );
									$proxies = "$proxy\n$proxies";
									// echo $proxies;
									update_option ( 'wp_automatic_proxy', $proxies );
									
									echo '<br>Connected successfully using proxy :' . $proxy;
									
									return true;
								} else {
								}
							}
						}
					}
					
					// all proxies not working let's call proxyfrog for new list
					
					// no proxyfrog list
					$this->unproxyify ();
					
					// proxifing the connection
				}else{
					echo '..No proxies';
				}
			}
			
			/*
			 * ---* Clear proxy function ---
			 */
			function unproxyify() {
				// clean the connection
				unset ( $this->ch );
				
				// curl ini
				$this->ch = curl_init ();
				curl_setopt ( $this->ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $this->ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $this->ch, CURLOPT_CONNECTTIMEOUT, 20 );
				curl_setopt ( $this->ch, CURLOPT_TIMEOUT, 30 );
				curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://www.google.com' );
				curl_setopt ( $this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8' );
				curl_setopt ( $this->ch, CURLOPT_MAXREDIRS, 5 ); // Good leeway for redirections.
				curl_setopt ( $this->ch, CURLOPT_FOLLOWLOCATION, 1 ); // Many login forms redirect at least once.
				curl_setopt ( $this->ch, CURLOPT_COOKIEJAR, "cookie.txt" );
			}
			
			/*
			 * ---* article base get links for a keyword ---
			 */
			function article_base_getlinks($keyword, $camp) {
				
			 	
					// get associated page num from the keyword and camp id from wp_automatic_articles_keys
					$query = "select * from {$this->wp_prefix}automatic_articles_keys where keyword = '$keyword' and camp_id  = '$camp->camp_id'";
					$camp_key = $this->db->get_results ( $query );
					$camp_key = $camp_key [0];
					
					if (count ( $camp_key ) == 0)
						return false;
					
					$page = $camp_key->page_num;
					 
					
					if (   $page == - 1) {
						
							//check if it is reactivated or still deactivated
							if($this->is_deactivated($camp->camp_id, $keyword)){
								 
								$page = 1;
								
							}else{
								//still deactivated
							
								return false;
							}
						
					}
					
					
					if ($page == 0) {
						$page = 1;
					}
					
					echo '<br>Trying to call EA for new links page:' . $page;
					
					$keywordenc = urlencode ( 'site:ezinearticles.com ' . $keyword );
					
					$linksearch = "http://www.webcrawler.com/search/web?qsi=$page&q=$keywordenc";
				 	
					echo '<br>Link Search:'.$linksearch;
					
					curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://webcrawler.com' );
					
					// Get the search page
					$url = $linksearch;
					curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
					
					// Get
					$x = 'error';
					while ( trim ( $x ) != '' ) {
						$exec = curl_exec ( $this->ch );
						echo $x = curl_error ( $this->ch );
					}
					
					 
					// validate a reply here
					if (! stristr ( $exec, 'WebCrawler' )) {
						echo '<br>Invalid WebCrawler search results try using fresh proxy  .';
						echo $exec;
						
						return false;
					}
					
					
					 
					// check if not found deactivate this keyword
					if (! stristr ( $exec, 'resultDisplayUrl' )) {
						 
						echo '<br> no matching results found for this keyword';
						$query = "update {$this->wp_prefix}automatic_articles_keys set page_num = '-1'  where keyword = '$keyword' and camp_id  = '$camp->camp_id'";
						$this->db->query ( $query );
						
						//deactivate for 60 minutes
						$this->deactivate_key($camp->camp_id, $keyword);
						
						return false;
					} else {
						echo '<br>found results for this keyword';
					}
					
					//extract links ClickHandler.ashx?du=http%3a%2f%2fezinearticles.com%2f%3fDiamond-Engagement-Rings-for-Young-Lovers%26id%3d8231753&
					preg_match_all ( '/ru\=(.*?)&/is', $exec, $matches );
				 	
					 
					$links = $matches [1];
					  
					echo '<br>Links got from EA:' . count ( $links );
					$this->log ( 'links found', count ( $links ) . ' New Links added from ezine articles to post articles from' );
					
					echo '<ol>';
					$i = 0;
					foreach ( $links as $link ) {
						
						// verify id in link
						echo '<li>Link:'.urldecode($link);
						 
						if (stristr ( $link, 'id%3d' )) {
							
							// verify uniqueness
							$link_url = urldecode($link);
							
							if( $this->is_execluded($camp->camp_id, $link_url) ){
								echo '<-- Execluded';
								continue;
							}
								 
								
							if ( ! $this->is_duplicate($link_url) )  {
								$title = '';
								$cache = '';
								$query = "insert into {$this->wp_prefix}automatic_articles_links (link,keyword,page_num,title,bing_cache) values('$link' ,'$keyword','$page','$title','$cache')";
								$this->db->query ( $query );
								$freshlinks = 1;
							} else {
								echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
							}
							
							echo '</li>';
							 
							// incrementing i
							
						} // if contain id
						$i ++;
					} // foreach link
		
					echo '</ol>';
					
					// updating page num
					$page = $page + 10;
					$query = "update {$this->wp_prefix}automatic_articles_keys set page_num = $page  where keyword = '$keyword' and camp_id  = '$camp->camp_id' ";
					$this->db->query ( $query );
					
					 
					
				 
				
				return;
			}
			
			/*
			 * ---* articlebase process camp ---
			 */
			function articlebase_get_post($camp) {
				$keywords = $camp->camp_keywords;
				$keywords = explode ( ",", $keywords );
				
				foreach ( $keywords as $keyword ) {
					if (trim ( $keyword ) != '') {
					
						 
						//update last keyword
						update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
						
						// check if keyword exhausted to skip
						$query = "select * from {$this->wp_prefix}automatic_articles_keys where keyword = '$keyword' and camp_id='$camp->camp_id'";
						$key = $this->db->get_results ( $query );
						$key = $key [0];
						
						
						 
					 		
							// process feed
							echo '<br><b>Getting article for Keyword:</b>' . $keyword;
							
							// get links to fetch and post on the blogs
							$query = "select * from {$this->wp_prefix}automatic_articles_links where keyword = '$keyword' and status =0 and link like '%ezinearticles.com%'";
							$links = $this->db->get_results ( $query );
							
							// when no links available get some links
							if (count ( $links ) == 0) {
								
								$this->article_base_getlinks ( $keyword, $camp );
								// get links to fetch and post on the blogs
								$query = "select * from {$this->wp_prefix}automatic_articles_links where keyword = '$keyword' and status =0 and link like '%ezinearticles.com%'";
								$links = $this->db->get_results ( $query );
							}
							
							// if no links then return
							if (count ( $links ) != 0) {
								
								foreach ( $links as $link ) {
									
									// updating status of the link to posted or 1
									$query = "update {$this->wp_prefix}automatic_articles_links set status = '1' where id = '$link->id'";
									$this->db->query ( $query );
									
									// processing page and getting content
									$url = htmlspecialchars_decode($link->link) ;
									 
									$title = $link->title;
									 
									
									echo '<br>Processing Article :' . urldecode($url);
									 
									 
									//$url=urldecode('http://ezinearticles.com/?Type-2-Diabetes---How-To-Exercise-When-You-Have-Diabetic-Neuropathy&id=7537399');
									//$url = "http%3a%2f%2fezinearticles.com%2f%3fType-2-Diabetes---How-To-Exercise-When-You-Have-Diabetic-Neuropathy%26id%3d7537399";
									
									// http://cc.bingj.com/cache.aspx?d=4653421819332026&mkt=en-US&setlang=en-US&w=TfDBcOSWb2JEIbDPE2QYN_hOUl81H25u
									//$binglink = "http://cc.bingj.com/cache.aspx?d=$d&mkt=en-US&setlang=en-US&w=$w";
									$binglink =  "http://webcache.googleusercontent.com/search?q=cache:".$url;
									
									 
									
									curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
									curl_setopt ( $this->ch, CURLOPT_URL, trim (  ( $binglink ) ) );
									curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://ezinearticles.com' );
									$exec = curl_exec ( $this->ch );
									
									//verify the content returned
									
									
		
									 
									
									if(stristr($exec,'comments')){
										//valid google cache	
									}else{
										echo '<br>Google cache didnot return valid result direct call to ezine ';
										curl_setopt ( $this->ch, CURLOPT_URL, trim (  ( urldecode( $url ) ) ) );
										curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://ezinearticles.com' );
										$exec = curl_exec ( $this->ch );
										
										if(stristr($exec, 'comments')){
											echo '<br>Ezinearticles returned the article successfully ';
										}else{
											if(stristr($exec,'excessive amount')){
												echo '<br>Ezinearticles says there is excessive amount of traffic';
												return false ;
											}else{
												echo '<br>Ezinearticles did not return the article we called';
												return false ;
											}
										}
										
										 
									}
									
								
								   
									// extracting articles
									$arr = explode ( 'article-content">', $exec );
									$lastpart = $arr [1];
									
									unset ( $arr );
									$newarr = explode ( '<div id="article-resource', $lastpart );
									
									$cont = '<div>' . $newarr [0];
									 
									//striping js
									$cont = preg_replace('{<script.*?script>}s', '', $cont);
									$cont = preg_replace('{<div class="mobile-ad-container">.*?</div>}s', '', $cont);
									 
									// get the title <title>Make Money With Google Profit Kits Exposed - Don't Get Ripped Off!</title>
									@preg_match_all ( "{<title>(.*?)</title>}", $exec, $matches, PREG_PATTERN_ORDER );
									@$res = $matches [1];
									@$ttl = $res [0];
									
									if (isset ( $ttl )) {
										$title = $ttl;
									}
									
									// get author name and author link <a href="/?expert=Naina_Jain" rel="author" class="author-name" title="EzineArticles Expert Author Naina Jain"> Naina Jain </a>
									@preg_match_all ( '{<a href="(.*?)" rel="author.*?>(.*?)</a>}', $exec, $matches, PREG_PATTERN_ORDER );
									
									$author_link = 'http://ezinearticles.com/' . $matches [1] [0];
									$author_name = trim ( $matches [2] [0] );
									
									
									
									$ret ['cont'] = $cont;
									$ret ['title'] = $title;
									$ret ['original_title'] = $title;
									$ret ['source_link'] = urldecode($url);
									$ret ['author_name'] = $author_name;
									$ret ['author_link'] = $author_link;
									$ret ['matched_content'] = $cont;
									$this->used_keyword=$link->keyword;
									if( trim($ret['cont']) == '' ) echo ' exec:'.$exec;
									
									
									return $ret;
								} // foreach link
							} // if count(links)
						 
					} // if keyword not ''
				} // foreach keyword
			}
			
			/*
			 * ---* Get Amazon Post ---
			 */
			function amazon_get_post($camp) {
				
				// reading keywords that need to be processed
				$keywords = explode ( ',', $camp->camp_keywords );
				
				foreach ( $keywords as $keyword ) {
					
					if (trim ( $keyword ) != '') {
						$keyword = trim ( $keyword );
						echome ( '<br><b>Processing Keyword:</b>' . $keyword . '<hr>' );
						 
						//update last keyword
						update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
						
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_amazon_links where link_keyword='{$camp->camp_id}_$keyword' and link_status ='0'";
						$res = $this->db->get_results ( $query );
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							$this->amazon_fetch_links ( $keyword, $camp );
							// getting links from the db for that keyword
							
							$res = $this->db->get_results ( $query );
						}
						
						//delete already posted items from other campaigns
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
						
							$t_row = $res[$i];
							$t_link_url=$t_row->link_url;
						
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Amazon Item ('. $t_row->link_title .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_amazon_links where link_id='{$t_row->link_id}'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
							
						}
						
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
							
							// lets process that link
							$ret = $res [$i];
							
							echo '<br>Link:'.$ret->link_url;
				 
							
							$offer_title = $ret->link_title;
							$offer_desc = $ret->link_desc;
							$offer_url = $ret->link_url;
							$offer_price = trim($ret->link_price);
							$offer_img = $ret->link_img;
							
							$temp ['offer_title'] = $offer_title;
							$temp ['product_title'] = $offer_title;
							$temp ['offer_desc'] = $offer_desc;
							$temp ['product_desc'] = $offer_desc;
							$temp ['offer_url'] = $offer_url;
							$temp ['product_link'] = $offer_url;
							$temp ['source_link'] = $offer_url;
							$temp ['offer_price'] = $offer_price;
							$temp ['product_price'] = $offer_price;
							$temp ['offer_img'] = $offer_img;
							$temp ['product_img'] = $offer_img;
							$temp ['price_numeric'] = '00.00';
							$temp ['price_currency'] = '$';
							$temp ['review_iframe'] = '<iframe style="width:100%" class="wp_automatic_amazon_review" src="'.$ret->link_review.'" ></iframe>';
							
							//chart url 
							$enc_url = urldecode($offer_url);
							$enc_url = explode('?', $enc_url);
							$enc_parms =  $enc_url[1];
							$enc_parms_arr = explode('&',$enc_parms);
							
							$asin='';
							$tag = '' ;
							$subscription = '';
							
							foreach($enc_parms_arr as $param){
							
								if(stristr($param, 'creativeASIN')){
									$asin = str_replace('creativeASIN=', '', $param);
								}elseif(stristr($param, 'tag=')){
									$tag = str_replace('tag=', '', $param);
								}elseif( stristr($param, 'SubscriptionId')){
									$subscription = str_replace('SubscriptionId=', '', $param);
								}
							
							}
							
							$temp['product_asin'] = $asin;
							
							$region = $camp->camp_amazon_region;
							
							$chart_url = "http://www.amazon.$region/gp/aws/cart/add.html?AssociateTag=$tag&ASIN.1=$asin&Quantity.1=1&SubscriptionId=$subscription";
								
							
							$temp['chart_url'] = $chart_url;
							//price extraction 
							if(trim($ret->link_price) != ''){
								//good we have a price 
								$price_no_commas = str_replace(',', '', $offer_price);
								preg_match('{\d.*\d}is', ($price_no_commas),$price_matches);
							 
								$temp ['price_numeric'] = $price_matches[0];
								$temp ['price_currency'] =str_replace($price_matches[0], '', $offer_price);  
								
							}

							 
							$this->used_keyword = $ret->link_keyword;
							
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_amazon_links set link_status='1' where link_id=$ret->link_id";
							$this->db->query ( $query );
							
							return $temp;
						} else {
							
							return false;
						}
					} // trim
				} // foreach keyword
			}
			
			/*
			 * ---* Get Amazon links ---
			 */
			function amazon_fetch_links($keyword, $camp) {
				echo "so I should now get some links from Amazon for keyword :" . $keyword;
				
				// ini
				$camp_opt = unserialize ( $camp->camp_options );
				
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general) );
				
				$camp_general=array_map('stripslashes', $camp_general);
				$amazonPublic = get_option ( 'wp_amazonpin_abk', '' );
				$amazonSecret = get_option ( 'wp_amazonpin_apvtk', '' );
				$amazonAid = get_option ( 'wp_amazonpin_aaid', '' );
				
				if (trim ( $amazonPublic ) == '' || trim ( $amazonSecret ) == '' || trim ( $amazonAid ) == '') {
					$this->log ( 'Error', 'Amazon Public Key,Private Key and associate id required visit settings and add them' );
					echo '<br>Amazon Public Key,Private Key and associate id required visit settings and add them';
					return false;
				}
				
				// using clickbank
				$clickkey =   ( $keyword );
				
				// getting start
				$query = "select  * from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp = {$camp->camp_id} ";
				$ret = $this->db->get_results ( $query );
				
				$row = $ret [0];
				$start = $row->amazon_start;
				
				echo '<br>current page is ' . $start;
				// check if the start = -1 this means the keyword is exhausted
				if ($start == '-1' || $start == 11) {
					echo "<br>Keyword $keyword already exhausted and don't have any links to fetch";
					
					//check if it is reactivated or still deactivated
					if($this->is_deactivated($camp->camp_id, $keyword)){
						$start =1;
					}else{
						//still deactivated
						return false;
					}
					 
				}
				
				// amazon
				
				$obj = new wp_automatic_AmazonProductAPI ( trim($amazonPublic), trim($amazonSecret) , trim($amazonAid), $camp->camp_amazon_region );
				
				try {
					
					//additional params
					$additionalParm=array();
					
					//node param
					if (in_array ( 'OPT_AMAZON_NODE', $camp_opt ) & trim ( $camp_general ['cg_am_node'] ) != '') {
						echo '<br>Specific node : ' . $camp_general ['cg_am_node'];
						$additionalParm['BrowseNode'] = $camp_general ['cg_am_node'];
					} 
					
		
					//min and max param
					$max = '';
					$min = '';
					
					if (in_array ( 'OPT_AM_PRICE', $camp_opt )) {
						$min = $camp_general ['cg_am_min'];
						$max = $camp_general ['cg_am_max'];
						
						echo '<br>Price range ' . $min . ' - ' . $max;
					}
					
					//search param
					if (in_array ( 'OPT_AMAZON_PARAM', $camp_opt )) {
						$additionalParm[$camp_general['cg_am_param_type']]=$camp_general['cg_am_param'];
					}
					
					//order param
					if (in_array ( 'OPT_AM_ORDER', $camp_opt )) {
						$additionalParm['Sort']=$camp_general['cg_am_order'];
					}
					
					$result = $obj->getItemByKeyword ( "$clickkey", $start, $camp->camp_amazon_cat, $additionalParm , $min, $max );
					
				} catch ( Exception $e ) {
					$this->log ( 'Amazon Error', $e->getMessage () );
					echo '<br>' . $e->getMessage ();
					return false;
				}
				
		
				
			  
				if ( isset($result->Items->Item) && count ( $result->Items->Item ) != 0) {
		
					
					 
					$pagesNum = $result->Items->TotalPages;
					
					 
					echo '<br>Available Pages:' . $pagesNum;
					
					$camp_cb_category = $camp->camp_cb_category;
				
				 
					echo '<ol>';
					foreach ( $result->Items->Item as $Item ) {
						
						
						
				 		echo '<li>';
						
						// echo "Sales Rank : {$Item->SalesRank}<br>";
						echo "ASIN : {$Item->ASIN} ";
						echo " Link : <a href=\"{$Item->DetailPageURL}\">{$Item->ItemAttributes->Title}</a> <br>";
		
						$desc = '';
						@$desc = $Item->EditorialReviews->EditorialReview->Content;
						
		
						//Features existence 
						if(isset($Item->ItemAttributes->Feature)){
							echo '<br>Features found appending to the description';
							$desc .= implode( '<br>', (array) $Item->ItemAttributes->Feature );
						}
						
						 
						$desc = addslashes ( $desc );
						$title = addslashes ( $Item->ItemAttributes->Title );
						$linkUrl = (string)$Item->DetailPageURL;
						
					 
						 		
							if( $this->is_execluded($camp->camp_id,  $linkUrl) ){
								echo '<-- Execluded';
								continue;
							}
								
							  
							if (  ! $this->is_duplicate($linkUrl) ) {
								
								$price= '';
								
								@$price=$Item->Offers->Offer->OfferListing->Price->FormattedPrice;
								
								if(trim($price) == ''){
									@$price=$Item->ItemAttributes->ListPrice->FormattedPrice;
								}
								
								if(trim($price) == ''){
									@$price = $Item->OfferSummary->LowestNewPrice->FormattedPrice;
								}
								
								if(trim($price) == ''){
									@$price = $Item->OfferSummary->LowestCollectiblePrice->FormattedPrice;
								}
								
								if(trim($price) == ''){
									@$price = $Item->OfferSummary->LowestUsedPrice->FormattedPrice;
								}
								
								$imgurl = '';
								$imgurl=$Item->LargeImage->URL;
								
								if(trim($imgurl) == ''){
									//get it from the sets
									$imgurl=$Item->ImageSets->ImageSet[0]->LargeImage->URL;
								}
								
								//review url 
								$review = $Item->CustomerReviews->IFrameURL;
								
								 
								
								$query = "INSERT INTO {$this->wp_prefix}automatic_amazon_links ( link_url , link_title , link_keyword  , link_status ,link_desc,link_price,link_img,link_review)VALUES ( '$linkUrl', '$title', '{$camp->camp_id}_$keyword', '0','$desc','{$price}','{$imgurl}','{$review}')";
								$this->db->query ( $query );
							} else {
								echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
							}
							
							echo '</li>';
						 
					}
					
					echo '</ol>';
					
		 		} // if count
				
				if ( isset($result->Items->Item )  && count ( $result->Items->Item ) > 0) {
					// increment the start with 1
					$newstart = $start + 1;
					$query = "update {$this->wp_prefix}automatic_keywords set  amazon_start  = '$newstart' where keyword_name='$keyword'  and keyword_camp = {$camp->camp_id} ";
					$this->db->query ( $query );
					
					return true;
				} else {
					// there was no links lets deactivate
					$newstart = '-1';
					$query = "update {$this->wp_prefix}automatic_keywords set amazon_start  = '$newstart' where keyword_name='$keyword'  and keyword_camp = {$camp->camp_id} ";
					$this->db->query ( $query );
					
					//deactivate key
					$this->deactivate_key($camp->camp_id, $keyword);
					
					echo '<br>No more items at amazon to get ';
					
					return false;
				}
			} // end func
			
			/*
			 * ---* Get Clickbank links ---
			 */
			function clickbank_fetch_links($keyword, $camp) {
				echo "<br>so I should now get some links from clickbank ...";
				
				// ini
				$camp_opt = unserialize ( $camp->camp_options );
				$wp_wp_automatic_cbu = get_option('wp_wp_automatic_cbu','');
				
				// using clickbank
				$clickkey = urlencode ( $keyword );
				
				// getting start
				$query = "select clickbank_start from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp='{$camp->camp_id}' ";
				$ret = $this->db->get_results ( $query );
				
				$row = $ret [0];
				$start = $row->clickbank_start;
				// check if the start = -1 this means the keyword is exhausted
				if ($start == '-1') {
					
					//check if it is reactivated or still deactivated
					if($this->is_deactivated($camp->camp_id, $keyword)){
						$start =1;
					}else{
						//still deactivated
						return false;
					}
					
				}
				
				$sortby = $camp->camp_search_order;
				$camp_cb_category = $camp->camp_cb_category;
				$cbname = trim(get_option ( 'wp_wp_automatic_cbu', '' ));
				$cbpass =trim( urlencode( get_option ( 'wp_wp_automatic_cbp', '' ) ));
				
				if (trim ( $cbname ) == '' || trim ( $cbpass ) == '') {
					echo '<br>Clickbank username and password required visit settings and add them ';
					exit ();
				}
				
				$clickbank = "https://$cbname.accounts.clickbank.com/account/mkplSearchResult.htm?includeKeywords=$clickkey&resultsPerPage=50&firstResult=$start&sortField=$sortby&$camp_cb_category";
				echo "<br>Clickbank Remote Link:$clickbank....";
				
				// login to clickbank
				$url = "https://$cbname.accounts.clickbank.com/account/login";
				$postst = "nick=$cbname&pass=$cbpass&login=Log+In&rememberMe=true&j_username=$cbname&j_password=$cbpass";
				// Post
				
				$x = 'error';
				while ( trim ( $x ) != '' ) {
					
					curl_setopt ( $this->ch, CURLOPT_POST, 1 );
					
					//curl ini
					curl_setopt($this->ch, CURLOPT_HEADER,1);
					
					curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
					curl_setopt ( $this->ch, CURLOPT_POSTFIELDS, $postst );
					$contexec = curl_exec ( $this->ch );
					echo $x = curl_error ( $this->ch );
					
					curl_setopt($this->ch, CURLOPT_HEADER,0);
					
					
				}
				
		 	
				if (stristr ( $contexec, 'mainMenu.htm' )) {
					echo '<br>Clickbank Login success';
				} else {
					echo '<br>Clickbank Login fail';
					return false;
				}
				
				// Get
				$x = 'error';
				 
				while ( trim ( $x ) != '' ) {
					$url = $clickbank;
					curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
					$cont = curl_exec ( $this->ch );
					echo $x = curl_error ( $this->ch );
					
				}
				
				 
				preg_match_all ( '/details">.*?(http:\/\/zzzzz\..*?net).*?>(.*?)<\/a>/s', $cont, $matches, PREG_PATTERN_ORDER );
				
		 		$links = $matches [1];
		 		$titles = $matches [2];
			 
				
				echo '<br>links found:' . count ( $links );
				
				
				// descreptions <div class="description">The #1 Ballet Product. Fundamental Dance Skills. Gaining Popularity Very Quickly. We Cloak Your Affiliate Link. Http://www.ballet-bible.com/affiliates.php</div>
				preg_match_all ( '{description">(.*?)</div>}', $cont, $matches, PREG_PATTERN_ORDER );
				$descs = $matches [1];
				
				 
				echo '<ol>';
				for($i = 0; $i < count ( $links ); $i ++) {
					$title = addslashes ( $titles [$i] );
					
					echo '<li>' . $links [$i] . '<br>' . $titles [$i] . '</li>';
					
					 		
						// check if exists
				 
							
							$link_url = str_replace('zzzzz', $wp_wp_automatic_cbu,$links[$i]) ;
							
							if( $this->is_execluded($camp->camp_id, $link_url) ){
								echo '<-- Execluded';
								continue;
							}
								
							if ( ! $this->is_duplicate($link_url) )  {
								$desc = addslashes ( $descs [$i] );
								$query = "INSERT INTO {$this->wp_prefix}automatic_clickbank_links ( link_url , link_title , link_keyword  , link_status , link_desc )VALUES ( '$links[$i]', '$title', '$keyword', '0' ,'$desc')";
								$this->db->query ( $query );
								
							} else {
								echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
							}
							
						 
					 
				}
				echo '</ol>';
				
				if (count ( $links ) > 0) {
					// increment the start with 50
					$newstart = $start + 50;
					$query = "update {$this->wp_prefix}automatic_keywords set  clickbank_start  = '$newstart' where keyword_name='$keyword'";
					$this->db->query ( $query );
					return true;
				} else {
					// there was no links lets deactivate
					$newstart = '-1';
					$query = "update {$this->wp_prefix}automatic_keywords set clickbank_start  = '$newstart' where keyword_name='$keyword'";
					$this->db->query ( $query );
					
					$this->deactivate_key($camp->camp_id, $keyword);
					
					return false;
				}
			}
			function clickbank_get_post($camp) {
				$keywords = explode ( ',', $camp->camp_keywords );
				
				foreach ( $keywords as $keyword ) {
					
					if (trim ( $keyword ) != '') {
						 
						//update last keyword
						update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
						
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_clickbank_links where link_keyword='$keyword' and link_status ='0'";
						$res = $this->db->get_results ( $query );
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							$this->clickbank_fetch_links ( $keyword, $camp );
							// getting links from the db for that keyword
							$query = "select * from {$this->wp_prefix}automatic_clickbank_links where link_keyword='$keyword' and link_status ='0'";
							$res = $this->db->get_results ( $query );
						}
						
						//duplicate but posted
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
						
							$t_row = $res[$i];
							$t_link_url=$t_row->link_url;
						
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Clickbank Item ('. $t_row->link_title .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_clickbank_links where link_id='{$t_row->link_id}'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
								
						}
						
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
							// ini
							$cbname = get_option ( 'wp_wp_automatic_cbu', '' );
							
							if (trim ( $cbname ) == '') {
								$message = '<a href="http://clickbank.net">Click Bank</a> account needed visit settings and add the username ';
								echo "<br>$message";
								$this->log ( 'Error', $message );
							}
							
							// lets process that link
							$ret = $res [$i];
							
							$offer_title = $ret->link_title;
							$offer_url = $ret->link_url;
							$offer_url = str_replace ( 'zzzzz', $cbname, $offer_url );
							$offer_desc = $ret->link_desc;
							
							// lets call the downloader for offer_title and offer_real_link
							$downloader_link = get_home_url () . '/wp-content/plugins/wp-automatic/downloader.php';
							$downloader_link = site_url('?wp_automatic=download');
							
							curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
							curl_setopt ( $this->ch, CURLOPT_URL, trim ( $downloader_link . '&link=' . str_replace ( 'http:', 'httpz:', $offer_url ) ) );
							$exec = curl_exec ( $this->ch );
						
							
							$json = json_decode ( $exec );
							// print_r($json);
							@$original_link = $json->link;
							@$original_title = $json->title;
							
							$original_link = str_replace ( "?hop=$cbname", '', $original_link );
							
							if (trim ( $original_link ) == '' || trim ( $original_title ) == '') {
								echo '<br>could not extract original';
								
								$original_title = $offer_title;
								
								if (trim ( $original_link ) == '') {
									$original_link = $offer_url;
								}
							} else {
								
								$offer_title = $original_title;
							}
							
							// img
							$tempo = str_replace ( "http://$cbname.", '', $original_link );
							$tempo = str_replace ( 'http://', '', $tempo );
							$tempo = str_replace ( "?hop=$cbname", '', $tempo );
							
							$tempo = urlencode ( trim ( $tempo ) );
							$wp_amazonpin_tw = get_option ( 'wp_amazonpin_tw', 400 );
							
							$img = '<img class="product_thumb" style="width:' . $wp_amazonpin_tw . 'px" src="http://pagepeeker.com/t/l/' . strtolower ( $tempo ) . '" />';
							
							$temp = array ();
							$temp ['title'] = $offer_title;
							$temp ['original_title'] = $offer_title;
							$temp ['offer_link'] = $offer_url;
							$temp ['source_link'] = $offer_url;
							$temp ['product_link'] = $offer_url;
							$temp ['original_link'] = $original_link;
							$temp ['product_original_link'] = $original_link;
							$temp ['offer_desc'] = $offer_desc;
							$temp ['product_desc'] = $offer_desc;
							$temp ['img'] = $img;
							$temp ['product_img'] = $img;
							$this->used_keyword = $ret->link_keyword ;
							
							// update link status to used
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_clickbank_links set link_status='1' where link_id=$ret->link_id";
							$this->db->query ( $query );
							
							return $temp;
						} else {
							
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
			} // end funs
			
			/*
			 * ---* feed process camp ---
			 */
			function feeds_get_post($camp) {
				
				// feeds
				$feeds = $camp->feeds;
				$feeds = explode ( "\n", $feeds );
				
				$msg = "Processing " . count ( $feeds ) . " Feeds for campaign " . get_the_title ( $camp->camp_id );
				echo '<br>' . $msg;
				
				if (count ( $feeds ) > 0) {
					$this->log ( 'Process Feeds', $msg );
				}
				
				foreach ( $feeds as $feed ) {
					if (trim ( $feed ) != '') {
						// process feed
						echo '<b><br><br>Processing Feed:</b>' . $feed;
						
						update_post_meta($camp->camp_id, 'last_feed', trim($feed));
						
						$cont = $this->feed_process_link ( $feed, $camp );
					 
						
						if (trim ( $cont ['cont'] ) != '') {
							return $cont;
						}
					}
				}
				
				return false;
			}
			
			/*
			 * ---* processing feed link ---
			 */
			function feed_process_link($feed, $camp) {
				
				
				//ini
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general=unserialize( base64_decode( $camp->camp_general));
				$camp_general=array_map('stripslashes', $camp_general);
				$camp_opt = unserialize ( $camp->camp_options );
				
				
				// check last time adition
				$feed = trim ( $feed );
				$myfeed = addslashes ( $feed );
				$query = "select * from {$this->wp_prefix}automatic_feeds_list where feed='$myfeed' and camp_id = '$camp->camp_id' limit 1";
				
				$feeds = $this->db->get_results ( $query );
				$feed_o = $feeds [0];
				
				$this->log ( 'Process Feed', '<a href="' . $feed . '">' . $feed . '</a>' );
				
				
				// force feed
				if(! function_exists('wp_automatic_force_feed')){
					
					add_action('wp_feed_options', 'wp_automatic_force_feed', 10, 1);
					function wp_automatic_force_feed($feed) {
						$feed->force_feed(true);
					}	
					
				}
				
				//wrong feed length 
			    if( ! function_exists('wp_automatic_setup_curl_options') ){
					//feed timeout
					
					
					function wp_automatic_setup_curl_options( $curl ) {
						if ( is_resource( $curl ) ) {
							curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
						}
					}
				
				}
				
				include_once (ABSPATH . WPINC . '/feed.php');
				
				// Get a SimplePie feed object from the specified feed source.
		
				//add action to fix the problem of curl transfer closed without complete data
				add_action( 'http_api_curl', 'wp_automatic_setup_curl_options' );
				
				if( ! function_exists('wp_automatic_wp_feed_options') ){
					function wp_automatic_wp_feed_options($args){
						
						$args->set_useragent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/41.0.2272.76 ');
						
					}
					
				 	add_action('wp_feed_options','wp_automatic_wp_feed_options');
				}
			 	
				
				$rss = fetch_feed ( $feed );
				if (! is_wp_error ( $rss )){ // Checks that the object is created correctly
				                              // Figure out how many total items there are, but limit it to 5.
				$maxitems = $rss->get_item_quantity ();
					
				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items ( 0, $maxitems );
					
		
				//remove the expect again as it makes jetpack publicize to not work
				remove_action('http_api_curl', 'wp_automatic_setup_curl_options');
		 
				 
			}else{
				 $error_string = $rss->get_error_message();
				   echo '<br><strong>Error:</strong>' . $error_string ;
			}
			
			
			
				
				if (!isset($maxitems) || $maxitems == 0)
					return false;
				else
					
				//reverse order if exists
				if(in_array('OPT_FEED_REVERSE', $camp_opt)){
					echo '<br>Reversing order';
					$rss_items = array_reverse($rss_items);
				}
				
				// Loop through each feed item and display each item as a hyperlink.
				$i = 0;
				foreach ( $rss_items as $item ) :
					
				
				$url = esc_url ( $item->get_permalink () );
				
					if(trim($url) == ''){
						echo '<br>item have no url skipping';
						continue;
					} 	
				
					
					echo '<br>Link:'.$url;
					$wpdate= $item->get_date ( "Y-m-d H:i:s");
					echo '<br> Published:'.$wpdate;
					
					$cats=  ($item->get_categories());
					
		 
					$cat_str = '';
					
					if(isset($cats)){
						foreach($cats as $cat ){
						
							if(trim($cat_str) != '') $cat_str.= ',';
						
							$cat_str.= $cat->term;
						
						}	
					}
					
					//fix empty titles
					if(trim($item->get_title ()) == ''){
						echo '<--Empty title skipping';
						continue;
					}
					
					  
					//check if execluded link due to exact match does not exists 
					if( $this->is_execluded($camp->camp_id, $url)){
						echo '<-- Execluded link';
						continue;
					}
					
					//check if older than minimum date
					if($this->is_link_old($camp->camp_id,  strtotime($wpdate) )){
						echo '<--old post execluding...';
						continue;
					}
					
					//check media images
					unset($media_image_url);
					$enclosure = $item->get_enclosure();
					
				
					
					$enclosure_link = $enclosure->link;
					
					if(isset($enclosure->type)  && stristr($enclosure->type, 'image') && isset($enclosure->link) ){
					
						$media_image_url = $enclosure->link;
							
					}
					
					
				
					if (! $this->is_duplicate($url) ) {
						
						echo '<-- new link';
						
						$title = esc_html ( $item->get_title () );
						
						//check if there is a post published with the same title
						if(in_array('OPT_FEED_TITLE_SKIP',$camp_opt)  ){
							 if($this->is_title_duplicate($title,$camp->camp_post_type)){
							 	echo '<-- duplicate title skipping..';
							 	continue;
							 }
						}
						
						
						$i ++;
						// posting content to emails
						$date = $item->get_date ( 'j F Y | g:i a' );
						$wpdate= $item->get_date ( "Y-m-d H:i:s");
						 
						
						
						 
						
						$html = $item->get_content ();
						
					 
					 
						if(trim($html) == ''){
							if( trim($title) != '' ) $html =$title;
						}
						
						
						$md5 = md5 ( $url );
						// loging the feeds
						$query = "insert into {$this->wp_prefix}automatic_feeds_links (link,camp_id) values ('$md5','$camp->camp_id')";
						$this->db->query ( $query );
					
						
						//if not image escape it 
						$res ['cont'] = $html;
						$res ['original_content']=$html;
						$res ['title'] = $title;
						$res ['original_title'] = $title;
						$res ['matched_content'] = $html;
						$res ['source_link'] = $url;
						$res ['publish_date'] = $date;
						$res ['wpdate']=$wpdate;
						$res ['cats'] = $cat_str;
						$res ['tags'] = '';
						$res ['enclosure_link'] = $enclosure_link;
						
						// check now if full feeds is needed
						$camp_opt = unserialize ( $camp->camp_options );
						
						if (in_array ( 'OPT_FULL_FEED', $camp_opt )) {
							
							// READABILITY
							require_once 'inc/readability/Readability.php';
							
							// get content
							// curl get
							$x = 'error';
							
							curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
							curl_setopt ( $this->ch, CURLOPT_URL, trim ( html_entity_decode($url)  ) );
							while ( trim ( $x ) != '' ) {
								$html = curl_exec ( $this->ch );
								$x = curl_error ( $this->ch );
							}
							
							$url = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
							
							//strip js from html 
							$html = preg_replace('{<script.*?script>}s', '', $html);
						 	
							$readability = new Readability ( $html, $url );
							
							$readability->debug = false;
							
							$result = $readability->init ();
							
							if ($result) {
								$title = $readability->getTitle ()->textContent;
								$content = $readability->getContent ()->innerHTML;
								 
								//cleaning redability for better memory
								unset($readability);
								unset($result);
							
								
								//check existence of title words in the content 
								$title_arr=explode(' ', $title);
								
								$valid='';
								$nocompare=array('is','Is','the','The','this','This','and','And','or','Or','in','In','if','IF','a','A','|','-');
								foreach($title_arr as $title_word){
									
									
									
									if(! in_array($title_word, $nocompare) &  preg_match('/\b'. preg_quote(trim($title_word),'/') .'\b/ui', $content)){
										echo '<br>word '.$title_word .' exists';
										//echo $content;
										$valid='yeah';
										break;
									}else{
										echo '<br>word '.$title_word .' dont exists';
									}
								}
								
								if(trim($valid) != ''){
								
									$res ['cont'] = $content;
									$res ['matched_content'] = $content;
									//$res ['original_title'] = $title;
									//$res ['title'] = $title;
									$res ['og_img'] = '';
									
									
									//let's find og:image may be the content we got has no image
									preg_match('{<meta[^<]*?property=["|\']og:image["|\'][^<]*?>}s', $html,$plain_og_matches);
									 
									if(stristr($plain_og_matches[0], 'og:image')){
										preg_match('{content=["|\'](.*?)["|\']}s', $plain_og_matches[0],$matches);
										$og_img = $matches[1];

										if(trim($og_img) !=''){
											$res ['og_img']=$og_img ;
										}
										
									}
 									
									
									
									 		
									
								}
								
							} else {
								echo '<br>Looks like we couldn\'t find the full content. :( returning summary';
							}
						}elseif(in_array ( 'OPT_FEED_CUSTOM', $camp_opt )){
							
							echo '<br>Extracting content from original post for ';
							
							$cg_selector=$camp_general['cg_custom_selector'];
							$cg_selecotr_data=$camp_general['cg_feed_custom_id'];
							
							echo $cg_selector . ' = "'.$cg_selecotr_data.'"';
							
							//dom class
							require_once 'inc/simple_html_dom.php';
							
							
							//get content
							$x='error'; 
							curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
							curl_setopt ( $this->ch, CURLOPT_URL, trim ( html_entity_decode(  $url   ) ));
							while ( trim ( $x ) != '' ) {
								$original_cont = $this->curl_exec_follow ( $this->ch );
								$x = curl_error ( $this->ch );
							}
		  
							
							$url = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
							  
							$original_html = str_get_html($original_cont);
		
							if(method_exists($original_html, 'find')){
								
								if($cg_selector != 'xpath'){
									$ret = $original_html->find('*['.$cg_selector.'='.trim($cg_selecotr_data).']');
								}else{
									$ret = $original_html->find( trim($cg_selecotr_data) );
								}
								
								$extract='';
								
								foreach ($ret as $itm ) {
									
									if(in_array('OPT_SELECTOR_INNER', $camp_opt)){
										$extract = $extract . $itm->innertext ;
									}else{
										$extract = $extract . $itm->outertext ;
									}
									 
									if(in_array('OPT_SELECTOR_SINGLE', $camp_opt)){
										break;
									}
									
								}
								
								 
								
								if(trim($extract) == ''){
									echo '<br>Nothing found to extract for this rule' ;
								}else{
									echo '<br>Rule one extracted ' . strlen($extract) .' charchters ';
								}
								
								 
								
								//echo ' Extract:'.$extract;
								
								//echo ' Encoding:'. mb_detect_encoding($extract);
								
								
								 
								
								//good we found the first rule let's append rule 2
								$cg_selector2=$camp_general['cg_custom_selector2'];
								$cg_selecotr_data2=$camp_general['cg_feed_custom_id2'];
								
								if(trim($cg_selecotr_data2) != ''){
									
									echo '<br>Rule 2:'. $cg_selector2 . ' = "'.$cg_selecotr_data2.'"';
									 
									if($cg_selector2 != 'xpath'){
										$ret2 = $original_html->find('*['.$cg_selector2.'='.trim($cg_selecotr_data2).']');
									}else{
										$ret2 = $original_html->find( trim($cg_selecotr_data2) );
									}
									
									
									$extract2='';
									foreach ($ret2 as $itm2 ) {
										
										if(in_array('OPT_SELECTOR_INNER2', $camp_opt)){
											$extract2 = $extract2 . $itm2->innertext ;
										}else{
											$extract2 = $extract2 . $itm2->outertext ;
										}
										
										if(in_array('OPT_SELECTOR_SINGLE2', $camp_opt)){
											break;
										}
										
									}
									
									if(trim($extract2 == '')){
											echo '<br>Nothing found to extract for this rule'; 
									}else{
										echo '<br>Rule two extracted ' . strlen($extract2) .' charchters ';
										$extract = $extract . $extract2;
									}
									
									
								}
								
								//good we found the first rule let's append rule 2
								$cg_selector3=$camp_general['cg_custom_selector3'];
								$cg_selecotr_data3=$camp_general['cg_feed_custom_id3'];
								
								if(trim($cg_selecotr_data3) != ''){
										
									echo '<br>Rule 3:'. $cg_selector3 . ' = "'.$cg_selecotr_data3.'"';
										
									if($cg_selector3 != 'xpath'){
										$ret3 = $original_html->find('*['.$cg_selector3.'='.trim($cg_selecotr_data3).']');
									}else{
										$ret3 = $original_html->find( trim($cg_selecotr_data3) );
									}
									
										
									$extract3='';
									foreach ($ret3 as $itm3 ) {
										
										if(in_array('OPT_SELECTOR_INNER3', $camp_opt)){
											$extract3 = $extract3 . $itm3->innertext ;
										}else{
											$extract3 = $extract3 . $itm3->outertext ;
										}
										
										
										
										if(in_array('OPT_SELECTOR_SINGLE3', $camp_opt)){
											break;
										}
										
									}
										
									if(trim($extract3 == '')){
										echo '<br>Nothing found to extract for this rule';
									}else{
										echo '<br>Rule two extracted ' . strlen($extract3) .' charchters ';
										$extract = $extract . $extract3;
									}
										
										
								}
									
								if(trim($extract) != ''){						
									$res ['cont'] = $extract;
									$res ['matched_content'] = $extract;
								}
								
							}else{
								echo '<br>could not parse the content returning summary';
							}
							
							
						}elseif(in_array ( 'OPT_FEED_CUSTOM_R', $camp_opt )){
							
							echo '<br>Extracting content using REGEX ';
							$cg_feed_custom_regex = html_entity_decode($camp_general['cg_feed_custom_regex']);
							$cg_feed_custom_regex2= html_entity_decode($camp_general['cg_feed_custom_regex2']);
							
							if(trim($cg_feed_custom_regex) != '' ){
								
								$finalmatch = '';
								$finalmatch2 = '';
								
								//we have a regex
								echo '<br>Regex1:'. htmlspecialchars($cg_feed_custom_regex);
								
								//get content
								$x='error';
								curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
								curl_setopt ( $this->ch, CURLOPT_URL, trim ( html_entity_decode( $url) ) );
								while ( trim ( $x ) != '' ) {
									$original_cont = curl_exec ( $this->ch );
									$x = curl_error ( $this->ch );
								}
								
								//extracting
								if(trim($original_cont) !=''){
									preg_match_all('{'.$cg_feed_custom_regex.'}is', $original_cont,$matchregex);
								 
									
									 
									
									for( $i=1 ; $i < count($matchregex);$i++ ){
										
										foreach($matchregex[$i] as $newmatch){
											
											if(trim($newmatch) !=''){
												if(trim($finalmatch) !=''){
													$finalmatch.='<br>'.$newmatch;
												}else{
													$finalmatch.=$newmatch;
												}	
											}
											
										}
									}
									
									 
									if(trim($cg_feed_custom_regex2) != ''){
										echo '<br>Extracting cotnent for regex2:'.htmlspecialchars($cg_feed_custom_regex2);
										
										if(in_array('OPT_REGEX_TWO', $camp_opt)){
											preg_match_all('{'.$cg_feed_custom_regex2.'}is', $finalmatch,$matchregex2);
										}else{
											preg_match_all('{'.$cg_feed_custom_regex2.'}is', $original_cont,$matchregex2);
										}
										
									
										 
										
										for( $i=1 ; $i < count($matchregex2);$i++ ){
										 
											foreach($matchregex2[$i] as $newmatch){
												if(trim($newmatch) !=''){
													if(trim($finalmatch2) !=''){
														$finalmatch2.='<br>'.$newmatch;
													}else{
														$finalmatch2.=$newmatch;
													}	
												}
											}
												
										}
									 
										
										if(in_array('OPT_REGEX_TWO', $camp_opt)){
											$finalmatch =$finalmatch2;
										
										}else{
											$finalmatch.=$finalmatch2;
										}
										
										
									}
									
									 
									
								}
								
								if(trim($finalmatch) != ''){
									//overwirte
									echo '<br>'.  strlen($finalmatch) . ' chars extracted using REGEX';
									$res ['cont'] = $finalmatch;
									$res ['matched_content'] = $finalmatch;
								 
									 
								}else{
									echo '<br>Nothing extracted using REGEX using summary instead..';
								}
								
								
							}
							 
						}
						
						//stripping content using id or class from $res[cont]
						if(in_array('OPT_STRIP_CSS', $camp_opt)){
							
							echo '<br>Striping content using ';
							
							$cg_selector=$camp_general['cg_custom_strip_selector'];
							$cg_selecotr_data=$camp_general['cg_feed_custom_strip_id'];
								
							echo $cg_selector . ' = "'.$cg_selecotr_data.'"';
								
							//dom class
							require_once 'inc/simple_html_dom.php';
							
							$original_html = str_get_html($res['cont']);
							
							if(method_exists($original_html, 'find')){
							
								$ret = $original_html->find('*['.$cg_selector.'='.trim($cg_selecotr_data).']');
							
								foreach ($ret as $itm ) {
									  $itm->outertext = '' ;
								}
								
								//rule2
								$cg_selector=$camp_general['cg_custom_strip_selector2'];
								$cg_selecotr_data=$camp_general['cg_feed_custom_strip_id2'];
								
								if(trim($cg_selector) != '' && trim($cg_selecotr_data) != ''){
									
									echo '<br>Striping content using '.$cg_selector .'="'.$cg_selecotr_data.'"';
									
									$ret = $original_html->find('*['.$cg_selector.'='.trim($cg_selecotr_data).']');
										
									foreach ($ret as $itm ) {
										$itm->outertext = '' ;
									}
									
								}
								
								//rule3
								$cg_selector=$camp_general['cg_custom_strip_selector3'];
								$cg_selecotr_data=$camp_general['cg_feed_custom_strip_id3'];
								
								if(trim($cg_selector) != '' && trim($cg_selecotr_data) != ''){
										
									echo '<br>Striping content using '.$cg_selector .'="'.$cg_selecotr_data.'"';
										
									$ret = $original_html->find('*['.$cg_selector.'='.trim($cg_selecotr_data).']');
								
									foreach ($ret as $itm ) {
										$itm->outertext = '' ;
									}
										
								}
								
								//overwirte
								$res ['cont'] = $original_html;
								$res ['matched_content'] = $original_html;
							 
							}else{
								echo '<br>Can not parse final html to strip by id/class';
							}
							
						}
						
						//stripping content of $res[cont]
						if(in_array('OPT_STRIP_R', $camp_opt)){
							$current_content =$res ['matched_content']  ;
							$current_title=$res['title'];
							$cg_post_strip = html_entity_decode($camp_general['cg_post_strip']);
							
							$cg_post_strip=explode("\n", $cg_post_strip);
							$cg_post_strip=array_filter($cg_post_strip);
							
							foreach($cg_post_strip as $strip_pattern){
								if(trim($strip_pattern) != ''){
									 
									//$strip_pattern ='<img[^>]+\\>';
										
									echo '<br>Stripping:'.htmlentities($strip_pattern);
									$current_content= preg_replace('{'.trim($strip_pattern).'}is', '', $current_content);
									  
									$current_title= preg_replace('{'.trim($strip_pattern).'}is', '', $current_title);
								    
								}
							}
							
						 
							
							if(trim($current_content) !=''){
								$res ['matched_content'] =$current_content ;
								$res ['cont'] =$current_content ;
							}
							
							if(trim($current_title) !=''){
								$res ['matched_title'] =$current_title ;
								$res ['original_title'] =$current_title ;
								$res ['title'] =$current_title ;
								
							}
							
						}
						
						
		
					 
						//entity decode if set
						if(in_array('OPT_FEED_ENTITIES', $camp_opt)){
							echo '<br>Decoding html entities';
							
							//php 5.3 and lower convert &nbsp; to invalid charchters that broke everything
							
							$res ['original_title'] = str_replace('&nbsp;', ' ', $res ['original_title']);
							$res ['matched_content'] = str_replace('&nbsp;', ' ', $res ['matched_content']);
							
							$res ['original_title'] = html_entity_decode($res ['original_title']);
							$res ['title'] =$res ['original_title'] ;
							
							
							$res ['matched_content'] = html_entity_decode($res ['matched_content']);
							$res ['cont'] = $res ['matched_content'];
						}
						
						
						
						
						//extract tags from original source
						if(in_array('OPT_ORIGINAL_TAGS', $camp_opt)){
							
							echo '<br>Extracting original post tags ';
							
							$cg_selector_tag=$camp_general['cg_custom_selector_tag'];
							$cg_selecotr_data_tag=$camp_general['cg_feed_custom_id_tag'];
		
							echo ' for '.$cg_selector_tag . ' = '.$cg_selecotr_data_tag;
							
							if(in_array('OPT_FULL_FEED', $camp_opt)){	
								
								$original_cont = $html ;
								
							}elseif(in_array('OPT_FEED_CUSTOM', $camp_opt)) {
		
								//valid original cont
								
							}elseif( in_array('OPT_FEED_CUSTOM_R', $camp_opt)){
		
								//valid original cont
								
							}else{
								
								//get content
								 
								curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
								curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
								$original_cont = $this->curl_exec_follow ( $this->ch );
							
							
							}
							
		
							 
							//dom class
							require_once 'inc/simple_html_dom.php';
							 
							$original_html_tag = str_get_html($original_cont);
							
							unset($ret);
							if(method_exists($original_html_tag, 'find')){
							
								if($cg_selector_tag != 'xpath'){
									$ret = $original_html_tag->find('*['.$cg_selector_tag.'='.trim($cg_selecotr_data_tag).']');
								}else{
									$ret = $original_html_tag->find( trim($cg_selecotr_data_tag) );
								}
							
								$extract='';
							
								foreach ($ret as $itm ) {
										
									if(in_array('OPT_SELECTOR_INNER_TAG', $camp_opt)){
										$extract = $extract . $itm->innertext ;
									}else{
										$extract = $extract . $itm->outertext ;
									}
							
									if(in_array('OPT_SELECTOR_SINGLE_TAG', $camp_opt)){
										break;
									}
										
								}
							
							
							
								if(trim($extract) == ''){
									echo '<br>Nothing found to extract for this tag rule';
								}else{
									echo '<br>Tag Rule extracted ' . strlen($extract) .' charchters ';
									
									if(stristr($extract, '<a')){
										preg_match_all('{<a .*?>(.*?)<}', $extract,$tags_matches);
										
										$tags_founds = $tags_matches[1];
										$tags_str = implode(',', $tags_founds);
										
										echo ' found tags:'.$tags_str;
										$res['tags'] =$tags_str;
										
										 
									}
									
								}
							
								 
							}
							 
						
						unset($original_html_tag);
							
						}//extract tags
						
						//extract author from original source
						if(in_array('OPT_ORIGINAL_AUTHOR', $camp_opt)){
								
							echo '<br>Extracting original post author ';
								
							$cg_selector_author=$camp_general['cg_custom_selector_author'];
							$cg_selecotr_data_author=$camp_general['cg_feed_custom_id_author'];
						
							echo ' for '.$cg_selector_author . ' = '.$cg_selecotr_data_author;
								
							if(in_array('OPT_FULL_FEED', $camp_opt)){
						
								$original_cont = $html ;
						
							}elseif(in_array('OPT_FEED_CUSTOM', $camp_opt)) {
						
								//valid original cont
						
							}elseif( in_array('OPT_FEED_CUSTOM_R', $camp_opt)){
						
								//valid original cont
						
							}else{
						
								//get content
									
								curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
								curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
								$original_cont = $this->curl_exec_follow ( $this->ch );
									
									
							}
								
						
						
							//dom class
							require_once 'inc/simple_html_dom.php';
						
							$original_html_author = str_get_html($original_cont);
								
							unset($ret);
							if(method_exists($original_html_author, 'find')){
									
								if($cg_selector_author != 'xpath'){
									$ret = $original_html_author->find('*['.$cg_selector_author.'='.trim($cg_selecotr_data_author).']');
								}else{
									$ret = $original_html_author->find( trim($cg_selecotr_data_author) );
								}
									
								$extract='';
									
								foreach ($ret as $itm ) {
						
									if(in_array('OPT_SELECTOR_INNER_AUTHOR', $camp_opt)){
										$extract = $extract . $itm->innertext ;
									}else{
										$extract = $extract . $itm->outertext ;
									}
										
									if(in_array('OPT_SELECTOR_SINGLE_AUTHOR', $camp_opt)){
										break;
									}
						
								}
									
									
									
								if(trim($extract) == ''){
									echo '<br>Nothing found to extract for this author rule';
								}else{
									echo '<br>author Rule extracted ' . strlen($extract) .' charchters ';
										
									if(stristr($extract, '<a')){
										preg_match_all('{<a .*?>(.*?)<}', $extract,$author_matches);
						
										$author_founds = $author_matches[1];
										$author_str = $author_founds[0];
						
										echo ' found author:'.$author_str;
										$res['author'] =$author_str;
						
											
									}
										
								}
									
									
							}
						
						
						}//extract author
						
						
						if(  isset($media_image_url)    &&    ! stristr($res['cont'], '<img') ){
							echo '<br>enclosure image:'.$media_image_url;
							$res['cont'] = '<img src="'.$media_image_url.'" /><br>' . $res['cont'];
							
						} 
						
						
						//og:image check
						if( in_array('OPT_FEEDS_OG_IMG', $camp_opt)){
							
							if(in_array('OPT_FULL_FEED', $camp_opt)){
							
								$original_cont = $html ;
							
							}elseif(in_array('OPT_FEED_CUSTOM', $camp_opt)) {
							
								//valid original cont
							
							}elseif( in_array('OPT_FEED_CUSTOM_R', $camp_opt)){
							
								//valid original cont
							
							}else{
							
								//get content
									
								curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
								curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
								$original_cont = $this->curl_exec_follow ( $this->ch );
									
									
							}
							
							//getting the og:image
							//let's find og:image  

							// if no http
							$original_cont = str_replace('content="//', 'content="http://', $original_cont );

							echo '<br>Extracting og:image :';
							
							//let's find og:image may be the content we got has no image
							preg_match('{<meta[^<]*?property=["|\']og:image["|\'][^<]*?>}s', $original_cont,$plain_og_matches);
							
							if(stristr($plain_og_matches[0], 'og:image')){
								preg_match('{content=["|\'](.*?)["|\']}s', $plain_og_matches[0],$matches);
								$og_img = $matches[1];
							
								if(trim($og_img) !=''){
									$res ['og_img']=$og_img ;
									
									echo $og_img;
									
								}
							
							}
								 
							
						}
						 
						//check if image or not 
						if( in_array ( 'OPT_MUST_IMAGE', $camp_opt ) &&   ! stristr($res['cont'], '<img')     ) {
							echo '<br>Post contains no images skipping it ...';
							 
						}else{
							
						
							
							//fix images 
							$pars=parse_url($url);
							$host = $pars['host'];
		
							preg_match_all('{<img.*?src[\s]*=[\s]*["|\'](.*?)["|\'].*?>}is', $res['cont'] , $matches);
							
							$img_srcs =  ($matches[1]);
							
						 
							
							
							foreach ($img_srcs as $img_src){
							
								$original_src = $img_src;
								
								// ../ remove
								if(stristr($img_src, '../')){
									$img_src = str_replace('../', '', $img_src);
								}
							
								if(stristr($img_src, 'http:') || stristr($img_src, 'www.') || stristr($img_src, 'https:')  ){
									//valid image
								}else{
									//not valid image i.e relative path starting with a / or not or //
									$img_src = trim($img_src);
							
									if(preg_match('{^//}', $img_src)){
							
										$img_src = 'http:'.$img_src;
							
									}elseif( preg_match('{^/}', $img_src) ){
										$img_src = 'http://'.$host.$img_src;
									}else{
										$img_src = 'http://'.$host.'/'.$img_src;
									}
							
										
									$res['cont'] = preg_replace( '{["|\'][\s]*'.preg_quote($original_src,'{').'[\s]*["|\']}s', '"'.$img_src.'"', $res['cont']);
							
								}
							
							}
							
							
							//Fix relative links
							$res['cont'] = str_replace('href="../', 'href="http://'.$host.'/', $res['cont']);
							$res['cont'] = preg_replace('{href="/(\w)}', 'href="http://'.$host.'/$1', $res['cont']);
							
							return $res;
						}
						
						
						
						
					}else{
						
						//duplicated link
						echo '<-- duplicate in post <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						
					}
				endforeach
				;
				
				$msg = "$i new posts added to post queue for current campaign";
				echo '<br>' . $msg;
				$this->log ( 'Posts queued', $msg );
			} // end function
			
			/**
			 * function : fb_get_post
			 */
			function fb_get_post($camp){
				
				//get page id
				$camp_general=unserialize(base64_decode($camp->camp_general));
				$camp_opt = unserialize ( $camp->camp_options );
				
				echo '<br>Processing FB page:'.$camp_general['cg_fb_page'];
				$cg_fb_page_id = get_post_meta($camp->camp_id,'cg_fb_page_id',1);
				
				
				//get page id if not still extracted 
				if(trim($cg_fb_page_id) == ''){
					echo '<br>Extracting page id from original page link';
					 
					//curl get
					$x='error';
					$url= $camp_general['cg_fb_page'] ;
					curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
					curl_setopt($this->ch, CURLOPT_URL, trim($url));
					$exec=curl_exec($this->ch);
					$x  = curl_error($this->ch);
		
					if(stristr($exec, 'entity_id')){
						
						//extracting 
						preg_match_all('{entity_id":"(\d*?)"}', $exec,$matches);
						$smatch =  $matches[1];
						$cg_fb_page_id = $smatch[0];
						
						if(trim($cg_fb_page_id) !=''){
							echo '<br>Successfully extracted entityID:'.$cg_fb_page_id;
							update_post_meta($camp->camp_id, 'cg_fb_page_id', $cg_fb_page_id);
						}else{
							echo '<br>Can not find numeric entityID';
						}
						
					}else{
						echo 'Can not find valid FB reply.';
					}
					 
				}
				
				//getting access tocken
				$cg_fb_access = get_option('wp_automatic_fb_token','');
				
				if(trim($cg_fb_access ) == ''){
					
					echo '<br>Getting a FB access token..';
					
					$wp_automatic_fb_app = trim( get_option('wp_automatic_fb_app','') );
					$wp_automatic_fb_secret = trim( get_option('wp_automatic_fb_secret','') );
					
					if(trim($wp_automatic_fb_app) == '' || trim($wp_automatic_fb_secret) == ''){
						echo '<br>NO APP ID FOUND, PLEASE VISIT THE PLUGIN SETTING AND ADD THE FACEBOOK APP ID/SECRET';
						return false;						
					}
					
					//get token
					//curl get
					$x='error';
					$url="https://graph.facebook.com/oauth/access_token?client_id=$wp_automatic_fb_app&client_secret=$wp_automatic_fb_secret&grant_type=client_credentials";
					curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
					curl_setopt($this->ch, CURLOPT_URL, trim($url));
 
					$exec=curl_exec($this->ch);
					$x=curl_error($this->ch);
					
					if(stristr($exec, 'access_token=')){
						
						//found 
						echo '<br>Successfully got an access token';
						$acexplode = explode('=', $exec);
						$cg_fb_access = $acexplode[1];
						
						update_option('wp_automatic_fb_token', $cg_fb_access);
						
					}else{
						
						echo '<br>Can not find access token at content after requesting it'.$x.$exec;
						return false;
						
					}
 
					
				}
				
				
				
				//building feed
				if(  (trim($cg_fb_page_id) !='' ) &&  (trim($cg_fb_access) !='' )  ){
					

					
					$cg_fb_page_feed = "https://graph.facebook.com/v2.4/$cg_fb_page_id/posts?access_token=$cg_fb_access&limit=100&fields=message,story,created_time,id,type,picture,link,name,description";
					$cg_fb_page_feed2 = "https://graph.facebook.com/v2.4/$cg_fb_page_id/posts?access_token=[token]";
					echo '<br>FB URL:'.$cg_fb_page_feed2;
					
					//load feed 
					//curl get
					$x='error';
					$url=$cg_fb_page_feed;
					curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
					curl_setopt($this->ch, CURLOPT_URL, trim($url));
					$exec=curl_exec($this->ch);
					$x=curl_error($this->ch);


					
					if ( stristr($exec, '"data"') ){ // Checks that the object is created correctly
						
						$fb_json =json_decode($exec);
						
						$items = $fb_json->data;
						
						//echo '<pre>';
						//print_r($items);
						//exit; 						
						 
						// Loop through each feed item and display each item as a hyperlink.
						$i = 0;
						foreach ( $items as $item ){

							
							// building the link
							$item_id = $item->id;
							$id_parts = explode('_', $item_id);
							$url = "https://www.facebook.com/{$id_parts[0]}/posts/{$id_parts[1]}";
							
							echo '<br>Link:'.$url ;
							 
							//check if execluded link due to exact match does not exists
							if( $this->is_execluded($camp->camp_id, $url)){
								echo '<-- Execluded link';
								continue;
							}
								
							
								
							
							if (! $this->is_duplicate($url) ) {
							
								
								echo '<-- new link';
								
								//hyperlinking
								
								if(isset($item->message) ){
									
									
									
									$item->message = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$0</a>', $item->message);
										
									//hyperlinking the message links
									if( isset($item->message) && trim($item->message) != '' ){
									
										//extracting  hashtags
										$item->message = preg_replace('/#(\w+)/u', ' <a href="https://www.facebook.com/hashtag/$1">#$1</a>', $item->message);
									
											
									}
									
								}else{
									$item->message = '';
								}
								
							
								$i ++;
								// posting content to emails
								$created_time  = $item->created_time;
								
								
								
								
								$created_time_parts = explode('+', $created_time);
								$created_time = $created_time_parts[0];
								$created_time = str_replace('T', ' ', $created_time);
								$created_time = get_date_from_gmt($created_time);
 								
								$wpdate = $date = $created_time;

								
							}else{
								echo '<-- duplicate in post <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
								continue;
								
							}
							
							//check if older than minimum date
							if($this->is_link_old($camp->camp_id,  strtotime($wpdate) )){
								echo '<--old post execluding...';
								continue;
							}

							//building content
							$type = $item->type;
							echo '<br>Item Type:'.$type;
							
							//buidling content
							$title = ''; 
							$content = '';
							
							if($type == 'link'){
								 
								$title = $item->name;
								$content = $item->message;
								$link = $item->link;

								 if(trim($item->picture != '')){
								 	$content .= '<p><a href="'.$link.'"><img title="'.$title.'" src="'. $item->picture .'" /></a><br><a href="'.$link.'">'.@$item->description.'</a> </p>';
								 }
								 
								 
							}elseif($type == 'status'){
								  
								$content = $item->message;
								
							}elseif($type == 'photo'){
								
								$content = $item->message;
								$link = $item->link;
								 
								if(trim($item->picture != '')){
									$content .= '<p><a href="'.$link.'"><img class="wp_automatic_fb_img" title="'.$title.'" src="'. $item->picture .'" /><!--reset_images--></a> </p>';
								}
									
							}elseif( $type == 'video'  ){
								
								$style='';

								if (in_array('OPT_FB_VID_IMG_HIDE', $camp_opt) ){
									$style = ' style="display:none" ';
								}
								
								
								$content = '<img '.$style.' title="'.$title.'" src="'. $item->picture .'" /></a><br>';
								
								$content .= $item->message;
								
								$vidurl = $item->link;
								
								
								if( stristr($vidurl, '/videos/') ){
									$vi_parts = explode('/videos/', $vidurl);
									$vid_id = $vi_parts[1];
									
									$vid_id = str_replace('/', '', $vid_id);
									
									echo '<br>Found video id:'. $vid_id;
										
									$content.= '[fb_vid id="'.$vid_id.'"]';
								}elseif(stristr($vidurl, 'youtube.com')){
									
									$content.= '<br><br>[embed]'.$vidurl.'[/embed]';
									
								}
								
								
								
								
							}
							 					
							
							//check if title exits or generate it
							if(trim($title) == '' && in_array('OPT_GENERATE_FB_TITLE', $camp_opt) ){
								
								echo '<br>No title generating...';
								
								$tempContent = strip_tags(strip_shortcodes($content));
								$tempContent = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '', $tempContent); 
								
								if(function_exists('mb_substr')){
									$newTitle =  mb_substr($tempContent, 0,80) ;
								}else{
									$newTitle =  substr($tempContent, 0,80) ;
								}
								
								
								
								if(trim($newTitle) == ''){
									echo '<- did not appropriate title';
								}else{
									$title = $newTitle.'...';
								}
								
							}
							
		
							if(trim($title) == '' && in_array('OPT_FB_TITLE_SKIP', $camp_opt)){
								echo '<-- No title skiping.'; 
								continue;
							}
									
							
							//remove referral suffix 
							if( stristr($content, 'com/l.php') ){
								
								//extract links 
								preg_match_all('{"http://l\.facebook\.com/l\.php\?u=(.*?)"}', $content,$matches);
								
								$founds = $matches[0];
								$links = $matches[1];
								
								$i=0;
								foreach ($founds as $found){
									
									$found = str_replace('"', '', $found);
									$link = $links[$i];
									
									$link_parts = explode('&h', $link);
									$link = $link_parts[0];
									 
									$content = str_replace($found, urldecode($link), $content);
									
									$i++;
								}
								
							}
							
							
							
							 
							
							//replace thumbnails by full image for external links 
							if (  stristr($content, 'safe_image.php')  ){
								
								preg_match_all('{https://fbexternal-a\.akamaihd\.net/safe_image\.php.*?url=(.*?)"}', $content, $matches);
								
								$found_imgs = $matches[0];
								$found_imgs_links = $matches[1];
								
								$i=0;
								
								foreach ($found_imgs as $found_img ){
									
									$found_imgs_links[$i] = preg_replace('{&.*}', '', $found_imgs_links[$i]);
		
									$found_img_link = urldecode($found_imgs_links[$i] );
									$content = str_replace($found_img, $found_img_link."\"", $content);
									
									
								}
								
								 
							}
							
							 
							
							//small images check s130x130
							if(stristr($content, '130x130') ){
								echo '<br>Small images found extracting full images..';
								
								preg_match_all('{"https://[^"]*?\w130x130/(.*?)\..*?"}', $content,$matches);
								
								$small_imgs_srcs = str_replace('"', '', $matches[0]);
								 
								
								$small_imgs_ids = $matches[1];
								
								//remove _o or _n
								$small_imgs_ids = preg_replace('{_\D}', '', $small_imgs_ids); 
								
								//remove start of the id 
								$small_imgs_ids = preg_replace('{^\d*?_}', '', $small_imgs_ids);
								
							     	
								//get oritinal page 
								$x='error';
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim( html_entity_decode( $url)));
								$exec=curl_exec($this->ch);
								$x=curl_error($this->ch);
							 	
								if(stristr($exec, $small_imgs_ids[0])){
									echo '<br>success loaded original page';
									
									//get imgs displayed
									preg_match_all('{<img class="scaled.*?>}', $exec,$all_scalled_imgs_matches);
									$plain_imas_html = implode(' ', $all_scalled_imgs_matches[0]) ;
									 
									 
									//get ids without date at start \d{8}_(\d*?_\d*?)_
									preg_match_all('{\d{8}_(\d*?_\d*?)_}', $plain_imas_html,$all_ids_imgs_matches);
									
									$all_ids_imgs = array_unique($all_ids_imgs_matches[1]);
 									
									$firstImage = '';
									@$firstImage = $all_ids_imgs[0]; 
									
									if($firstImage == $small_imgs_ids[0] ){
										echo '<br>Found '.count($all_ids_imgs). ' images at original post';
										$small_imgs_ids = $all_ids_imgs;
									  	
										
									}
									 
								
									
									$i=0;
									foreach ($small_imgs_ids as $small_imgs_id){
										
		
										unset($large_imgs_matches);
										
		 
										//searching full image 
										preg_match('{src="(https://[^"]*?'.$small_imgs_id.'.*?)"}', $exec,$large_imgs_matches);
										
										//ajaxify images
										unset($large_imgs_matches_ajax);
										preg_match('{src=(https%3A%2F%2F[^&]*?'.$small_imgs_id.'.*?)&}', $exec,$large_imgs_matches_ajax);
 					
										
										
										if(trim($large_imgs_matches[1]) != ''){

											$replace_img = $large_imgs_matches[1];
											
											//check if there is a larger ajaxify image or not 
											if( isset($large_imgs_matches_ajax[1]) && trim($large_imgs_matches_ajax[1]) != ''){
												$replace_img = urldecode($large_imgs_matches_ajax[1]);
											}
										 
											
											
											//echo ' Replacing  '.$small_imgs_srcs[$i] . ' with '.$replace_img;
											if( stristr($content,$small_imgs_id) ){
												$content = str_replace( $small_imgs_srcs[$i], $replace_img, $content);
											}else{
												 $content = str_replace('<!--reset_images-->', '<img class="wp_automatic_fb_img" src="'.$replace_img.'"/><!--reset_images-->', $content);
											}
										
										
										} 
										
										
										 
										$i++;
									}
									
								}else{
									echo $exec;
									exit;
								}
								  
								
							}
							
							//fix links of facebook short /
							//$content = str_replace('href="/', 'href="https://facebook.com/', $content);
							$content = preg_replace('{href="/(\w)}', 'href="https://facebook.com/$1', $content);

							//change img class
							$content = str_replace('class="img"', 'class="wp_automatic_fb_img"', $content); 
							
							
							
							$ret['original_title'] = $title;
							$ret['original_link'] = $url;
							$ret['matched_content'] = $content;
							$ret['original_date'] = $wpdate;
							  
							
						
							if(trim($title) == '') $ret['original_title']= '(notitle)'; 
							
							return $ret;
		 					
						
					
						}//endforeach
						
						echo '<br>End of available items reached....';
						
				    }else{
				    	echo '<br>Unexpected api response: '.$x.$exec;
				    	 
				    }//wp error 
				 
				}//trim pageid
			}
			
			/*
			 * ---* Feed Exists function ---
			 */
			
			 
			function feed_link_exists($link, $camp_id) {
				$feed = md5 ( $link );
				echo '<br>-Link:' . $link;
				$query = "select * from {$this->wp_prefix}automatic_feeds_links where link = '$feed' and camp_id = '$camp_id'";
				if (count ( $this->db->get_results ( $query ) )) {
					echo '<---duplicate ';
					return true;
				} else {
					echo '<----new';
					return false;
				}
			}
			
			/*
			 * ---* youtube get links ---
			 */
			function youtube_fetch_links($keyword, $camp) {
				echo "<br>so I should now get some links from youtube for keyword :" . $keyword;
				
				//check if there is an api key added
				$wp_automatic_yt_tocken = get_option('wp_automatic_yt_tocken','');
				
				if(trim($wp_automatic_yt_tocken) == ''){
					echo '<br>Youtube API key is required, please visit settings page and add it';
					return false;
				}
				
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general=unserialize(base64_decode($camp->camp_general));
				$camp_general=array_map('stripslashes', $camp_general);
				
				$sortby = $camp->camp_youtube_order;
				$camp_youtube_category = $camp->camp_youtube_cat;
				
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
				
				
				if ($start == - 1 ) {
					echo '<- exhausted keyword';
					
					   //check if it is reactivated or still deactivated
					   if($this->is_deactivated($camp->camp_id, $keyword)){
					   		$start =1;
					   }else{
					   	    //still deactivated 
					   	    return false;
					   }
					
					 
				}
				
				echo ' index:' . $start;
				
				// update start index to start+50
				if( ! in_array( 'OPT_YT_CACHE' , $camp_opt )){
					echo '<br>Caching is not enabled setting youtube page to query to 1';
					$nextstart =1;
				}else{
					$nextstart = $start + 50;
				}
				
				
				
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
				
				// get items
				$orderby = $camp->camp_youtube_order;
				$cat = $camp->camp_youtube_cat;
				
				 
				
				
				if ($cat != 'All')
					$criteria .= '&category=' . $cat;
				
				 //base url 
				 $search_url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&key=".trim($wp_automatic_yt_tocken)."&maxResults=50";
				 
				 //keyword add
				 if( trim($keyword) != '*') {
				 	$search_url = $search_url .'&q='.urlencode(trim($keyword));
				 }
				 
				 //published after filter 2014-3-10T00:00:00.000-00
				 if(in_array('OPT_YT_DATE', $camp_opt)){
				 	$beforeDate=$camp_general['cg_yt_dte_year']."-".$camp_general['cg_yt_dte_month']."-".$camp_general['cg_yt_dte_day']."T00:00:00.000-00";
				 	$search_url.= "&publishedAfter=".$beforeDate;
				 }
				 
				 //OPT_YT_LIMIT_EMBED
				 if(in_array('OPT_YT_LIMIT_EMBED', $camp_opt)){
				 	$search_url.= "&videoEmbeddable=true";
				 }
				 
				 //license
				 $cg_yt_license = $camp_general['cg_yt_license'];
				 if( trim($cg_yt_license)!='' && $cg_yt_license != 'any'){
				 	$search_url.="&videoLicense=".$cg_yt_license;
				 }
				 
				 //cg_yt_type
				 $cg_yt_type = $camp_general['cg_yt_type'];
				 if( trim($cg_yt_type)!='' && $cg_yt_type != 'any'){
				 	$search_url.="&videoType=".$cg_yt_type;
				 }
				 
				 
				 //videoDuration
				 $cg_yt_duration = $camp_general['cg_yt_duration'];
				 if( trim($cg_yt_duration)!='' && $cg_yt_duration != 'any'){
				 	$search_url.="&videoDuration=".$cg_yt_duration;
				 }
				
				 //videoDefinition
				 $cg_yt_definition = $camp_general['cg_yt_definition'];
				 if( trim($cg_yt_definition)!='' && $cg_yt_definition != 'any'){
				 	$search_url.="&videoDefinition=".$cg_yt_definition;
				 }
				 
				 //order
				 $camp_youtube_order = $camp->camp_youtube_order;
				 if(trim($camp_youtube_order) == 'published') $camp_youtube_order = 'date';
				 $search_url.="&order=".$camp_youtube_order;
				 
				 //videoCategoryId
				 $videoCategoryId = $camp->camp_youtube_cat;
				 if(trim($videoCategoryId) != 'All' && is_numeric($videoCategoryId)){
				 	$search_url.="&videoCategoryId=".$videoCategoryId;		
				 } 
				 
				 //regionCode
				 if(in_array('OPT_YT_LIMIT_CTRY', $camp_opt) && trim($camp_general['cg_yt_ctr']) !=''){
				 	$search_url.="&regionCode=".trim($camp_general['cg_yt_ctr']);
				 }
				 
				 //relevanceLanguage
				 if(in_array('OPT_YT_LIMIT_LANG', $camp_opt) && trim($camp_general['cg_yt_lang']) !=''){
				 	$search_url.="&relevanceLanguage=".trim($camp_general['cg_yt_lang']);
				 }
				 	
				 
				if (in_array ( 'OPT_YT_USER', $camp_opt )) {
					echo '<br>Fetching vids for specific user/channel ' . $camp->camp_yt_user;
		
					//check if playlist
					if (in_array ( 'OPT_YT_PLAYLIST', $camp_opt )) {
						echo '<br>Specific Playlist:'.$camp_general['cg_yt_playlist'];
						
						$search_url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=".$camp_general['cg_yt_playlist']."&key=".trim($wp_automatic_yt_tocken)."&maxResults=50"; 
						
					}else{
						$camp_yt_user = $camp->camp_yt_user;
						$search_url.= "&channelId=".trim($camp_yt_user);
					}
					
					
				
				
				} else {
		
					//no user just search
					
				}
				
				//check nextpagetoken
				$nextPageToken = get_post_meta($camp->camp_id,'wp_automatic_yt_nt_'.md5($keyword),true);
				
				if(  in_array( 'OPT_YT_CACHE' , $camp_opt )){
					
					if(trim($nextPageToken) != ''  ){
						echo '<br>nextPageToken:'.$nextPageToken;
						$search_url.= '&pageToken='.$nextPageToken;
					}else{
						echo '<br>No page token let it the first page';
					}
					
				}
				
				echo '<br>Search URL:'.$search_url;
				
				//process request
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($search_url));
		 		$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
		  
				//verify reply 
				if(!stristr($exec, '"kind"')){
					echo '<br>Not valid reply from Youtube:'.$exec.$x;
					return false;
				}
				
				$json_exec = json_decode($exec);
				
				//check nextpage token
				if(isset($json_exec->nextPageToken) && trim($json_exec->nextPageToken)!='' ){
					$newnextPageToken = $json_exec->nextPageToken;
					echo '<br>New page token:'.$newnextPageToken;
					update_post_meta($camp->camp_id, 'wp_automatic_yt_nt_'.md5($keyword), $newnextPageToken);
				}else{
					//delete the token
					echo '<br>No next page token';
					delete_post_meta($camp->camp_id, 'wp_automatic_yt_nt_'.md5($keyword));
					
					//set start to -1 exhausted
					$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid";
					$this->db->query ( $query );
					
					//deactivate for 60 minutes
					$this->deactivate_key($camp->camp_id, $keyword);
					
				}
				
				//get items
				$search = array();
				$search = $json_exec->items;
				
				// disable keyword if no new items
				if (count ( $search ) == 0) {
					echo '<br>No more vids for this keyword deactivating it ..';
					$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid";
					$this->db->query ( $query );
					
					//deactivate for 60 minutes
					$this->deactivate_key($camp->camp_id, $keyword);
					
					return;
				}
				
				echo '<ol>';
				
				//reversing?
				if(in_array('OPT_YT_REVERSE', $camp_opt)){
					echo '<br>Reversing vids list order.';
					$search = array_reverse($search);
				}
				
				foreach ( $search as $itm ) {
					
					//general added details
					$general = array();
					
					//get vid id from response
					if( stristr($search_url,'playlistItems' ) ){
						$vid_id = $itm->snippet->resourceId->videoId;
					}else{
						$vid_id = $itm->id->videoId;
					}  
					
					//vid url 
					$link_url = 'https://www.youtube.com/watch?v='.$vid_id;
					$httplink_url =  'http://www.youtube.com/watch?v='.$vid_id;
					
					//vid thumbnail 
					$link_img = $itm->snippet->thumbnails->high->url;
					
					//get largest size
					//$link_img = str_replace('hqdefault', 'hqdefault', $link_img);
					
					//get item title
					$link_title = addslashes ( $itm->snippet->title );
					
					//get item description
					$link_desc = addslashes ( $itm->snippet->description );
					 
					//channel title
					$general['vid_author_title']= $itm->snippet->channelTitle;
					 
					//channel id
					$author = addslashes ( $itm->snippet->channelId );
					
					//link time 
					$link_time = strtotime($itm->snippet->publishedAt);
		 			
					//Clear these values and generate at runtime to save costs of api requests
					$link_player = ''  ;
					
					//needs a separate request with v3 api
					$link_views = '';
					$link_rating = '';
					$link_duration = '';
					
					echo '<li>' . $link_title . '</li>';
					
					//echo 'Published:'. date('Y-m-d',$itm['time']).' ';
					
					if( $this->is_execluded($camp->camp_id, $link_url) ){
						echo '<-- Execluded';
						continue;
					}
					
					
					/*
					//check if older than minimum date
					if($this->is_link_old($camp->camp_id, $itm['time'])){
						echo '<--old video execluding...';
						continue;
					}
					*/
					
					
					//serializing general
					$general = base64_encode(serialize($general));
					
					
					if ( ! $this->is_duplicate($link_url) && ! $this->is_duplicate($httplink_url) )  {
						$query = "INSERT INTO {$this->wp_prefix}automatic_youtube_links ( link_url , link_title , link_keyword  , link_status , link_desc ,link_time,link_rating ,link_views,link_player,link_img,link_author,link_duration, link_general ) VALUES ( '$link_url', '$link_title', '{$camp->camp_id}_$keyword', '0' ,'$link_desc','$link_time','$link_rating','$link_views','$link_player','$link_img','$author','$link_duration','$general')";
						$this->db->query ( $query );
					} else {
						echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
					}
				}
				echo '</ol>';
			}
			
			/*
			 * ---* youtube post ---
			 */
			function youtube_get_post($camp) {
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode  ( ',', $camp->camp_keywords );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				$camp_post_content = $camp->camp_post_content;
				$camp_post_custom_v = implode(',', unserialize ( $camp->camp_post_custom_v ) ); 
				
				foreach ( $keywords as $keyword ) {
					
					if (trim ( $keyword ) != '') {
						
						echo '<br>Keyword:'.$keyword;
						
						//update last keyword 
						update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
						
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_youtube_links where link_keyword='{$camp->camp_id}_$keyword' and link_status ='0'";
						$res = $this->db->get_results ( $query );
						
						
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							$this->youtube_fetch_links ( $keyword, $camp );
							// getting links from the db for that keyword
							
							$res = $this->db->get_results ( $query );
						}
						
						
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
						
							$t_row = $res[$i];
							$t_link_url=$t_row->link_url;
						
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Vid ('. $t_row->link_title .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_youtube_links where link_id='{$t_row->link_id}'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
						
						}
						
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
							
							// lets process that link
							$ret = $res [$i];
							
							echo '<br>Link:'.$ret->link_url;
							
							//extract video id
							$temp_ex=explode('v=', $ret->link_url);
							$vid_id=$temp_ex[1];
							
							//set used url
							$this->used_link = trim($ret->link_url);
							
							$temp ['vid_title'] = trim($ret->link_title);
							$temp ['vid_url'] = trim($ret->link_url);
							$temp ['source_link'] = trim($ret->link_url);
							
							//generate player
							$width = $camp_general['cg_yt_width'];
							$height = $camp_general['cg_yt_height'];
							if(trim($width) == '') $width=580;
							if(trim($height) == '') $height=385;

							
							
							$embedsrc= "//www.youtube.com/embed/".$vid_id;
							
							if (in_array('OPT_YT_SUGGESTED', $camp_opt) && in_array('OPT_YT_AUTO', $camp_opt) ){

								$embedsrc.= '?rel=0&autoplay=1';
							
							}elseif( in_array('OPT_YT_SUGGESTED', $camp_opt) ){
								
								$embedsrc.= '?rel=0';
								
							}elseif( in_array('OPT_YT_AUTO', $camp_opt) ){
								
								$embedsrc.= '?autoplay=1';
								
							}
							
							
							$temp ['vid_player'] =  '<iframe width="'.$width.'" height="'.$height.'" src="'.$embedsrc.'" frameborder="0" allowfullscreen></iframe>';
							
							//ini get video details flag if true will request yt again for new data
							$get_vid_details = false;
							$get_vid_details_parts = array();
							
							//statistics part
							$temp ['vid_views'] = trim($ret->link_views);
							$temp ['vid_rating'] = trim($ret->link_rating);
							
							//general
							$general =  unserialize( base64_decode ($ret->link_general) );
							$temp ['vid_author_title'] = $general['vid_author_title'];
							
							//merging post content with custom fields values to check what tags
							$camp_post_content_original = $camp_post_content;
							$camp_post_content = $camp_post_custom_v . $camp_post_content;
							
							if ( stristr($camp_post_content, 'vid_views') || stristr($camp_post_content, 'vid_rating') || stristr($camp_post_content, 'vid_likes') || stristr($camp_post_content, 'vid_dislikes') ){
								$get_vid_details = true;
								$get_vid_details_parts[] = 'statistics';
							}
							
							
							//contentdetails part
							$temp ['vid_duration'] =  trim($ret->link_duration);
							
							if ( stristr($camp_post_content, 'vid_duration')){
								$get_vid_details = true;
								$get_vid_details_parts[] = 'contentDetails';
							}
								
							//snippet part full content 
							$temp ['vid_desc'] = trim($ret->link_desc);
							
							//if full description from youtube or tags let's get them 
							if(in_array('OPT_YT_FULL_CNT', $camp_opt)  ){
								$get_vid_details = true;
								$get_vid_details_parts[] = 'snippet';
								
							}
							
							
							//restore the content 
							$camp_post_content = $camp_post_content_original   ;
							
							//now get the video details again if active
							if($get_vid_details){
		
								echo '<br>Getting more details from youtube for the vid..';
								
								//token
								$wp_automatic_yt_tocken = get_option('wp_automatic_yt_tocken','');
								
								//curl get
								$x='error';
								$ccurl='https://www.googleapis.com/youtube/v3/videos?key='.$wp_automatic_yt_tocken.'&part='.implode(',', $get_vid_details_parts).'&id='.$vid_id;
								
								echo '<br>yt link:'.$ccurl;
								
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($ccurl));
								$exec=curl_exec($this->ch);
								$x=curl_error($this->ch);
								
								 
								if(stristr($exec, 'kind')){
									
									$json_exec = json_decode($exec);
									$theItem = $json_exec->items[0];
									
									//check snippet
									if( isset($theItem->snippet) ){
										//setting full content 
										$temp ['vid_desc'] =  $theItem->snippet->description;
										echo '<br>Full description set';
									}
									
									//check contentdetails details
									if( isset($theItem->contentDetails)){
										$temp ['vid_duration'] = $theItem->contentDetails->duration;
										
										$temp ['vid_duration'] = str_replace('PT', '', $temp ['vid_duration']);
										$temp ['vid_duration'] = str_replace('M', ':', $temp ['vid_duration']);
										$temp ['vid_duration'] = str_replace('H', ':', $temp ['vid_duration']);
										$temp ['vid_duration'] = str_replace('S', '', $temp ['vid_duration']);
										
									}
									
									//check statistics details
									if( isset($theItem->statistics)){
										$temp ['vid_views'] = $theItem->statistics->viewCount;
										
										$likeCount=$theItem->statistics->likeCount;
										$dislikeCount = $theItem->statistics->dislikeCount;
										
										$rating = $likeCount/($likeCount + $dislikeCount);
										$rating = $rating  * 5;
										$rating = number_format($rating,2);
										
										$temp ['vid_rating'] = $rating;
										$temp ['vid_likes'] = $theItem->statistics->likeCount;
										$temp ['vid_dislikes'] = $theItem->statistics->dislikeCount;
										
											
									}
									 
								}else{
									echo '<br>no valid reply from youtube ';
								}
								 
								
							}
							
						
							
							$temp ['vid_img'] = trim($ret->link_img);
							$temp ['vid_author'] = trim($ret->link_author);
							$temp ['vid_time'] = trim($ret->link_time);
							$temp ['vid_id'] =trim($vid_id);
							$this->used_keyword = $ret->link_keyword;
							
		
							
							//if vid_image contains markup extract the source only
							if(stristr($temp['vid_img'], '<img')){
								preg_match_all('/src\="(.*?)"/',$temp['vid_img'],$matches);
								$temp['vid_img'] = $matches[1][0];
							}
							
							
								
								//curl get
								$x='error';
								$url=$this->used_link;
								 
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($url));
								$exec=curl_exec($this->ch);
								$x=curl_error($this->ch);
								
								 
								if(trim($x) != '') echo '<br>'.$x;
								
								//tags
								if(in_array('OPT_YT_TAG', $camp_opt)){
								
									//extracting tags
									echo '<br>Extracting youtube video tags from youtube ';
										
									//<meta name="keywords" content="wordpress plugin, spin, rewriter, rewrite, auto rewrite posts, auto rewrite articles, wordpress rewriter, wordpress spinner, spinrewriter, spin rewwriter">
									if(stristr($exec, 'name="keywords"')){
										//extract it
										preg_match_all('/keywords" content="(.*?)"/', $exec,$matches);
								
										$tags= $matches[1][0];
								
											
										if(stristr($tags, ',')){
											$tag_count=count(explode(',', $tags));
											echo '<br>Found '.$tag_count . ' tags ';
											
											$this->used_tags=$tags ;
											
										}
								
									}else{
										echo '<br>Can not find keywords meta tag';
									}
										
								}
								
							 
							 
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_youtube_links set link_status='1' where link_id=$ret->link_id";
							$this->db->query ( $query );
							
							// if cache not active let's delete the cached videos and reset indexes
							if (! in_array ( 'OPT_YT_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_youtube_links where link_keyword='{$camp->camp_id}_$keyword' and link_status ='0'";
								//$query = "update {$this->wp_prefix}automatic_youtube_links set link_status ='1' where link_keyword='{$camp->camp_id}_$keyword' and link_status ='0'";
								
								$this->db->query ( $query );
								
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
							}
							
							return $temp;
						} else {
							
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
			}
			
			/*
			 * ---* youtube get links ---
			 */
			function flicker_fetch_items($keyword, $camp) {
				echo "<br>so I should now get some images from flicker for keyword :" . $keyword;
				
				$api_key = get_option ( 'wp_automatic_flicker', '' );
				
				if (trim ( $api_key ) == '') {
					echo '<br>Flicker Api key required ';
					exit ();
				}
				
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				
				$sortby = $camp_general ['cg_fl_order'];
				
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
				
				if ($start == - 1) {
					echo '<- exhausted keyword';
					
					if( ! in_array( 'OPT_FL_CACHE' , $camp_opt )){
						$start =1;
						echo '<br>Cache disabled resetting index to 1';
					}else{
						
						//check if it is reactivated or still deactivated
						if($this->is_deactivated($camp->camp_id, $keyword)){
							$start =1;
						}else{
							//still deactivated
							return false;
						}
						
					}
					
				 
				}
				
				echo ' index:' . $start;
				
				// update start index to start+1
				$nextstart = $start + 1;
				
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
				
				// get items
				$orderby = $camp_general ['cg_fl_order'];
				
				$flink = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$api_key&format=php_serial&page=$start&sort=$sortby";
		
				
				
				if (in_array ( 'OPT_FL_USER', $camp_opt )) {
					echo '<br>Fetching images for specific user  ' . $camp_general ['cg_fl_user'];
					$flink = $flink . '&user_id=' . $camp_general ['cg_fl_user'];
					// if keyword *
					if (trim ( $keyword ) == '*') {
						echo '<br>No filtering get all ';
					} else {
						// specific keyword
						$flink = $flink . '&text=' . urlencode($keyword);
					}
				} else {
					// no specific user just text
					$flink = $flink . '&text=' . urlencode($keyword);
				}
				
				
				//licensing license
				if(in_array('OPT_FL_LICENSE', $camp_opt)){
					$licenses = array();
				
					if(in_array('OPT_FL_LICENSE_0', $camp_opt)) $licenses[] = 0;
					if(in_array('OPT_FL_LICENSE_1', $camp_opt)) $licenses[] = 1;
					if(in_array('OPT_FL_LICENSE_2', $camp_opt)) $licenses[] = 2;
					if(in_array('OPT_FL_LICENSE_3', $camp_opt)) $licenses[] = 3;
					if(in_array('OPT_FL_LICENSE_4', $camp_opt)) $licenses[] = 4;
					if(in_array('OPT_FL_LICENSE_5', $camp_opt)) $licenses[] = 5;
					if(in_array('OPT_FL_LICENSE_6', $camp_opt)) $licenses[] = 6;
					if(in_array('OPT_FL_LICENSE_7', $camp_opt)) $licenses[] = 7;
					if(in_array('OPT_FL_LICENSE_8', $camp_opt)) $licenses[] = 8;
				
					if(count($licenses) > 0 ) $flink.="&license=".implode(',', $licenses);
				
				}
				
				  
				 
				// curl get
				$x = 'error';
				$url = $flink;
				curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
				curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
				while ( trim ( $x ) != '' ) {
					$exec = curl_exec ( $this->ch );
					$x = curl_error ( $this->ch );
				}
				
				$result = unserialize ( $exec );
				
				if (is_array ( $result )) {
					 
					echo '<br>Valid array returned from flicker ';
					
					$imgs = $result ['photos'] ['photo'];
					
					if (is_array ( $imgs )) {
						
						echo '<br>Valid reply array returned with ' . count ( $imgs ) . ' child';
						
						 
						if (count ( $imgs ) == 0) {
							echo '<br>Keyword have no more images deactivating...';
							$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
							$this->db->query ( $query );
							
							$this->deactivate_key($camp->camp_id, $keyword);
							
						}
					} else {
						echo '<br>Did not find valid image array in the response ';
						$imgs = array ();
					}
				} else {
					
					echo '<br>Flicker did not reuturn valid reply array ';
				}
				
				/*
				 * disable keyword if no new items if (count ( $search ) == 0) { echo '<br>No more vids for this keyword deactivating it ..'; $query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid"; $this->db->query ( $query ); return; }
				 */
				
				echo '<ol>';
				
				foreach ( $imgs as $itm ) {
					
					$id = $itm ['id'];
					$data = serialize ( $itm );
					
					$item_link = 'http://flicker.com/' . $itm ['owner'] . '/' . $id;
					echo '<li> Link:'.$item_link;
					
					
					if( $this->is_execluded($camp->camp_id, $item_link) ){
						echo '<-- Execluded';
						continue;
					}
					
					
						
					if ( ! $this->is_duplicate($item_link) )  {
						$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (  '$id', '0', '$data' ,'fl_{$camp->camp_id}_$keyword')  ";
						$this->db->query ( $query );
					} else {
						echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
					}
					
				}
				
				echo '</ol>';
				
			}
			
			/**
			 * Vimeo get post function
			 */
			function vimeo_get_post($camp){
				
				//ini keywords
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				
				//looping keywords
				
				foreach ( $keywords as $keyword ) {
				
					//update last keyword
					update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
		
					//when valid keyword
					if (trim ( $keyword ) != '') {
						
						//record current used keyword
						$this->used_keyword=$keyword;
						
				
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'vm_{$camp->camp_id}_$keyword' and item_status ='0'";
						$res = $this->db->get_results ( $query );
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							
							//get new links
							$this->vimeo_fetch_items ( $keyword, $camp );
							
							// getting links from the db for that keyword
							$res = $this->db->get_results ( $query );
						}
						
						//check if already duplicated
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
				
							$t_row = $res[$i];
		 
							$t_data =  unserialize (base64_decode( $t_row->item_data) );
							 
							$t_link_url=$t_data['vid_url'];
				
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Vimeo video ('. $t_data ['vid_title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->vid_id}' and item_type=  'vm_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
				
						}
				
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
								
							// lets process that link
							$ret = $res [$i];
							$temp = unserialize ( base64_decode($ret->item_data ));
		
							//report link
							echo '<br>Found Link:<a href="'.$temp['vid_url'].'">'.$temp ['vid_title'].'</a>';

							$temp['source_link'] = $temp['vid_url'];
							
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type='vm_{$camp->camp_id}_$keyword' ";
							$this->db->query ( $query );
								
							// if cache not active let's delete the cached videos and reset indexes
							if (! in_array ( 'OPT_VM_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='vm_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
				
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
							}
		
							
							
							return $temp;
						} else {
								
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
				
				
			}
			
			/**
			 * vimeo fetch items
			 * 
			 */
			 
			 function vimeo_fetch_items($keyword, $camp){
		
			 	//report 
				echo "<br>So I should now get some videos from vimeo for keyword :" . $keyword;
				
			 	//access tocken
				$access_tocken = get_option('wp_automatic_vm_tocken','');
				
				//validate tocken
				if (trim ( $access_tocken ) == '') {
					echo '<br>Vimeo Access token is required visit settings and add it...';
					exit ();
				}
				
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
				
				if ($start == - 1) {
					echo '<- exhausted keyword';
						
					if( ! in_array( 'OPT_VM_CACHE' , $camp_opt )){
						$start =1;
						echo '<br>Cache disabled resetting index to 1';
					}else{
				
						//check if it is reactivated or still deactivated
						if($this->is_deactivated($camp->camp_id, $keyword)){
							$start =1;
						}else{
							//still deactivated
							return false;
						}
				
					}
			
				}
				
				echo ' index:' . $start;
				
				// update start index to start+1
				$nextstart = $start + 1;
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
				
				//auth
				curl_setopt($this->ch,CURLOPT_HTTPHEADER,array('Authorization: bearer '.trim($access_tocken)));
				
				//if specific user posting
				if(in_array('OPT_VM_USER', $camp_opt)){
		
					$author = $camp_general['cg_vm_user'];
					$url = 'https://api.vimeo.com/'.$camp_general['cg_vm_user_channel'].'/'.trim($author).'/videos?page='.$start.'&per_page=50'; 
					
					if($keyword !='*'){
						$url.= '&query='.urlencode($keyword);
					}
					
					if($camp_general['cg_vm_order'] != 'relevant'){
						$url.='&sort='.$camp_general['cg_vm_order'].'&direction='.$camp_general['cg_vm_order_dir'];	
					}
					
					 
				}else{
		
					// get items
					$url='https://api.vimeo.com/videos?query='.urlencode($keyword).'&page='.$start.'&per_page=50&sort='.$camp_general['cg_vm_order'].'&direction='.$camp_general['cg_vm_order_dir'];
					
					//filter?
					if( $camp_general['cg_vm_cc'] != 'none' ){
						$url.= '&filter='.$camp_general['cg_vm_cc'];
					}
					
				}
				
				
				
				//report url
				echo '<br>Vimeo url:'.$url;
				
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($url));
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
		
				//echo $exec.$x;
				
				//validating reply 
				if(stristr($exec, 'paging')){
					//valid reply
					
					//handle vids
					$arr = json_decode($exec);
					$vids = $arr->data;
					unset($arr);
		
					//if reversion order
					if(in_array('OPT_VM_REVERSE', $camp_opt)){
						echo '<br>Reversing order';
						$vids = array_reverse($vids);
					}
					
					echo '<ol>';
					 
					//loop videos
					$i = 0; 
					foreach ($vids as $vid){
						
						$itm['vid_url'] =  $vid->link;
						
						echo '<li>'.$itm['vid_url'];
						
						
						$itm['vid_title'] = $vid->name;
						$itm['vid_id'] = str_replace('/videos/', '', $vid->uri) ;
						$itm['vid_description'] = $vid->description;
						$itm['vid_duration'] = $vid->duration;
						$itm['vid_width'] = $vid->width;
						$itm['vid_height'] = $vid->height;
						//$itm['vid_rating'] = $vid->content_rating;
						$itm['vid_img'] = $vid->pictures->sizes[count($vid->pictures->sizes) -1]->link;
						
						//embed code
						$itm['vid_embed'] = $vid->embed->html;
						
						//replace width and height 
						$itm['vid_embed'] = str_replace('width="'.$itm['vid_width'].'"', 'width="560"', $itm['vid_embed']);
						$itm['vid_embed'] = str_replace('height="'.$itm['vid_height'].'"', 'height="315"', $itm['vid_embed']);
		 				
						//extract tags
						$tags=$vid->tags;
						$tags_arr = array();
						
						foreach ($tags as $tag){
							$tags_arr[]=$tag->tag;
						}
						
						if(count($tags_arr) > 0 ){
							$itm['vid_tags'] = implode(',', $tags_arr);
						}else{
							$itm['vid_tags'] = '';
						}
						
						
						
						$itm['vid_views'] = $vid->stats->plays;
						$itm['vid_created_time'] = $vid->created_time;
						$itm['vid_modified_time'] = $vid->modified_time;
						
						//fixing dates
						$itm['vid_created_time'] =  str_replace('T', ' ', $itm['vid_created_time']) ;
						$itm['vid_created_time'] =   str_replace('+00:00', '', $itm['vid_created_time']);
		
						$itm['vid_modified_time'] =  str_replace('T', ' ', $itm['vid_modified_time']) ;
						$itm['vid_modified_time'] =   str_replace('+00:00', '', $itm['vid_modified_time']);
						
						
						
						
						$itm['vid_likes'] = $vid->metadata->connections->likes->total;
						$itm['vid_author_name'] = $vid->user->name;
						$itm['vid_author_id'] =  str_replace('/users/', '', $vid->user->uri)  ;
						$itm['vid_author_link'] = $vid->user->link;
						$itm['vid_author_picutre'] = @$vid->user->pictures->sizes[count($vid->user->pictures->sizes) -1]->link;
						$itm['vid_comments_count'] = $vid->metadata->connections->comments->total;
					 
						$data = base64_encode(serialize ( $itm ));
							
							
						if( $this->is_execluded($camp->camp_id, $itm['vid_url']) ){
							echo '<-- Execluded';
							continue;
						}
							
						if ( ! $this->is_duplicate($itm['vid_url']) )  {
							$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (    '{$itm['vid_id']}', '0', '$data' ,'vm_{$camp->camp_id}_$keyword')  ";
							$this->db->query ( $query );
						} else {
							echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						}
						
						echo '</li>';
						$i++;
						
					}
					
					echo '</ol>';
					
					echo '<br>Total '. $i .' videos added cached';
					 
					//check if nothing found so deactivate
					if($i == 0){
						echo '<br>No new vimeo vids found ';
						echo '<br>Keyword have no more images deactivating...';
						$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
						$this->db->query ( $query );
						$this->deactivate_key($camp->camp_id, $keyword);
					 	
					}
					
				}else{
					
					//no valid reply
					echo '<br>No Valid reply for video search from vimeo<br>'.$exec;
					
					if(stristr($exec, 'valid user token')){
						echo '<br>Please visit the plugin settings and add a Vimeo api access token';
					}
					 
					if(stristr($exec,'Page is out of bounds')){
		
						$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
						$this->db->query ( $query );
						
						//deactivate for 60 minutes
						$this->deactivate_key($camp->camp_id, $keyword);
						
						
					}
					
					 
						
				}
		
			}
		
			/**
			 * function pinterest_get_post: return valid pinterest pin to post
			 * @param unknown $camp
			 */
			function pinterest_get_post($camp){
				
				//ini keywords
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				$camp_general=unserialize(base64_decode($camp->camp_general));
				
				//looping keywords
				foreach ( $keywords as $keyword ) {
				
					//update last keyword
					update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
				
					//when valid keyword
					if (trim ( $keyword ) != '') {
				
						//record current used keyword
						$this->used_keyword=$keyword;
				
				
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'pt_{$camp->camp_id}_$keyword' and item_status ='0'";
						$res = $this->db->get_results ( $query );
				
						// when no links lets get new links
						if (count ( $res ) == 0) {
								
							//get new links
							$this->pinterest_fetch_items ( $keyword, $camp );
								
							// getting links from the db for that keyword
							$res = $this->db->get_results ( $query );
						}
				
						//check if already duplicated
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
				
							$t_row = $res[$i];
				
							$t_data =  unserialize (base64_decode( $t_row->item_data) );
				
							$t_link_url=$t_data['pin_url'];
				
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Pinterest pin ('. $t_data ['pin_title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->vid_id}' and item_type=  'pt_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
				
						}
				
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
				
							// lets process that link
							$ret = $res [$i];
							
							$temp = unserialize ( base64_decode($ret->item_data ));
							
							//generating title for pinterest
							if( trim($temp['pin_title']) == '' ){
							
								if(in_array('OPT_PT_AUTO_TITLE', $camp_opt)){
		
									echo '<br>No title generating...';
								
									$cg_pt_title_count = $camp_general['cg_pt_title_count'];
									if(! is_numeric($cg_pt_title_count)) $cg_pt_title_count = 80;
								 
									$newTitle = substr( strip_tags( $temp['pin_description']), 0,  $cg_pt_title_count);
									
									$temp['pin_title'] = $newTitle.'...';
								
								}else{
									
									$temp['pin_title'] = '(notitle)';
									
								}
								
							}
								
					 
							//report link
							echo '<br>Found Link:'.$temp['pin_url'] ;
				
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type='pt_{$camp->camp_id}_$keyword' ";
							$this->db->query ( $query );
				
							// if cache not active let's delete the cached videos and reset indexes
							if (! in_array ( 'OPT_PT_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='pt_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
				
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
							}
							
							//fix tags links 
							$temp['pin_description'] = str_replace('<a href="/', '<a href="https://pinterest.com/', $temp['pin_description'] );
				
						   
								
							return $temp;
							
							
						} else {
				
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
				
				
				
			}
			
			/**
			 * function pinterest_fetch_items: get new items from pinterest for specific keyword
			 * @param unknown $keyword
			 * @param unknown $camp
			 */
			function pinterest_fetch_items($keyword,$camp){
				
				//report
				echo "<br>So I should now get some pins from Pinterest for keyword :" . $keyword;
				
				 
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				$pin_host = 'www.pinterest.com';
				
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
				
				if ($start == - 1) {
					echo '<- exhausted keyword';
				
					if( ! in_array( 'OPT_PT_CACHE' , $camp_opt )){
						$start =1;
						echo '<br>Cache disabled resetting index to 1';
					}else{
				
						//check if it is reactivated or still deactivated
						if($this->is_deactivated($camp->camp_id, $keyword)){
							$start =1;
						}else{
							//still deactivated
							return false;
						}
				
					}
				
				}
				
				
				echo ' index:' . $start;
				
				// update start index to start+1
				$nextstart = $start + 1;
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
				
				//prepare referes 
				curl_setopt($this->ch,CURLOPT_HTTPHEADER,array('Referer: https://www.pinterest.com/','X-NEW-APP: 1','X-Requested-With: XMLHttpRequest'));
				
				//if specific user posting
				if(in_array('OPT_PT_USER', $camp_opt)){
				
					
					$cg_pt_user_channel = $camp_general['cg_pt_user_channel'];
					$author = $camp_general['cg_pt_user'];
					
					
					if( $cg_pt_user_channel == 'users' ){
						
						// get requrest url from the zero index
						
						if( $start == 1 ){
		
							//use first basse query
							$url ="https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430667298559";
						
						}else{
							
							//not first page get the bookmark
							$wp_pinterest_bookmark = get_post_meta ($camp->camp_id,'wp_pinterest_bookmark'.md5($keyword),1);
							
							if(trim($wp_pinterest_bookmark) == ''){
								echo '<br>No Bookmark';
								$url ="https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430667298559";
							}else{
								echo '<br>Bookmark:'.$wp_pinterest_bookmark;
								$url = "https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%2C%22bookmarks%22%3A%5B%22".urlencode($wp_pinterest_bookmark)."%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430667298566";
							}
							
						}
						
						//replace username
						$url = str_replace( 'welkerpatrick', $author, $url);
						
					}else{
						
						//board id 
						$wp_pinterest_board_id = get_post_meta($camp->camp_id,'wp_pinterest_board_id', 1);
						
						if(trim($wp_pinterest_board_id) == ''){
							
							//must get board id from it's page 
							//curl get
							$x='error';
							$url='https://api.pinterest.com/v3/pidgets/boards/'.trim($author).'/pins/';
							curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
							curl_setopt($this->ch, CURLOPT_URL, trim($url));
							$exec=curl_exec($this->ch);
							$x=curl_error($this->ch);
							
							//extract id 
							$exIDjson = json_decode($exec);
							$exID = $exIDjson->data->board->id;
							
							echo '<br>Extracted board ID:'.$exID;
							  
							if(is_numeric($exID)){
								update_post_meta($camp->camp_id, 'wp_pinterest_board_id', $exID);
								$wp_pinterest_board_id = $exID;
							}
						}
						
						if(! is_numeric($wp_pinterest_board_id)){
							echo '<br>Can not get valid board id. make sure your data is correct';
							return false;
						}
						
						
						//specific board
						if($start == 1){
							$url ="https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Fwelkerpatrick%2Frecipes%2F&data=%7B%22options%22%3A%7B%22board_id%22%3A%221266774834151486%22%2C%22board_url%22%3A%22%2Fwelkerpatrick%2Frecipes%2F%22%2C%22board_layout%22%3A%22default%22%2C%22prepend%22%3Atrue%2C%22page_size%22%3Anull%2C%22access%22%3A%5B%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserProfileContent(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserBoards()%3EGrid(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EGridItems(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EBoard(show_board_context%3Dfalse%2C+show_user_icon%3Dfalse%2C+view_type%3DboardCoverImage%2C+component_type%3D1%2C+resource%3DBoardResource(board_id%3D1266774834151486))&_=1430694254166";
						}else{
							$wp_pinterest_bookmark = get_post_meta ($camp->camp_id,'wp_pinterest_bookmark'.md5($keyword),1);
							
							if(trim($wp_pinterest_bookmark) == ''){
								$url ="https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Fwelkerpatrick%2Frecipes%2F&data=%7B%22options%22%3A%7B%22board_id%22%3A%221266774834151486%22%2C%22board_url%22%3A%22%2Fwelkerpatrick%2Frecipes%2F%22%2C%22board_layout%22%3A%22default%22%2C%22prepend%22%3Atrue%2C%22page_size%22%3Anull%2C%22access%22%3A%5B%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserProfileContent(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserBoards()%3EGrid(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EGridItems(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EBoard(show_board_context%3Dfalse%2C+show_user_icon%3Dfalse%2C+view_type%3DboardCoverImage%2C+component_type%3D1%2C+resource%3DBoardResource(board_id%3D1266774834151486))&_=1430694254166";
							}else{
								echo '<br>Bookmark:'.$wp_pinterest_bookmark;
								$url ="https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Fwelkerpatrick%2Frecipes%2F&data=%7B%22options%22%3A%7B%22board_id%22%3A%221266774834151486%22%2C%22board_url%22%3A%22%2Fwelkerpatrick%2Frecipes%2F%22%2C%22board_layout%22%3A%22default%22%2C%22prepend%22%3Atrue%2C%22page_size%22%3Anull%2C%22access%22%3A%5B%5D%2C%22bookmarks%22%3A%5B%22".urlencode($wp_pinterest_bookmark)."%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=UserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserProfileContent(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserBoards()%3EGrid(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EGridItems(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EBoard(show_board_context%3Dfalse%2C+show_user_icon%3Dfalse%2C+view_type%3DboardCoverImage%2C+component_type%3D1%2C+resource%3DBoardResource(board_id%3D1266774834151486))&_=1430694254182";
									
							}
							
						}
						
						//replacing board id 
						$url = str_replace('1266774834151486', $wp_pinterest_board_id, $url);
						
					}
					
				
				}else{
				
					// get requrest url from the zero index
					if( $start == 1 ){
					
						//use first basse query
						$url ="https://www.pinterest.com/resource/BaseSearchResource/get/?source_url=%2Fsearch%2Fpins%2F%3Fq%3Darabian&data=%7B%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22arabian%22%7D%2C%22context%22%3A%7B%7D%2C%22module%22%3A%7B%22name%22%3A%22SearchPage%22%2C%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22arabian%22%7D%7D%2C%22render_type%22%3A1%2C%22error_strategy%22%3A0%7D&module_path=App()%3EHeader()%3ESearchForm()%3ETypeaheadField(enable_recent_queries%3Dtrue%2C+support_guided_search%3Dtrue%2C+resource_name%3DAdvancedTypeaheadResource%2C+name%3Dq%2C+tags%3Dautocomplete%2C+class_name%3DbuttonOnRight%2C+type%3Dtokenized%2C+prefetch_on_focus%3Dtrue%2C+value%3D%22%22%2C+input_log_element_type%3D227%2C+hide_tokens_on_focus%3Dundefined%2C+support_advanced_typeahead%3Dfalse%2C+view_type%3Dguided%2C+populate_on_result_highlight%3Dtrue%2C+search_delay%3D0%2C+search_on_focus%3Dtrue%2C+placeholder%3DDiscover%2C+show_remove_all%3Dtrue)&_=1430685210358";
					
					}else{
							
						//not first page get the bookmark
						$wp_pinterest_bookmark = get_post_meta ($camp->camp_id,'wp_pinterest_bookmark'.md5($keyword),1);
							
						if(trim($wp_pinterest_bookmark) == ''){
							echo '<br>No Bookmark';
							$url ="https://www.pinterest.com/resource/BaseSearchResource/get/?source_url=%2Fsearch%2Fpins%2F%3Fq%3Darabian&data=%7B%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22arabian%22%7D%2C%22context%22%3A%7B%7D%2C%22module%22%3A%7B%22name%22%3A%22SearchPage%22%2C%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22arabian%22%7D%7D%2C%22render_type%22%3A1%2C%22error_strategy%22%3A0%7D&module_path=App()%3EHeader()%3ESearchForm()%3ETypeaheadField(enable_recent_queries%3Dtrue%2C+support_guided_search%3Dtrue%2C+resource_name%3DAdvancedTypeaheadResource%2C+name%3Dq%2C+tags%3Dautocomplete%2C+class_name%3DbuttonOnRight%2C+type%3Dtokenized%2C+prefetch_on_focus%3Dtrue%2C+value%3D%22%22%2C+input_log_element_type%3D227%2C+hide_tokens_on_focus%3Dundefined%2C+support_advanced_typeahead%3Dfalse%2C+view_type%3Dguided%2C+populate_on_result_highlight%3Dtrue%2C+search_delay%3D0%2C+search_on_focus%3Dtrue%2C+placeholder%3DDiscover%2C+show_remove_all%3Dtrue)&_=1430685210358";
						}else{
							echo '<br>Bookmark:'.$wp_pinterest_bookmark;
							$url = "https://www.pinterest.com/resource/SearchResource/get/?source_url=%2Fsearch%2Fpins%2F%3Fq%3Darabian&data=%7B%22options%22%3A%7B%22layout%22%3Anull%2C%22places%22%3Afalse%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22arabian%22%2C%22scope%22%3A%22pins%22%2C%22bookmarks%22%3A%5B%22".urlencode($wp_pinterest_bookmark)."%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EHeader()%3ESearchForm()%3ETypeaheadField(enable_recent_queries%3Dtrue%2C+support_guided_search%3Dtrue%2C+resource_name%3DAdvancedTypeaheadResource%2C+name%3Dq%2C+tags%3Dautocomplete%2C+class_name%3DbuttonOnRight%2C+type%3Dtokenized%2C+prefetch_on_focus%3Dtrue%2C+value%3D%22%22%2C+input_log_element_type%3D227%2C+hide_tokens_on_focus%3Dundefined%2C+support_advanced_typeahead%3Dfalse%2C+view_type%3Dguided%2C+populate_on_result_highlight%3Dtrue%2C+search_delay%3D0%2C+search_on_focus%3Dtrue%2C+placeholder%3DDiscover%2C+show_remove_all%3Dtrue)&_=1430685210363";
						}
							
					}	
					
					//replace keyword
					$url = str_replace('arabian', urlencode($keyword), $url);
				}
				  
				//report url
				echo '<br>Pinterest url:<abbr title="'.$url.'">'.substr($url, 0,50).'...</abbr> ';
				 
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($url));
				curl_setopt($this->ch, CURLOPT_ENCODING , "");
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
				  
				//validating reply
				if(stristr($exec, 'request_identifier')){
					//valid reply
						
					//handle pins
					$arr = json_decode($exec);
		
					 
					
					//pins get 
					if ( !in_array('OPT_PT_USER', $camp_opt) && $start ==1 ){
						// first basesearch with no user specified 
						$pins = $arr->resource_data_cache[0]->data->results;
						$new_bookmark = $arr->resource_data_cache[0]->resource->options->bookmarks[0];
						unset($pins[0]);
					}else{
						
						$pins = $arr->resource_response->data;
						$new_bookmark = $arr->resource->options->bookmarks[0];
					}
					
					
					//delete first item of specific boards search
					if(in_array('OPT_PT_USER', $camp_opt) && $cg_pt_user_channel != 'users' && count($pins) > 0){
						unset($pins[0]);
					}
					
					 
					//if reversion order
					if(in_array('OPT_PT_REVERSE', $camp_opt)){
						echo '<br>Reversing order';
						$pins = array_reverse($pins);
					}
					 
					echo '<ol>';
				
					//loop pins
					$i = 0;
					foreach ($pins as $pin){
						
						 
						$itm['pin_url'] =  'https://'.$pin_host.'/pin/'.$pin->id;
						echo '<li>'.$itm['pin_url'];
				
						$itm['pin_id'] = $pin->id;
						$itm['pin_domain'] = $pin->domain;
						$itm['pin_link'] = $pin->link;
						$itm['pin_likes'] = $pin->like_count;
						$itm['repin_count'] = $pin->repin_count;
						$pin_img = current( (Array)$pin->images );
						$itm['pin_img'] = $pin_img->url ;
						$itm['pin_img_width'] = $pin_img->width;
						$itm['pin_img_height'] = $pin_img->height;
						$itm['pin_description'] = $pin->description_html;
						$itm['pin_board_id'] = $pin->board->id;
						$itm['pin_board_url'] = 'https://'. $pin_host . $pin->board->url;
						$itm['pin_board_name'] = $pin->board->name;
						$itm['pin_pinner_username'] = $pin->pinner->username;
						$itm['pin_pinner_full_name'] = $pin->pinner->full_name;
						$itm['pin_pinner_id'] = $pin->pinner->id;
						$itm['pin_title'] = $pin->title;
						 
						$data = base64_encode(serialize ( $itm ));
							 
						if( $this->is_execluded($camp->camp_id, $itm['pin_url']) ){
							echo '<-- Execluded';
							continue;
						}
							
						if ( ! $this->is_duplicate($itm['pin_url']) )  {
							$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (    '{$itm['pin_id']}', '0', '$data' ,'pt_{$camp->camp_id}_$keyword')  ";
							$this->db->query ( $query );
						} else {
							echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						}
				
						echo '</li>';
						$i++;
				
					}
						
					echo '</ol>';
						
					echo '<br>Total '. $i .' pins found & cached';
				
					//check if nothing found so deactivate
					if($i == 0 ){
						echo '<br>No new pins found ';
						echo '<br>Keyword have no more images deactivating...';
						$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
						$this->db->query ( $query );
						$this->deactivate_key($camp->camp_id, $keyword);
							
						//delete bookmark value
						delete_post_meta($camp->camp_id, 'wp_pinterest_bookmark'.md5($keyword));
					}else{
						
						echo '<br>Updating bookmark:'.$new_bookmark;
						update_post_meta($camp->camp_id, 'wp_pinterest_bookmark'.md5($keyword), $new_bookmark ) ;
					}
						
				}else{
					  
						//no valid reply
						echo '<br>No Valid reply for pins search from Pinterest<br>'.$exec;
					  
				}
				
				
			}
			
			function instagram_get_post($camp){
				
				//ini keywords
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				$camp_general=unserialize(base64_decode($camp->camp_general));
				
				//looping keywords
				foreach ( $keywords as $keyword ) {
				
					//update last keyword
					update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
				
					//when valid keyword
					if (trim ( $keyword ) != '') {
				
						//record current used keyword
						$this->used_keyword=$keyword;
				
				
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'it_{$camp->camp_id}_$keyword' and item_status ='0'";
						$res = $this->db->get_results ( $query );
				
						// when no links lets get new links
						if (count ( $res ) == 0) {
				
							//get new links
							$this->instagram_fetch_items( $keyword, $camp );
				
							// getting links from the db for that keyword
							$res = $this->db->get_results ( $query );
						}
				
						//check if already duplicated
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
				
							$t_row = $res[$i];
				
							$t_data =  unserialize (base64_decode( $t_row->item_data) );
				
							$t_link_url=$t_data['item_url'];
				
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Instagram pic ('. $t_data ['item_title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->item_id}' and item_type=  'it_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
				
						}
				
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
				
							// lets process that link
							$ret = $res [$i];
								
							$temp = unserialize ( base64_decode($ret->item_data ));
								
							//generating title   
							if(   @trim($temp['item_title']) == '' ){
									
								if(in_array('OPT_IT_AUTO_TITLE', $camp_opt)){
				
									echo '<br>No title generating...';
				
									$cg_it_title_count = $camp_general['cg_it_title_count'];
									if(! is_numeric($cg_it_title_count)) $cg_it_title_count = 80;
										
									if(function_exists('mb_substr')){
										$newTitle = ( mb_substr( $this->removeEmoji( strip_tags( strip_shortcodes( $temp['item_description'])) ), 0,$cg_it_title_count));
									}else{
										$newTitle = ( substr( $this->removeEmoji( strip_tags( strip_shortcodes( $temp['item_description']) ) ), 0,$cg_it_title_count));
									}
									
										
									$temp['item_title'] = ($newTitle).'...';
				
								}else{
										
									$temp['item_title'] = '(notitle)';
										
								}
				
							}
				
				
							//report link
							echo '<br>Found Link:'.$temp['item_url'] ;
				
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type='it_{$camp->camp_id}_$keyword' ";
							$this->db->query ( $query );
				
							// if cache not active let's delete the cached videos and reset indexes
							if (! in_array ( 'OPT_IT_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='tw_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
						 
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
								
								delete_post_meta($camp->camp_id, 'wp_twitter_next_max_id'.md5($keyword));
							}
				
							
							 
							return $temp;
								
								
						} else {
				
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
				
				
				
			}
			
			function twitter_fetch_items($keyword,$camp ){
				
				//report
				echo "<br>So I should now get some tweets from Twitter for Search :" . $keyword;
		
				//verify twitter token 
				$wp_automatic_tw_consumer = trim( get_option('wp_automatic_tw_consumer',''));
				$wp_automatic_tw_secret = trim( get_option('wp_automatic_tw_secret',''));
				
				if( ($wp_automatic_tw_consumer) == '' || $wp_automatic_tw_consumer == ''){
					echo '<br>Twitter consumer key and secret key are required, please visit the settings page and add it';
					return false;
				}
				 
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				 
				
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
				
				if ($start == - 1) {
					echo '<- exhausted keyword';
				
					if( ! in_array( 'OPT_IT_CACHE' , $camp_opt )){
						$start =1;
						echo '<br>Cache disabled resetting index to 1';
					}else{
				
						//check if it is reactivated or still deactivated
						if($this->is_deactivated($camp->camp_id, $keyword)){
							$start =1;
						}else{
							//still deactivated
							return false;
						}
				
					}
				
				}
				
				
				//generating token if not exists
				$wp_automatic_tw_token = get_option('wp_automatic_tw_token','');
				
				if(trim($wp_automatic_tw_token) == ''){
					
					echo '<br>Generating a new twitter access token...';
					
					$concated = urlencode($wp_automatic_tw_consumer) . ':'. urlencode($wp_automatic_tw_secret);
					
					$concatedBase64 = base64_encode($concated);
					 
					//curl get
					$x='error';
					$url='https://api.twitter.com/oauth2/token';
					
					curl_setopt($this->ch,CURLOPT_HTTPHEADER,array("Authorization:Basic $concatedBase64" , "Content-Type:application/x-www-form-urlencoded;charset=UTF-8."));
					
					//curl post
					curl_setopt($this->ch, CURLOPT_URL, $url);
					curl_setopt($this->ch, CURLOPT_POST, true);
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
					$exec=curl_exec($this->ch);
					$x=curl_error($this->ch);
					
					if(stristr($exec, 'bearer')){
						
						$token_json = json_decode($exec);
						$wp_automatic_tw_token = $token_json->access_token;
						
						if(trim($wp_automatic_tw_token) == ''){
							echo '<br>Can not extract twitter token from twitter response:'.$exec;
						}else{
							update_option('wp_automatic_tw_token', $wp_automatic_tw_token);
						}
						
						
					}else{
						echo '<br>Response from twitter does not contain the expected token:'.$exec;
						return false;
					}
					
				}
				
				//good we now have a valid twitter token
				echo ' index:' . $start;
				
				// update start index to start+1
				$nextstart = $start + 1;
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
				
		 
		 		
				//building the twitter url
				$url='https://api.twitter.com/1.1/search/tweets.json?q='.urlencode(trim($keyword));
	
				//language 
				if(in_array('OPT_TW_COUNTRY', $camp_opt)){
					
					$cg_tw_lang = $camp_general['cg_tw_lang'];
					
					if(trim($cg_tw_lang) != ''){
						$url.='&lang='.trim($cg_tw_lang);
					}
				}
					 
				//pagination
				// get requrest url from the zero index
				if( $start == 1 ){
				
					//use first base query
						
				
				}else{
						
					//not first page get the bookmark
					$wp_tw_next_max_id = get_post_meta ($camp->camp_id,'wp_twitter_next_max_id'.md5($keyword),1);
						
					if(trim($wp_tw_next_max_id) == ''){
						echo '<br>No new page max id';
							
					}else{
						echo '<br>max_id:'.$wp_tw_next_max_id;
						$url = $url ."&max_id=".$wp_tw_next_max_id ;
					}
						
				}
				
				
				
				//report url
				echo '<br>Twitter url:'.$url;
				 
				//skip ssl
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
				
				//authorize
				curl_setopt($this->ch,CURLOPT_HTTPHEADER,array("Authorization: Bearer $wp_automatic_tw_token"));
				
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($url));
					 
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
				
		 
				//validating reply
				if(stristr($exec, 'search_metadata')){
				//valid reply
		
				//handle pins
				$arr = json_decode($exec);
		
				$items = $arr->statuses;
		
				 
				//reverse 
				if(in_array('OPT_PT_REVERSE', $camp_opt)){
					echo '<br>Reversing order';
					$items = array_reverse($items);
				}
				 
				echo '<ol>';
				
					//loop pins
							$i = 0;
							foreach ($items as $item){
								
								 	
								//report 
								echo '<li>http://twitter.com/statuses/'.$item->id_str;
								
							 	
								 //build item
								 $itm['item_id']  = $item->id;
								 $itm['item_url'] = 'http://twitter.com/statuses/'.$item->id_str ;
								 $itm['item_description'] = $item->text;
								 $itm['item_description'] =  $this->hyperlink_this( $itm['item_description']);
								 
								 //check images
								 if(isset($item->entities->media[0])){
								 	
								 	$media_img =$item->entities->media[0];
								 	
								 	if($media_img->type == 'photo'){
								 		//good let's append it
								 		$itm['item_description'] = '<img src="'.$media_img->media_url.'" /><br><br>'.$itm['item_description'];
								 	}
 								 	
								 }
								 
								 $itm['item_retweet_count'] = $item->retweet_count;
								 $itm['item_favorite_count'] = $item->favorite_count;
								 $itm['item_author_id'] = $item->user->id_str;
								 $itm['item_author_name'] = $item->user->name;
								 $itm['item_author_description'] = $item->user->description;
								 $itm['item_author_url'] = $item->user->url;
								 $itm['item_created_at'] = $item->created_at;
								  
								 
								$data = base64_encode(serialize ( $itm ));
				
								if( $this->is_execluded($camp->camp_id, $itm['item_url']) ){
								echo '<-- Execluded';
								continue;
							}
								
							if ( ! $this->is_duplicate($itm['item_url']) )  {
							$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (    '{$itm['item_id']}', '0', '$data' ,'tw_{$camp->camp_id}_$keyword')  ";
							$this->db->query ( $query );
							} else {
							echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
							}
				
							echo '</li>';
						$i++;
				
					}
				
					echo '</ol>';
				
							echo '<br>Total '. $i .' Tweets found & cached';
				
						//check if nothing found so deactivate
						if($i == 0 ){
								echo '<br>No new tweets found ';
								echo '<br>Keyword have no more tweets deactivating...';
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
								$this->db->query ( $query );
								$this->deactivate_key($camp->camp_id, $keyword);
									
								//delete bookmark value
								delete_post_meta($camp->camp_id, 'wp_twitter_next_max_id'.md5($keyword));
							}else{
				
								//get max id 
								if(isset($arr->search_metadata->next_results)){
								
									//extracting max id 
									$next_res = $arr->search_metadata->next_results;
									
									preg_match('{max_id\=(\d*?)\&}', $next_res ,$matchmax);
									 
									$next_max = $matchmax[1];
									
									if(trim($next_max) !=''){

										echo '<br>Updating max_id:'.$next_max;
										update_post_meta($camp->camp_id, 'wp_twitter_next_max_id'.md5($keyword), $next_max ) ;
										
									}else{
										echo '<br>Can not extract next max';
									}
									
									
									
								}else{
									echo '<br>No pagination found deleting next page index';
									delete_post_meta($camp->camp_id, 'wp_twitter_next_max_id'.md5($keyword));
								}
								
							}
				
							}else{
					
						//no valid reply
								echo '<br>No Valid reply for twitter search <br>'.$exec;
									
							}
				
				
				
			}
			
			//Twitter 
			function twitter_get_post($camp){
			
				//ini keywords
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				$camp_general=unserialize(base64_decode($camp->camp_general));
			
				//looping keywords
				foreach ( $keywords as $keyword ) {
			
					//update last keyword
					update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
			
					//when valid keyword
					if (trim ( $keyword ) != '') {
			
						//record current used keyword
						$this->used_keyword=$keyword;
			
			
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'tw_{$camp->camp_id}_$keyword' and item_status ='0'";
						$res = $this->db->get_results ( $query );
			
						// when no links lets get new links
						if (count ( $res ) == 0) {
			
							//get new links
							$this->twitter_fetch_items( $keyword, $camp );
			
							// getting links from the db for that keyword
							$res = $this->db->get_results ( $query );
						}
						 
						//check if already duplicated
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
			
							$t_row = $res[$i];
			
							$t_data =  unserialize (base64_decode( $t_row->item_data) );
			
							$t_link_url=$t_data['item_url'];
			
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Tweet ('. $t_data ['item_title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->item_id}' and item_type=  'it_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
			
						}
			
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
			
							// lets process that link
							$ret = $res [$i];
			
							$temp = unserialize ( base64_decode($ret->item_data ));
			
							//generating title
							if(   @trim($temp['item_title']) == '' ){
									
								if(in_array('OPT_IT_AUTO_TITLE', $camp_opt)){
			
									echo '<br>No title generating...';
			
									$cg_it_title_count = $camp_general['cg_it_title_count'];
									if(! is_numeric($cg_it_title_count)) $cg_it_title_count = 80;
			
									if(function_exists('mb_substr')){
										$newTitle = ( mb_substr( $this->removeEmoji( strip_tags( $temp['item_description']) ), 0,$cg_it_title_count));
									}else{
										$newTitle = ( substr( $this->removeEmoji( strip_tags( $temp['item_description']) ), 0,$cg_it_title_count));
									}
									

			
									$temp['item_title'] = ($newTitle).'...';
									
									echo '<br>Generated title:'.$temp['item_title'];
									
			
								}else{
			
									$temp['item_title'] = '(notitle)';
			
								}
			
							}
			
			
							//report link
							echo '<br>Found Link:'.$temp['item_url'] ;
			
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type='tw_{$camp->camp_id}_$keyword' ";
							$this->db->query ( $query );
			
							// if cache not active let's delete the cached items and reset indexes
							if (! in_array ( 'OPT_IT_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='tw_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
			
			
			
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
			
								delete_post_meta($camp->camp_id, 'wp_instagram_next_max_id'.md5($keyword));
							}
			

							//if card OPT_TW_CARDS
							if(in_array('OPT_TW_CARDS', $camp_opt)){
								
								$item_id = $temp['item_id'];
								
								//getting card embed https://api.twitter.com/1/statuses/oembed.json?url=https://twitter.com/zzz/status/463440424141459456
								
								echo '<br>Getting embed code from twitter...';
								
								//curl get
								$x='error';
								$url='https://api.twitter.com/1/statuses/oembed.json?url=https://twitter.com/zzz/status/463440424141459456';
								$url= str_replace('463440424141459456', $item_id, $url);
								
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($url));
							 
								$exec=curl_exec($this->ch);
								$x=curl_error($this->ch);
								
								if(stristr($exec, 'widgets.js')){

									$json_embed = json_decode($exec);
									
									$embed_html = $json_embed->html;
									
									if(trim($embed_html) !=''){
										$temp['item_description']=$embed_html;
									}else{
										echo '<br>Can not extract embed html.';
									}
									
									
								}else{
									echo '<br>Non expected embed reply.';
								}
								 
							  
								
								
								
							}
							
							
							return $temp;
			
			
						} else {
			
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
			
			
			
			}
			
			
			function instagram_fetch_items($keyword,$camp ){
			
				//report
				echo "<br>So I should now get some pics from Instagram for keyword :" . $keyword;
			
				//verify instagram app id
				$wp_automatic_it_tocken = trim( get_option('wp_automatic_it_tocken',''));
				if(trim($wp_automatic_it_tocken) == ''){
					echo '<br>Instagram APP client ID is required, please visit the settings page and add it';
					return false;
				}
					
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
					
			
				// get start-index for this keyword
				$query = "select keyword_start ,keyword_id from {$this->wp_prefix}automatic_keywords where keyword_name='$keyword' and keyword_camp={$camp->camp_id}";
				$rows = $this->db->get_results ( $query );
				$row = $rows [0];
				$kid = $row->keyword_id;
				$start = $row->keyword_start;
				if ($start == 0)
					$start = 1;
			
				if ($start == - 1) {
					echo '<- exhausted keyword';
			
					if( ! in_array( 'OPT_IT_CACHE' , $camp_opt )){
						$start =1;
						echo '<br>Cache disabled resetting index to 1';
					}else{
			
						//check if it is reactivated or still deactivated
						if($this->is_deactivated($camp->camp_id, $keyword)){
							$start =1;
						}else{
							//still deactivated
							return false;
						}
			
					}
			
				}
			
			
				echo ' index:' . $start;
			
				// update start index to start+1
				$nextstart = $start + 1;
				$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = $nextstart where keyword_id=$kid ";
				$this->db->query ( $query );
			
					
				 
				//if specific user posting
				if(in_array('OPT_IT_USER', $camp_opt)){
			
					$cg_it_user = $camp_general['cg_it_user'];
					echo '<br>Specific user:'.$cg_it_user;
			
					//check if is a numeric id or we will need to grap the id
					$cg_it_user_numeric = get_post_meta($camp->camp_id,'wp_instagram_user_'.trim($cg_it_user),1);
						
					if(trim($cg_it_user_numeric) == ''){
						echo '<br>Getting numeric user ID from Instagram..';
			
						$uurl = "https://api.instagram.com/v1/users/search?q=".urlencode(trim($cg_it_user))."&client_id=".$wp_automatic_it_tocken;
			
						echo '<br>User lookup url:'.$uurl;
			
						//curl get
							
						curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
						curl_setopt($this->ch, CURLOPT_URL, trim($uurl));
						$exec=curl_exec($this->ch);
						$x=curl_error($this->ch);
						$uarr = json_decode($exec);
			
						if(stristr($exec, 'meta')){
							echo '<br>Valid instagram reply';
								
							foreach ($uarr->data as $user){
			
								if($user->username == trim($cg_it_user)){
									$cg_it_user_numeric = $user->id;
									echo '<br>Found ID:'.$cg_it_user_numeric;
									update_post_meta($camp->camp_id, 'wp_instagram_user_'.trim($cg_it_user), $cg_it_user_numeric);
								}
			
							}
			
								
						}else{
								
							echo '<br>Non valid instagarm reply';
							return false;
								
						}
			
					}//no vlaid nueric gnerate
						
					//build url;
					if(is_numeric($cg_it_user_numeric)){
							
						//build  url
						$url = "https://api.instagram.com/v1/users/$cg_it_user_numeric/media/recent/?client_id=".$wp_automatic_it_tocken;
							
							
					}else{
						echo '<br>can not find valid numeric id for the user .. exiting';
						return;
					}
						
					 
				}else{
			
					//prepare keyword
					$qkeyword = str_replace(' ', '', $keyword);
						
					$url ="https://api.instagram.com/v1/tags/".urlencode($qkeyword)."/media/recent?client_id=".$wp_automatic_it_tocken;
						
						
			
				}
			
					
				//pagination
				// get requrest url from the zero index
				if( $start == 1 ){
			
					//use first base query
			
			
				}else{
			
					//not first page get the bookmark
					$wp_instagram_next_max_id = get_post_meta ($camp->camp_id,'wp_instagram_next_max_id'.md5($keyword),1);
			
					if(trim($wp_instagram_next_max_id) == ''){
						echo '<br>No new page max id';
							
					}else{
						echo '<br>max_id:'.$wp_instagram_next_max_id;
						$url = $url ."&max_id=".$wp_instagram_next_max_id ;
					}
			
				}
			
			
			
				//report url
				echo '<br>Instagram url:'.$url;
					
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($url));
			
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
			
				//validating reply
				if(stristr($exec, 'meta')){
					//valid reply
			
					//handle pins
					$arr = json_decode($exec);
			
					$items = $arr->data;
			
						
					//reverse
					if(in_array('OPT_PT_REVERSE', $camp_opt)){
						echo '<br>Reversing order';
						$items = array_reverse($items);
					}
						
					echo '<ol>';
			
					//loop pins
					$i = 0;
					foreach ($items as $item){
			
						//report
						echo '<li>'.$item->link;
			
						//build item
						$itm['item_id']  = $item->id;
						$itm['item_url'] = $item->link;
						$itm['item_description'] = $item->caption->text;
			
						//if video embed it
						if($item->type == 'video'){
							echo '<br>Found a video embeding...';
							
							if ( in_array('OPT_IT_VID_TOP', $camp_opt) ){
								$itm['item_description']= '[embed]' . $item->videos->standard_resolution->url .'[/embed]' . $itm['item_description'];
							}else{
								$itm['item_description'].= '[embed]' . $item->videos->standard_resolution->url .'[/embed]';
							}
							
							
						
						}
							
						$itm['item_img'] = $item->images->standard_resolution->url;
						$itm['item_img_width'] = $item->images->standard_resolution->width;
						$itm['item_img_height'] = $item->images->standard_resolution->height;
						$itm['item_user_id'] = $item->user->id;
						$itm['item_user_username'] = $item->user->username;
							
						//full name
						$itm['item_user_name'] = $item->user->full_name;
						if(trim($item->user->full_name) != ''){
							$itm['item_user_name'] = $item->user->full_name;
						}else{
							$itm['item_user_name'] = $item->user->username;
						}
							
						$itm['item_user_profile_pic'] = $item->user->profile_picture;
						$itm['item_created_time'] = $item->caption->created_time;
							
						//item date
						$itm['item_created_date'] = date('Y-m-d H:i:s', $item->caption->created_time ) ;
							
						$itm['item_likes_count'] = $item->likes->count;
							
							
							
						$itm['item_tags'] = implode(',', $item->tags);
							
						//comments postponed
						$commentsArray = array();
							
						//check if post comments is active
						if(in_array('OPT_IT_COMMENT', $camp_opt)){
							$commentsArray = $item->comments->data;
			
			
								
						}
							
						$itm['item_comments'] = $commentsArray;
			
							
							
						$data = base64_encode(serialize ( $itm ));
			
						if( $this->is_execluded($camp->camp_id, $itm['item_url']) ){
							echo '<-- Execluded';
							continue;
						}
			
						if ( ! $this->is_duplicate($itm['item_url']) )  {
							$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (    '{$itm['item_id']}', '0', '$data' ,'it_{$camp->camp_id}_$keyword')  ";
							$this->db->query ( $query );
						} else {
							echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						}
			
						echo '</li>';
						$i++;
			
					}
			
					echo '</ol>';
			
					echo '<br>Total '. $i .' pics found & cached';
			
					//check if nothing found so deactivate
					if($i == 0 ){
						echo '<br>No new pics found ';
						echo '<br>Keyword have no more images deactivating...';
						$query = "update {$this->wp_prefix}automatic_keywords set keyword_start = -1 where keyword_id=$kid ";
						$this->db->query ( $query );
						$this->deactivate_key($camp->camp_id, $keyword);
							
						//delete bookmark value
						delete_post_meta($camp->camp_id, 'wp_instagram_next_max_id'.md5($keyword));
					}else{
			
						//get max id
						if(isset($arr->pagination->next_max_id)){
			
							echo '<br>Updating max_id:'.$arr->pagination->next_max_id;
							update_post_meta($camp->camp_id, 'wp_instagram_next_max_id'.md5($keyword), $arr->pagination->next_max_id ) ;
								
						}else{
							echo '<br>No pagination found deleting next page index';
							delete_post_meta($camp->camp_id, 'wp_instagram_next_max_id'.md5($keyword));
						}
			
					}
			
				}else{
						
					//no valid reply
					echo '<br>No Valid reply for instagram search <br>'.$exec;
						
				}
			
			
			
			}
				
			
			/*
			 * ---* flicker post ---
			 */
			function flicker_get_post($camp) {
				$api_key = get_option ( 'wp_automatic_flicker', '' );
				
				if (trim ( $api_key ) == '') {
					echo '<br>Flicker Api key required visit settings and add it ';
					exit ();
				}
				
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				
				foreach ( $keywords as $keyword ) {
					 
					//update last keyword
					update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
					
					if (trim ( $keyword ) != '') {
						
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'fl_{$camp->camp_id}_$keyword' and item_status ='0'";
						$this->used_keyword=$keyword;
						
						$res = $this->db->get_results ( $query );
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							$this->flicker_fetch_items ( $keyword, $camp );
							// getting links from the db for that keyword
							
							$res = $this->db->get_results ( $query );
						}
						
						
						//check if already duplicated
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
						
							$t_row = $res[$i];
							$t_data =  unserialize ( $t_row->item_data );
							$t_link_url='http://flicker.com/' . $t_data ['owner'] . '/' . $t_row->item_id;
						
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>Flicker image ('. $t_data ['title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->item_id}' and item_type=  'fl_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
						
						}
						
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
							
							// lets process that link
							$ret = $res [$i];
							
							$data = unserialize ( $ret->item_data );
							
							$temp ['img_title'] = $data ['title'];
							$temp ['img_author'] = $data ['owner'];
							$temp ['img_src'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}.jpg";
							$temp ['img_src_s'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_s.jpg";
							$temp ['img_src_q'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_q.jpg";
							$temp ['img_src_t'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_t.jpg";
							$temp ['img_src_m'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_m.jpg";
							$temp ['img_src_n'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_n.jpg";
							$temp ['img_src_z'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_z.jpg";
							$temp ['img_src_c'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_c.jpg";
							$temp ['img_src_b'] = "http://farm{$data['farm']}.staticflickr.com/{$data['server']}/{$data['id']}_{$data['secret']}_b.jpg";
							$temp ['img_link'] = 'http://flicker.com/' . $data ['owner'] . '/' . $ret->item_id;
							
							echo '<br>Found Link:<a href="'.$temp['img_link'].'">'.$temp ['img_title'].'</a>';
							
							// getting photo description
							// curl get
							$x = 'error';
							$url = "https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=$api_key&photo_id={$ret->item_id}&format=php_serial";
							curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
							curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
							while ( trim ( $x ) != '' ) {
								$exec = curl_exec ( $this->ch );
								$x = curl_error ( $this->ch );
							}
							
							$exec = unserialize ( $exec );
							
							if (! is_array ( $exec )) {
								echo '<br> Not valid array ';
							} else {
								
								$temp ['img_author_name'] = $exec ['photo'] ['owner'] ['username'];
								$temp ['img_description'] = $exec ['photo'] ['description'] ['_content'];
								$temp ['img_date_posted'] = date ( 'Y-m-d H:i:s', $exec ['photo'] ['dates'] ['posted'] );
								$temp ['img_date_taken'] = $exec ['photo'] ['dates'] ['taken'];
								$temp ['img_viewed'] = $exec ['photo'] ['views'];
								
								$tags = '';
								foreach ( $exec ['photo'] ['tags'] ['tag'] as $tag ) {
									$tags = $tags . ' , ' . $tag ['raw'];
								}
								
								$temp ['img_tags'] = $tags;
							}
							
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type='fl_{$camp->camp_id}_$keyword' ";
							
							$this->db->query ( $query );
							
							// if cache not active let's delete the cached videos and reset indexes
							if (! in_array ( 'OPT_FL_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='fl_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
								
								// reset index
								$query = "update {$this->wp_prefix}automatic_keywords set keyword_start =1 where keyword_camp={$camp->camp_id}";
								$this->db->query ( $query );
							}
						
							
							return $temp;
						} else {
							
							echo '<br>No links found for this keyword';
						}
					} // if trim
				} // foreach keyword
			}
			
			/*
			 * ebay fetch items
			 */
			function ebay_fetch_items($keyword, $camp) {
				
				//ref:https://docs.google.com/spreadsheet/ccc?key=0Auf5oUAL4RXDdHhiSFpUYjloaUFOM0NEQnF2d1FodGc&hl=en_US
				
				echo "<br>so I should now get some items from ebay for keyword :" . $keyword;
				
				$campaignid = get_option ( 'wp_automatic_ebay_camp', '' );
				
				// ini options
				$camp_opt = unserialize ( $camp->camp_options );
				if( stristr($camp->camp_general, 'a:') ) $camp->camp_general=base64_encode($camp->camp_general);
				$camp_general = unserialize ( base64_decode( $camp->camp_general ) );
				$camp_general=array_map('stripslashes', $camp_general);
				
				// prepare the link
				$elink = 'http://rest.ebay.com/epn/v1/find/item.rss?';
				
				// ebay site
				$elink .= 'programid=' . $camp_general ['cg_eb_site'];
				
				// campaign id
				if (trim ( $campaignid ) != '') {
					$elink .= '&campaignid=' . $campaignid;
				}else{
					$elink .= '&campaignid=1343253';
				}
				
				
				if(in_array('OPT_EBAY_CUSTOM', $camp_opt) && trim($camp_general['cg_ebay_custom']) != '' ){
					
					$elink .= '&categoryId1=' . $camp_general['cg_ebay_custom'];
					
				}else{
					
					// ebay category cg_eb_cat
					$cg_eb_cat = $camp_general ['cg_eb_cat'];
			
					if (trim ( $cg_eb_cat != '0' )) {
						$elink .= '&categoryId1=' . $cg_eb_cat;
					}
				}
				
				
				// if user
				if (in_array ( 'OPT_EB_USER', $camp_opt )) {
					$cg_eb_user = $camp_general ['cg_eb_user'];
					$elink .= '&sellerId1=' . trim($cg_eb_user) ;
					
					if (in_array ( 'OPT_EB_FULL', $camp_opt )) {
						echo '<br>No filtering add all ..';
						$elink .= '&keyword=';
					} else {
						// keyword
						$elink .= '&keyword=' . urlencode($keyword);
					}
				} else {
					// keyword
					$elink .= '&keyword=' . urlencode($keyword);
				}
				
				// listing type
				$elink .= '&listingType1=' . $camp_general ['cg_eb_listing'];
				
				// listing order cg_eb_order
				$elink .= '&sortOrder=' . $camp_general ['cg_eb_order'];
				
				// price range
				if (in_array ( 'OPT_EB_PRICE', $camp_opt )) {
					$cg_eb_min = $camp_general ['cg_eb_min'];
					$cg_eb_max = $camp_general ['cg_eb_max'];
					
					// min
					if (trim ( $cg_eb_min ) != '')
						$elink .= '&minPrice=' . trim($cg_eb_min);
						
						// max
					if (trim ( $cg_eb_max ) != '')
						$elink .= '&maxPrice=' . trim($cg_eb_max); 
				}
				
				// topRatedSeller=true
				if (in_array ( 'OPT_EB_TOP', $camp_opt )) {
					$elink .= '&topRatedSeller=true';
				}
				
				// OPT_EB_SHIP
				if (in_array ( 'OPT_EB_SHIP', $camp_opt )) {
					$elink .= '&freeShipping=true';
				}
				
				// OPT_EB_DESCRIPTION
				if (in_array ( 'OPT_EB_DESCRIPTION', $camp_opt )) {
					$elink .= '&descriptionSearch=true';
				}
				
				echo '<br>Link:' . $elink;
				 
				// curl get
				$x = 'error';
				$url = $elink;
				curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
				curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
				 
		 
				$exec = curl_exec ( $this->ch );
				$x = curl_error ( $this->ch );
		 
		 
				// titles
				preg_match_all ( '/<item><title>(.*?)<\/title>/', $exec, $matches );
				$titles = ($matches [1]);
				
				// imgs
				preg_match_all ( '/src=\'(.*?)\'/', $exec, $matches );
				$imgs = ($matches [1]);
				
				// links
				preg_match_all ( '/guid><link>(.*?)<\/link>/', $exec, $matches );
				$links = ($matches [1]);
				
				// ids <guid>
				preg_match_all ( '/guid>(.*?)<\/guid>/', $exec, $matches );
				$ids = ($matches [1]);
				
				// pubDate
				preg_match_all ( '/pubDate>(.*?)<\/pubDate>/', $exec, $matches );
				$pubdates = ($matches [1]);
				
				// bids count BidCount>0</e
				preg_match_all ( '/BidCount>(.*?)<\/e\:BidCount/', $exec, $matches );
				$bids = ($matches [1]);
				
				// current price <e:CurrentPrice>79.99</e:CurrentPrice>
				preg_match_all ( '/CurrentPrice>(.*?)<\/e\:CurrentPrice/', $exec, $matches );
				$prices = ($matches [1]);
				
				// bin BuyItNowPrice
				preg_match_all ( '/BuyItNowPrice>(.*?)<\/e\:BuyItNowPrice/', $exec, $matches );
				$bins = ($matches [1]);
				
				// listing end time ListingEndTime
				preg_match_all ( '/ListingEndTime>(.*?)<\/e\:ListingEndTime/', $exec, $matches );
				$ends = ($matches [1]);
				
				// paymentmethod PaymentMethod
				preg_match_all ( '/PaymentMethod>(.*?)<\/e\:PaymentMethod/', $exec, $matches );
				$payment = ($matches [1]);
				
				if (count ( $titles ) > 0) {
				} else {
					
					echo '<br>eBay did not reuturn valid results ';
				}
				
				$i = 0;
				echo '<ol>';
				foreach ( $titles as $title ) {
					
					echo '<li>Link:'.$links [$i];
					
					$id = $ids [$i];
					
					$itm ['item_id'] = $ids [$i];
					$itm ['item_title'] = $titles [$i];
					$itm ['item_img'] = $imgs [$i];
					$itm ['item_link'] = str_replace ( 'amp;', '', $links [$i] );
					$item_link = $itm ['item_link']; 
					$itm ['item_publish_date'] = str_replace ( 'T', ' ', str_replace ( 'Z', ' ', $pubdates [$i] ) );
					$itm ['item_publish_date'] = str_replace ( '.000', '', $itm ['item_publish_date'] );
					$itm ['item_bids'] = $bids [$i];
					$itm ['item_price'] = $prices [$i];
					$itm ['item_bin'] = $bins [$i];
					$itm ['item_end_date'] = str_replace ( 'T', ' ', str_replace ( 'Z', ' ', $ends [$i] ) );
					$itm ['item_end_date'] = str_replace ( '.000', '', $itm ['item_end_date'] );
					$itm ['item_payment'] = $payment [$i];
					
					
					
					$data = base64_encode(serialize ( $itm ));
					
					
					if( $this->is_execluded($camp->camp_id, $item_link) ){
						echo '<-- Execluded';
						continue;
					}
					
					if ( ! $this->is_duplicate($item_link) )  {
						$query = "INSERT INTO {$this->wp_prefix}automatic_general ( item_id , item_status , item_data ,item_type) values (    '$id', '0', '$data' ,'eb_{$camp->camp_id}_$keyword')  ";
						$this->db->query ( $query );
					} else {
						echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
					}
					
					$i ++;
					
					echo '</li>';
					
				}
				
				echo '</ol>';
				
				
				echo '<br>' . $i . ' items from ebay';
			}
			
			/*
			 * ebay get post
			 */
			function ebay_get_post($camp) {
				$camp_opt = unserialize ( $camp->camp_options );
				$keywords = explode ( ',', $camp->camp_keywords );
				
				foreach ( $keywords as $keyword ) {
					
					if (trim ( $keyword ) != '') {
						 
						//update last keyword
						update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));
						
						$this->used_keyword = $keyword;
						
						// getting links from the db for that keyword
						$query = "select * from {$this->wp_prefix}automatic_general where item_type=  'eb_{$camp->camp_id}_$keyword' and item_status ='0'";
						$res = $this->db->get_results ( $query );
						 
						
						// when no links lets get new links
						if (count ( $res ) == 0) {
							$this->ebay_fetch_items ( $keyword, $camp );
							// getting links from the db for that keyword
							
							$res = $this->db->get_results ( $query );
						}
						
						//check duplicate
						//deleting duplicated items
						for($i=0;$i< count($res);$i++){
						
							$t_row = $res[$i];
							$t_data =  unserialize ( base64_decode($t_row->item_data) );
							 
							
							$t_link_url=$t_data ['item_link'];
						
							if( $this->is_duplicate($t_link_url) ){
									
								//duplicated item let's delete
								unset($res[$i]);
									
								echo '<br>eBay item ('. $t_data['item_title'] .') found cached but duplicated <a href="'.get_permalink($this->duplicate_id).'">#'.$this->duplicate_id.'</a>'  ;
									
								//delete the item
								$query = "delete from {$this->wp_prefix}automatic_general where item_id='{$t_row->item_id}' and item_type=  'eb_{$camp->camp_id}_$keyword'";
								$this->db->query ( $query );
									
							}else{
								break;
							}
						
						}
						
						// check again if valid links found for that keyword otherwise skip it
						if (count ( $res ) > 0) {
							
							// lets process that link
							$ret = $res [$i];
						 
							$data = unserialize ( base64_decode($ret->item_data) );
							
							// get item big image and description
							// curl get
							$x = 'error';
							$url = $data ['item_link'];
							
							echo '<br>Found Link:'.$url;
							
							curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
							curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
							
							$exec = curl_exec ( $this->ch );
							
							// extract img itemprop="image" src="
							preg_match_all ( '/itemprop\="image" src="(.*?)"/', $exec, $matches );
							$img = '';
							$img = $matches [1] [0];
							
							if (trim ( $img ) != '') {
								$data ['item_img'] = $img;
							}
							
							// extract description
							$data['item_desc']=$data['item_title'];
							$data['item_images'] = '<img src="'.$data['item_img'] .'" />';
							
							// update the link status to 1
							$query = "update {$this->wp_prefix}automatic_general set item_status='1' where item_id='$ret->item_id' and item_type=  'eb_{$camp->camp_id}_$keyword'";
							
							$this->db->query ( $query );
							
							// if cache not active let's delete the cached items and reset indexes
							if (! in_array ( 'OPT_EB_CACHE', $camp_opt )) {
								echo '<br>Cache disabled claring cache ...';
								$query = "delete from {$this->wp_prefix}automatic_general where item_type='eb_{$camp->camp_id}_$keyword' and item_status ='0'";
								$this->db->query ( $query );
							}
						 
							
							//if full description and all images needed extract them
							if(in_array('OPT_EB_FULL_DESC', $camp_opt) || in_array('OPT_EB_FULL_IMG', $camp_opt)){
								
								echo '<br>Extracting full description and images from original product page...';
								
								//building url
								
								//extract ebay site ext
								$item_link=$data['item_link'] ;
								$item_id  =$data['item_id'];
								preg_match('{ebay\.(.*?)/}', $item_link,$matches);
								if(isset($matches[1]) && trim($matches[1]) != ''){
									$ext = $matches[1];
									echo '<br>Found ebay ext:'.$ext;
								}else{
									$ext='com';
									echo '<br> can not extract ext setting it to com';
								}
								
								$the_link = "http://www.ebay.$ext/itm/www/$item_id";
								
								echo '<br>Item link with desc '.$the_link;
		
								 
								
								//curl get
								$x='error';
								$url=$the_link;
								curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
								curl_setopt($this->ch, CURLOPT_URL, trim($url));
							    $exec=curl_exec($this->ch);
								$x=curl_error($this->ch);
		
								if(trim($exec) != ''){
									
									
									if(in_array('OPT_EB_FULL_DESC', $camp_opt)){
										require_once 'inc/simple_html_dom.php';
										
										$original_html = str_get_html($exec);
										
										if(method_exists($original_html, 'find')){
											
											 
											$ret = $original_html->find('*[id=desc_div]');
										
											$extract='';
										
											foreach ($ret as $itm ) {
												$extract = $extract . $itm->outertext ;
											}
										
											if(trim($extract) == ''){
												echo '<br>Nothing found to extract for this rule';
											}else{
												echo '<br>Rule desc extracted ' . strlen($extract) .' charchters ';
												
												$extract = str_replace('height="10000"', '', $extract);
												
												$data['item_desc'] = $extract;
												
											}
											
											//specification box 
											if(in_array('OPT_EB_FULL_DESC_SPEC', $camp_opt)){
													
												$ret2 = $original_html->find('*[class=itemAttr]');
													
												$extract2='';
													
												foreach ($ret2 as $itm ) {
													$extract2 = $extract2 . $itm->outertext ;
												}
												
												if(trim($extract2) == ''){
													echo '<br>Nothing found to extract for item specs';
												}else{
													echo '<br>Rule specs extracted ' . strlen($extract2) .' charchters ';
													
													$extract2 = preg_replace('{<span id="hiddenContent.*?td>}', '</td>', $extract2);
													
													$data['item_desc'] = $extract2.$data['item_desc'];
														
												}	
												
												//prodDetailDesc
												$ret3 = $original_html->find('*[class=prodDetailDesc]');
													
												$extract3='';
													
												foreach ($ret3 as $itm ) {
													$extract3 = $extract3 . $itm->outertext ;
												}
												
												if(trim($extract3) == ''){
													echo '<br>Nothing found to extract for item prodDetailDesc';
												}else{
													echo '<br>Rule prodDetailDesc extracted ' . strlen($extract3) .' charchters ';
														
													$extract3 = preg_replace('{<span id="hiddenContent.*?td>}', '</td>', $extract3);
														
													$data['item_desc'] = $extract3.$data['item_desc'];
												
												}
													
												
														
											}
												
										}else{
											echo '<br>Simple html dom can not load the html';
										}
									
									}// OPT_EB_FULL_DESC 
									
									//extracting images
									if( in_array('OPT_EB_FULL_IMG', $camp_opt) )  {
										
										preg_match_all('{tdThumb.*?src="(.*?)"}s', $exec,$imgs_matches);
										
										if(isset($imgs_matches[1]) && count($imgs_matches[1]) != 0 ){
										
											$imgs=$imgs_matches[1];
											
											//remove duplicate
											$imgs=array_unique($imgs);
											
											//inlarge images
											$imgs=preg_replace('{_\d*?\.}', '_1.', $imgs);
											
											//report
											echo '<br>'.count($imgs) .' images extracted from original product page';
										 	
											//form html
											$data['item_images'] = $imgs;
		
										 	 
											
										}else{
											echo '<br>did not find additional images from original source';
										}
										
									}//OPT_EB_FULL_IMG
									
								}else{
									echo '<br>Can not load original product page';
								}
								
								
								 
								
								
							}
							
							return $data;
						} else {
							
							echo '<br>No links found for this criteria';
						}
					} // if trim
				} // foreach keyword
			}
			
			/*
			 * ---* Spin function that calls TBS ---
			 */
			function spin($html) {
				
				
				
				$url = 'http://thebestspinner.com/api.php';
				
				// $testmethod = 'identifySynonyms';
				$testmethod = 'replaceEveryonesFavorites';
				
				// Build the data array for authenticating.
				
				$data = array ();
				$data ['action'] = 'authenticate';
				$data ['format'] = 'php'; // You can also specify 'xml' as the format.
				                          
				// The user credentials should change for each UAW user with a TBS account.
				$tbs_username = get_option ( 'wp_automatic_tbs', '' ); // "gigoftheday@gmail.com"; // Enter your The Best Spinner's Email ID
				$tbs_password = get_option ( 'wp_automatic_tbs_p', '' ); // "nd8da759a40a551b9aafdc87a1d902f3d"; // Enter your The Best Spinner's Password
				$tbs_protected = get_option ('wp_automatic_tbs_protected','');
				
				if(trim($tbs_protected) != ''){
					$tbs_protected = explode("\n", $tbs_protected);
					$tbs_protected = array_filter($tbs_protected);
					$tbs_protected = array_map('trim', $tbs_protected);
					
					$tbs_protected = array_filter($tbs_protected);
					
					$tbs_protected = implode(',', $tbs_protected);
				}
				
				//add , if not exists
				if(! stristr($tbs_protected, ',')  ){
					$tbs_protected = $tbs_protected .',';
				}
				
				//add ad_1, ad_2 , numbers
				 
				$tbs_protected = $tbs_protected . 'ad_1,ad_2';
				
				 
				
				if (trim ( $tbs_username ) == '' || trim ( $tbs_password ) == '') {
					//$this->log ( 'Info', 'No BTS account found , it is highly recommended ' );
					return $html;
				}
				
				$data ['username'] = $tbs_username;
				$data ['password'] = $tbs_password;
				
				// Authenticate and get back the session id.
				// You only need to authenticate once per session.
				// A session is good for 24 hours.
				$output = unserialize ( $this->curl_post ( $url, $data, $info ) );
				
				
				
				if ($output ['success'] == 'true') {
					
					$this->log ( 'TBS', "TBS Login success" );
					echo '<br>TBS Login success';
					// Success.
					$session = $output ['session'];
					
					// Build the data array for the example.
					$data = array ();
					$data ['session'] = $session;
					$data ['format'] = 'php'; // You can also specify 'xml' as the format.
				 
					$data ['protectedterms'] = $tbs_protected ;
					
					//instantiate original html
					$newhtml = $html;
						
					
					//replace nospins with astrics
					preg_match_all('{\[nospin.*?\/nospin\]}s', $html ,$nospins);
					$nospins = $nospins[0];
					
					//remove empty and duplicate
					$nospins = array_filter(array_unique($nospins));
					
					
					//replace nospin parts with astrics
					$i=1;
					foreach ($nospins as $nospin){
						 $newhtml = str_replace($nospin, '['.str_repeat('*', $i).']', $newhtml);
						 $i++;
					}
					
				
					$data ['text'] =   ( html_entity_decode($newhtml) );
					 
				 
					//$data ['text'] = 'test <br> word <a href="http://onetow.com">http://onetow.com</a> ';
					
					$data ['action'] = $testmethod;
					$data ['maxsyns'] = '100'; // The number of synonyms per term.
					
					if ($testmethod == 'replaceEveryonesFavorites') {
						// Add a quality score for this method.
						$data ['quality'] = '1';
					}
					
					 
					// Post to API and get back results.
					$output = $this->curl_post ( $url, $data, $info );
					
				  
					$output = unserialize ( $output );
					
				
					
					// Show results.
					// echo "<p><b>Method:</b><br>$testmethod</p>";
					// echo "<p><b>Text:</b><br>$data[text]</p>";
					
					if ($output ['success'] == 'true') {
						$this->log ( 'TBS', "TBS Successfully spinned the content" );
					
						
						//replace the astrics with nospin tags 
						if( count($nospins) > 0 ){
							
							$i = 1 ;
							
							foreach($nospins as $nospin){
		
								$output ['output'] = str_replace('['.str_repeat('*', $i).']', $nospin, $output ['output']);
								
								$i++;
							}
							
							 
						}
						
		 				
						echo '<br>TBS Successfully spinned the content';
						return $output ['output'];
					} else {
						
						
						$this->log ( 'error', "TBS Returned an error:$output[error]" );
						echo "TBS Returned an error:$output[error]";
						return $html;
					}
				} else {
					// There were errors.
					echo "<br>TBS returned an error : $output[error]";
					$this->log ( 'error', "TBS returned an error : $output[error]" );
					return $html;
				}
			} // end function
			
			/*
			 * gtranslte function
			 */
			function gtranslate($title, $content, $from, $to) {
				
			  
				
				echo '<br>Translating from '.$from . ' to '.$to;
				
				$text = $title . '##########' . $content;
				
				//decode html for chars like &euro;  
				$text = html_entity_decode($text);
			
				// STRIP html and links
				preg_match_all ( "/<[^<>]+>/is", $text, $matches, PREG_PATTERN_ORDER );
				$htmlfounds = array_filter( array_unique($matches [0]));
				$htmlfounds[] = '&quot;'; 

				//<!-- <br> -->
				preg_match_all ( "/<\!--.*?-->/is", $text, $matches2, PREG_PATTERN_ORDER );
				$newhtmlfounds = $matches2[0];
				
				$htmlfounds=array_merge($htmlfounds,$newhtmlfounds);
				
			 	$start = 19459001;
				foreach ( $htmlfounds as $htmlfound ) {
					$text = str_replace ( $htmlfound, '[' . $start . ']', $text );
					$start++;
				}
			 	
				// old url stopped since v3.13
				//$url = "http://translate.google.com/translate_a/t?client=p&sl=$from&tl=$to&hl=$from&ie=UTF-8&oe=UTF-8&uptl=$to&alttl=en&pc=1&oc=1&otf=1&ssel=0&tsel=0";
				
				$url ="https://translate.google.com/translate_a/single?client=t&sl=$from&tl=$to&hl=$from&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&ie=UTF-8&oe=UTF-8&otf=2&ssel=0&tsel=0&kc=5&tk=523127|49636";
			 	
				// curl post
				curl_setopt ( $this->ch, CURLOPT_URL, $url );
				curl_setopt ( $this->ch, CURLOPT_POST, true );
				curl_setopt ( $this->ch, CURLOPT_POSTFIELDS, "q=".urlencode($text) );
				$x = 'error';
				
				$exec = curl_exec ( $this->ch );
				$x = curl_error ( $this->ch );
				
				 
				 
				if (trim ( $x ) != '')
					echo '<br>Curl returned an error ' . $x;
				
				//get sentenses
				preg_match_all('{\[\[\[.*,,,0\]}', $exec,$matches);
				
				
				$sentenses_match= $matches[0];
				$sentenses_plain = $sentenses_match[0];
				 	
				//remove the [[[
				$sentenses_plain = str_replace('[[[', '[[', $sentenses_plain);
				$sentenses_plain = str_replace(',,,0', '', $sentenses_plain);
				
				$sentenses_plain.= ']';
				
				 
				$sentenses_json = json_decode($sentenses_plain);
				
				$translated = '';
				
				if(is_array($sentenses_json)){
				
					foreach ($sentenses_json as $sentense_arr){
						$translated.= $sentense_arr[0];
					}
				
				}
				
				
		   		// check if successful translation contains ***
				if (stristr ( $translated, '##########' )) {
					
				 
					//grab all replacements with **
					preg_match_all('{\[.*?\]}', $translated,$brackets);
					 
					$brackets = $brackets[0];
					$brackets = array_unique($brackets);
					
				 	 
					foreach ($brackets as $bracket){
						if(stristr($bracket, '19')){
							
							
							$corrrect_bracket = str_replace(' ', '', $bracket);
							$corrrect_bracket = str_replace('.', '', $corrrect_bracket);
							$corrrect_bracket = str_replace(',', '', $corrrect_bracket);
							
							$translated = str_replace($bracket, $corrrect_bracket, $translated);
							
							 
						}
					}
					
					 
					// restore html again
					// restore html tags
					
					$start = 19459001;
					foreach ( $htmlfounds as $htmlfound ) {
						$translated = str_replace ( '[' . $start . ']', $htmlfound, $translated );
						$start ++;
					}
					
					 
					$contents = explode ( '##########', $translated );
					$title = $contents [0];
					$content = $contents [1];
				} else {
					echo '<br>Translation failed ';
				}
				
				return array (
						$title,
						$content 
				);
			}
			function curl_post($url, $data, &$info) {
				$ch = curl_init ();
				
				 
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_TIMEOUT,20);
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_POST, true );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->curl_postData ( $data ) );
				curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_REFERER, $url );
				$html = trim ( curl_exec ( $ch ) );
				
				print_r(curl_error($ch));
				 
				return $html;
			}
			function curl_postData($data) {
				$fdata = "";
				foreach ( $data as $key => $val ) {
					$fdata .= "$key=" . urlencode ( $val ) . "&";
				}
				
				return $fdata;
			}
			
			/*
			 * ---* update cb categories ---
			 */
			function update_categories() {
				// Get
				$x = 'error';
				while ( trim ( $x ) != '' ) {
					$url = 'http://www.clickbank.com/advancedMarketplaceSearch.htm';
					curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $this->ch, CURLOPT_URL, trim ( $url ) );
					$exec = curl_exec ( $this->ch );
					echo $x = curl_error ( $this->ch );
				}
				
				if (stristr ( $exec, '<option value="">- All categories -</option>' )) {
					echo '<br>categories found';
					preg_match_all ( "{>- All categories -</option>((.|\s)*?)</select>}", $exec, $matches, PREG_PATTERN_ORDER );
					
					$res = $matches [0];
					$cats = $res [0];
					
					// extracting single parent categories [<option value="1510">Betting Systems</option>]
					preg_match_all ( "{<option value=\"(.*?)\">(.*?)</option>}", $cats, $matches, PREG_PATTERN_ORDER );
					$paretcats_ids = $matches [1];
					$paretcats_names = $matches [2];
					
					// delete current records
					if (count ( $paretcats_names ) > 0) {
						$query = "delete from {$this->wp_prefix}automatic_categories ";
						$this->db->query ( $query );
					}
					
					// adding parent categories
					$i = 0;
					foreach ( $paretcats_ids as $parentcat_id ) {
						
						$parentcat_name = $paretcats_names [$i];
						
						// inserting cats
						$query = "insert into {$this->wp_prefix}automatic_categories (cat_id , cat_name) values ('$parentcat_id','$parentcat_name')";
						$this->db->query ( $query );
						$i ++;
					}
					
					echo '<br>Parent Categories added:' . $i;
					
					// extracting subcategories
					/*
					 * <option value="1265" parent="1253" path="Arts & Entertainment &raquo; Architecture"> Architecture </option>
					 */
					
					// echo $exec;
					// exit;
					preg_match_all ( "{<option value=\"(.*?)\"  parent=\"(.*?)\"(.|\s)*?>((.|\s)*?)</option>}", $exec, $matches, PREG_PATTERN_ORDER );
					$subcats_ids = $matches [1];
					$subcats_parents = $matches [2];
					$subcats_names = $matches [4];
					
					$i = 0;
					foreach ( $subcats_ids as $subcats_id ) {
						$subcats_names [$i] = trim ( $subcats_names [$i] );
						$subcats_parents [$i] = trim ( $subcats_parents [$i] );
						$query = "insert into {$this->wp_prefix}automatic_categories(cat_id,cat_parent,cat_name) values('$subcats_id','$subcats_parents[$i]','$subcats_names[$i]')";
						$this->db->query ( $query );
						$i ++;
					}
					
					echo '<br>Sub Categories added ' . $i;
					
					// print_r($matches);
					exit ();
					
					$res = $matches [2];
					$form = $res [0];
					
					preg_match_all ( "{<option value=\"(.*?)\"  parent=\"(.*?)\"}", $exec, $matches, PREG_PATTERN_ORDER );
					
					print_r ( $matches );
					
					// print_r($matches);
					exit ();
					$res = $matches [0];
					$cats = $res [0];
				}
			}
			
			/*
			 * ---* Proxy Frog Integration ---
			 */
			function alb_proxyfrog() {
				
				// get the current list
				$proxies = get_option ( 'alb_proxy_list' );
				
				// no proxies
				echo '<br>Need new valid proxies';
				
				if (function_exists ( 'proxyfrogfunc' )) {
					echo '<br>Getting New Proxy List from ProxyFrog.me';
					// Get
					$x = 'error';
					
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_HEADER, 0 );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
					curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
					curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
					curl_setopt ( $ch, CURLOPT_REFERER, 'http://www.bing.com/' );
					curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8' );
					curl_setopt ( $ch, CURLOPT_MAXREDIRS, 5 ); // Good leeway for redirections.
					curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // Many login forms redirect at least once.
					curl_setopt ( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
					
					// Get
					// license
					$paypal = get_option ( 'pf_license' );
					$paypal = urlencode ( $paypal );
					$url = "http://proxyfrog.me/proxyfrog/api.php?email=$paypal";
					curl_setopt ( $ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $ch, CURLOPT_URL, trim ( $url ) );
					$exec = curl_exec ( $ch );
					
					// echo $exec;
					
					if (stristr ( $exec, ':' )) {
						update_option ( 'be_proxy_list', $exec );
						update_option ( 'alb_proxy_list', $exec );
						echo '<br>New Proxy List <b>Added successfully</b> ';
						$this->log ( 'ProxyFrog', "New Proxy list added from ProxyFrog" );
						return true;
					} else {
						$this->log ( 'ProxyFrog', $exec );
					}
				} else {
					
					return false;
				}
			} // end fun
			
			/*
			 * ---* Logging Function ---
			 */
			function log($type, $data) {
				// $now= date("F j, Y, g:i a");
				$now = date ( 'Y-m-d H:i:s' );
				$data = @addslashes ( $data );
				
				 
				$query = "INSERT INTO {$this->wp_prefix}automatic_log (action,date,data) values('$type','$now','$data')";
				
				 
				
				// echome$query;
				$this->db->query ( $query );
				
				$insert = $this->db->insert_id;
				
				$insert_below_100 = $insert -100 ;
				
				if($insert_below_100 > 0){
					//delete
					$query="delete from {$this->wp_prefix}automatic_log where id < $insert_below_100 and action not like '%Posted%'" ;
					$this->db->query($query);
				}
				
			}
			
			/**
			 * Function that checks if the current link is already posted 
			 * @param unknown $link
			 */
			function is_duplicate($link_url){
				
				$query = "SELECT post_id from {$this->wp_prefix}postmeta where meta_value='$link_url' ";
				$pres = $this->db->get_results ( $query );
				
				//double check again
				if(count ( $pres ) == 0 ){
					$pres = $this->db->get_results ( $query );
				}
				 
		
				$duplicate=false;
				if(count ( $pres ) == 0 ){
					$duplicate = false;
				}else{
					
					$duplicate = true;
					
		 
					foreach($pres as $prow){
						
						$ppid=$prow->post_id;
						$this->duplicate_id = $ppid;
						
						$pstatus = get_post_status($ppid);
							
						if($pstatus != 'trash') {
							break;
						}
					} 
					
				}
				
				
				
				return $duplicate;
				
			}
		
			/**
			 * Function link exclude to execlude links
			 * @param unknown $camp_id
			 * @param unknown $source_link
			 */
			function link_execlude($camp_id,$source_link){
				update_post_meta($camp_id,'_execluded_links', get_post_meta($camp_id,'_execluded_links',1).','.$source_link );
			}
			
			/**
			 * Check if link is execluded or not i.e it didn't contain exact match keys or contins blocked keys
			 * @param unknown $camp_id
			 * @param unknown $link
			 */
			function is_execluded($camp_id,$link){
				
				  
				$execluded_links = get_post_meta($camp_id,'_execluded_links',1);
				
		 
				if(stristr(','.$execluded_links, $link )){
					return true;
				}else{
					return false;
				}
				
			}
			
			/**
			 * function cache_image 
			 * return local image src if found 
			 * return false if not cached 
			 */
			function is_cached($remote_img,$data_md5){
				
				//md5
				$md5=md5($remote_img);
				
				//query database for this image 
				
				$query="SELECT * FROM {$this->db->prefix}automatic_cached where img_hash='$md5' and img_data_hash='$data_md5' limit 1";
				
				 
				$rows=$this->db->get_results($query);
				
				if(count($rows) == 0 ) return false;
				$row=$rows[0];
				
				//hm we have cached image with previous same source let's compare 
				$local_src = $row->img_internal;
				
				//make sure current image have same data md5 right now otherwise delete
				//curl get
				curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
				curl_setopt($this->ch, CURLOPT_URL, trim($local_src));
			 	$exec=curl_exec($this->ch);
			 	
			 	if(md5($exec) == $data_md5) {
			 		
			 		$this->cached_file_path = $row->img_path;
			 		
			 		return $local_src;
			 	}else{
			 		
			 		//now the local image no more giving the same md5 may be deleted or changed delete the record
			 		$query="delete from {$this->db->prefix}automatic_cached where img_hash = '$md5' ";
			 		$this->db->query($query);
			 		
			 		return false;
			 	}
			 	
			 	 
				 	  
			}
			
			/**
			 * 
			 * @param unknown $remote_img
			 * @param unknown $local_img
			 * @param number $thumb_id
			 */
			
			function img_cached($remote_img,$local_img,$image_data_md5,$file_path  ){
				
				$md5= md5($remote_img);
				$query="insert into {$this->db->prefix}automatic_cached(img_external,img_internal,img_hash,img_data_hash,img_path) values ('$remote_img','$local_img','$md5','$image_data_md5','$file_path')";
				$this->db->query($query);
				
				 
			}
			
			/**
			 * deactivate keyword : set the reactivation time to one comig hour
			 * @param integer $camp_id
			 * @param string $keyword
			 */
			function deactivate_key($camp_id,$keyword){
				update_post_meta($camp_id, '_'.md5($keyword), time('now') + 60*60 );
			}
			
			/**
			 * is_deactivated: check if the current deactivated keyword is still deactivated or not 
			 * if yes it return false 
			 * if not deactivated return true
			 * @param integer $camp_id
			 * @param string $key
			 */
			function is_deactivated($camp_id,$keyword){
				
				//let's see if this keyword deactivated till date or not
				$keyword_key = '_'.md5($keyword) ;
				$deactivated_till = get_post_meta($camp_id,$keyword_key,1);
				if(trim($deactivated_till) == '') $deactivated_till = 1410020931;
				
				if(time('now') > $deactivated_till ){
					//time passed let's reactivate
					echo '<br>Keyword search reached end page lets sart from first page again ';
					return true;	
				}else{
						
					//still deactivated
					echo '<br>Calling source for this keyword is <strong>deactivated</strong> for one hour due to last time we called there were no more results to get we will reactivate it after '. number_format((    $deactivated_till - time('now') )  /60 , 2 )   . ' minutes. You can still <a class="wp_automatic_key_reactivate" data-id="'.$camp_id.'" data-key="'. $keyword_key . '" href="#">Reactivate Now.</a><span class="spinner_'.$keyword_key.'  spinner"></span>' ;
					return false;
				}
				
			}
			
			/**
			 * Function is_link_old check if the timestamp for the link is older than minimum
			 * @param unknown $camp_id
			 * @param unknown $link_timestamp
			 */
			function is_link_old($camp_id , $link_timestamp){
				
				if($this->minimum_post_timestamp_camp == $camp_id ){
					if($link_timestamp < $this->minimum_post_timestamp){
						return true;
					}else{
						return false;
					}
				}
			}
			
			/**
			 * function is_title_duplicate
			 * @param unknown $title
			 */
			function is_title_duplicate($title,$post_type){
				if( get_page_by_title( $title, 'OBJECT', $post_type )  ){
					
					return true;
					
				}else{
					return false;
				}
			}
			
			/*
			 * ---* validating ---
			 */
			function validate() {
				$paypal = get_option ( 'alb_license', '' );
				$active = get_option ( 'alb_license_active', '' );
				$link = 'http://wpplusone.com/trafficautomator/activate.php';
				
				// no license
				if (trim ( $paypal ) == '') {
					$this->log ( 'Error', 'License Required please visit settings and add the paypal email you used to purchase the product' );
					exit ();
				}
				
				// cehck validety
				if (trim ( $active ) != '1') {
					// first time activation
					// opening the page using curl
					$this->c->set ( CURLOPT_URL, trim ( "$link?email=$paypal" ) );
					$this->c->set ( CURLOPT_CONNECTTIMEOUT, 20 );
					$this->c->set ( CURLOPT_TIMEOUT, 50 );
					$this->c->set ( CURLOPT_HTTPGET, 1 );
					$ret = $this->c->execute ();
					$ret = trim ( $ret );
					// when no response
					if ($ret == '') {
						// service not available
						$this->log ( 'Error', 'Could not activate licence at this time may be our server is under maintenance now I will keep try and if the problem exists contact support' );
						exit ();
					} elseif ($ret == '0') {
						// not valid license
						$this->log ( 'Error', 'License is not valid please visit settings and use a valid license please, if you do\'t have a license consider to purchase <a href="http://wpsbox.com/buy">Here</a> and if you have just purchased just hold on our records will update after 10 minutes please be patient' );
						exit ();
					} elseif ($ret == '-1') {
						// Refunded
						$this->log ( 'Error', 'License is not valid a Refund may have been already issued for this license' );
						exit ();
					} elseif ($ret == '1') {
						// valid license
						update_option ( 'alb_license_active', '1' );
						// register last chek
						$date = date ( "m\-d\-y" );
						update_option ( 'alb_license_last', $date );
					} else {
						$this->log ( 'Error', 'License could not be validated at this time, our server may be under maintenance now will try the next cron' );
						exit ();
					}
				} else {
					// license is working without problem we should check again
					$date = date ( "m\-d\-y" );
					$last_check = get_option ( 'alb_license_last', $date );
					$offset = $this->dateDiff ( "-", $date, $last_check );
					if ($offset >= 1) {
						// echo 'checking license again';
						// check again
						// opening the page using curl
						$this->c->set ( CURLOPT_URL, trim ( "$link?email=$paypal" ) );
						$this->c->set ( CURLOPT_CONNECTTIMEOUT, 20 );
						$this->c->set ( CURLOPT_TIMEOUT, 50 );
						$this->c->set ( CURLOPT_HTTPGET, 1 );
						$ret = $this->c->execute ();
						$ret = trim ( $ret );
						// when no response
						if ($ret == '0') {
							// not valid license
							$this->log ( 'Error', 'License is not valid please visit settings and use a valid license please, if you do\'t have a license consider to purchase <a href="http://wpsbox.com/buy">Here</a>' );
							update_option ( 'alb_license_active', '' );
							exit ();
						} elseif ($ret == '-1') {
							// Refunded
							$this->log ( 'Error', 'License is not valid a Refund may have been already issued for this license' );
							update_option ( 'alb_license_active', '' );
							exit ();
						} elseif ($ret == '1') {
							// valid license
							update_option ( 'alb_license_active', '1' );
							// register last chek
							$date = date ( "m\-d\-y" );
							update_option ( 'alb_license_last', $date );
						}
					}
				}
				
				return true;
			}
			
			/*
			 * ---* Date Difference return days between two dates ---
			 */
			function dateDiff($dformat, $endDate, $beginDate) {
				$date_parts1 = explode ( $dformat, $beginDate );
				$date_parts2 = explode ( $dformat, $endDate );
				$start_date = gregoriantojd ( $date_parts1 [0], $date_parts1 [1], $date_parts1 [2] );
				$end_date = gregoriantojd ( $date_parts2 [0], $date_parts2 [1], $date_parts2 [2] );
				return $end_date - $start_date;
			}
			
			/*
			 * ---* Download File ---
			 */
			function downloadfile($link) {
				$downloader = $this->plugin_url . 'downloader.php';
				// $downloader='http://localhost/php/wpsbox_aals/downloader.php';
				$link = str_replace ( 'http', 'httpz', $link );
				
				$enc = urlencode ( $link );
				// $return=file_get_contents($downloader.'?link='.$enc);
				// echo $return ;
				
				if (stristr ( $return, 'error' )) {
					echo '<br>An Error downloading the <b>damn file</b> :';
					echo ' <i><small>' . $return . '</small></i>';
					
					return false;
				}
				return true;
			}
			
			/*
			 * ---* Solve captcha function ---
			 */
			function solvecap($url) {
				$decap_user = get_option ( 'alb_de_u' );
				$decap_pass = get_option ( 'alb_de_p' );
				
				// if decap not registered return false
				if (trim ( $decap_user ) == '' || trim ( $decap_pass ) == '') {
					echo '<br>decaptcher.com <b>account needed</b>';
					$this->log ( 'Error', 'Capatcha Met at ' . $proxy . ' , Decapatcher Account needed please register one at decapatcher.com , add balance to it then enter login details at settings tab ' );
					return false;
				}
				
				// curl ini
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
				curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
				curl_setopt ( $ch, CURLOPT_REFERER, 'http://www.bing.com/' );
				curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8' );
				curl_setopt ( $ch, CURLOPT_MAXREDIRS, 5 ); // Good leeway for redirections.
				curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // Many login forms redirect at least once.
				curl_setopt ( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
				curl_setopt ( $ch, CURLOPT_URL, trim ( $url ) );
				curl_setopt ( $ch, CURLOPT_HEADER, 0 );
				$img = curl_exec ( $ch );
				if (trim ( $img ) == '')
					return false;
				if (curl_error ( $ch ) != '') {
					echo '<br>Image fetched with error:' . curl_error ( $ch ) . '<br>';
					return false;
				}
				
				// file_put_contents('files/cap.jpg',$img);
				
				// positng image to capatcher to get the decapatched version
				curl_setopt ( $ch, CURLOPT_VERBOSE, 0 );
				curl_setopt ( $ch, CURLOPT_URL, 'http://poster.decaptcher.com' );
				curl_setopt ( $ch, CURLOPT_POST, true );
				
				$decap_acc = '1169';
				
				$post = array (
						"pict" => "@files/cap.jpg",
						"function" => "picture2",
						"username" => $decap_user,
						"password" => $decap_pass,
						"pict_to" => "0",
						"pict_type" => $decap_acc 
				);
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
				if (curl_error ( $ch ) != '') {
					echo '<br>Captacha Posted with error:' . curl_error ( $ch ) . '<br>';
					return false;
				}
				
				$decap = curl_exec ( $ch );
				echo '<br>Decap returned:' . $decap;
				// check if decapatcher returned an error -
				if (stristr ( $decap, '-' ) || trim ( $decap ) == '') {
					echo '<br>Decapatcher returned an <b>error</b> ' . $decap;
					$this->log ( 'Error', 'Decapatcher Account Error Please check login details and suffecient balance' );
					return false;
				}
				
				if (trim ( $decap ) == '')
					return false;
				$decaps = explode ( '|', $decap );
				$decap = $decaps [5];
				if (trim ( $decap ) == '')
					return false;
				echo '<br>Decap Solution:' . $decap;
				return $decap;
			}
			
			/*
			 * ---* Trackback function using wp modification ---
			 */
			function trackback($trackback_url, $author, $ttl, $excerpt, $link) {
				$options = array ();
				$options ['timeout'] = 4;
				$options ['body'] = array (
						'title' => $ttl,
						'url' => $link,
						'blog_name' => $author,
						'excerpt' => $excerpt 
				);
				
				$response = wp_remote_post ( $trackback_url, $options );
				
				if (is_wp_error ( $response )) {
					echo '<br>Trackback Error';
					return;
				} else {
					echo '<br>No Track back error';
				}
			}
			
			 
			/*
			 * function get_time_difference: get the time difference in minutes.
			* @start: time stamp
			* @end: time stamp
			*/
			
			function get_time_difference( $start, $end )
			{
			
				$uts['start']      =     $start ;
				$uts['end']        =      $end ;
			
			
			
				if( $uts['start']!==-1 && $uts['end']!==-1 )
				{
					if( $uts['end'] >= $uts['start'] )
					{
						$diff    =    $uts['end'] - $uts['start'];
			
						return round($diff/60,0);
			
					}
			
				}
			}
			
			function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
				if ($considerHtml) {
					// if the plain text is shorter than the maximum length, return the whole text
					if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
						return $text;
					}
					// splits all html-tags to scanable lines
					preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
					$total_length = strlen($ending);
					$open_tags = array();
					$truncate = '';
					foreach ($lines as $line_matchings) {
						// if there is any html-tag in this line, handle it and add it (uncounted) to the output
						if (!empty($line_matchings[1])) {
							// if it's an "empty element" with or without xhtml-conform closing slash
							if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
								// do nothing
								// if tag is a closing tag
							} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
								// delete tag from $open_tags list
								$pos = array_search($tag_matchings[1], $open_tags);
								if ($pos !== false) {
									unset($open_tags[$pos]);
								}
								// if tag is an opening tag
							} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
								// add tag to the beginning of $open_tags list
								array_unshift($open_tags, strtolower($tag_matchings[1]));
							}
							// add html-tag to $truncate'd text
							$truncate .= $line_matchings[1];
						}
						// calculate the length of the plain text part of the line; handle entities as one character
						$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
						if ($total_length+$content_length> $length) {
							// the number of characters which are left
							$left = $length - $total_length;
							$entities_length = 0;
							// search for html entities
							if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
								// calculate the real length of all entities in the legal range
								foreach ($entities[0] as $entity) {
									if ($entity[1]+1-$entities_length <= $left) {
										$left--;
										$entities_length += strlen($entity[0]);
									} else {
										// no more characters left
										break;
									}
								}
							}
							$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
							// maximum lenght is reached, so get off the loop
							break;
						} else {
							$truncate .= $line_matchings[2];
							$total_length += $content_length;
						}
						// if the maximum length is reached, get off the loop
						if($total_length>= $length) {
							break;
						}
					}
				} else {
					if (strlen($text) <= $length) {
						return $text;
					} else {
						$truncate = substr($text, 0, $length - strlen($ending));
					}
				}
				// if the words shouldn't be cut in the middle...
				if (!$exact) {
					// ...search the last occurance of a space...
					$spacepos = strrpos($truncate, ' ');
					if (isset($spacepos)) {
						// ...and cut the text in this position
						$truncate = substr($truncate, 0, $spacepos);
					}
				}
				// add the defined ending to the text
				$truncate .= $ending;
				if($considerHtml) {
					// close all unclosed html-tags
					foreach ($open_tags as $tag) {
						$truncate .= '</' . $tag . '>';
					}
				}
				return $truncate;
			}//end function
			
			
			/**
			 * function: curl with follocation that will get url if openbasedir is set or safe mode enabled
			 * @param unknown $ch
			 * @return mixed
			 */
			
			function curl_exec_follow( &$ch){
			
				$max_redir = 3;
			
				for ($i=0;$i<$max_redir;$i++){
			
					$exec=curl_exec($ch);
				 
					$info = curl_getinfo($ch);
			
					
					
					if($info['http_code'] == 301 ||  $info['http_code'] == 302){
							
						curl_setopt($ch, CURLOPT_URL, trim($info['redirect_url']));
						$exec=curl_exec($ch);
							
					}else{
							
						//no redirect just return
						break;
							
					}
			
			
				}
			
				return $exec;
			
			}
			
			//function to get user id and create it if not exists
			function get_user_id_by_display_name( $display_name ) {
				 
				//trim
				$display_name = trim($display_name);
				
				
				//check user existence 
				if ( ! $user = $this->db->get_row( $this->db->prepare(
						"SELECT `ID` FROM {$this->db->users} WHERE `display_name` = %s", $display_name
				) ) ){
					
					//replace spaces
					$login_name = str_replace(' ', '_', $display_name);
					
					
					//no user with this name let's create it and return the id
					$userdata['display_name'] = $display_name;
					$userdata['user_login'] = $display_name; 
					
					$user_id = wp_insert_user( $userdata );
					
					
					if( !is_wp_error($user_id) ) {
						echo '<br>New user created:'.$login_name;
						return $user_id;
					}else {
						return false;
					}
					
					
					
					 
					return false;
					
				}
					
			
				return $user->ID;
			}
			
			//remove emoji from instagram 
			 function removeEmoji($text) {
			
				$clean_text = "";
			
				// Match Emoticons
				$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
				$clean_text = preg_replace($regexEmoticons, '', $text);
			
				// Match Miscellaneous Symbols and Pictographs
				$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
				$clean_text = preg_replace($regexSymbols, '', $clean_text);
			
				// Match Transport And Map Symbols
				$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
				$clean_text = preg_replace($regexTransport, '', $clean_text);
			
				// Match Miscellaneous Symbols
				$regexMisc = '/[\x{2600}-\x{26FF}]/u';
				$clean_text = preg_replace($regexMisc, '', $clean_text);
			
				// Match Dingbats
				$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
				$clean_text = preg_replace($regexDingbats, '', $clean_text);
			
				return $clean_text;
			}

			//function for hyperlinking
			function hyperlink_this($text){
				
				return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$0</a>', $text);
				
			}
			
			//fix invalid utf chars
			function fix_utf8($string){
			
				//check if wrong utf8
				if ( 1 === @preg_match( '/^./us', $string ) ) {
			
					return $string;
				}else{
					echo '<br>Fixing invalid utf8 text...';
					return iconv( 'utf-8', 'utf-8//IGNORE', $string );
			
				}
			
			}
			
		} // End
		
?>
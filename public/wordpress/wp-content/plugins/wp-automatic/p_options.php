<?php
function gm_setting() {
	
	// save values if post requested
	$updated = '';
	if (isset ( $_POST ['wp_amazonpin_tw'] )) {
		
		
		//default check 
		if(! isset($_POST['wp_automatic_options'])){
			$_POST['wp_automatic_options']=array();			
		}
		

		
		foreach ( $_POST as $key => $val ) {
			update_option ( $key, $val );
		}
		
		$updated = '<div class="updated below-h2" id="message"><p>Settings updated.  </p></div>';
	}
	
	//remove twitter token
	if(isset($_POST['wp_automatic_opt'])){
		
		if( in_array('wp_automatic_tw_reset', $_POST['wp_automatic_opt']) ){
			delete_option('wp_automatic_tw_token');
		}
		
		if( in_array('wp_automatic_fb_reset', $_POST['wp_automatic_opt']) ){
			delete_option('wp_automatic_fb_token');
		}
		
	}
	
	$dir = WP_PLUGIN_URL . '/' . str_replace ( basename ( __FILE__ ), "", plugin_basename ( __FILE__ ) );
	// echo dirname(__FILE__);
	if (! function_exists ( 'cchecked' )) {
		function cchecked($name, $val) {
			$arr = get_option ( $name ,array('OPT_CRON') );
			
			if (in_array ( $val, $arr )) {
				return 'checked="checked"';
			}
		}
	}
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<script type="text/javascript" src="<?php echo $dir; ?>js/jquery.tools.js"></script>
<script type="text/javascript" src="<?php echo $dir; ?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?php echo $dir; ?>js/main.js"></script>

<link href='<?php echo $dir; ?>css/style.css' rel='stylesheet' type='text/css'>
<link href='<?php echo $dir; ?>css/uniform.css' rel='stylesheet' type='text/css'>

<div>
	<div class="wrap">
		<div style="margin-left: 8px" class="icon32" id="icon-options-general">
			<br>
		</div>
		<h2>General Settings</h2>
			
			<?php echo $updated?>
			
			<!--start container-->

		<div id="dashboard-widgets-wrap">

			<form method="post" novalidate="novalidate">
				<div class="metabox-holder columns-2" id="dashboard-widgets">



					<!-- General post box -->
					<div class="postbox-container">
						<div style="min-height: 1px;" class="meta-box-sortables ui-sortable" id="normal-sortables">

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/amazon.png',__FILE__)?>" /> <span>Amazon settings</span>
								</h3>
								<div class="inside main" style="padding-bottom: 14px">
									<!--start container-->
									<div class="TTWForm">

										<div id="field5-container" class="field f_100  ">
											<label for="field5"> Procuct Thumbnail width ?</label> <input value="<?php echo get_option( 'wp_amazonpin_tw', 400 ) ?>" max="1000" min="0" name="wp_amazonpin_tw" id="field1" class="ttw-range range" type="range">
										</div>
										<div id="field285-container" class="field f_100  ">
											<label for="field285"> Amazon Acess Key ID * <a target="blank" href="https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html"><small>(GET ONE)</small></a>
											</label> <input value="<?php echo get_option( 'wp_amazonpin_abk' ) ?>" name="wp_amazonpin_abk" id="field285" type="text">
										</div>
										<div id="field285-container" class="field f_100  ">
											<label for="field285"> Amazon Secret Access Key * </label> <input value="<?php echo get_option( 'wp_amazonpin_apvtk' ) ?>" name="wp_amazonpin_apvtk" id="field285" type="text">
										</div>
										<div id="field285-container" class="field f_100  ">
											<label for="field285"> Amazon Associate ID * </label> <input value="<?php echo get_option( 'wp_amazonpin_aaid' ) ?>" name="wp_amazonpin_aaid" id="field285" type="text">
										</div>


										<div id="form-submit" class="field f_100 clearfix submit" style>
											<input style="margin-left: 0" value="Save Changes" type="submit">
										</div>



									</div>
									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							<!-- post box -->

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/clickbank.png',__FILE__)?>"><span> Clickbank settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->
									<div id="field285-container" class="field f_100 ">
										<label for="field285"> Clickbank username ? </label> <input value="<?php echo get_option( 'wp_wp_automatic_cbu' ) ?>" name="wp_wp_automatic_cbu" id="field285" type="text">
									</div>
									<div id="field285-container" class="field f_100 ">
										<label for="field285"> Clickbank password ? </label> <input value="<?php echo get_option( 'wp_wp_automatic_cbp' ) ?>" name="wp_wp_automatic_cbp" id="field285" type="text">
									</div>

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>


							

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/write.png',__FILE__)?>"><span> The Best Spinner settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div id="field285-container" class="field f_100 ">
										<label for="field285"> <b><a target="blank" href="http://sweetheatmn.jonathanleger.zaxaa.com/s/4152949213724"> The best spinner </a></b> user name <i>(optional)</i>
										</label> <input value="<?php echo get_option( 'wp_automatic_tbs' ) ?>" name="wp_automatic_tbs" id="field285" type="text">
									</div>
									<div id="field485-container" class="field f_100 ">
										<label for="field485"> <b>The best spinner password</b>
										</label> <input name="wp_automatic_tbs_p" id="field485" type="text" value="<?php echo get_option( 'wp_automatic_tbs_p' ) ?>">
									</div>
									
									
									
									 <div   class="field f_100">
										<label> Protected terms <i>(one/line)(optional)</i>
										</label>
										<textarea rows="5" cols="20" name="wp_automatic_tbs_protected" ><?php echo stripslashes( get_option('wp_automatic_tbs_protected') )?></textarea>
										
										<br>
										
										<p>Note: you can always skip spinning parts of the content by wrapping it with the [nospin]part not to spin[/nospin] tags at the post template</p>
										
									</div>
									
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>
									
									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/flicker.png',__FILE__)?>"><span> Flicker settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div id="field285-container" class="field f_100 ">
										<label for="field285"> <b><a target="blank" href="http://www.flickr.com/services/api/misc.api_keys.html"> Flicker Api key </a></b> <i>(click the link to get your's)</i>
										</label> <input value="<?php echo get_option( 'wp_automatic_flicker' ) ?>" name="wp_automatic_flicker" id="field285" type="text">
									</div>

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/ebay.png',__FILE__)?>"><span> eBay settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label> Campaign ID (optional)</label> <input value="<?php echo get_option( 'wp_automatic_ebay_camp' ) ?>" name="wp_automatic_ebay_camp" type="text">
									</div>

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="postbox " >
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/yt.png',__FILE__)?>"><span> Youtube settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label>Youtube API Key</label> <input value="<?php echo get_option( 'wp_automatic_yt_tocken' ) ?>" name="wp_automatic_yt_tocken" type="text">
										<div class="description">Check <a href="http://valvepress.com/how-to-get-a-youtube-api-key-to-post-from-youtube-to-wordpress/" target="_blank">this tutorial</a> on how to get your youtube api key </div>
									</div>
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							
							<div class="postbox " >
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/it.png',__FILE__)?>"><span> Instagram settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label>Instagram APP client id</label> <input value="<?php echo get_option( 'wp_automatic_it_tocken' ) ?>" name="wp_automatic_it_tocken" type="text">
										<div class="description">Check <a href="http://valvepress.com/how-to-get-instagram-client-id-to-post-using-wordpress-automatic/" target="_blank">this tutorial</a> on how to get your APP client ID </div>
									</div>
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							
							
							
							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/vimeo.png',__FILE__)?>"><span> Vimeo settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label> Access Token</label> <input value="<?php echo get_option( 'wp_automatic_vm_tocken' ) ?>" name="wp_automatic_vm_tocken" type="text">
										<div class="description">Check <a href="http://valvepress.com/how-to-generate-a-vimeo-access-token-to-post-from-vimeo-to-wordpress/" target="_blank">this tutorial</a> on how to get your vimeo access token </div>
									</div>
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							
							
							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/fb.png',__FILE__)?>"><span> Facebook settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label> APP ID</label> <input value="<?php echo get_option( 'wp_automatic_fb_app' ) ?>" name="wp_automatic_fb_app" type="text">
										<div class="description">Check <a href="http://valvepress.com/how-to-create-a-fb-app-to-post-to-wordpress-using-wordpress-automatic/" target="_blank">this tutorial</a> on how to get your APP ID and secret </div>
									</div>
									
									
									<div class="field f_100 ">
										<label> APP Secret</label> <input value="<?php echo get_option( 'wp_automatic_fb_secret' ) ?>" name="wp_automatic_fb_secret" type="text">
										
									</div>
									
									<div class="field f_100 ">
										<label>Clean any generated fb tokens (Tick this if you want to regenerate)</label>
										<input type="checkbox" name= "wp_automatic_opt[]" value= "wp_automatic_fb_reset" />
										
										 
									</div>
									
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							
							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/tw.png',__FILE__)?>"><span> Twitter settings</span>
								</h3>
								<div class="inside TTWForm main" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">
										<label>Consumer Key (API Key)</label> <input value="<?php echo get_option( 'wp_automatic_tw_consumer' ) ?>" name="wp_automatic_tw_consumer" type="text">
										<div class="description">Check <a href="http://valvepress.com/how-to-post-from-twitter-to-wordpress-using-wordpress-automatic/" target="_blank">this tutorial</a> on how to get your Key and secret </div>
									</div>
									
									
									<div class="field f_100 ">
										<label>Consumer Secret (API Secret)</label> <input value="<?php echo get_option( 'wp_automatic_tw_secret' ) ?>" name="wp_automatic_tw_secret" type="text">
										
									</div>
									
									<div class="field f_100 ">
										<label>Clean any generated twitter tokens (Tick this if you want to regenerate)</label>
										<input type="checkbox" name= "wp_automatic_opt[]" value= "wp_automatic_tw_reset" />
										
										 
									</div>
									
									<?php 
									
									
									 
									?>
									

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>
							
							
							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/cron.png',__FILE__)?>"><span> Cron settings</span>
								</h3>
								<div class="inside main TTWForm" style="padding-bottom: 14px">
									<!--start container-->
									
									 
										<label> Cron Secret word [optional]</label> <input placeholder="cron" value="<?php echo get_option( 'wp_automatic_cron_secret' ) ?>" name="wp_automatic_cron_secret" type="text">
										<div class="description">Your cron link will be <strong>example.com/?wp_automatic=YOUR_SECRET_WORD</strong>  and if you setup your cron job you will need to use the new cron link appears below after clicking save.</div>
										
									 <br><br>
									
									
					     <?php 
					     
					     $wp_automatic_secret = trim(get_option('wp_automatic_cron_secret'));
					     if(trim($wp_automatic_secret) == '') $wp_automatic_secret = 'cron';
					     
					     $cronurl=site_url('?wp_automatic='.$wp_automatic_secret,__FILE__)?>
					    Cron Command - ( <a target="blank" href="<?php echo $cronurl ?>">Start now </a>)
									<div style="background-color: #FFFBCC; border: 1px solid #E6DB55; color: #555555; padding: 5px; width: 97%; margin-top: 10px">
						<?php
	
	echo 'curl ' . $cronurl;
	?> 
						</div>
									<br>
									
									<p>if the above command didn't work use the one bleow</p>
									<div style="background-color: #FFFBCC; border: 1px solid #E6DB55; color: #555555; padding: 5px; width: 97%; margin-top: 10px">
						<?php
	//$cronpath = dirname ( __FILE__ ) . '/cron.php';
	echo 'wget -O /dev/null ' . $cronurl;
	?>
						</div>

									<div  class="field f_100">
										<div class="option clearfix">
											<input name="wp_automatic_options[]" <?php echo cchecked('wp_automatic_options', 'OPT_CRON')  ?> value="OPT_CRON" type="checkbox"> <span class="option-title"> Use <abbr title="Tick this option to use wordpress built-in cron ">Built in cron</abbr> instead </span>
										</div>
									</div>
									
									<div  class="field f_100">
										<div class="option clearfix">
											<input name="wp_automatic_options[]" <?php echo cchecked('wp_automatic_options', 'OPT_PREVIEW_EDIT')  ?> value="OPT_PREVIEW_EDIT" type="checkbox"> <span class="option-title">Preview posts in edit screen not via front end</span>
										</div>
									</div>
									
									
									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

						</div>
					</div>
					<!-- End post box  -->

					<!-- General post box -->
					<div class="postbox-container">
						<div style="min-height: 1px;" class="meta-box-sortables ui-sortable" id="normal-sortables">

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/search.png',__FILE__)?>"><span> Search and Replace</span>
								</h3>
								<div class="inside main TTWForm" style="padding-bottom: 14px">
									<!--start container-->

									<div class="field f_100 ">

										<p style="margin-top: 0;">
											Search for words in the article and replace it (one set per line) <br>like <strong>word1|word2|word3</strong> . if the post contains <strong>word1</strong> it will be replaced by <strong>word2</strong> or <strong>word3</strong>
										</p>
										<textarea name="wp_automatic_replace"><?php echo  stripslashes( get_option('wp_automatic_replace') )?></textarea>

									</div>
									
									<div  class="field f_100">
										<div class="option clearfix">
											<input name="wp_automatic_options[]" <?php echo cchecked('wp_automatic_options', 'OPT_REPLACE_NO_REGEX')  ?> value="OPT_REPLACE_NO_REGEX" type="checkbox"> <span class="option-title">Replace litterally don't expect words replace</span>
										</div>
									</div>
									
									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>


									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/money.png',__FILE__)?>"><span>Ads settings</span>
								</h3>
								<div class="inside main TTWForm" style="padding-bottom: 14px">
									<!--start container-->
									<div id="field11-container" class="field f_100">
										<label for="field11"> Top Ad Code <i>(optional)</i>
										</label>
										<textarea rows="5" cols="20" name="wp_automatic_ad1" id="field11"><?php echo stripslashes(  get_option('wp_automatic_ad1') )?></textarea>
									</div>
									<div id="field11-container" class="field f_100">
										<label for="field11"> Bottom Ad Code <i>(optional)</i>
										</label>
										<textarea rows="5" cols="20" name="wp_automatic_ad2" id="field11"><?php echo stripslashes( get_option('wp_automatic_ad2') )?></textarea>
									</div>

									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="postbox " id="dashboard_right_now">
								<h3 class="hndle">
									<img style="width: 14px" src="<?php echo plugins_url('images/proxy.png',__FILE__)?>"><span> Proxy settings</span>
								</h3>
								<div class="inside main TTWForm" style="padding-bottom: 14px">
									<!--start container-->


									<div id="field11-container" class="field f_100">
										<label for="field11"> Use this proxy list <i>(one/line)(optional)</i>
										</label>
										<textarea rows="5" cols="20" name="wp_automatic_proxy" id="field11"><?php echo stripslashes( get_option('wp_automatic_proxy') )?></textarea>
									</div>
									<div id="form-submit" class="field f_100 clearfix submit" style>
										<input style="margin-left: 0" value="Save Changes" type="submit">
									</div>

									<!--start container-->
									<div style="clear: both"></div>
								</div>
							</div>


						</div>
					</div>
					<!-- End post box  -->


				</div>
				<!-- dashboard widgets -->
			</form>
		</div>
		<!-- dashboard widgets wrap -->

		<!--start container-->
	</div>
</div>
<!-- Panels -->

<script type="text/javascript">

 

	jQuery('.postbox h3').click( function() {
		   jQuery(jQuery(this).parent().get(0)).toggleClass('');
		    } );
	</script>


</body>
</html>
<?php } ?>
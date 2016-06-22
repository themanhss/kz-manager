<?php

namespace App\Http\Controllers\Gmail;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Blogspot as Blogspot;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module;
use App\Models\Gmail as Gmail;

class GmailController extends Controller {
	/*
	  |--------------------------------------------------------------------------
	  | Dashboard Controller
	  |--------------------------------------------------------------------------
	 */

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	protected $client;

	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index() {
		$gmails = Gmail::all();
		return view('backend.gmail.index',['gmails'=> $gmails]);
	}

	/*
     *Create a Gmail Account
     *
     *@POST("/admin/gmails/create")
     *@Param: ({'firstName','lastName', 'email', 'password', '', 'isCompanyAdmin'})
     *@Version("v1")
     */
	public function create(Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$datas = $request->all();

			/*Validation form*/
			$validator = Validator::make($request->all(), [
				'gmail' => 'required'
			]);

			if ($validator->fails()) {
				return redirect('admin/gmails/create')
					->withErrors($validator)
					->withInput();
			}


			$file = array_get($datas, 'client_key');
			if ($file) {
				// SET UPLOAD PATH
				$destinationPath = "uploads/gmail/client_key/";
				// GET THE FILE EXTENSION
				$extension = $file->getClientOriginalExtension();
				// RENAME THE UPLOAD WITH RANDOM NUMBER
				$fileName = rand(11111, 99999) . '.' . $extension;
				// MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
				$upload_success = $file->move($destinationPath, $fileName);


				if ($upload_success) {
					$datas['client_key'] = $fileName;
				}
			} else {
				$datas['client_key'] = '';
			}

			$gmail = new Gmail();

			$gmail->gmail = $datas['gmail'];
			$gmail->phone = $datas['phone'];

			$gmail->client_key = $datas['client_key'];

			if ($gmail->save()) {
				return redirect()->action('Gmail\GmailController@index');
			} else {

			}

		}
		return view('backend.gmail.create');
	}

	/*
     * Edit a Gmail Account
     *
     *@POST("/admin/gmails/{$gmail_id}/edit")
     *@Param: ({'firstName','lastName', 'email', 'password', '', 'isCompanyAdmin'})
     *@Version("v1")
     */
	public function edit($gmail_id, Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$datas = $request->all();

			/*Validation form*/
			$validator = Validator::make($request->all(), [
				'gmail' => 'required'
			]);

			if ($validator->fails()) {
				return redirect('admin/gmails/'.$gmail_id.'/edit')
					->withErrors($validator)
					->withInput();
			}


			$file = array_get($datas, 'client_key');
			if ($file) {
				// SET UPLOAD PATH
				$destinationPath = "uploads/gmail/client_key/";
				// GET THE FILE EXTENSION
				$extension = $file->getClientOriginalExtension();
				// RENAME THE UPLOAD WITH RANDOM NUMBER
				$fileName = rand(11111, 99999) . '.' . $extension;
				// MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
				$upload_success = $file->move($destinationPath, $fileName);


				if ($upload_success) {
					$datas['client_key'] = $fileName;
				}
			} else {
				$datas['client_key'] = '';
			}

			$gmail = Gmail::find($gmail_id);

			$gmail->gmail = $datas['gmail'];
			$gmail->phone = $datas['phone'];

			if(!$datas['client_key']){
				$gmail->client_key = $datas['client_key'];
			}

			if ($gmail->save()) {
				return redirect()->action('Gmail\GmailController@index');
			} else {

			}

		}

		$gmail = Gmail::find($gmail_id);
		return view('backend.gmail.edit',['gmail'=>$gmail]);
	}



	/*
     *Save a Blogspot Account
     *
     *@POST("/admin/gmails/{gmail_id}/blogspots/create")
     *@Param: ({'firstName','lastName', 'email', 'password', '', 'isCompanyAdmin'})
     *@Version("v1")
     */
	public function createBlogspot($gmail_id, Request $request)
	{

		if ($request->getMethod() == 'POST') {

			$datas = $request->all();

			/*Validation form*/
			$validator = Validator::make($request->all(), [
				'url' => 'required'
			]);

			if ($validator->fails()) {
				return redirect('admin/gmails/'.$gmail_id.'/blogspot/create')
					->withErrors($validator)
					->withInput();
			}

			$blogspot = new Blogspot();

			$blogspot->url = $datas['url'];
			$blogspot->blog_id = $datas['blog_id'];
			$blogspot->gmail_id = $gmail_id;
			$blogspot->description = $datas['description'];

			if ($blogspot->save()) {
				return redirect()->action('Gmail\GmailController@blogspot',[$gmail_id]);
			} else {

			}

		}
		return view('backend.gmail.blogspot.create',['gmail_id'=> $gmail_id]);
	}

	public function blogspot($gmail_id){
		$blogspots = Blogspot::where('gmail_id',$gmail_id)->get();

		return view('backend.gmail.blogspot.index',['blogspots'=> $blogspots, 'gmail_id'=> $gmail_id]);
	}


	/*
	 * Run post to all blogspot
	 *
	 * */

	public function postAllBlog($gmail_id){
		//get all blogspot belong to this gmail

		$blogs = Blogspot::where('gmail_id',$gmail_id)->get();
		foreach ($blogs as $blog){
			$this->postToBlog($gmail_id,$blog->blog_id);
		}

		return redirect()->to('admin/gmails/'.$gmail_id.'/blogspots');
	}



	public function postToBlog($gmail_id, $blog_id){

		/*$se = \Session::all();
		dd($se);*/

		$this->client = new \Google_Client();
		$this->client->setAccessType('offline');
		$this->client->setApprovalPrompt('force');	

		/*Get File client key name*/
		$gmail = Gmail::find($gmail_id);
		$client_key = $gmail->client_key;

		\Session::put('client_key',$client_key);

		$this->client->setAuthConfigFile(public_path().'/uploads/gmail/client_key/'.$client_key);
		$this->client->addScope(\Google_Service_Blogger::BLOGGER);


		$se = \Session::get('access_token');
//		dd(\Session::all());
		if (isset($se) && $se) {


			if($_SERVER['REQUEST_URI'] == "/favicon.ico") return false;

			$this->client->setAccessToken(\Session::get('access_token'));
			$this->client->setAccessType("offline");

			//$accessToken = json_decode(\Session::get('access_token'));

			$accessToken = $se['access_token'];


			if($this->client->isAccessTokenExpired()) {
				dd('het han token');
				//$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
				//header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			}


			//var_dump($accessToken);die();

			//$blogid = '5032988436021182927';

			$content = $this->mixContent();
			$title = $content['title'];
			$main_content = $content['content'];

			$blogid = $blog_id;

			$url = 'https://www.googleapis.com/blogger/v3/blogs/'.$blogid.'/posts/';

			$body = array(
				'kind' => 'blogger#post',
				'blog' => array('id' => $blogid),
				'title' => $title,
				'content' => $main_content
			);

			$data_string = json_encode($body);

			$headerQuery = array();
			$headerQuery[] = 'Authorization: Bearer '.$accessToken;
			$headerQuery[] = 'Content-length: '.strlen($data_string);
			$headerQuery[] = 'Content-Type: application/json';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerQuery);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			curl_exec($ch);

			//var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));
			//echo "<br><br><br>".$data;
			//echo curl_errno($ch);

			//$response = json_decode($data);

			curl_close($ch);
			//$gmail_id = 1;
			return redirect()->to('admin/gmails/'.$gmail_id.'/blogspots');


		} else {
			//$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
			//dd($redirect_uri);
			//header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

			if (! isset($_GET['code'])) {

				$auth_url = $this->client->createAuthUrl();
				//$auth_url = 'https://www.google.com.vn';
				return \Redirect::to($auth_url);
			} else {
				$this->client->authenticate($_GET['code']);
				$_SESSION['access_token'] = $this->client->getAccessToken();
				dd($_SESSION['access_token']);
				$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
				//header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			}
		}
	}

	/*
	 * Google redirect to if auth success
	 * param : code
	 * */
	public function array_copy($arr) {
		$newArray = array();
		foreach($arr as $key => $value) {
			if(is_array($value)) $newArray[$key] = array_copy($value);
			else if(is_object($value)) $newArray[$key] = clone $value;
			else $newArray[$key] = $value;
		}
		return $newArray;
	}

	public function returnSuccess(){

		$code = \Input::get('code');

		$client_key = \Session::get('client_key');

		$this->client = new \Google_Client();
		$this->client->setAuthConfigFile(public_path().'/uploads/gmail/client_key/'.$client_key);
		$this->client->addScope(\Google_Service_Blogger::BLOGGER);
		$this->client->setAccessType("offline");


		$token = $this->client->authenticate($code);

		$b = $this->array_copy($token);


		//\Session::set('access_token', $this->client->authenticate($code));
		\Session::set('access_token', $b);

		//		$temp = \Session::get('access_token');
//		dd(\Session::all());
		return redirect()->to('admin/gmails');
	}


	/*
	 * Mix content to post
	 * @return : {$title, $content}
	 * */

	public function mixContent(){

		//Get all img name
		$array_images_name =  file(public_path().'/tool/pre/images-name.txt', FILE_IGNORE_NEW_LINES);

		// Get Origin Content
		$main_data = file_get_contents(public_path().'/tool/pre/data.txt');

		/*get one phrase to insert title*/
		$one_string = file(public_path().'/tool/pre/title.txt', FILE_IGNORE_NEW_LINES);


		/*Proccess title*/
		$titles = $one_string;
		$max_string = count($titles);

		$new_titles = $this->array_random($titles,$max_string);


		$new_title = '';
		$new_title_data = '';

		$kk = 0;
		foreach ($new_titles as $new) {
			if($kk < 5){
				if(!$new_title){
					$new_title = $new;
					$new_title_data = $new;
				}else{
					$new_title = $new_title.'-'.$new;
					$new_title_data = $new_title_data.' '.$new;
				}
			}
			$kk = $kk + 1;
		}

		$new_title_data = str_replace('-',' ', $new_title_data);
		$new_title_data = ucfirst($new_title_data);


		$new_title = $this->convert_vi_to_en($new_title);

		/*remove images*/
		$main_data = preg_replace("/<img[^>]+\>/i", "", $main_data);
		$main_data = str_replace("'","", $main_data);

		/*remove a tag*/
//         $main_data = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "", $main_data);
		$main_data = preg_replace('/<a(.*?)">(.*?)<\/a>/', "", $main_data);

		/* Replace text*/
		$lines = file(public_path().'/tool/pre/replace.txt', FILE_IGNORE_NEW_LINES);
		foreach($lines as $line){

			$temps = explode("-", $line);
			$length_temps = count($temps);

//			foreach($temps as $temp){
			$index = rand(1,$length_temps-1);

			$temp_main = str_replace($temps[0], ' '.$temps[$index].' ', $main_data);
			$main_data = $temp_main;
//			}
		};

		/*Insert keyword to content*/
		$lines = file(public_path().'/tool/pre/keyword.txt', FILE_IGNORE_NEW_LINES);
		for($i = 0 ; $i < 10; $i++){
			$index = rand(0,count($lines)-1);
		}

		$mains = explode("</p>", $main_data);
		$mains = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $mains);
		$mains = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $mains);

		$mains = $this->array_random($mains, count($mains));

		$result = '';
		/*remove p tag, if this has content < 100 string*/
		foreach($mains as $key => $main){
			if(strlen($main) < 100){
				unset($mains[$key]);
			}
		}

		$k =1;
		$temp_add_links = '';
		foreach($mains as $main){
			if($k<=30) {
				if($k%2 == 1 && $k <= 25 ) {
					//$main = str_replace('<span>','<span style=""><b>'.$lines[rand(0,count($lines)-1)].'</b> ',$main);
					if($k < 8) {
						$main = str_replace('<span>','<span style=""><h2>'.$lines[rand(0,count($lines)-1)].'</h2> ',$main);
					}
					if(6 < $k &&  $k <=12) {
						//$main = str_replace('<span>','<span style=""><h3>'.$lines[rand(0,count($lines)-1)].'</h3> ',$main);
					}
					if(12 < $k &&  $k <=15) {
						//$main = str_replace('<span>','<span style=""><h4>'.$lines[rand(0,count($lines)-1)].'</h4> ',$main);
					}
					if(15 < $k &&  $k <=19) {
						$main = str_replace('<span>','<span style=""><h5>'.$lines[rand(0,count($lines)-1)].'</h5> ',$main);
					}
					if(19 < $k &&  $k <=25) {
						//$main = str_replace('<span>','<span style=""><h6>'.$lines[rand(0,count($lines)-1)].'</h6> ',$main);
					}
				}
				$result = trim($result).trim($main);
				if($k==1){
					$result = $result.'<!--more-->';
				}

				/*Insert images*/
				$temp_img = $array_images_name[array_rand($array_images_name)];
				if($k%3 == 1 && $k <= 5) {
					$result = $result.'<p><a href="#"><img class="aligncenter" src="'.$temp_img.'" alt="'.$temp_img.'" width="600"></a></p>';
				}

				if($k%3 == 1 && $k >= 5 && $k < 9) {
					$result = $result.'<p><a href="#"><img class="aligncenter" src="'.$temp_img.'" alt="'.$temp_img.'" width="600"></a></p>';
				}

				if($k%3 == 1 && $k >= 9) {
					$result = $result.'<p><img class="aligncenter" src="'.$temp_img.'" alt="'.$temp_img.'" width="600"></p>';
				}
			}
			if($k==31){
				$one_links = file(public_path().'/tool/pre/links.txt', FILE_IGNORE_NEW_LINES);
				if($one_links){
					$one_links = $this->array_random($one_links, count($one_links));

					$jj = 0;
					foreach($one_links as $one_link){
						if($jj < 5){
							$temp_add_links = $temp_add_links.$one_link.'<br>';
						}
						$jj++;
					}
					//$result = $result.'<div>'.$temp_add_links.'</div>';
				}
			}
			$k++;
		}

		$result_return = array(
			"title" => $new_title_data,
			"content" => $result,
		);

		return $result_return;
	}

	/*
	 * Convert Vi to EN
	 *
	 * */
	public function convert_vi_to_en($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);

		return $str;
	}

	/*
	 * Get random in array
	 * */
	public function array_random($arr, $num = 1) {
		shuffle($arr);

		$r = array();
		for ($i = 0; $i < $num; $i++) {
			$r[] = $arr[$i];
		}
		return $num == 1 ? $r[0] : $r;
	}

}

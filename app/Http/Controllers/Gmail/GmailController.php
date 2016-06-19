<?php

namespace App\Http\Controllers\Gmail;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Blogspot;
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
				'firstName' => 'required|max:255',
				'lastName' => 'required|max:255',
				'email' => 'required|email|max:255',
				'avatar' => 'image',
				'newPassword' => 'required|max:60',
				'confirmPass' => 'required|max:60|same:newPassword'
			]);

			/*Check exist email*/
			$userExist = $this->checkEmailExist($request->get('email'));

			if ($userExist == false) {
				$validator->errors()->add('email', 'This email already exists!');
				return redirect('admin/users/create')
					->withErrors($validator)
					->withInput();
			}

			if ($validator->fails()) {
				return redirect('admin/users/create')
					->withErrors($validator)
					->withInput();
			}


			/*Save new user*/

			$check_old_user = User::withTrashed()->where('email',$datas['email'])->first();
			if ($check_old_user){
				$user = $check_old_user;
				$user->restore();
			}else{
				$user = new User();
			}

			$user->firstName = $datas['firstName'];
			$user->lastName = $datas['lastName'];
			$user->email = $datas['email'];
			$user->password = Hash::make($datas['newPassword']);

			$user->updated_at = date('Y-m-d');
			$user->avatar = $datas['avatar'];
			$user->isActive = $datas['isActive'];
			$user->isAdmin = 1;

			if ($user->save()) {
				return redirect()->action('User\Backend\UserController@index');
			} else {

			}

		}
		return view('backend.gmail.create');
	}


	public function blogspot($gmail_id){
		$blogspots = Blogspot::where('gmail_id',$gmail_id)->get();

		return view('backend.gmail.blogspot.index',['blogspots'=> $blogspots]);
	}

	public function postToBlog(){

		$this->client = new \Google_Client();
		$this->client->setAccessType('offline');
		$this->client->setApprovalPrompt('force');
		$this->client->setAuthConfigFile(public_path().'/keys/client_secret_1016595116679-ihemkgsfn66h8l3h8d0o28ksnm7e5su2.apps.googleusercontent.com.json');
		$this->client->addScope(\Google_Service_Blogger::BLOGGER);

		$se = \Session::get('access_token');
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

			$blogid = '5032988436021182927';

			$url = 'https://www.googleapis.com/blogger/v3/blogs/'.$blogid.'/posts/';

			$body = array(
				'kind' => 'blogger#post',
				'blog' => array('id' => $blogid),
				'title' => 'This is title 11111111111111111111111',
				'content' => 'With <b>exciting</b> content 222222...'
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
			$gmail_id = 1;
			return redirect()->to('admin/gmails/'.$gmail_id.'/blogspots');


		} else {

			//$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
			//dd($redirect_uri);
			//header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

			if (! isset($_GET['code'])) {

				$auth_url = $this->client->createAuthUrl();
				//dd($auth_url);
				//header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
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
	public function returnSuccess(){

		$code = \Input::get('code');


		$this->client = new \Google_Client();
		$this->client->setAuthConfigFile(public_path().'/keys/client_secret_1016595116679-ihemkgsfn66h8l3h8d0o28ksnm7e5su2.apps.googleusercontent.com.json');
		$this->client->addScope(\Google_Service_Blogger::BLOGGER);
		$this->client->setAccessType("offline");

		$this->client->authenticate($code);

		\Session::put('access_token', $this->client->getAccessToken());

		return redirect()->to('admin/gmails/blogspots/run');
	}
}

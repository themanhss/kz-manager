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

	public function postToBlog($blogid){
		
	}
}

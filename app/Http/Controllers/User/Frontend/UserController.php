<?php 
namespace App\Http\Controllers\User\Frontend;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| User Admin Controller
	|--------------------------------------------------------------------------
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

	/**
	 * Show the application index screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{            
            return view('user.frontend.index');
	}
        /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function profile($id)
	{            
            return view('user.frontend.profile');
	}

}

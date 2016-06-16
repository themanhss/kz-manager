<?php
namespace App\Http\Controllers\User\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User as User;
use DB;

class UserController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | User Admin Controller
    |--------------------------------------------------------------------------
    */
    private $_userModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //Init Entity Model
        $this->_userModel = new User();
    }

    /**
     * Show the user list at backend.
     *
     * @return Response
     */
    public function index()
    {
        //Get all users
        $users = $this->_userModel->all();
        //Response view
        return view('user.backend.index', ['users' => $users]);
    }

    /*
     *Create a new User
     *
     *@POST("/admin/users/create")
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


            $file = array_get($datas, 'avatar');
            if ($file) {
                // SET UPLOAD PATH
                $destinationPath = "uploads/avatar/";
                // GET THE FILE EXTENSION
                $extension = $file->getClientOriginalExtension();
                // RENAME THE UPLOAD WITH RANDOM NUMBER
                $fileName = rand(11111, 99999) . '.' . $extension;
                // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                $upload_success = $file->move($destinationPath, $fileName);


                if ($upload_success) {
                    $datas['avatar'] = $fileName;
                }
            } else {
                $datas['avatar'] = '';
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
        return view('user.backend.create');
    }

    /*
     * Update user's information
     *@POST("/admin/users/edit")
     *@Param: ({'firstName','lastName', 'email', 'password', 'isActive', 'avatar','status})
     *@Version("v1")
     */
    public function edit($userID, Request $request)
    {
        $user = User::find($userID);
        if($user == null) {
            //Response view
            return redirect()->action('User\Backend\UserController@index');
        }
        if($request->getMethod() == 'POST'){

            $datas = $request->all();

            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|max:255',
                'lastName' => 'required|max:255',
                'avatar' => 'image',
                'newPassword' => 'max:60',
                'confirmPass' => 'max:60|same:newPassword'
            ]);

            if ($validator->fails()) {
                return redirect('admin/users/edit/'.$user->id)
                    ->withErrors($validator)
                    ->withInput();
            }


            $file = array_get($datas,'avatar');
            if($file){
                // SET UPLOAD PATH
                $destinationPath = "uploads/avatar/";
                // GET THE FILE EXTENSION
                $extension = $file->getClientOriginalExtension();
                // RENAME THE UPLOAD WITH RANDOM NUMBER
                $fileName = rand(11111, 99999) . '.' . $extension;
                // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                $upload_success = $file->move($destinationPath, $fileName);


                if($upload_success){
                    $datas['avatar'] = $fileName;
                }
            }else{
                $datas['avatar'] = $user->avatar;
            }


            /*Save new user*/
            $user->firstName = $datas['firstName'];
            $user->lastName = $datas['lastName'];
            $user->avatar = $datas['avatar'];

            $user->isActive = $datas['isActive'];

            if($datas['newPassword']){
                $user->password = Hash::make($datas['newPassword']);
            }

            if($user->save()){
                //Response view
                return redirect()->action('User\Backend\UserController@index');
            }else{

            }

        }
        return view('user.backend.edit',['user'=>$user]);
    }

    /*
     * Delete user's information
     *@POST("/admin/users/delete/id")
     *@Param: ({'id'})
     *@Version("v1")
     */
    public function delete($id, Request $request)
    {
        $user = User::find($id);
        if($user == null) {
            //Response view
            return redirect()->action('User\Backend\UserController@index');
        }
        if($request->getMethod() == 'POST'){
            $user->delete();
            //Get all users
            $users = $this->_userModel->all();
            //Response view
            return view('user.backend.index', ['users' => $users]);
        }
        return view('user.backend.delete',['user'=>$user]);
    }

    /**
     * Show the user profile at backend.
     *
     * @return Response
     */
    public function profile($id)
    {
        return view('user.backend.profile');
    }

    /*
     * Check Email if exit in system
     * @Param: ({'email'})
     * @Version("v1")
     * */
    private function checkEmailExist($email)
    {
        $userExist = User::where(array('email' => $email))->count();
        if ($userExist > 0) {
            return false;
        } else {
            return true;
        }
    }
}

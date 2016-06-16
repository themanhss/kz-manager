<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;
use Log;
use DateTime;
use DB;
use URL;
use Validator;


class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /*
     * Forgot Password
     *
     */
    public function forgot(Request $request)
    {
        $message = null;
        $status = 0;
        if($request->isMethod('post')){
            //Get User Info from email
            $userInfo = User::where(array('email'=>$request->get('email')))->get()->first();
            if($userInfo && $userInfo->email){
                $token = md5(time());

                // Save to token
                $date = new DateTime();
                $id = DB::table('password_resets')->insertGetId(
                    [
                        'email' => $userInfo->email,
                        'token' => $token,
                        'created_at' => $date
                    ]
                );

                if($id){
                    //Send mail to user when updated new password
                    $mailStatus = $this->sendMail($userInfo,$token);

                    if($mailStatus == true){
                        $message = "Your account have been reset, Please check your email!";
                        $status = 1;
                    }else{
                        $message = "Error!";
                    }
                }
            }else{
                $message = "Sorry! this email doesn't exist, please check again!";
            }
        }

        return view('auth.forgot-password',['message'=>$message,'status'=>$status]);
    }

    /**
     * Send Email
     *
     * Send an email to user when they reset a password
     *
     * @Param ({receiverUser : this is user information which are associated, token: The tocken of user to confirm
     * email})
     * @Versions({"v1"})
     */
    private function sendMail($receiverUser,$token)
    {
        try{
            //Define mail content
            $mailContent = new \StdClass();
            $mailContent->subject = '[IAG] - Reset password successful!';
            $mailContent->from = 'no-reply@iag.com';
            $mailContent->email_to = $receiverUser->email;
            $mailContent->full_name = $receiverUser->firstName.' '.$receiverUser->lastName;
            $mailContent->link = URL::to('/').'/auth/new-password/'.$token;

            //Send email to Admin who are related the asset commented by user

            Mail::send('emails.user-reset-password', ['message' => $mailContent, 'link'=>$mailContent->link, 'fullname'=>$mailContent->full_name], function ($m) use ($mailContent) {
                $m->from($mailContent->from, 'IAG');
                $m->to($mailContent->email_to, $mailContent->full_name)->subject($mailContent->subject);
            });

            return true;
        }catch(\Exception $e){
            Log::error($e);
        }

    }

    /**
     * New Password
     *
     * User enter new password
     *
     * @Param ({token : token of user get form email, request: when user click submit form})
     * @Versions({"v1"})
     */
      public function newPassword($token, Request $request){

        $password_reset = DB::table('password_resets')->where('token', $token)->first();

        if($password_reset){
            $message = '';
        }else{
            $message = 'Your token has incorrect!';
            return view('auth.new-password',['message'=>$message,'token'=>$token,'status'=>2]);
        }

          if($request->isMethod('post')){

              $datas = $request->all();
              /*Validation form*/
              $messages_error = [
                  'same' => 'The Password and Retype Password must match.'
              ];
              $validator = Validator::make($request->all(), [
                  'newpassword' => 'required',
                  'retypepassword' => 'required|same:newpassword'
              ],$messages_error);


              if ($validator->fails()) {
                  return redirect('auth/new-password/'.$token)
                      ->withErrors($validator)
                      ->withInput();
              }

              $user_email = $password_reset->email;
              $user = User::where('email','=',$user_email)->firstOrFail();
              $user->password = Hash::make($datas['newpassword']);

              $old_time = new DateTime($password_reset->created_at);
              $current_time = new DateTime();
              $diff_time =  $current_time->getTimestamp() - $old_time->getTimestamp();

              if($diff_time > 3600){
                  $message = 'Your token has expired!';
                  return view('auth.new-password',['message'=>$message,'token'=>$token,'status'=>2]);
              }

              if($user->save()){
                  DB::table('password_resets')->where('token', $token)->delete();
                  $message = "Your password has been changed successfully!";
                  return view('auth.new-password',['message'=>$message,'token'=>$token,'status'=>1]);
              }else{
                  $message = "Sorry! Something error";
              }

          }

        return view('auth.new-password',['message'=>$message,'token'=>$token,'status'=>0]);
      }
}

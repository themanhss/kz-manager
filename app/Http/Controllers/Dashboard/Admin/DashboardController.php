<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module;
use App\Models\Gmail as Gmail;
use App\Models\Blogspot as Blogspot;


class DashboardController extends Controller {
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

        $gmail_main = Gmail::where('type',1)->get();
        $blogger_main = 0;
        foreach ($gmail_main as $gm) {
            $temp_count = Blogspot::where('gmail_id',$gm->id)->count();
            $blogger_main = $blogger_main + $temp_count;
        }


        $gmail_vetinh = Gmail::where('type',0)->get();

        $blogger_vetinh = 0;
        foreach ($gmail_vetinh as $gm) {
            $temp_count = Blogspot::where('gmail_id',$gm->id)->count();
            $blogger_vetinh = $blogger_vetinh + $temp_count;
        }

        return view('dashboard.admin.index',[
            'gmail_main'=> $gmail_main,
            'blogger_main'=> $blogger_main,
            'gmail_vetinh'=> $gmail_vetinh,
            'blogger_vetinh'=> $blogger_vetinh
        ]);
    }
}

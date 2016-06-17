<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module;
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

        return view('dashboard.admin.index',[]);
    }
}

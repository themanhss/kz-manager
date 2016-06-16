<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module;
use App\Models\Promotion;
use App\Models\Lead;
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

        $promotions = Promotion::take(5)->get();
        $leads = Lead::take(5)->get();

        return view('dashboard.admin.index',['promotions'=> $promotions, 'leads'=> $leads]);
    }
}

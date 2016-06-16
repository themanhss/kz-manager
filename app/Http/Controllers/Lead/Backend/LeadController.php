<?php
namespace App\Http\Controllers\Lead\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User as User;
use App\Models\Lead as Lead;
use App\Models\Promotion as Promotion;
use DB;

class LeadController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the lead list at backend.
     *
     * @return Response
     */

    public function index()
    {
        $leads = Lead::all();
        //Response view
        return view('lead.backend.index', ['leads' => $leads]);
    }

}

<?php
namespace App\Http\Controllers\Report\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User as User;
use DB;

class ReportController extends Controller
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
     * Show the user list at backend.
     *
     * @return Response
     */
    public function index()
    {
        // Get Enquiries per Promotion
        $leads = DB::table("lead")
            ->where('deleted_at',null)
            ->select(DB::raw("promotion_id, COUNT(client_id) as total_leads"))
            ->groupBy("promotion_id")
            ->get();


        $promotions_enquiries = Promotion::all();
        foreach ($promotions_enquiries as &$promo){

            $promo->total_leads = 0;

            foreach ($leads as $lead){
                if($promo->promotion_id == $lead->promotion_id){
                    $promo->total_leads = $lead->total_leads;
                }
            }
        }

        // Get all promotions
        $promotions = Promotion::all();

        // Get all leads (enquiries)
        $leads = Lead::all();

        // Get all campaign
        $campaigns = Campaign::all();

        // Process most active users
        $visitors =  DB::table("visitors_log")
            ->where('deleted_at',null)
            ->select(DB::raw("visitor_email, COUNT(visitor_page) as total_page"))
            ->groupBy("visitor_email")
            ->orderBy('total_page', 'desc')
            ->get();

        $customers = Client::all();

        foreach ($customers as $key=>&$customer){
            $customer->total_page = 0;

            foreach ($visitors as $visitor){
                if($customer->email == $visitor->visitor_email){
                    $customer->total_page = $visitor->total_page;
                }
            }

            if($customer->total_page == 0) {
                unset($customers[$key]);
            }

        }


        //Response view
        return view('backend.report.index',[
            'promotions_enquiries' =>$promotions_enquiries,
            'promotions'=> $promotions,
            'leads'=> $leads,
            'campaigns'=> $campaigns,
            'customers'=> $customers

        ]);
    }

    public function pieChart(){
        // Get Enquiries per Promotion
        $leads = DB::table("lead")
            ->where('deleted_at',null)
            ->select(DB::raw("promotion_id, COUNT(client_id) as total_leads"))
            ->groupBy("promotion_id")
            ->get();


        $promotions_enquiries = Promotion::select('promotion_id','name')->get();
        foreach ($promotions_enquiries as &$promo){

            $promo->total_leads = 0;

            foreach ($leads as $lead){
                if($promo->promotion_id == $lead->promotion_id){
                    $promo->total_leads = $lead->total_leads;
                }
            }
        }

//        $promotions_enquiries =  json_encode($promotions_enquiries);

        return response()->json(['data'=> $promotions_enquiries]);

    }

}

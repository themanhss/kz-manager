<?php
namespace App\Http\Controllers\Customer\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User as User;
use App\Models\Client as Customer;
use App\Models\Visitors as Visitors;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\ServiceProvider;
use Mailchimp;

class CustomerController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | User Admin Controller
    |--------------------------------------------------------------------------
    */
    private $_userModel;
    protected $mailchimp;
    protected $listId = '533a922a30';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\Mailchimp $mailchimp)
    {
        $this->middleware('auth');
        //Init Entity Model
        $this->_userModel = new User();
        $this->mailchimp = $mailchimp;
    }

    /**
     * Show the user list at backend.
     *
     * @return Response
     */
    public function index()
    {


        //Get all customer
        $customers = Customer::all();

        //Response view
        return view('customer.backend.index', ['customers' => $customers]);
    }

    /*
     *Create a new Customer
     *
     *@POST("/admin/customers/create")
     *@Param: ({'first_name','last_name', 'email', 'phone', 'state', 'suburb','postcode'})
     *@Version("v1")
     */
    public function create(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $datas = $request->all();

            $datas['opt_in'] = isset($datas['opt_in']) ? true : false;

            /*Check if request is ajax form*/
            if ($request->get('post_type')) {
                /*Validation form*/
                $validator = Validator::make($request->get('datas'), [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email',
                    'mobile_phone' => 'required|numeric',
                    'suburb' => 'required|max:255',
                    'postcode' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'validator' => $validator->errors()]);
                }

                $customer = new Customer();
                $temp_customer = $request->get('datas');
                $customer->first_name = $temp_customer['first_name'];
                $customer->last_name = $temp_customer['last_name'];
                $customer->email = $temp_customer['email'];
                $customer->mobile_phone = $temp_customer['mobile_phone'];
                $customer->state = $temp_customer['state'];
                $customer->suburb = $temp_customer['suburb'];
                $customer->postcode = $temp_customer['postcode'];

                if ($customer->save()) {
                    $this->addEmailToList($customer, $datas['group_id'], $datas['opt_in']);
                    return response()->json(['status' => 1, 'customer_name' => $customer->first_name . ' ' . $customer->last_name, 'customer_id' => $customer->id]);
                }
            }

            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'mobile_phone' => 'required|numeric',
                'suburb' => 'required|max:255',
                'postcode' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return redirect('admin/customers/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new user*/
            $customer = new Customer();
            $customer->first_name = $datas['first_name'];
            $customer->last_name = $datas['last_name'];
            $customer->email = $datas['email'];
            $customer->mobile_phone = $datas['mobile_phone'];
            $customer->state = $datas['state'];
            $customer->suburb = $datas['suburb'];
            $customer->postcode = $datas['postcode'];

            if ($customer->save()) {
                //Get all customer
                $this->addEmailToList($customer, $datas['group_id'], $datas['opt_in']);
                //Response view
                return redirect()->action('Customer\Backend\CustomerController@index');
            }
        }

        $groups = $this->mailchimp->lists->getList();

        return view('customer.backend.create')->with('groups', $groups);
    }

    /**
     * Access the mailchimp lists API
     * for more info check "https://apidocs.mailchimp.com/api/2.0/lists/subscribe.php"
     * @param Customer $customer
     * @param Mailchimp Group $group_id
     * @param Mailchimp Welcome mail $group_id
     */
    public function addEmailToList($customer, $group_id, $single_opt)
    {
        try {
            $user = $this->mailchimp->lists->subscribe($group_id, array('email' => $customer->email), array('FNAME' => $customer->first_name, 'LNAME' => $customer->last_name), 'html', false, false, true, true);
            \Log::Debug($user);

            if($single_opt)
                $this->mailchimp->lists->unsubscribe($group_id, ['email' => $user['email']] , false, false, false);

        } catch (\Mailchimp_List_AlreadySubscribed $e) {
            \Log::Debug($e->getMessage());
        } catch (\Mailchimp_Error $e) {
            \Log::Debug($e->getMessage());
        }

//        try {
//
//            if($single_opt && $user)
//                $this->mailchimp->lists->unsubscribe($user['leid'], $user['email'] , false, false, false);
//        } catch (\Mailchimp_Error $e) {
//            \Log::Debug(' unsubscribe ' . $e->getMessage());
//        }
    }

    /*
     *Update a Customer
     *
     *@POST("/admin/customers/edit/id")
     *@Param: ({'first_name','last_name', 'email', 'phone', 'state', 'suburb','postcode'})
     *@Version("v1")
     */
    public function edit($id, Request $request)
    {
        $customer = Customer::find($id);
        if ($customer == null) {
            //Response view
            return redirect()->action('Customer\Backend\CustomerController@index');
        }
        if ($request->getMethod() == 'POST') {


            $datas = $request->all();
            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'mobile_phone' => 'required|numeric',
                'suburb' => 'required|max:255',
                'postcode' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return redirect('admin/customers/edit/' . $customer->id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $old_email = $customer->email;
            if ($old_email) {
                $this->mailchimp->lists->unsubscribe($this->listId, ['email' => $old_email], true, true, false);
            }


            /*Save new user*/
            $customer->first_name = $datas['first_name'];
            $customer->last_name = $datas['last_name'];
            $customer->email = $datas['email'];
            $customer->mobile_phone = $datas['mobile_phone'];
            $customer->state = $datas['state'];
            $customer->suburb = $datas['suburb'];
            $customer->postcode = $datas['postcode'];

            if ($customer->save()) {

                $this->addEmailToList($datas['email'], $datas['first_name'], $datas['last_name']);
                //Response view
                return redirect()->action('Customer\Backend\CustomerController@index');
            } else {

            }

        }
        return view('customer.backend.edit', ['customer' => $customer]);
    }

    /*
     * Delete customer's information
     *@POST("/admin/customers/delete/id")
     *@Param: ({'id'})
     *@Version("v1")
     */
    public function delete($id, Request $request)
    {
        $customer = Customer::find($id);
        $email_customer = $customer->email;

        if ($customer == null) {
            //Response view
            return redirect()->action('Customer\Backend\CustomerController@index');
        }
        if ($request->getMethod() == 'POST') {
            $result = $customer->delete();

            if ($result) {
                if ($email_customer) {
                    $this->mailchimp->lists->unsubscribe($this->listId, ['email' => $email_customer], true, true, false);
                }
                //Redirect to list Customer
                return redirect()->action('Customer\Backend\CustomerController@index');
            }

        }
        return view('customer.backend.delete', ['customer' => $customer]);
    }

    /**
     * Show the Customer profile at backend.
     *
     * @return Response
     */
    public function profile($id)
    {
        $customer = Customer::find($id);
        $customer_email = $customer->email;
        $pages = Visitors::where('visitor_email', $customer_email)->get();

        if ($customer == null) {
            //Response view
            return redirect()->action('Customer\Backend\CustomerController@index');
        }

        return view('customer.backend.profile', ['customer' => $customer, 'pages' => $pages]);
    }

    /*
     * Import Customer (import from .csv file)
     * @POST("/admin/customers/import")
     * @Param:({})
     * @Version("v1")
     * */
    public function importCustomer(Request $request)
    {

        // get the results
        if ($request->getMethod() == 'POST') {

            $opt_in = null !== $request->input('opt_in') ? true : false;

            $groups = $this->mailchimp->lists->getList();

            /*Validate request*/
            $validator = Validator::make($request->all(), [
                'import_customer' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('admin/customers/import')
                    ->withErrors($validator)
                    ->withInput();
            }

            $file = array_get($request->all(), 'import_customer');
            if ($file) {
                // SET UPLOAD PATH
                $destinationPath = "uploads/customer/";
                // GET THE FILE EXTENSION
                $extension = $file->getClientOriginalExtension();
                // RENAME THE UPLOAD WITH RANDOM NUMBER
                $fileName = rand(1111111, 9999999) . '.' . $extension;
                // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                $upload_success = $file->move($destinationPath, $fileName);

                if ($upload_success) {

                    $customers = Excel::load('uploads/customer/' . $fileName, function ($reader) {
                        // Getting all results
                        $results = $reader;
                        return $results;
                    })->get();

                    foreach ($customers as &$customer) {

                        $temp = array();
                        $temp['first_name'] = $customer->first_name;
                        $temp['last_name'] = $customer->last_name;
                        $temp['email'] = $customer->email;
                        $temp['mobile_phone'] = $customer->mobile_phone;
                        $temp['state'] = $customer->state;
                        $temp['postcode'] = $customer->postcode;
                        $temp['suburb'] = $customer->suburb;

                        $res = $this->validateCustomer($temp);

                        if ($res['status']) {
                            $customer->status = 1;
                            $customer->errors = $res['errors'];

                            if ($this->checkEmailExist($customer->email)) {
                                $customer_save = new Customer();
                            } else {
                                $customer_save = Customer::where('email', '=', $customer->email)->first();
                            }

                            $customer_save->first_name = $customer->first_name;
                            $customer_save->last_name = $customer->last_name;
                            $customer_save->email = $customer->email;
                            $customer_save->mobile_phone = $customer->mobile_phone;
                            $customer_save->state = $customer->state;
                            $customer_save->postcode = $customer->postcode;
                            $customer_save->suburb = $customer->suburb;

                            if ($customer_save->save()) {
                                $customer->id = $customer_save->id;
                                $this->addEmailToList($customer, $request->input('group_id'), $opt_in);
                            } else {
                                $customer->id = null;
                            }

                        } else {
                            $customer->status = 0;
                            $customer->errors = $res['errors'];
                        }

                    }

                    return view('customer.backend.import', ['customers' => $customers, 'status' => 1, 'groups' => $groups]);
                }
            }
        }
        
        $groups = $this->mailchimp->lists->getList();

        return view('customer.backend.import', ['customers' => null, 'status' => 0, 'groups' => $groups]);
    }

    /*
     * Check Validate Customer Info
     * @Param : ({})
     * */
    private function validateCustomer($customer)
    {

        $validator = Validator::make($customer, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile_phone' => 'required|numeric',
            'suburb' => 'required',
            'postcode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        };
        return [
            'status' => true,
            'errors' => $validator->errors()
        ];
    }

    /*
     * Check Email if exit in system
     * @Param: ({'email'})
     * @Version("v1")
     * */
    private function checkEmailExist($email)
    {
        $userExist = Customer::where(array('email' => $email))->count();
        if ($userExist > 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Get All Customer information
     * @Param: none
     * @Return : listCustomer object
     * @Version("V1")
     * */
    public function allCustomer(Request $request)
    {
//        $text_search = strtoupper($request->get('text_search'));
        $text_search = $request->get('text_search');

        $customers = Customer::Where('first_name', 'ILIKE', '%' . $text_search . '%')->orWhere('last_name', 'ILIKE', '%' . $text_search . '%')->get();

        $results = array();
        if (!empty($customers)) {
            foreach ($customers as $key => $customer) {
                $temp = new \stdClass();
                $temp->id = $customer->id;
                $temp->name = $customer->first_name . ' ' . $customer->last_name;


//                array_push($results,$customer->first_name.' '.$customer->last_name);
                array_push($results, $temp);
            }
        };
        return response()->json(['customers' => $results]);
    }

    /*
     * API get All customer
     * @Method: 'POST'
     * @Param: {}
     * @Return: json
     * @Version("V1")
     * */
    public function getCustomer(Request $request)
    {
        $customers = Customer::all();

        if ($request->getMethod() == 'GET') {
            $state = $request->get('state');
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $campaign_id = $request->get('campaign_id');

            if ($campaign_id) {
                $fields = ['c.id', 'c.email', 'c.first_name', 'c.last_name', 'c.mobile_phone', 'c.state', 'c.postcode', 'c.suburb', \DB::raw("TO_CHAR(c.created_at, 'DD/MM/YYYY') as created_at")];
                if ($state == "0") {
                    if ($start_date == 'null' && $end_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                    if ($start_date != 'null' && $end_date != 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->whereBetween('updated_at', [$start_date, $end_date])
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                    if ($start_date != 'null' && $end_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('updated_at', '>=', $start_date)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                    if ($end_date != 'null' && $start_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('updated_at', '<=', $end_date)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                } else {
                    if ($start_date == 'null' && $end_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('state', '=', $state)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                    if ($start_date != 'null' && $end_date != 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->whereBetween('updated_at', [$start_date, $end_date])
                            ->where('state', '=', $state)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }


                    if ($start_date != 'null' && $end_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('updated_at', '>=', $start_date)
                            ->where('state', '=', $state)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                    if ($end_date != 'null' && $start_date == 'null') {
                        $customers = \DB::table('communication AS m')
                            ->join('clients AS c', 'c.id', '=', 'm.client_id')
                            ->where('updated_at', '<=', $end_date)
                            ->where('state', '=', $state)
                            ->where('m.campaign_id', $campaign_id)
                            ->get($fields);
                    }

                }
            } else if ($state == "0") {
                if ($start_date == 'null' && $end_date == 'null') {
                    $customers = Customer::all();
                }

                if ($start_date != 'null' && $end_date != 'null') {
                    $customers = Customer::whereBetween('updated_at', [$start_date, $end_date])->get();
                }

                if ($start_date != 'null' && $end_date == 'null') {
                    $customers = Customer::where('updated_at', '>=', $start_date)->get();
                }

                if ($end_date != 'null' && $start_date == 'null') {
                    $customers = Customer::where('updated_at', '<=', $end_date)->get();
                }

            } else {
                if ($start_date == 'null' && $end_date == 'null') {
                    $customers = Customer::where('state', '=', $state)->get();
                }

                if ($start_date != 'null' && $end_date != 'null') {
                    $customers = Customer::whereBetween('updated_at', [$start_date, $end_date])->where('state', '=', $state)->get();
                }

                if ($start_date != 'null' && $end_date == 'null') {
                    $customers = Customer::where('updated_at', '>=', $start_date)->where('state', '=', $state)->get();
                }

                if ($end_date != 'null' && $start_date == 'null') {
                    $customers = Customer::where('updated_at', '<=', $end_date)->where('state', '=', $state)->get();
                }

            }


            return response()->json(['data' => $customers]);
        }

        return response()->json(['status' => 0, 'customers' => $customers]);
    }

}

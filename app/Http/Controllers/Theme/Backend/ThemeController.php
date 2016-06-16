<?php
namespace App\Http\Controllers\Theme\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Productvariant;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User as User;
use App\Models\Client as Customer;
use App\Models\Theme as Theme;
use App\Models\Themefield as Themefield;
use App\Models\Variant as Variant;
use DB;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        /*$current_user = Auth::user();
        if($current_user->isSuperAdmin == 1){
            return response()->view('errors.404', [], 404);
        }*/
    }

    /**
     * Show All the Theme list at backend.
     *
     * @return Response
     */
    public function index()
    {
        //Get all theme
        $themes = Theme::all();

        //Response view
        return view('theme.backend.index', ['themes' => $themes]);
    }

    /*
     *Create a new Theme
     *
     *@POST("/admin/themes/create")
     *@Param: ({'first_name','last_name', 'email', 'phone', 'state', 'suburb','postcode'})
     *@Version("v1")
     */
    public function create(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $datas = $request->all();

            // save new Theme
            if(!$datas['is_edit']){
                $theme = new Theme();
            }else{
                $theme = Theme::find($datas['theme_id']);
            }

            $theme->name = $datas['name'];

            if($theme->save()){
                $theme_id = $theme->theme_id;
            }

            // save one by one theme field
            if (!empty($datas['theme_field'])){
                foreach ($datas['theme_field'] as $theme_field) {
                    if ($theme_field['theme_field_promo_id']){
                        $field = Themefield::find($theme_field['theme_field_promo_id']);
                    }else{
                        $field = new Themefield;
                    }

                    $field->theme_id = $theme_id;
                    $field->field_name = $theme_field['field_name'];
                    $field->field_help_image = $theme_field['field_help_image'];
                    $field->order = $theme_field['order'];
                    $field->field_type = $theme_field['field_type'];
                    $field->promo_or_product = $theme_field['tab_selected'];

                    $field->save();
                }
            }

            return response()->json(['status' => 1]);

        }

        return view('theme.backend.create');
    }

    /*
     * Upload Image Theme File
     * @POST/AJAX("/admin/themes/uploadImage")
     * @Version("v1")
     * */

    public function uploadImage(Request $request){
        if ($request->getMethod() == 'POST'){
            $datas = $request->all();
            $file = $request->file('field_help_image');
            if ($file) {
                // SET UPLOAD PATH
                $destinationPath = "uploads/themes/";
                // GET THE FILE EXTENSION
                $extension = $file->getClientOriginalExtension();
//                $img = $file['name'];
//                $ext = strtolower(pathinfo($extension, PATHINFO_EXTENSION));
                // RENAME THE UPLOAD WITH RANDOM NUMBER
//                $fileName = rand(11111, 99999) . '.' . $extension;
                $fileName =  $file->getClientOriginalName();
                // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                $upload_success = $file->move($destinationPath, $fileName);


                if ($upload_success) {
                    return response()->json(['status'=> 1,'image_name'=> $fileName]);
                }
            } else {
                return response()->json(['status'=> 0]);
            }
        }

    }

    /*
     *Update a Theme
     *
     *@POST("/admin/themes/edit/id")
     *@Param: ({'first_name','last_name', 'email', 'phone', 'state', 'suburb','postcode'})
     *@Version("v1")
     */
    public function edit($id, Request $request)
    {
        $theme = Theme::find($id);

        $theme_fields = Themefield::where('theme_id','=',$id)->orderBy('order','ASC')->get();

        return view('theme.backend.edit',['theme'=>$theme, 'theme_fields'=> $theme_fields]);
    }

    /*
     * Delete theme's information
     *@POST("/admin/themes/delete")
     *@Param: ({'id'})
     *@Version("v1")
     */
    public function delete(Request $request)
    {
        // check request method
        if($request->getMethod() == 'POST'){
            $theme_id = $request->get('theme_id');
            $theme = Theme::find($theme_id);

            $result = $theme->delete();
            if($result) {
                return response()->json(['status'=> 1,'theme_id'=> $theme_id]);
            }

        }
        return response()->json(['status'=>0]);
    }

    /**
     * Show the Customer profile at backend.
     *
     * @return Response
     */
    public function profile($id)
    {
        $customer = Customer::find($id);
        if($customer == null) {
            //Response view
            return redirect()->action('Customer\Backend\CustomerController@index');
        }

        return view('customer.backend.profile',['customer'=>$customer]);
    }

    /*
     * Import Customer (import from .csv file)
     * @POST("/admin/customers/import")
     * @Param:({})
     * @Version("v1")
     * */
    public function importCustomer(Request $request){

        // get the results
        if($request->getMethod() == 'POST'){

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

                    $customers = Excel::load('uploads/customer/'.$fileName, function($reader) {
                        // Getting all results
                        $results = $reader;
                        return $results;
                    })->get();

                    foreach($customers as &$customer){

                        $temp = array();
                        $temp['first_name'] = $customer->first_name;
                        $temp['last_name'] = $customer->last_name;
                        $temp['email'] = $customer->email;
                        $temp['mobile_phone'] = $customer->mobile_phone;
                        $temp['state'] = $customer->state;
                        $temp['postcode'] = $customer->postcode;
                        $temp['suburb'] = $customer->suburb;

                        $res = $this->validateCustomer($temp);

                        if($res['status']){
                            $customer->status = 1;
                            $customer->errors = $res['errors'];

                            if($this->checkEmailExist($customer->email)){
                                $customer_save = new Customer();
                            }else{
                                $customer_save = Customer::where('email','=',$customer->email)->first();
                            }

                            $customer_save->first_name = $customer->first_name;
                            $customer_save->last_name = $customer->last_name;
                            $customer_save->email = $customer->email;
                            $customer_save->mobile_phone = $customer->mobile_phone;
                            $customer_save->state = $customer->state;
                            $customer_save->postcode = $customer->postcode;
                            $customer_save->suburb = $customer->suburb;

                            if($customer_save->save())
                            {
                                $customer->id = $customer_save->id;
                            }else{
                                $customer->id = null;
                            }

                        }else{
                            $customer->status = 0;
                            $customer->errors = $res['errors'];
                        }

                    }

                    return view('customer.backend.import',['customers'=>$customers,'status'=>1]);
                }
            }
        }


        return view('customer.backend.import',['customers'=>null,'status'=>0]);
    }

    /*
     * Check Validate Customer Info
     * @Param : ({})
     * */
    private function validateCustomer($customer){

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
                'status'=> false,
                'errors'=> $validator->errors()
            ];
        };
        return [
            'status'=> true,
            'errors'=> $validator->errors()
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
    public function allCustomer(Request $request){
//        $text_search = strtoupper($request->get('text_search'));
        $text_search = $request->get('text_search');

        $customers = Customer::Where('first_name', 'ILIKE', '%' . $text_search . '%')->orWhere('last_name', 'ILIKE', '%' . $text_search . '%')->get();

        $results = array();
        if (!empty($customers)){
            foreach ($customers as $key=> $customer){
                $temp = new \stdClass();
                $temp->id = $customer->id;
                $temp->name = $customer->first_name.' '.$customer->last_name;


//                array_push($results,$customer->first_name.' '.$customer->last_name);
                array_push($results,$temp);
            }
        };
        return response()->json(['customers'=>$results]);
    }

    /*
     * Edit or add New Pricing Page Content
     * @POST('/admin/themes/{theme_id}/pricing')
     * @Params({'theme_id'})
     * @Version('v1')
     * */
    public function editPricing($theme_id, Request $request){
        $theme = Theme::find($theme_id);
        $old_variants = Variant::where('theme_id',$theme_id)->get();

        // Process method POST
        if($request->getMethod() == 'POST') {
            $datas = $request->all();
            $variants = json_decode($datas['datas']);
            $theme_id = $datas['theme_id'];
            $remove_id = $datas['remove_id'];

            // Process remove variant
            $pieces = explode(",", $remove_id);
            if ($pieces[0] != "" && $pieces[0] != null){

                for ($i =0 ; $i<count($pieces); $i++){

                    // Get all variant has been remove
                    $vari = Variant::find($pieces[$i]);

                    // Get all promotion has theme_id = $theme_id
                    $promotions = Promotion::where('theme_id',$theme_id)->get();

                    // Get all Product belong to promotions
                    foreach ($promotions as $promo){
                        $products = Product::where('promotion_id',$promo->promotion_id)->get();

                        // remove all product_variant belong to product
                        foreach ($products as $pro){
                            $pro_variants = Productvariant::where('product_id',$pro->product_id)->get();

                            // delete all pro_variant belong to product
                            foreach ($pro_variants as $pro_vari){
                                $pro_vari->delete();
                            }
                        }
                    }


                    // Delete variant
                    $vari->delete();
                }
            }

            // Add new or update Variant
            foreach ($variants as $key=> $variant){
                if ($variant->variant_id !=0) {
                    $vari = Variant::find($variant->variant_id);
                }else{
                    $vari =  new Variant();
                }

                $vari->theme_id = $theme_id;
                $vari->label = $variant->label;
                $vari->options = $variant->tags;
                $vari->save();
            }

            return response()->json(['status'=>1, 'theme_id'=> $theme_id]);


        }

        return view('theme.backend.pricing.edit',
            [
                'theme'=> $theme,
                'old_variants'=> $old_variants
            ]);
    }

}

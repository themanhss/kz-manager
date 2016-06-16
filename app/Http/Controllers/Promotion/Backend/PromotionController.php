<?php
namespace App\Http\Controllers\Promotion\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Variant;
use Illuminate\Http\Request;
use Validator;
use App\Models\User as User;
use App\Models\Promotion as Promotion;
use App\Models\Theme as Theme;
use App\Models\Product as Product;
use App\Models\Lead as Lead;
use App\Models\Client as Customer;
use App\Models\Campaign as Campaign;
use App\Models\Communication as Communication;
use App\Models\Themefield as Themefield;
use App\Models\Productvariant as Productvariant;
use DB;
use DateTime;
use Mbarwick83\Shorty\Facades\Shorty;

class PromotionController extends Controller
{

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
        $this->middleware('auth');
    }

    /**
     * Show the promotions list at backend.
     *
     * @return Response
     */
    public function index()
    {
        //Get all Promotions
        $promotions = Promotion::all();
        //Response view
        return view('promotion.backend.index', ['promotions' => $promotions]);
    }

    /**
     * Create a new Promotion
     *
     * @POST("/admin/promotions/create")
     * @Param: ({'name','start_date', 'end_date', 'status', 'theme', 'Description'})
     * @Version("v1")
     */
    public function create(Request $request)
    {
        $themes = Theme::all();

        if ($request->getMethod() == 'POST') {

            $datas = $request->all();

            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'start_date' => 'date_format:d/m/Y',
                'end_date' => 'date_format:d/m/Y|after:start_date',
                'promotion_key' => 'required|max:5'
            ]);

            if ($validator->fails()) {
                return redirect('admin/promotions/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new promotion*/
            $promotion = new Promotion();

            $promotion->name = $datas['name'];

            $promotion->theme_id = NULL;
            $promotion->status = $datas['status'];

            if ($datas['theme_id']) {
                $promotion->theme_id = $datas['theme_id'];
            }

            $promotion->start_date = NULL;
            if ($datas['start_date']) {
                $promotion->start_date = DateTime::createFromFormat('d/m/Y', $datas['start_date'])->format('Y-m-d');
            }

            $promotion->end_date = NULL;
            if ($datas['end_date']) {
                $promotion->end_date = DateTime::createFromFormat('d/m/Y', $datas['end_date'])->format('Y-m-d');
            }

            $promotion->promotion_key = $datas['promotion_key'];

            $promotion->description = $datas['description'];

            if ($promotion->save()) {
                return redirect()->action('Promotion\Backend\PromotionController@index');
            } else {

            }

        }
        return view('promotion.backend.create', ['themes' => $themes]);
    }

    /**
     * Edit a new Promotion
     *
     * @POST("/admin/promotions/edit/promotion_id")
     * @Param: ({'name','start_date', 'end_date', 'status', 'theme', 'Description'})
     * @Version("v1")
     */
    public function edit($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        $themes = Theme::all();

        if ($promotion == null) {
            //Response view
            return redirect()->action('Promotion\Backend\PromotionController@index');
        }

        if ($request->getMethod() == 'POST') {

            $datas = $request->all();

            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'start_date' => 'date_format:d/m/Y',
                'end_date' => 'date_format:d/m/Y|after:start_date',
                'promotion_key' => 'required|max:5'
            ]);

            if ($validator->fails()) {
                return redirect('admin/promotions/edit/' . $promotion_id)
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Update promotion*/

            $promotion->name = $datas['name'];

            //$promotion->theme_id = NULL;
            $promotion->status = $datas['status'];

            if ($datas['theme_id']) {
                if ($promotion->theme_id != $datas['theme_id']) {
                    $promotion->fields_json = '';

                    // Delete Product Variant before change Theme of promotion
                    // Get all promotion has theme_id = $theme_id
                    $promotions = Promotion::where('theme_id', $promotion->theme_id)->get();

                    // Get all Product belong to promotions
                    foreach ($promotions as $promo) {
                        $products = Product::where('promotion_id', $promo->promotion_id)->get();

                        // remove all product_variant belong to product
                        foreach ($products as $pro) {
                            $pro_variants = Productvariant::where('product_id', $pro->product_id)->get();

                            // delete all pro_variant belong to product
                            foreach ($pro_variants as $pro_vari) {
                                $pro_vari->delete();
                            }
                        }
                    }


                }
                $promotion->theme_id = $datas['theme_id'];
            }

            $promotion->start_date = NULL;
            if ($datas['start_date']) {
                $promotion->start_date = DateTime::createFromFormat('d/m/Y', $datas['start_date'])->format('Y-m-d');
            }

            $promotion->end_date = NULL;
            if ($datas['end_date']) {
                $promotion->end_date = DateTime::createFromFormat('d/m/Y', $datas['end_date'])->format('Y-m-d');
            }

            $promotion->promotion_key = $datas['promotion_key'];

            $promotion->description = $datas['description'];

            if ($promotion->save()) {
                // Redirect to Promotion profile page
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }
        return view('promotion.backend.edit', ['promotion' => $promotion, 'themes' => $themes]);
    }

    /**
     * Delete Promotion information
     * @POST("/admin/promotions/delete/id")
     * @Param: ({'id'})
     * @Version("v1")
     */
    public function delete($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        if ($promotion == null) {
            //Response view
            return redirect()->action('Promotion\Backend\UserController@index');
        }
        if ($request->getMethod() == 'POST') {
            $promotion->delete();
            //Get all users
            return redirect()->action('Promotion\Backend\PromotionController@index');
        }
        return view('promotion.backend.delete', ['promotion' => $promotion]);
    }

    /**
     * Show the Promotion profile at backend.
     *
     * @return Response
     */
    public function profile($promotion_id)
    {
        $promotion = Promotion::find($promotion_id);
        $themes = Theme::all();
        $campaigns = Campaign::where('promotion_id', '=', $promotion_id)->get();

        if ($promotion == null) {
            //Response view
            return redirect()->action('Promotion\Backend\PromotionController@index');
        }


        return view('promotion.backend.profile', ['promotion' => $promotion, 'themes' => $themes, 'campaigns' => $campaigns]);
    }

    /**
     * Create New Promotion Page Content
     * @POST('/admin/promotions/{promotion_id}/page/create')
     * @Params({})
     * @Version('v1')
     * */
    public function createPage($promotion_id, Request $request)
    {

        return view('promotion.backend.page.create');
    }

    /**
     * Edit Promotion Page Content
     * @POST('/admin/promotions/{promotion_id}/page/edit')
     * @Params({})
     * @Version('v1')
     * */
    public function editPage($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        $theme = Theme::find($promotion->theme_id);

        $theme_fields = Themefield::where('theme_id', $promotion->theme_id)->where('promo_or_product', 'promotion')->orderBy('order', 'ASC')->get();
        $fields_json = json_decode($promotion->fields_json, true);

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();

            $results = array();
            foreach ($datas as $key => $data) {
                if ($key != '_token') {
                    $pieces = explode("-", $key);
                    if ($pieces[0] == 'fileUpload') {
                        $file = array_get($datas, 'fileUpload-' . $pieces[1]);
                        if ($file) {
                            // SET UPLOAD PATH
                            $destinationPath = "uploads/promotion/" . $promotion_id . "/";
                            // GET THE FILE EXTENSION
                            $extension = $file->getClientOriginalExtension();
                            // RENAME THE UPLOAD WITH RANDOM NUMBER
                            $fileName = rand(11111, 99999) . '.' . $extension;
                            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                            $upload_success = $file->move($destinationPath, $fileName);

                            if ($upload_success) {
                                $data = $fileName;
                            }
                        } else {
                            $data = '';
                        }
                    }

                    $results[$pieces[1]] = array();
                    $results[$pieces[1]]['type'] = $pieces[0];
                    $results[$pieces[1]]['data'] = $data;
                    $results[$pieces[1]]['id'] = $key;
                }
            }
            $fields_json = json_encode($results);

            $promotion->fields_json = $fields_json;
            if ($promotion->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }
        return view('promotion.backend.page.edit',
            [
                'promotion' => $promotion,
                'theme' => $theme,
                'theme_fields' => $theme_fields,
                'fields_json' => $fields_json
            ]);
    }


    /**
     * Edit Promotion Page Content
     * @POST('/admin/promotions/{promotion_id}/page/delete')
     * @Params({})
     * @Version('v1')
     * */
    public function deletePage($promotion_id, Request $request)
    {

        return view('promotion.backend.page.delete');
    }


    /**
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

    /**
     * Create Product Belong to Promotion
     * @POST('/admin/promotions/{promotion_id}/product/create')
     * @Param: ({'promotion_id'})
     * @Version("V1")
     * */
    public function createProduct($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);

        if ($promotion == null) {
            //Response view
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();
            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'product_key' => 'required|max:5',
//                'price'=> 'required|integer|min:0',
                'description' => 'required'
            ]);


            if ($validator->fails()) {
                return redirect('admin/promotions/' . $promotion_id . '/product/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new product*/

            $product = new Product();
            $product->name = $datas['name'];
            $product->status = $datas['status'];
            $product->promotion_id = $promotion_id;
            $product->promotion_key = $promotion->promotion_key;
            $product->product_key = $datas['product_key'];
//            $product->price = $datas['price'];
            $product->description = $datas['description'];

            if ($product->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }

        return view('promotion.backend.product.create', ['promotion' => $promotion]);
    }

    /**
     * Edit Product
     * @POST('/admin/promotions/{promotion_id}/product/edit/{product_id}')
     * @Param: ({'promotion_id','product_id'})
     * @Version("V1")
     * */
    public function editProduct($promotion_id, $product_id, Request $request)
    {
        $product = Product::find($product_id);
        $promotion = Promotion::find($promotion_id);

        if ($product == null) {
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();
            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'product_key' => 'required|max:5',
//                'price'=> 'required|integer|min:0',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('admin/promotions/' . $promotion_id . '/product/edit/' . $product_id)
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new product*/
            $product->name = $datas['name'];
            $product->status = $datas['status'];
            $product->promotion_id = $promotion_id;
            $product->promotion_key = $promotion->promotion_key;
            $product->product_key = $datas['product_key'];
//            $product->price = $datas['price'];
            $product->description = $datas['description'];

            if ($product->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }

        return view('promotion.backend.product.edit', ['product' => $product, 'promotion' => $promotion]);
    }

    /**
     * Delete Product
     * @POST('/admin/promotions/{promotion_id}/product/delete/{product_id}')
     * @Param: ({'promotion_id'})
     * @Version("V1")
     * */
    public function deleteProduct($promotion_id, Request $request)
    {

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();

            $product_id = $datas['product_id'];
            $product = Product::find($product_id);
            if ($product->delete()) {
                return response()->json(['status' => 1, 'product_id' => $product_id]);
            } else {
                return response()->json(['status' => 0, 'product_id' => $product_id]);
            }
        }

    }

    /**
     * Edit Page Product
     * @POST('/admin/promotions/{promotion_id}/product/page/{product_id}')
     * @Param: ({'promotion_id', 'product_id'})
     * @Version("V1")
     * */
    public function editPageProduct($promotion_id, $product_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        $product = Product::find($product_id);
        $theme = Theme::find($promotion->theme_id);

        $theme_fields = Themefield::where('theme_id', $promotion->theme_id)->where('promo_or_product', 'product')->orderBy('order', 'ASC')->get();
        $fields_json = json_decode($product->fields_json, true);

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();

            $results = array();
            foreach ($datas as $key => $data) {
                if ($key != '_token') {
                    $pieces = explode("-", $key);
                    if ($pieces[0] == 'fileUpload') {
                        $file = array_get($datas, 'fileUpload-' . $pieces[1]);
                        if ($file) {
                            // SET UPLOAD PATH
                            $destinationPath = "uploads/promotion/" . $promotion_id . "/";
                            // GET THE FILE EXTENSION
                            $extension = $file->getClientOriginalExtension();
                            // RENAME THE UPLOAD WITH RANDOM NUMBER
                            $fileName = rand(11111, 99999) . '.' . $extension;
                            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                            $upload_success = $file->move($destinationPath, $fileName);

                            if ($upload_success) {
                                $data = $fileName;
                            }
                        } else {
                            $data = '';
                        }
                    }

                    $results[$pieces[1]] = array();
                    $results[$pieces[1]]['type'] = $pieces[0];
                    $results[$pieces[1]]['data'] = $data;
                    $results[$pieces[1]]['id'] = $key;
                }
            }
            $fields_json = json_encode($results);

            $product->fields_json = $fields_json;
            if ($product->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }

        return view('promotion.backend.product.page',
            [
                'promotion' => $promotion,
                'product' => $product,
                'theme' => $theme,
                'theme_fields' => $theme_fields,
                'fields_json' => $fields_json
            ]);
    }

    /**
     * Edit Page Product Pricing
     * @POST('/admin/promotions/{promotion_id}/product/{product_id}/pricing')
     * @Param: ({'promotion_id', 'product_id'})
     * @Version("V1")
     * */
    public function editProductPricing($promotion_id, $product_id, Request $request)
    {

        $promotion = Promotion::find($promotion_id);
        $product = Product::find($product_id);
        $theme_id = $promotion->theme_id;
        $theme = Theme::find($theme_id);
        $variants = Variant::where('theme_id', $theme_id)->get();
        $product_variants = Productvariant::where('product_id', $product_id)->get();

        foreach ($variants as $key => $variant) {
            $temp_variant[$key] = $variant->options;
        }

        $mixeds = array();
        $row_array = array();
        $i = 0;

        while ($i < count($temp_variant)) {
            $row_array[$i] = explode(",", $temp_variant[$i]);
            $mixeds = $this->mix2array($mixeds, $row_array[$i]);
            $i = $i + 1;
        };

        $variants_result = array();

        for ($i = 0; $i < count($mixeds); $i++) {
            $variants_result[$i] = array();

            $variants_result[$i]['options_config'] = $mixeds[$i];
            $variants_result[$i]['product_variant_id'] = -1;
            $variants_result[$i]['price'] = -1;

            foreach ($product_variants as $key => $pro) {
                if ($pro->options_config == $mixeds[$i]) {
                    $variants_result[$i]['product_variant_id'] = $pro->product_variant_id;
                    $variants_result[$i]['price'] = $pro->price;
                }
            }
        }

        /*foreach ($product_variants as $pro){
            var_dump($pro->options_config);
        }
        die();*/
        if ($request->getMethod() == 'POST') {

            $prices = $request->get('prices');
            $ids = $request->get('id');

            foreach ($mixeds as $key => $variant) {
                if ($ids[$key] > 0) {
                    $pv = Productvariant::find($ids[$key]);
                } else {
                    $pv = new Productvariant();
                }

                $pv->product_id = $product_id;
                $pv->options_config = $variant;
                $pv->price = $prices[$key];
                $pv->save();
            }

            return redirect('admin/promotions/profile/' . $promotion_id);

        }

        return view('promotion.backend.product.pricing',
            [
                'product' => $product,
                'variants' => $variants_result,
                'theme' => $theme,
                'promotion' => $promotion,
                'product_variants' => $product_variants
            ]);
    }

    /**
     * Function Mix two array
     *
     * */
    protected function mix2array($array1, $array2)
    {
        $result = array();

        if (count($array1) == 0) {
            $result = $array2;
            return $result;
        }
        if (count($array2) == 0) {
            $result = $array1;
            return $result;
        }

        for ($i = 0; $i < count($array1); $i++) {
            for ($j = 0; $j < count($array2); $j++) {
                $temp = $array1[$i] . ',' . $array2[$j];
                array_push($result, $temp);
            }
        }

        return $result;
    }

    /**
     * Create Lead Belong to Promotion
     * @POST('/admin/promotions/{promotion_id}/lead/create')
     * @Param: ({'promotion_id'})
     * @Version("V1")
     * */
    public function createLead($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        $products = Product::all();

        if ($promotion == null) {
            //Response view
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();
            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'date' => 'date_format:d/m/Y',
                'email' => 'required',
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('admin/promotions/' . $promotion_id . '/lead/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new lead*/
            $lead = new Lead();
            $lead->client_id = $datas['name'];
            $lead->promotion_id = $promotion_id;
            $lead->message = $datas['message'];
            $lead->email = $datas['email'];
            $lead->phone = $datas['phone'];

            // process product id
            $productLeads = null;

            if (array_key_exists('product', $datas)) {
                $productLeads = $datas['product'];
            }

            $product_ids = array();
            if (!empty($productLeads)) {
                foreach ($productLeads as $key => $productLead) {
                    array_push($product_ids, $productLead);
                }
            }
            $lead->product_ids = json_encode($product_ids);

            $lead->source_type = $datas['source_type'];

            // process lead create date
            $lead->date = NULL;
            if (!empty($datas['date'])) {
//                $lead->date = date('Y-m-d', strtotime($datas['date']));
                $lead->date = DateTime::createFromFormat('d/m/Y', $datas['date'])->format('Y-m-d');
            }

            if ($lead->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }

        return view('promotion.backend.lead.create', ['promotion' => $promotion, 'products' => $products]);
    }

    /**
     * Edit Product
     * @POST('/admin/promotions/{promotion_id}/product/edit/{product_id}')
     * @Param: ({'promotion_id','product_id'})
     * @Version("V1")
     * */
    public function editLead($promotion_id, $lead_id, Request $request)
    {
        $lead = Lead::find($lead_id);
        $promotion = Promotion::find($promotion_id);
        $products = Product::all();
        $client = Client::find($lead->client_id);

        if ($lead == null) {
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();

            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'date' => 'date_format:d/m/Y',
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('admin/promotions/' . $promotion_id . '/lead/edit/' . $lead->lead_id)
                    ->withErrors($validator)
                    ->withInput();
            }

            /*Save new Lead*/
            $lead->client_id = $datas['name'];
            $lead->promotion_id = $promotion_id;
            $lead->message = $datas['message'];
            $lead->email = $datas['email'];
            $lead->phone = $datas['phone'];

            // process product id
            $productLeads = $datas['product'];
            $product_ids = array();
            if (!empty($productLeads)) {
                foreach ($productLeads as $key => $productLead) {
                    array_push($product_ids, $productLead);
                }
            }


            $lead->product_ids = json_encode($product_ids);

            $lead->source_type = $datas['source_type'];

            // process lead create date
            $lead->date = NULL;
            if (!empty($datas['date'])) {
                $lead->date = DateTime::createFromFormat('d/m/Y', $datas['date'])->format('Y-m-d');
            }


            if ($lead->save()) {
                return redirect('admin/promotions/profile/' . $promotion_id);
            }

        }

        return view('promotion.backend.lead.edit', ['promotion' => $promotion, 'products' => $products, 'lead' => $lead, 'client' => $client]);
    }

    /**
     * Delete Lead
     * @POST('/admin/promotions/{promotion_id}/product/lead/{product_id}')
     * @Param: ({'promotion_id'})
     * @Version("V1")
     * */
    public function deleteLead($lead_id, Request $request)
    {

        if ($request->getMethod() == 'POST') {
            $datas = $request->all();

            $lead_id = $datas['lead_id'];
            $lead = Lead::find($lead_id);
            if ($lead->delete()) {
                return response()->json(['status' => 1, 'lead_id' => $lead_id]);
            } else {
                return response()->json(['status' => 0, 'lead_id' => $lead_id]);
            }
        }

    }

    /**
     * View Lead
     * @POST('/admin/promotions/{promotion_id}/product/edit/{product_id}')
     * @Param: ({'promotion_id','product_id'})
     * @Version("V1")
     * */
    public function viewLead($promotion_id, $lead_id, Request $request)
    {
        $lead = Lead::find($lead_id);
        $promotion = Promotion::find($promotion_id);
        $products = Product::all();
        $client = Client::find($lead->client_id);

        if ($lead == null) {
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        return view('promotion.backend.lead.view', ['promotion' => $promotion, 'products' => $products, 'lead' => $lead, 'client' => $client]);
    }

    /**
     * Get Chart Ajax
     * @POST('/admin/promotions/chart')
     * @Param: ({})
     * @Version("V1")
     *
     * */
    public function getViewChart()
    {

        $leads = DB::table("lead")
            ->where('deleted_at', null)
            ->select(DB::raw("promotion_id, COUNT(client_id) as total_leads"))
            ->groupBy("promotion_id")
            ->get();


        $promotions = Promotion::all();
        foreach ($promotions as &$promo) {

            $promo->total_leads = 0;

            foreach ($leads as $lead) {
                if ($promo->promotion_id == $lead->promotion_id) {
                    $promo->total_leads = $lead->total_leads;
                }
            }
        }

        $leads = json_encode($promotions);

        return response()->json(['promotions' => $promotions]);
    }

    /**
     * Create Campaign Belong to Promotion
     * @POST('/admin/promotions/{promotion_id}/campaign/create')
     * @Param: ({'promotion_id'})
     * @Version("V1")
     * */
    public function createSMSCampaign($promotion_id, Request $request)
    {
        $promotion = Promotion::find($promotion_id);
        $customers = Customer::all();
        if ($promotion == null) {
            //Response view
            return redirect('admin/promotions/profile/' . $promotion_id);
        }

        if ($request->getMethod() == 'POST') {

            $datas = $request->all();
            /*Validation form*/
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'sms_text' => 'required'
            ]);

            if ($validator->fails()) {
                //return response()->json(['status'=> 0, 'errors' => $validator->errors]);
                return redirect('/admin/promotions/' . $promotion_id . '/campaign/sms/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            $ids = $request->get('id');
            $name = $request->get('name');
            $sms_text = $request->get('sms_text');
            $ref = "abc123";

            /*Save new SMS Campaign*/
            $campaign = new Campaign;
            $campaign->name = $name;
            $campaign->type = "SMS Send";
            $campaign->send_date = date('Y-m-d H:i:s');
            $campaign->sms_text = $sms_text;
            $campaign->promotion_id = $promotion_id;

            if ($campaign->save()) {
                $campaign_id = $campaign->campaign_id;
            }

            // Search link in message content like : http://iag.saltandfuessel.com.au/promotion/5e98b/promo-v1?email=test@yahoo.com
            $pattern = '(https?:\/\/[^\s]+\?email=.*)';

            $sms_text = preg_replace_callback($pattern,
                function ($matches) {
                    return Shorty::shorten($matches[0]);
                },
                $sms_text
            );

            /*Send sms to customer*/
            foreach ($ids as $id) {
                $customer = Customer::find($id);
                $phone_number = $customer->mobile_phone;

                $sms_text_after = str_replace("@first_name@", $customer->first_name, $sms_text);

                $result = $this->sendText($sms_text_after, $phone_number, $ref);

                // success send sms
                if ($result == 1) {
                    $communication = new Communication();
                    $communication->client_id = $id;
                    $communication->date = date('Y-m-d H:i:s');
                    $communication->message = $sms_text_after;
                    $communication->campaign_id = $campaign_id;
                    $communication->phone = $phone_number;
                    $communication->email = $customer->email;

                    $communication->save();
                }
            }

            return redirect('admin/promotions/profile/' . $promotion_id);

            /*if($product->save()){
                return redirect('admin/promotions/profile/'.$promotion_id);
            }*/
        }

        return view('promotion.backend.campaign.createSMS', ['promotion' => $promotion, 'customers' => $customers]);
    }

    /**
     * Send message to Phone Number
     *
     * */
    protected function sendSMS($content)
    {
        $ch = curl_init(env('SMS_BROADCAST_URL')); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * FUNCTION TO SEND THE SMS TO SMS BROADCAST USING CURL
     * @Param({'message','phoneNumber','ref'})
     *
     * */
    protected function sendText($message, $phoneNumber, $ref)
    {
        $username = env('SMS_USERNAME'); 
        $password = env('SMS_PASSWORD');
        // $phoneNumber = '0400000000'; //Multiple numbers can be entered, separated by a comma
        $source = 'IAG';
        //$message = 'This is our test message.';
        //$ref = 'abc123';

        try {

            $content = 'username=' . rawurlencode($username) .
                '&password=' . rawurlencode($password) .
                '&to=' . rawurlencode($phoneNumber) .
                '&from=' . rawurlencode($source) .
                '&message=' . rawurlencode($message) .
                '&ref=' . rawurlencode($ref);

            $smsbroadcast_response = $this->sendSMS($content);

            $response_lines = explode("\n", $smsbroadcast_response);

            foreach ($response_lines as $data_line) {
                $message_data = explode(':', $data_line);
                if ($message_data[0] == "OK") {
//                    return "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]."\n";
                    return 1;
                } elseif ($message_data[0] == "BAD") {
//                    return "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2]."\n";
                    return 2;
                } elseif ($message_data[0] == "ERROR") {
//                    return "There was an error with this request. Reason: ".$message_data[1]."\n";
                    return 3;
                }
            }

            return 3;


        } catch (\Exception $e) {
            return array("ERROR", sprintf('sendText failed with error #%d: %s', $e->getCode(), $e->getMessage()));
        }
    }

    public function showCampaign($campaign_id) {
        $campaign = Campaign::whereCampaignId($campaign_id)->first();

        return view('promotion.backend.campaign.show', ['campaign' => $campaign]);
    }


}

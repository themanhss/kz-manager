<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __construct()
    {

    }

    public function index($slug, $id)
    {
        $promotionIdx = getPromotionIdx($id);

        if ( $promotionIdx !== false )
        {
            $product = new Product();
		    $calculatorData = $product->getProductsData('json');

            if ( $promotionIdx === 0 )
                return view('frontend/home', array( 'calculatorData' => $calculatorData ));
            else if ( $promotionIdx === 1 )
                return view('frontend/promotion/index', array( 'calculatorData' => $calculatorData, 'showNav2' => true ));
        }

        return response()->view('errors.404', [], 404);
    }

    public function process($promotionId)
    {
        $data = array();

        $promotionIdx = getPromotionIdx($promotionId);
        if ( $promotionIdx !== false )
        {
            if ( $promotionIdx === 1 )
                $data['showNav2'] = true;

            return view('frontend/promotion/process', $data);
        }

        return response()->view('errors.404', [], 404);
    }
}

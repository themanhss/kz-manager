<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {

    }

    public function index($product_slug, $product_id)
    {
        $product = new Product();
        $calculatorData = $product->getProductsData('json');

        $productSlug = getProductSlugByProductKey($product_id);

        if ( $productSlug )
        {
            $data = array( 'calculatorData' => $calculatorData, 'productSlug' => $productSlug, 'promotionKey' => getPromotionKey( getPromotionIdxByProductKey( $product_id ) ) );

            if ( getPromotionIdxByProductKey( $product_id ) === 1 )
            {
                $data['showNav2'] = true;
                $data['calculatorData'] = $product->getProductsData('json', 1);
            }

            return view('frontend/product/' . $productSlug, $data);
        }

        return response()->view('errors.404', [], 404);
    }

    public function printing(Request $request)
    {
        $product = new Product();
        $productKey = $request->input('product', '');
        $productData = $product->getProductData($productKey);
        $variantData = $product->getProductVariant(
            $productKey,
            $request->input('variant', '0')
        );

        $data = array(
            'color' => $request->input('color', ''),
            'product' => $productKey,
            'productData' => $productData,
            'variantData' => $variantData
        );

        return view('frontend/product/printing', $data);
    }

    public function comparison()
    {
        return view('frontend/product/comparison');
    }

    public function comparison2()
    {
        return view('frontend/product/comparison2');
    }

    public function compare($promotionId)
    {
        $data = array();

        $promotionIdx = getPromotionIdx($promotionId);
        if ( $promotionIdx !== false )
        {
            if ( $promotionIdx === 1 )
            {
                $data['showNav2'] = true;
                return view('frontend/full-car-specifications-promo-2', $data);
            }

            return view('frontend/full-car-specifications-promo', $data);
        }

        return response()->view('errors.404', [], 404);
    }
}
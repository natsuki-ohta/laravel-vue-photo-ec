<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * 商品情報取得
     */
    public function index(Request $request)
    {
        $products = Product::where('is_public', true);
        
        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);
            $products->whereIn('id', $ids);
        }
    
        return ProductResource::collection($products->get());
    }
}

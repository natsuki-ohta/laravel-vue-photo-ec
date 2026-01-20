<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * 管理画面：商品一覧取得
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * 管理画面：在庫数更新
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock' => $request->stock,
        ]);

        return response()->json([
            'message' => '在庫を更新しました',
            'product' => $product,
        ]);
    }

    /**
     * 管理画面：商品削除
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => '削除完了',
        ]);
    }

    /**
     * 管理画面：商品一括更新
     */
    public function updateAll(Request $request)
    {
        foreach ($request->products as $product) {
            Product::where('id', $product['id'])->update([
                'name'        => $product['name'],
                'description' => $product['description'],
                'stock'       => $product['stock'],
                'price'       => $product['price'],
                'category_id' => $product['category_id'],
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * 管理画面：商品画像更新
     */
    public function updateImage(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $path = $request->file('image')->store('products', 'public');

        $product->update([
            'image_path' => Storage::url($path),
        ]);

        return response()->json([
            'image_path' => $product->image_path,
        ]);
    }

    /**
     * 管理画面：商品新規登録
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|integer|min:0',
            'image'       => 'required|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $path = $request->file('image')->store('products', 'public');

            $product = Product::create([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? '',
                'category_id' => $validated['category_id'],
                'stock'       => $validated['stock'],
                'price'       => $validated['price'],
                'image_path'  => Storage::url($path),
            ]);

            DB::commit();

            return new ProductResource($product);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

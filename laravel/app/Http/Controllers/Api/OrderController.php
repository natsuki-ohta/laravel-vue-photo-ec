<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    /**
     * 注文登録処理
     */
    public function store(StoreOrderRequest $request)
    {
        $items = $request->items;

        DB::transaction(function () use ($items, $request) {
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $totalWithTax = floor($subtotal * 1.1);

            $order = Order::create([
                'total_price'    => $totalWithTax,
                'payment_method' => 'コンビニ支払い',
                'last_name'       => $request->last_name,
                'first_name'      => $request->first_name,
                'last_name_kana'  => $request->last_name_kana,
                'first_name_kana' => $request->first_name_kana,
                'postal'          => $request->postal,
                'address'         => $request->address,
                'phone'           => $request->phone,
            ]);

            // 注文明細作成 & 在庫更新
            foreach ($items as $item) {
                $product = Product::where('id', $item['id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $item['quantity']) {
                    throw new \Exception('在庫不足');
                }

                // 在庫減算
                $product->decrement('stock', $item['quantity']);

                // 注文明細登録
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                ]);
            }
        });

        return response()->json([
            'message' => '注文完了',
        ]);
    }
}

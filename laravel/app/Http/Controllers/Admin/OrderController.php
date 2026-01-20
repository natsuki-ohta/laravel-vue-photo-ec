<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;

class OrderController extends Controller
{
    /**
     * 管理画面用の注文情報取得
     */
    public function index()
    {
        $orders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return OrderResource::collection($orders);
    }

    /**
     * 管理画面：注文詳細取得
     *
     * @param int $id
     * @return OrderResource
     */
    public function show($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        return new OrderResource($order);
    }
}
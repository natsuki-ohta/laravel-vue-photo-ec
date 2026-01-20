<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * 管理画面用 注文情報リソース
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'created_at'     => $this->created_at,
            'payment_method' => $this->payment_method,
            'total_price'    => $this->total_price,
            'total_quantity' => $this->items->sum('quantity'),

            /**
             * ダミー注文者情報
             * ※ 今回は入力情報を保存しないため固定値
             */
            'customer' => [
                'name'    => '山田 ジョンソン',
                'kana'    => 'ヤマダ ジョンソン',
                'tel'     => '090-1234-5678',
                'postal'  => '150-0000',
                'address' => '東京都山田区ジョンソン東1-2-3',
            ],

            // 注文明細
            'items' => $this->items->map(function ($item) {
                return [
                    'product_id'   => $item->product_id ?? '',
                    'product_name' => $item->product->name ?? '',
                    'price'        => $item->price ?? '',
                    'quantity'     => $item->quantity ?? '',
                    'subtotal'     => $item->price * $item->quantity ?? '',
                ];
            }),
        ];
    }
}

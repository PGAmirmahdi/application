<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ReturnProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnResource extends JsonResource
{
    public function toArray($request)
    {
        $products = [];
        if ($this->products){
            $return_products = json_decode($this->products, true);
            foreach ($return_products as $item){
                $products[] = Product::whereId($item['product_id'])->get()->map(function ($product) use($item){
                    return [
                        'title' => $product->title,
                        'main_image' => $product->main_image,
                        'count' => $item['count'],
                    ];
                });
            }
        } else {
            foreach ($this->order->items as $item){
                $product = Product::find($item->product_id);

                $products[] = [
                    'title' => $product->title,
                    'main_image' => $product->main_image,
                    'count' => $item->count,
                ];
            }
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'all' => $this->all,
            'status' => $this->status,
            'status_text' => ReturnProduct::STATUS[$this->status],
            'products' => $products ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

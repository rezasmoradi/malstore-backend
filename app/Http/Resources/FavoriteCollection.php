<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoriteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'user_id' => auth()->id(),
                'favorites' => new ProductCollection($this->getProducts())
            ]
        ];
    }

    private function getProducts()
    {
        $productIds = $this->collection->map(function ($fav) {
            return $fav->product_id;
        });

        return Product::query()->whereIn('id', $productIds)->get();
    }
}

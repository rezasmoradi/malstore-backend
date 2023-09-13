<?php

namespace App\Http\Resources;

use App\Models\ProductColor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = $this->resource->product;
        return [
            'id' => $product->id,
            'name' => $product->name,
            'model' => $product->model,
            'display_name' => $product->display_name,
            'slug' => $product->slug,
            'quantity' => $this->resource->quantity,
            'description' => $this->resource->description,
            'color' => new ProductColorResource($this->resource->color),
            'images' => $this->getProductImages(),
            'unit_price' => $product->unit_price,
            'final_price' => $product->unit_price * $this->resource->quantity,
        ];
    }

    private function getProductImages()
    {
        if ($this->resource['product']->images) {
            return collect(Storage::disk('product_images')->allFiles($this->resource['product']->id))->map(function ($image) {
                return asset('storage/product_images/' . $image);
            });
        }

        return null;
    }
}

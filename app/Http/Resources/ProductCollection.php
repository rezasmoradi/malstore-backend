<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'model' => $product->model,
                'category' => new ProductCategoryResource($product->category),
                'colors' => ProductColorResource::collection($product->colors),
                'display_name' => $product->display_name,
                'slug' => $product->slug,
                'created_at' => $this->resource->created_at,
                'image' => asset('storage/product_images/' . $product->images()->where('main', 1)->first()->name),
                'rating' => $product->rating ? $product->rating()->average('rate') : null,
            ];
        });
    }

    //TODO: remove getProductImages function

    /*    private function getProductImages($product)
        {
            if ($product->images) {
                return collect(Storage::disk('product_images')->allFiles($product->id))->map(function ($image) {
                    return asset('storage/product_images/' . $image);
                });
            }

            return null;
        }*/
}

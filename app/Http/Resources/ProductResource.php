<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'model' => $this->resource->model,
            'category' => new ProductCategoryResource($this->resource->category),
            'long_desc' => $this->resource->long_desc,
            'short_desc' => $this->resource->short_desc,
            'width' => $this->resource->width,
            'length' => $this->resource->length,
            'height' => $this->resource->height,
            'features' => $this->resource->features,
            'best_features' => $this->resource->best_features,
            'colors' => ProductColorResource::collection($this->resource->colors),
            'display_name' => $this->resource->display_name,
            'meta_description' => $this->resource->meta_description,
            'meta_keywords' => $this->resource->meta_keywords,
            'meta_title' => $this->resource->meta_title,
            'slug' => $this->resource->slug,
            'created_at' => $this->resource->created_at,
            'tags' => $this->getProductTags(),
            'images' => $this->getProductImages(),
            'rating' => $this->resource->rating ? $this->resource->rating()->average('rate') : null,
            'comments' => $this->resource->comments ? CommentResource::collection($this->resource->comments) : [],
        ];
    }

    private function getProductImages()
    {
        if ($this->resource->images) {
            return collect(Storage::disk('product_images')->allFiles($this->resource->id))->map(function ($image) {
                return asset('storage/product_images/' . $image);
            });
        }

        return null;
    }

    private function getProductTags()
    {
        if ($this->resource->tags) {
            return collect($this->resource->tags)->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            });
        }

        return [];
    }
}

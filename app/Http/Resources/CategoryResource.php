<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'url_name' => $this->resource->url_name,
            'thumbnail' => asset('storage/category/thumbnails/' . $this->resource->thumbnail),
            'image' => $this->resource->image ? asset('storage/category/images/' . $this->resource->image) : null,
            'return_condition' => $this->resource->return_condition,
            'created_at' => $this->resource->created_at->toDateString(),
            'products' => new ProductCollection($this->resource->products),
            'sub_categories' => $this->getSubCategories(),
            'parent_category' => $this->resource->parentCategory()->get()
        ];
    }

    private function getSubCategories()
    {
        return new CategoryCollection($this->resource->subCategories);
    }

    private function getParentCategories()
    {
        return new CategoryCollection($this->resource->parentCategory);
    }
}

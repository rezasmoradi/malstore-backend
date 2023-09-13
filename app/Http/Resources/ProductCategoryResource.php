<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'thumbnail' => asset('storage/category_thumbnails/' . $this->resource->thumbnail),
            'parent_category' => $this->getParentCategories(),
        ];
    }

    private function getParentCategories()
    {
        return new ProductCategoryResource($this->resource->parentCategory);
    }
}

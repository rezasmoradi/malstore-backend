<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'url_name' => $item->url_name,
                    'thumbnail' => $item->thumbnail ? asset('storage/category/thumbnails/' . $item->thumbnail) : null,
                    'image' => $item->image ? asset('storage/category/images/' . $item->image) : null,
                    'sub_categories' => $this->getSubCategories($item->subCategories),
                    'created_at' => $item->created_at->toDateString()
                ];
            });
    }

    private function getSubCategories($subCategories)
    {
        return new CategoryCollection($subCategories);
    }
}

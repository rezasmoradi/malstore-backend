<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopperResource extends JsonResource
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
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'shop_name' => $this->resource->shop_name,
            'phones' => json_decode($this->resource->phones, true),
            'created_at' => $this->resource->created_at->toDatetimeString(),
            'orders' => $this->resource->orders,
        ];
    }
}

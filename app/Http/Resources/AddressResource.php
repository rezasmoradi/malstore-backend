<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'province' => $this->resource->province,
            'city' => $this->resource->city,
            'address' => $this->resource->address,
            'postal_code' => $this->resource->postal_code,
            'plaque' => $this->resource->plaque,
        ];
    }
}

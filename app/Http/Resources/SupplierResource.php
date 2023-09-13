<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'company' => $this->resource->company,
            'business' => $this->resource->business,
            'phones' => json_decode($this->resource->phones, true),
            'addresses' => $this->resource->addresses ? new AddressResource($this->resource->addresses) : null,
            'orders' => $this->resource->orders,
        ];
    }
}

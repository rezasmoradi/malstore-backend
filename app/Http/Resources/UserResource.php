<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            // 'username' => $this->resource->username,
            'email' => $this->resource->email,
            'has_password' => (bool)$this->resource->password,
            'role' => $this->resource->role,
            // 'avatar' => $this->resource->avatar ? asset('storage/avatars/' . $this->resource->avatar) : null,
//            'addresses' => AddressResource::collection($this->resource->addresses)
        ];
    }
}

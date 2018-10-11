<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount' => $this->discount2,
            'address' => $this->address,
            'location' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ]
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => [
                'raw' => $this->price,
                'numberFormat' => number_format($this->price),
            ],
            'surface' => $this->surface,
            'address' => $this->address,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'description' => $this->description,
            'images' => $this->getMedia('images')->all(),
            'planos' => $this->getMedia('planos')->all(),
            'subdivision' => $this->whenLoaded('subdivision'),
            'created_at' => $this->created_at->isoFormat('DD MMM, YYYY h:mm A'),
        ];
    }
}

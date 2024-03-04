<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubdivisionResource extends JsonResource
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
            'type' => $this->type,
            'batches_availables' => $this->batches_availables,
            'lowest_price' => [
                'raw' => $this->lowest_price,
                'numberFormat' => number_format($this->lowest_price),
            ],
            'lowest_surface' => $this->lowest_surface,
            'address' => $this->address,
            'maps_url' => $this->maps_url,
            'description' => $this->description,
            'amenities' => $this->amenities,
            'soon' => $this->soon,
            'images' => $this->getMedia('images')->all(),
            'planos' => $this->getMedia('planos')->all(),
            'batches' => BatchResource::collection($this->whenLoaded('batches')),
            'created_at' => $this->created_at->isoFormat('DD MMM, YYYY h:mm A'),
        ];
    }
}

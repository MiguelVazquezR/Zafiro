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
            'description' => $this->description,
            'amenities' => $this->amenities,
            'images' => $this->getMedia('images')->all(),
            'planos' => $this->getMedia('planos')->all(),
            'created_at' => $this->created_at->isoFormat('DD MMM, YYYY h:mm A'),
        ];
    }
}

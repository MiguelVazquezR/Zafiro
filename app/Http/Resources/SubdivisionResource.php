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
            'lowest_price' => $this->lowest_price,
            'lowest_surface' => $this->lowest_surface,
            'address' => $this->address,
            'description' => $this->description,
            'amenities' => $this->amenities,
            'cover1' => $this->getMedia('cover1')->all(),
            'cover2' => $this->getMedia('cover2')->all(),
            'cover3' => $this->getMedia('cover3')->all(),
            'cover4' => $this->getMedia('cover4')->all(),
            'cover5' => $this->getMedia('cover5')->all(),
            'cover6' => $this->getMedia('cover6')->all(),
            'cover7' => $this->getMedia('cover7')->all(),
            'cover8' => $this->getMedia('cover8')->all(),
            'created_at' => $this->created_at->isoFormat('DD MMM, YYYY h:mm A'),
        ];
    }
}

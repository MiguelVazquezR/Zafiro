<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
            'folio' => 'C-' . str_pad($this->id, 3, "0", STR_PAD_LEFT),
            'name' => $this->name,
            'client' => $this->client,
            'description' => $this->description,
            'status' => $this->status,
            'price' => '$' . number_format($this->price),
            'price_raw' => $this->price,
            'payment_conditions' => $this->payment_conditions,
            'created_at' => $this->created_at?->isoFormat('DD MMM, YYYY - h:mm A')
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'folio' => substr(strtoupper($this->town), 0, 2) . '-' . substr(strtoupper($this->ejido), 0, 2) . '-' . str_pad($this->id, 4, "0", STR_PAD_LEFT),
            'town' => $this->town,
            'ejido' => $this->ejido,
            'work_type' => $this->work_type,
            'parcel_number' => $this->parcel_number,
            'customer_name' => $this->customer_name,
            'area' => $this->area,
            'price' => number_format($this->parcel_number, 2),
            'start_date' => $this->start_date->isoFormat('DD MMM, YYYY'),
            'created_at' => $this->created_at->isoFormat('DD MMM, YYYY HH:mma'),
        ];
    }
}

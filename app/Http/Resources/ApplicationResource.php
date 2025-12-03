<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'name' => $this->car->cars,
            'description' => $this->car->description,
            'price' => $this->car->price,
            'months' => $this->availableMonth->month,
            'status' => $this->status->status ?? null,
            'notes' => $this->notes,
            'apply_date' => $this->created_at,
        ];
    }
}

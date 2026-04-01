<?php
namespace App\Http\Resources\Catalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'       => $this->uuid,
            'name'       => $this->name,
            'code'       => $this->code,
            'sort_order' => (int) $this->sort_order,
            'color'      => $this->color,
            'is_system'  => (bool) $this->is_system,
            'is_final'   => (bool) $this->is_final,
            'is_active'  => (bool) $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

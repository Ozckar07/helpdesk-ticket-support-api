<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code'        => $this->code,
            'name'        => $this->name,
            'description' => $this->description,
            'is_system'   => (bool) $this->is_system,
            'is_active'   => (bool) $this->is_active,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}

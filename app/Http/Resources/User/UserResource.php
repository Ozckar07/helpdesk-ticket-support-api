<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'              => $this->uuid,
            'name'              => $this->name,
            'email'             => $this->email,
            'is_active'         => (bool) $this->is_active,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at'     => $this->last_login_at?->toISOString(),
            'roles'             => RoleResource::collection($this->whenLoaded('roles')),
            'created_at'        => $this->created_at?->toISOString(),
            'updated_at'        => $this->updated_at?->toISOString(),
        ];
    }
}

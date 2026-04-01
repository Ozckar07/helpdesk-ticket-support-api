<?php
namespace App\Http\Resources\Auth;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this['access_token'] ?? null,
            'token_type'   => $this['token_type'] ?? 'Bearer',
            'expires_at'   => $this['expires_at'] ?? null,
            'user'         => isset($this['user'])
                ? new UserResource($this['user'])
                : null,
        ];
    }
}

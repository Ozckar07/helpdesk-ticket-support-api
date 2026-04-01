<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'        => $this->uuid,
            'message'     => $this->message,
            'type'        => $this->type,
            'is_internal' => (bool) $this->is_internal,
            'user'        => new UserResource($this->whenLoaded('user')),
            'attachments' => TicketAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}

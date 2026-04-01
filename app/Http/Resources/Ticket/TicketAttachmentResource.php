<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'                => $this->uuid,
            'original_name'       => $this->original_name,
            'mime_type'           => $this->mime_type,
            'extension'           => $this->extension,
            'size'                => (int) $this->size,
            'is_image'            => method_exists($this->resource, 'isImage')
                ? $this->resource->isImage()
                : str_starts_with((string) $this->mime_type, 'image/'),
            'uploaded_by'         => new UserResource($this->whenLoaded('uploadedBy')),
            'ticket_message_uuid' => $this->when(
                $this->ticket_message_id !== null && $this->relationLoaded('message') && $this->message,
                fn() => $this->message->uuid
            ),
            'created_at'          => $this->created_at?->toISOString(),
        ];
    }
}

<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\Catalog\CategoryResource;
use App\Http\Resources\Catalog\PriorityResource;
use App\Http\Resources\Catalog\StatusResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TicketListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'                => $this->uuid,
            'ticket_number'       => $this->ticket_number,
            'subject'             => $this->subject,
            'description_excerpt' => Str::limit((string) $this->description, 160),
            'customer'            => new UserResource($this->whenLoaded('customer')),
            'assigned_agent'      => new UserResource($this->whenLoaded('assignedAgent')),
            'category'            => new CategoryResource($this->whenLoaded('category')),
            'priority'            => new PriorityResource($this->whenLoaded('priority')),
            'status'              => new StatusResource($this->whenLoaded('status')),
            'latest_message'      => new TicketMessageResource($this->whenLoaded('latestMessage')),
            'first_response_at'   => $this->first_response_at?->toISOString(),
            'resolved_at'         => $this->resolved_at?->toISOString(),
            'closed_at'           => $this->closed_at?->toISOString(),
            'created_at'          => $this->created_at?->toISOString(),
            'updated_at'          => $this->updated_at?->toISOString(),
        ];
    }
}

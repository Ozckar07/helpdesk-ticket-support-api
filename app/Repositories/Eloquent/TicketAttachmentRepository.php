<?php
namespace App\Repositories\Eloquent;

use App\Models\TicketAttachment;
use App\Repositories\Contracts\TicketAttachmentRepositoryInterface;

class TicketAttachmentRepository implements TicketAttachmentRepositoryInterface
{
    public function create(array $data): TicketAttachment
    {
        return TicketAttachment::query()->create($data);
    }
}

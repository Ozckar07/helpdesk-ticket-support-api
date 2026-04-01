<?php
namespace App\Repositories\Contracts;

use App\Models\TicketAttachment;

interface TicketAttachmentRepositoryInterface
{
    public function create(array $data): TicketAttachment;
}

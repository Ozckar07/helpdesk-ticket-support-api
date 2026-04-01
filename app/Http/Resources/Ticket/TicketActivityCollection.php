<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\BasePaginatedCollection;

class TicketActivityCollection extends BasePaginatedCollection
{
    public $collects = TicketActivityResource::class;
}

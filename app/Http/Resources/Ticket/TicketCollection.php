<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\BasePaginatedCollection;

class TicketCollection extends BasePaginatedCollection
{
    public $collects = TicketListResource::class;
}

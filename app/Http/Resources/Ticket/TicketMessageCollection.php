<?php
namespace App\Http\Resources\Ticket;

use App\Http\Resources\BasePaginatedCollection;

class TicketMessageCollection extends BasePaginatedCollection
{
    public $collects = TicketMessageResource::class;
}

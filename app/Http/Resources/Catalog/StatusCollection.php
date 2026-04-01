<?php
namespace App\Http\Resources\Catalog;

use App\Http\Resources\BasePaginatedCollection;

class StatusCollection extends BasePaginatedCollection
{
    public $collects = StatusResource::class;
}

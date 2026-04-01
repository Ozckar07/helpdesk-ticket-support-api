<?php
namespace App\Http\Resources\Catalog;

use App\Http\Resources\BasePaginatedCollection;

class PriorityCollection extends BasePaginatedCollection
{
    public $collects = PriorityResource::class;
}

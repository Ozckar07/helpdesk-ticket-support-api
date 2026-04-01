<?php
namespace App\Http\Resources\Catalog;

use App\Http\Resources\BasePaginatedCollection;

class CategoryCollection extends BasePaginatedCollection
{
    public $collects = CategoryResource::class;
}

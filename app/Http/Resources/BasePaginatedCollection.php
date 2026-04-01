<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

abstract class BasePaginatedCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof AbstractPaginator) {
            return [
                'items'      => $this->collection,
                'pagination' => [
                    'current_page'   => $this->resource->currentPage(),
                    'per_page'       => $this->resource->perPage(),
                    'total'          => $this->resource->total(),
                    'last_page'      => $this->resource->lastPage(),
                    'from'           => $this->resource->firstItem(),
                    'to'             => $this->resource->lastItem(),
                    'has_more_pages' => $this->resource->hasMorePages(),
                ],
            ];
        }

        return [
            'items' => $this->collection,
        ];
    }
}

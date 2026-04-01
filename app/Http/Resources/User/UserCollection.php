<?php
namespace App\Http\Resources\User;

use App\Http\Resources\BasePaginatedCollection;

class UserCollection extends BasePaginatedCollection
{
    public $collects = UserResource::class;
}

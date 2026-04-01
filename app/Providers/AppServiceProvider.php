<?php
namespace App\Providers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PriorityRepositoryInterface;
use App\Repositories\Contracts\StatusRepositoryInterface;
use App\Repositories\Contracts\TicketActivityRepositoryInterface;
use App\Repositories\Contracts\TicketAttachmentRepositoryInterface;
use App\Repositories\Contracts\TicketMessageRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\PriorityRepository;
use App\Repositories\Eloquent\StatusRepository;
use App\Repositories\Eloquent\TicketActivityRepository;
use App\Repositories\Eloquent\TicketAttachmentRepository;
use App\Repositories\Eloquent\TicketMessageRepository;
use App\Repositories\Eloquent\TicketRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PriorityRepositoryInterface::class, PriorityRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(TicketMessageRepositoryInterface::class, TicketMessageRepository::class);
        $this->app->bind(TicketAttachmentRepositoryInterface::class, TicketAttachmentRepository::class);
        $this->app->bind(TicketActivityRepositoryInterface::class, TicketActivityRepository::class);
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}

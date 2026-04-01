<?php
namespace App\Models;

use App\Enums\RoleCode;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements OAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'is_active'         => 'boolean',
            'password'          => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }

    public function createdTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_agent_id');
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function ticketAttachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'uploaded_by_id');
    }

    public function ticketActivities(): HasMany
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasRole(string $roleCode): bool
    {
        return $this->roles()
            ->where('code', $roleCode)
            ->exists();
    }

    public function hasAnyRole(array $roleCodes): bool
    {
        return $this->roles()
            ->whereIn('code', $roleCodes)
            ->exists();
    }

    public function permissions()
    {
        return $this->roles()
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->select('permissions.*')
            ->distinct();
    }

    public function hasPermission(string $permissionCode): bool
    {
        return Permission::query()
            ->where('code', $permissionCode)
            ->whereHas('roles.users', function ($query): void {
                $query->where('users.id', $this->id);
            })
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleCode::ADMIN->value);
    }

    public function isAgent(): bool
    {
        return $this->hasRole(RoleCode::AGENT->value);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(RoleCode::CUSTOMER->value);
    }
}

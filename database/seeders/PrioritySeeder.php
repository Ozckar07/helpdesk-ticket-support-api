<?php
namespace Database\Seeders;

use App\Enums\TicketPriorityCode;
use App\Models\Priority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        foreach (TicketPriorityCode::cases() as $priorityEnum) {
            $priority = Priority::withTrashed()->firstOrNew([
                'code' => $priorityEnum->value,
            ]);

            if (! $priority->exists && blank($priority->uuid)) {
                $priority->uuid = (string) Str::uuid();
            }

            $priority->fill([
                'name'      => $priorityEnum->label(),
                'level'     => $priorityEnum->level(),
                'color'     => $priorityEnum->color(),
                'is_system' => true,
                'is_active' => true,
            ]);

            $priority->deleted_at = null;
            $priority->save();
        }
    }
}

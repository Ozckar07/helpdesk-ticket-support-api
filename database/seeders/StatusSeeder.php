<?php
namespace Database\Seeders;

use App\Enums\TicketStatusCode;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TicketStatusCode::cases() as $statusEnum) {
            $status = Status::withTrashed()->firstOrNew([
                'code' => $statusEnum->value,
            ]);

            if (! $status->exists && blank($status->uuid)) {
                $status->uuid = (string) Str::uuid();
            }

            $status->fill([
                'name'       => $statusEnum->label(),
                'sort_order' => $statusEnum->order(),
                'color'      => $statusEnum->color(),
                'is_system'  => true,
                'is_final'   => $statusEnum->isFinal(),
                'is_active'  => true,
            ]);

            $status->deleted_at = null;
            $status->save();
        }
    }
}

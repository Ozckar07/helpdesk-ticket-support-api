<?php
namespace App\Enums;

use App\Enums\Concerns\EnumToArray;

enum TicketPriorityCode: string {

    case LOW    = 'low';
    case MEDIUM = 'medium';
    case HIGH   = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW    => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH   => 'High',
            self::URGENT => 'Urgent',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::LOW    => 1,
            self::MEDIUM => 2,
            self::HIGH   => 3,
            self::URGENT => 4,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW    => '#6B7280',
            self::MEDIUM => '#2563EB',
            self::HIGH   => '#F59E0B',
            self::URGENT => '#DC2626',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::LOW    => 'Non-critical issue with low impact.',
            self::MEDIUM => 'Standard operational issue.',
            self::HIGH   => 'Important issue affecting service or workflow.',
            self::URGENT => 'Critical issue requiring immediate attention.',
        };
    }
}

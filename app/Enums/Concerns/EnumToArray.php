<?php
namespace App\Enums\Concerns;

trait EnumToArray
{
    public static function values(): array
    {
        return array_map(
            static fn(self $case) => $case->value,
            self::cases()
        );
    }

    public static function names(): array
    {
        return array_map(
            static fn(self $case) => $case->name,
            self::cases()
        );
    }

    public static function options(): array
    {
        return array_map(
            static fn(self $case) => [
                'name'  => $case->name,
                'value' => $case->value,
                'label' => method_exists($case, 'label') ? $case->label() : $case->name,
            ],
            self::cases()
        );
    }

    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $case) {
            $labels[$case->value] = method_exists($case, 'label')
                ? $case->label()
                : $case->name;
        }

        return $labels;
    }
}

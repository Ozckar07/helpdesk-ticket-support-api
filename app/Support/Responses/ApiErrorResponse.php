<?php
namespace App\Support\Responses;

use Illuminate\Http\JsonResponse;

final class ApiErrorResponse
{
    public static function make(
        string $title,
        string $message,
        int $status,
        array $errors = [],
        array $meta = []
    ): JsonResponse {
        $request = request();

        return response()->json([
            'success' => false,
            'title'   => $title,
            'message' => $message,
            'errors'  => (object) $errors === (object) [] ? [] : $errors,
            'meta'    => array_merge([
                'request_id' => $request?->attributes->get('request_id'),
                'timestamp'  => now()->toISOString(),
            ], $meta),
        ], $status);
    }
}

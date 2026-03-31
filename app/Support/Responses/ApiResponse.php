<?php
namespace App\Support\Responses;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
        string $message = 'Success',
        mixed $data = null,
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => (object) $meta,
        ], $status);
    }

    public static function error(
        string $message = 'Error',
        mixed $errors = null,
        int $status = 400,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
            'meta'    => (object) $meta,
        ], $status);
    }
}

<?php
namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\AuthTokenResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()
            ->with('roles')
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return ApiResponse::error(
                message: 'Invalid credentials.',
                errors: [
                    'email' => ['The provided credentials are incorrect.'],
                ],
                status: 422
            );
        }

        if (! $user->is_active) {
            return ApiResponse::error(
                message: 'This account is inactive.',
                status: 403
            );
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $tokenResult = $user->createToken($data['token_name'] ?? 'api-token');

        return ApiResponse::success(
            message: 'Authenticated successfully.',
            data: new AuthTokenResource([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => $tokenResult->token->expires_at,
                'user'         => $user->load('roles'),
            ])
        );
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(
            message: 'Authenticated user retrieved successfully.',
            data: new UserResource($request->user()->load('roles'))
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->token();

        if ($token) {
            $token->revoke();
        }

        return ApiResponse::success(
            message: 'Logged out successfully.'
        );
    }
}

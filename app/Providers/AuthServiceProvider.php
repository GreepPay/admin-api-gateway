<?php

namespace App\Providers;

use App\Models\Auth\User;
use App\Services\AuthService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Auth::extend("custom", function ($app, $name, array $config) {
            return new RequestGuard(
                function ($request) {
                    $token = $request->bearerToken();

                    if (!$token) {
                        return null;
                    }

                    $authService = new AuthService();
                    $response = $authService->authUser();

                    if (!$response || empty($response["data"])) {
                        abort(401, 'Invalid or expired token.');
                    }

                    $user = User::with('role')->where("id", $response["data"]["id"])->first();

                    if (!$user) {
                        abort(401, 'User not found.');
                    }

                    $roleName = strtolower(optional($user->role)->name ?? '');

                    if (!in_array($roleName, ['admin', 'super-admin'])) {
                        abort(403, 'Only admin or super-admin can access this resource.');
                    }

                    return $user;
                },
                $app["request"],
                $app["auth"]->createUserProvider($config["provider"])
            );
        });
    }
}

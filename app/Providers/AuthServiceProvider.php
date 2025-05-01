<?php

namespace App\Providers;

use App\Exceptions\GraphQLException;
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
                        throw new GraphQLException('Invalid or expired token.');
                    }

                    $user = User::query()
                        ->with("profile")
                        ->find($response["data"]["id"]);

                    if (!$user || !$user->profile) {
                        throw new GraphQLException('User profile not found.');
                    }

                    if ($user->profile->user_type !== "Admin") {
                        throw new GraphQLException(
                            "Unauthorized. Please use the {$user->profile->user_type} app instead"
                        );
                    }

                    return $user;
                },
                $app["request"],
                $app["auth"]->createUserProvider($config["provider"])
            );
        });
    }
}

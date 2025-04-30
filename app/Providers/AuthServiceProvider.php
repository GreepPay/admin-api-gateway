<?php

namespace App\Providers;

use App\Exceptions\GraphQLException;
use App\Models\Auth\User;
use App\Services\AuthService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Auth;
use Log;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('custom', function ($app, $name, array $config) {
            return new RequestGuard(
                function ($request) {
                    $token = $request->bearerToken();

                    if (!$token) {
                        return null;
                    }

                    $authService = new AuthService();
                    $response = $authService->authUser();

                    if (!$response || empty($response['data'])) {
                        return null;
                    }

                    $user = User::query()
                        ->where('id', $response['data']['id'])
                        ->with('role')
                        ->first();

                    // if (!$user || !isset($user->role->name)) {
                    //     return null;
                    // }

                    // if (strtolower(trim($user->role->name)) !== 'admin') {
                    //     throw new GraphQLException("Unauthorized. Only admin can access this resource.");
                    // }

                    return $user;
                },
                $app['request'],
                $app['auth']->createUserProvider($config['provider'])
            );
        });
    }
}

<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\Models\Auth\User;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Auth;

final class UserMutator
{
    use FileUploadTrait;

    protected UserService $userService;
    protected AuthService $authService;
    protected BlockchainService $blockchainService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->authService = new AuthService();
        $this->blockchainService = new BlockchainService();
    }

    public function freeze($_, array $args): bool
    {
        $user = User::where('uuid', $args['user_uuid'])->first();

        if (!$user) {
            throw new GraphQLException("User not found");
        }

        $payload = [
            "status" => "frozen",
            "auth_user_id" => $user->id,
        ];

        $res = $this->userService->updateProfile($payload);

        return true;
    }

    public function unfreeze($_, array $args): bool
    {
        $user = User::where('uuid', $args['user_uuid'])->first();

        if (!$user) {
            throw new GraphQLException("User not found");
        }

        $payload = [
            "status" => "active",
            "auth_user_id" => $user->id,
        ];

        $res = $this->userService->updateProfile($payload);

        return true;
    }

    public function approveReject($_, array $args): bool
    {
        $user = User::where('uuid', $args['user_uuid'])->first();

        if (!$user) {
            throw new GraphQLException("User not found");
        }

        $payload = [
            "verificationId" => $args["verificationId"],
            "status" => $args["status"],
            "auth_user_id" => $user->id,
        ];

        $res = $this->userService->approveRejectVerificationRequest($payload);

        return true;
    }

    public function approveRject($_, array $args): bool
    {
        $user = User::where('uuid', $args['user_uuid'])->first();

        if (!$user) {
            throw new GraphQLException("User not found");
        }

        $payload = [
            "verificationId" => $args["verificationId"],
            "status" => "Approved",
            "auth_user_id" => $user->id,
        ];

        $res = $this->userService->approveRejectVerificationRequest($payload);

        return true;
    }

}

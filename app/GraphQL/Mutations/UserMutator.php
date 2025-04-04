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

}

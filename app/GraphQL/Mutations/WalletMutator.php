<?php

namespace App\GraphQL\Mutations;
use App\Exceptions\GraphQLException;
use App\Models\Wallet\UserBank;
use App\Services\BlockchainService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

final class WalletMutator
{
    protected WalletService $walletService;
    protected BlockchainService $blockchainService;

    public function __construct()
    {
        $this->walletService = new WalletService();
        $this->blockchainService = new BlockchainService();
    }

 
}

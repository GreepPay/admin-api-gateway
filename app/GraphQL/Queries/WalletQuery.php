<?php

namespace App\GraphQL\Queries;

use App\Services\WalletService;

final class WalletQuery
{
    protected $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

}

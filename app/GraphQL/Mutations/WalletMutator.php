<?php

namespace App\GraphQL\Mutations;
use App\Exceptions\GraphQLException;
use App\Models\Wallet\Transaction;
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

    /**
     * Approve or reject a withdrawal request
     *
     * @param mixed $_
     * @param array $args
     * @return Transaction
     * @throws GraphQLException
     */
    public function updateWithdrawalStatus($_, array $args): Transaction
    {
        $transaction = Transaction::where('id', $args['transaction_id'])
            ->where('chargeable_type', 'Withdrawal')
            ->where('status', 'pending')
            ->first();

        if (! $transaction) {
            throw new GraphQLException('Withdrawal transaction not found or not in pending state.');
        }

        $newStatus = $args['status'];

        if (!in_array($newStatus, ['rejected', 'successful'])) {
            throw new GraphQLException('Invalid status. Must be either rejected or successful.');
        }

        $this->walletService->updateTransactionStatus($transaction->id, $newStatus);

        return $transaction;
    }

}

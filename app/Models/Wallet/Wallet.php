<?php

namespace App\Models\Wallet;

use App\Models\Auth\User;
use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string $uuid
 * @property string $total_balance
 * @property string $point_balance
 * @property string $credited_amount
 * @property string $debited_amount
 * @property string $locked_balance
 * @property string $credited_point_amount
 * @property string $state
 * @property string $debited_point_amount
 * @property string $cash_point_balance
 * @property string $cash_per_point
 * @property int $user_id
 * @property string|null $wallet_account
 * @property string $currency
 * @property string|null $blockchain_account_id
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereBlockchainAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCashPerPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCashPointBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreditedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreditedPointAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereDebitedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereDebitedPointAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereLockedBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePointBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereTotalBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereWalletAccount($value)
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "wallet_service.wallets";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'auth_user_id', 'user_id');
    }

}

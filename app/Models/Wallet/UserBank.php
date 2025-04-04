<?php

namespace App\Models\Wallet;

use App\Models\Auth\User;
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
 * @property int $user_id
 * @property int $wallet_id
 * @property string $bank_code
 * @property string $bank_name
 * @property string $account_no
 * @property string $currency
 * @property bool $is_verified
 * @property string $state
 * @property string|null $meta_data
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereBankCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBank whereWalletId($value)
 * @mixin \Eloquent
 */
class UserBank extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "wallet_service.user_banks";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}

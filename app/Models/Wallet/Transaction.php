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
 * @property string $dr_or_cr
 * @property string $currency
 * @property int $wallet_id
 * @property int $user_id
 * @property string $amount
 * @property string $wallet_balance
 * @property string $charge_id
 * @property string $chargeable_type
 * @property string $description
 * @property string $status
 * @property string $charges
 * @property string $reference
 * @property string $state
 * @property string $gateway
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereChargeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDrOrCr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereWalletBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereWalletId($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use ReadOnlyTrait;
    protected $connection = "greep-wallet";

    // protected $table = "wallet_service.transactions";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

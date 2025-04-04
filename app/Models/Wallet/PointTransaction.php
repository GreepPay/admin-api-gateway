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
 * @property int $wallet_id
 * @property int $user_id
 * @property string $amount
 * @property string $point_balance
 * @property string $charge_id
 * @property string $state
 * @property string $chargeable_type
 * @property string $description
 * @property string $status
 * @property string $reference
 * @property string|null $extra_data
 * @property string $currency
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereChargeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereDrOrCr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereExtraData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction wherePointBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTransaction whereWalletId($value)
 * @mixin \Eloquent
 */
class PointTransaction extends Model
{
    use ReadOnlyTrait;
    protected $connection = "greep-wallet";

    protected $table = "wallet_service.point_transactions";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

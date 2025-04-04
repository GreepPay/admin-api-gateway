<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string $uuid
 * @property int $wallet_id
 * @property int $user_id
 * @property string $amount
 * @property string $balance_after
 * @property string $payment_reference
 * @property string $state
 * @property string $payment_channel
 * @property string $description
 * @property string $status
 * @property string $currency
 * @property string|null $extra_data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $senderName
 * @property string|null $senderCountry
 * @property string|null $senderPhone
 * @property string|null $senderAddress
 * @property string|null $senderDob
 * @property string|null $senderEmail
 * @property string|null $senderIdNumber
 * @property string|null $senderIdType
 * @property string|null $senderBusinessId
 * @property string|null $senderBusinessName
 * @property string|null $senderAdditionalIdType
 * @property string|null $senderAdditionalIdNumber
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereBalanceAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereExtraData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp wherePaymentChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderAdditionalIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderAdditionalIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereSenderPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OffRamp whereWalletId($value)
 * @mixin \Eloquent
 */
class OffRamp extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "wallet_service.offramp";
}

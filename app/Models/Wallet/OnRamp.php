<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string $uuid
 * @property string $payment_reference
 * @property string $payment_channel
 * @property string $description
 * @property string $status
 * @property string $currency
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string $localAmount
 * @property string|null $recipientName
 * @property string|null $recipientCountry
 * @property string|null $recipientAddress
 * @property string|null $recipientDob
 * @property string|null $recipientEmail
 * @property string|null $recipientIdNumber
 * @property string|null $recipientIdType
 * @property string|null $recipientAdditionalIdType
 * @property string|null $recipientAdditionalIdNumber
 * @property string|null $recipientPhone
 * @property string|null $recipientBusinessId
 * @property string|null $recipientBusinessName
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereLocalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp wherePaymentChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientAdditionalIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientAdditionalIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereRecipientPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnRamp whereUuid($value)
 * @mixin \Eloquent
 */
class OnRamp extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "wallet_service.onramp";
}

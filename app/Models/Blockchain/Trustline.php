<?php

namespace App\Models\Blockchain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string|null $account_id
 * @property string $asset_code
 * @property string $asset_issuer
 * @property string|null $trust_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string $status
 * @property-read \App\Models\Blockchain\Account|null $account
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereAssetCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereAssetIssuer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trustline whereTrustLimit($value)
 * @mixin \Eloquent
 */
class Trustline extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-blockchain";

    protected $table = "blockchain_service.trustlines";

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}

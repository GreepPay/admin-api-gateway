<?php

namespace App\Models\Blockchain;

use App\Models\Wallet\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string $stellar_address
 * @property string $account_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blockchain\Trustline> $trustlines
 * @property-read int|null $trustlines_count
 * @property-read Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereStellarAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-blockchain";

    protected $table = "blockchain_service.accounts";

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, "blockchain_account_id", "id");
    }

    public function trustlines(): HasMany
    {
        return $this->hasMany(Trustline::class);
    }
}

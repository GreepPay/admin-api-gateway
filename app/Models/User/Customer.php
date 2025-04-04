<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use User;

/**
 *
 *
 * @property int $id
 * @property string $auth_user_id
 * @property string|null $resident_permit
 * @property string|null $passport
 * @property string|null $registration_number
 * @property string|null $passport
 * @property string|null $documents
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $country
 * @property string|null $city
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereResidentPermit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereWebsite($value)
 * @mixin \Eloquent
 */

class Customer extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}

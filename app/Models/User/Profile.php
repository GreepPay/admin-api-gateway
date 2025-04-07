<?php

namespace App\Models\User;

use App\Models\Auth\User;
use App\Traits\FiltersProfilesByTypeAndQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property string $auth_user_id
 * @property string|null $profile_picture
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string $user_type
 * @property string|null $default_currency
 * @property string $verification_status
 * @property-read \App\Models\User\Customer|null $customer
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\Verification> $verifications
 * @property-read int|null $verifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDefaultCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereVerificationStatus($value)
 * @mixin \Eloquent
 */
class Profile extends Model
{
    use FiltersProfilesByTypeAndQuery;
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "user_service.user_profiles";

    protected $with = ['customer', 'user', 'business'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, "auth_user_id", "auth_user_id");
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class, "auth_user_id", "auth_user_id");
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(
            Verification::class,
            "auth_user_id",
            "auth_user_id"
        );
    }

    public function scopeFilterCustomers($query, $args)
    {
        return $query->where('user_type', 'Customer')
            ->filterByUserTypeAndQuery($args);
    }

    public function scopeFilterBusinesses($query, $args)
    {
        return $query->where('user_type', 'Business')
            ->filterByUserTypeAndQuery($args);
    }
}

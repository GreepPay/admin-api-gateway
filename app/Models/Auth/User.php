<?php

namespace App\Models\Auth;

use App\Models\Notification\Notification;
use App\Models\User\Profile;
use App\Models\Wallet\Wallet;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string|null $username
 * @property string $email
 * @property string|null $phone
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $password_created_at
 * @property string|null $phone_verified_at
 * @property string $status
 * @property string|null $otp
 * @property string|null $otp_expired_at
 * @property string|null $deleted_at
 * @property string|null $sso_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $role_id
 * @property string|null $state
 * @property string|null $country
 * @property string|null $default_currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Profile|null $profile
 * @property-read Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDefaultCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSsoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract
{
    use ReadOnlyTrait, Authenticatable;

    protected $connection = "greep-auth";
    protected $table = "auth_service.users";

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, "auth_user_id", "id");
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, "auth_user_id", "id");
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}

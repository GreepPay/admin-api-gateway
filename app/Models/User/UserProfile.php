<?php

namespace App\Models\User;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property string $auth_user_id
 * @property string|null $profile_picture
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $user_type
 * @property string|null $default_currency
 * @property string $verification_status
 * @property-read \App\Models\User\Business|null $business
 * @property-read \App\Models\User\Customer|null $customer
 * @property-read \App\Models\User\Rider|null $rider
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereDefaultCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereVerificationStatus($value)
 * @mixin \Eloquent
 */
class UserProfile extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "user_service.user_profiles";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "auth_user_id", "id");
    }

    public function getUserTypeAttribute(): string|null
    {
        return $this->attributes["user_type"] ?? null;
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class, "auth_user_id", "auth_user_id");
    }
}

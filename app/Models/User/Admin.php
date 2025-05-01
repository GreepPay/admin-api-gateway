<?php

namespace App\Models\User;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 *
 *
 * @property int $id
 * @property string $auth_user_id
 * @property string|null $location
 * @property string|null $role
 * @property string|null $passport
 * @property string $notification_preferences
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $country
 * @property string|null $city
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereNotificationPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereResidentPermit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "user_service.admins";

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}

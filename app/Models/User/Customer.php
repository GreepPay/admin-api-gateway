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
 * @property string|null $resident_permit
 * @property string|null $passport
 * @property string|null $student_id
 * @property string $notification_preferences
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $country
 * @property string|null $city
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereNotificationPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereResidentPermit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "user_service.customers";

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}

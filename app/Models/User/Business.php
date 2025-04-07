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
 * @property string|null $business_name
 * @property string|null $logo
 * @property string|null $location
 * @property string|null $banner
 * @property string|null $description
 * @property string|null $website
 * @property string|null $resident_permit
 * @property string|null $passport
 * @property string|null $registration_number
 * @property string|null $documents
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $country
 * @property string|null $city
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereAuthUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereResidentPermit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereWebsite($value)
 * @mixin \Eloquent
 */
class Business extends Model
{
    use ReadOnlyTrait;
    protected $connection = "greep-user";

    protected $table = "user_service.businesses";

    protected $casts = [
        'auth_user_id' => 'int',
        'documents' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');

    }
}

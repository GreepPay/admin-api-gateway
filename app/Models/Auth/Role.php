<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Auth\User;

class Role extends Model
{
    protected $connection = 'greep-auth';
    protected $table = 'auth_service.roles';

}

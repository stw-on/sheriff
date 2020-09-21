<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 * @property string account
 * @property string display_name
 * @property string password_hash
 * @property bool can_manage_locations
 */
class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable;
    use HasApiTokens;

    protected $visible = [
        'account',
        'display_name',
        'can_manage_locations',
    ];
}

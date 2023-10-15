<?php

namespace App\Models;

use App\Traits\HasUserFields;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasUserFields;

    protected $table = 'organizations_users';

    protected $fillable = [
        'organization_id',
        'user_id',
    ];
}

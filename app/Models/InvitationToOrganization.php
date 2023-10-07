<?php

namespace App\Models;

use App\Traits\HasUserFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationToOrganization extends Model
{
    use HasFactory;
    use HasUserFields;

    protected $table = 'invitations_to_organizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'code',
    ];
}

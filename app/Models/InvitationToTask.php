<?php

namespace App\Models;

use App\Traits\HasUserFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationToTask extends Model
{
    use HasFactory;
    use HasUserFields;

    protected $table = 'invitations_to_tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'code',
        'user_id',
    ];
}

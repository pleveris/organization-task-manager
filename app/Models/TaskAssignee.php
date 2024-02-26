<?php

namespace App\Models;

use App\Traits\HasUserFields;
use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    use HasUserFields;

    protected $table = 'tasks_assignees';

    protected $fillable = [
        'task_id',
        'user_id',
    ];
}

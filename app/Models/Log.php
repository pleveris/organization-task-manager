<?php

namespace App\Models;

use App\Traits\HasUserFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    use HasUserFields;

    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'message',
    ];

    public function createdUser(): User
    {
        return User::find($this->create_user_id);
    }
}

<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\HasUserFields;
//use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
//use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
    //implements HasMedia
{
    //use HasFactory, SoftDeletes, InteractsWithMedia, Filter;
    use HasFactory;
    use SoftDeletes;
    use HasUserFields;

    protected $fillable = [
        'title',
        'description',
        'primary',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function accessibleToUser(int $userId) {
        $user = User::find($userId);
        $created = $this->create_user_id === $userId;
        $member = $user->organization_id === $this->id;

        if($created && $member) {
            return true;
        }

        if($created && ! $member) {
            return true;
        }

        if(! $created && $member) {
            return true;
        }

        return false;
    }
}

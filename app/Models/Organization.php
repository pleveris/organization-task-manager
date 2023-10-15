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
        return $this->belongsToMany(User::class, 'organizations_users');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function accessibleToUser(int $userId)
    {
        return OrganizationUser::where('organization_id', $this->id)
        ->where('user_id', $userId)
        ->get()
        ->isNotEmpty();
        /*$user = User::find($userId);
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

        return false;*/
    }

    public function createdByLoggedInUser(): bool
    {
        return $this->create_user_id === currentUser()->id;
    }
}

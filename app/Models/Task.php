<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\HasUserFields;
//use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
//use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
    //implements HasMedia
{
    //use HasFactory, SoftDeletes, InteractsWithMedia, Filter;
    use HasFactory;
    use SoftDeletes;
    use Filter;
    use HasUserFields;

    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'user_id',
        'organization_id',
        'deadline',
        'logic_test',
        'logic',
        'hidden'
    ];

    //public const STATUS = ['open', 'in progress', 'pending', 'blocked', 'completed'];
    //public const STATUS = ['unsetup', 'logic satisfied', 'logic unsatisfied', 'Logic test'];
    public const LOGIC_TESTS = ['All subtasks must be completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function createdByLoggedInUser(): bool
    {
        return $this->create_user_id === currentUser()->id;
    }

    public function subtasks()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}

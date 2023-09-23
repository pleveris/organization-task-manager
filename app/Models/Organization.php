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
    ];

    //    public const STATUS = ['open', 'in progress', 'blocked', 'cancelled', 'completed'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

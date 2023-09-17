<?php

namespace App\Models;

use App\Traits\Filter;
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

    protected $fillable = [
        'title',
        'description',
    ];

    //    public const STATUS = ['open', 'in progress', 'blocked', 'cancelled', 'completed'];

    //public function user()
    //{
    //return $this->belongsTo(User::class);
    //}

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

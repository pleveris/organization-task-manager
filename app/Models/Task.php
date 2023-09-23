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
        'title',
        'description',
        'user_id',
        'organization_id',
        'deadline',
        'status'
    ];

    public const STATUS = ['opened', 'in progress', 'pending', 'blocked', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

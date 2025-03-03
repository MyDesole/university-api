<?php

namespace App\Models;

use App\Observers\LessonObserver;
use App\Observers\UniversityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[ObservedBy([LessonObserver::class])]

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Lesson::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Lesson::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}

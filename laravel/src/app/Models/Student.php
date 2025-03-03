<?php

namespace App\Models;

use App\Observers\StudentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

#[ObservedBy([StudentObserver::class])]

class Student extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'course', 'university_id'];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function searchableAs(): string
    {
        return 'students';
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'university' => $this->university?->name
            ];
    }

}

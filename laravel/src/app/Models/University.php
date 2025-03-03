<?php

namespace App\Models;

use App\Observers\UniversityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

#[ObservedBy([UniversityObserver::class])]
class University extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['country', 'name', 'alpha_two_code', 'id'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'alpha_two_code' => $this->alpha_two_code,
        ];
    }
}

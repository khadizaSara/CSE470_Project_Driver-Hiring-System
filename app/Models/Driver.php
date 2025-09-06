<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'age',
        'experience',
        'rating',
        'type',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

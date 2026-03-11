<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'model',
        'year',
        'price',
        'mileage',
        'engine_size',
        'horsepower',
        'fuel_type',
        'transmission',
        'body_type',
        'color',
        'seats',
        'doors',
        'weight',
        'registration_valid_until',
        'description',
    ];

    public function listing()
    {
        return $this->morphOne(Listing::class, 'listable');
    }

    public function photos()
    {
        return $this->hasMany(CarPhoto::class);
    }
}

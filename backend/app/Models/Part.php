<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'name',
        'category',
        'condition',
        'compatible_make',
        'compatible_model',
        'description',
    ];

    public function listing()
    {
        return $this->morphOne(Listing::class, 'listable');
    }
}
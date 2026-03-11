<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'seller_id',
        'listable_type',
        'listable_id',
        'price',
        'status',
    ];

    public function listable()
    {
        return $this->morphTo();
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
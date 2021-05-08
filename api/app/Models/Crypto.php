<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    protected $casts = [
        'price' => 'float',
        'balance' => 'float'
    ];

    // Relationships
    public function orders() {
        return $this->hasMany(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Relationships
    public function crypto() {
        return $this->belongsTo(Crypto::class);
    }
}

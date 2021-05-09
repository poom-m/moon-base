<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'price' => 'float',
        'balance' => 'float'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function orders() {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */
    public function thbtToCrypto($amount_thbt) {        
        $balance = $this->balance;
        $base_price = $this->price;
        $tier = floor((1000 - $balance) / 10); // change tier every 10 crypto sold
        $multiplier = pow(1.1, $tier); // increase 10% every tier
        $price = $base_price * $multiplier; // price for current tier
        $crypto_balance_current_tier = 10 - ( (1000 - $balance) - ($tier * 10)); // How many crypto left in this tier

        $value_current_tier = $price * $crypto_balance_current_tier;
        $remaining_value_current_tier = $value_current_tier - $amount_thbt;

        if ($remaining_value_current_tier > 0) { // Buy within current tier (normal case)
            $amount_crypto = $amount_thbt / $price;
        } else { // Buy over current tier (edge case)
            // Current tier amount
            $current_tier_amount_crypto = $value_current_tier / $price;

            // Next tier amount
            $next_tier_price = $base_price * ( pow(1.1, ($tier + 1)) );
            $next_tier_amount_crypto = abs($remaining_value_current_tier) / $next_tier_price;

            $amount_crypto = $current_tier_amount_crypto + $next_tier_amount_crypto;
        }

        $remaining_crypto = $balance - $amount_crypto;

        return [
            'amount_crypto' => $amount_crypto,
            'remaining_crypto' => $remaining_crypto
        ];
    }
}

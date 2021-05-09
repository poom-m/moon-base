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
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getCurrentTierAttribute() {
        // Tier start at 0, every 10 crypto sold will goes up by 1 tier
        return floor((1000 - $this->balance) / 10);
    }

    public function getCurrentPriceAttribute() {
        $multiplier = pow(1.1, $this->currentTier); // multiplier increase 10% every tier
        $currentPrice = $this->base_price * $multiplier; // price for current tier

        return $currentPrice;
    }

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */
    public function thbtToCrypto($amount_thbt) {        
        $balance = $this->balance;
        $tier = $this->currentTier;
        $price = $this->currentPrice;

        $crypto_balance_current_tier = 10 - ( (1000 - $balance) - ($tier * 10) ); // How many crypto left in this tier

        $value_current_tier = $price * $crypto_balance_current_tier;
        $remaining_value_current_tier = $value_current_tier - $amount_thbt;

        if ($remaining_value_current_tier > 0) { // Buy within current tier (normal case)
            $amount_crypto = $amount_thbt / $price;
        } else { // Buy over current tier (edge case)
            // Current tier amount
            $current_tier_amount_crypto = $value_current_tier / $price;

            // Next tier amount
            $next_tier_price = $this->base_price * ( pow(1.1, ($tier + 1)) );
            $next_tier_amount_crypto = abs($remaining_value_current_tier) / $next_tier_price;

            $amount_crypto = $current_tier_amount_crypto + $next_tier_amount_crypto;
        }

        return $amount_crypto;
    }

    public function cryptoToThbt($amount_crypto) {        
        $balance = $this->balance;
        $tier = $this->currentTier;
        $price = $this->currentPrice;

        $crypto_balance_current_tier = 10 - ( (1000 - $balance) - ($tier * 10) ); // How many crypto left in this tier
        $remaining_crypto_current_tier = $crypto_balance_current_tier - $amount_crypto; // -5

        if ($remaining_crypto_current_tier > 0) { // Buy within current tier (normal case)
            $amount_thbt = $amount_crypto * $price;
        } else { // Buy over current tier (edge case)
            // Current tier amount
            $current_tier_amount_thbt = $crypto_balance_current_tier * $price;

            // Next tier amount
            $next_tier_price = $this->base_price * ( pow(1.1, ($tier + 1)) );
            $next_tier_amount_thbt = abs($remaining_crypto_current_tier) * $next_tier_price;

            $amount_thbt = $current_tier_amount_thbt + $next_tier_amount_thbt;
        }

        return $amount_thbt;
    }
}

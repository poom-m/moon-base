<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Models\Crypto;

class BuyTest extends TestCase
{
    use RefreshDatabase;

    public function test_buy_edge_case() {
        // Seed database
        $this->seed();

        $user = Str::uuid();

        // Buy 9 cryptos
        for ($i = 0; $i < 9; $i++) {
            $response = $this->postJson('/api/orders', [
                'crypto_id' => 1,
                'user_id' => $user,
                'amount_thbt' => 50,
                'amount_crypto' => 1,
                'slippage' => 0,
                'balance_thbt' => 100,
            ]);
            $response->dump();
            $response->assertJson([
                'message' => 'Order created',
            ]);
        }

        // Buy another 75 THBT worth of crypto for edge case
        $response = $this->postJson('/api/orders', [
            'crypto_id' => 1,
            'user_id' => Str::uuid(),
            'amount_thbt' => 75,
            'amount_crypto' => 1.4545454545455,
            'slippage' => 0,
            'balance_thbt' => 100,
        ]);
        $response->dump();
        $response->assertJson([
            'message' => 'Order created',
        ]);

        // Check for crypto balance
        $response = $this->getJson('/api/cryptos/1');
        $response->dump();
        $response->assertJsonFragment([
            'balance' => 989.54545454545,
        ]);

        // Try to buy with incorrect rate
        $response = $this->postJson('/api/orders', [
            'crypto_id' => 1,
            'user_id' => Str::uuid(),
            'amount_thbt' => 1,
            'amount_crypto' => 500,
            'slippage' => 0,
            'balance_thbt' => 100,
        ]);
        $response->dump();
        $response->assertJson([
            'message' => 'Incorrect exchange rate or exceed slippage tolerance. Please try again.',
        ]);
    }
}

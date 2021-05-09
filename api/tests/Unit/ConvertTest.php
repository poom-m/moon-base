<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Crypto;

class ConvertTest extends TestCase
{
    use RefreshDatabase;

    public function test_convert_thbt_to_crypto()
    {
        // Seed database
        $this->seed();

        // Select crypto
        $crypto = Crypto::first();

        // Try to convert 50 thbt when crypto balance = 1000, user will get 1 crypto
        // Try to convert 100 thbt, user will get 2 crypto
        $this->assertEquals(1000, $crypto->balance);
        $this->assertEquals(1, $crypto->thbtToCrypto(50));
        $this->assertEquals(2, $crypto->thbtToCrypto(100));
    }
}

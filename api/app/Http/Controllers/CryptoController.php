<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CryptoResource;
use App\Models\Crypto;

class CryptoController extends Controller
{
    public function show($id) {
        $crypto = Crypto::find($id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found'
        ], 404);

        return new CryptoResource($crypto);
    }
}

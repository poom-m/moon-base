<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Crypto;
use App\Models\Order;

class OrderController extends Controller
{
    public function index() {
        return OrderResource::collection(Order::all());
    }

    public function thbtToCrypto($crypto_id = null, $amount_thbt = null) {
        // Get parameter values
        $crypto_id = $crypto_id ?? request()->crypto_id;
        $crypto = Crypto::find($crypto_id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found.'
        ], 404);
        $amount_thbt = $amount_thbt ?? request()->amount_thbt;

        // Convert THBT to crypto
        $convertCrypto = $crypto->thbtToCrypto($amount_thbt);

        return response()->json($convertCrypto);
    }

    public function create(OrderRequest $request) {
        // Get crypto instance
        $crypto = Crypto::find($request->crypto_id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found.'
        ], 404);

        // Check sufficient thbt balance
        if ($request->balance_thbt < $request->amount_thbt) return response()->json([
            'message' => 'Insufficient THBT balance.'
        ], 400);

        // Check sufficient crypto balance
        if ($crypto->balance < $request->amount_crypto) return response()->json([
            'message' => 'Insufficient ' . $crypto->name . ' balance.'
        ], 400);

        // Check if user send correct exchange rate
        $convertCrypto = $crypto->thbtToCrypto($request->amount_thbt);
        if (round($request->amount_crypto, 10) != round($convertCrypto['amount_crypto'], 10)) return response()->json([
            'message' => 'Incorrect exchange rate detected. Please try again.'
        ], 400);

        // Create order
        $order = new Order;
        $order->crypto_id = $request->crypto_id;
        $order->user_id = $request->user_id;
        $order->amount_thbt = $request->amount_thbt;
        $order->amount_crypto = $request->amount_crypto;
        $order->price = $crypto->current_price;
        $order->slippage = $request->slippage;
        $order->save();

        // Update crypto balance
        $crypto->balance = $convertCrypto['remaining_crypto'];
        $crypto->save();

        return response()->json([
            'message' => 'Order created',
        ], 201);
    }
}

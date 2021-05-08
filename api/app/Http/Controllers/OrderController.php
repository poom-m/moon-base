<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Crypto;
use App\Models\Order;

class OrderController extends Controller
{
    public function create(OrderRequest $request) {
        // Get crypto instance
        $crypto = Crypto::find($request->crypto_id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found'
        ], 404);

        // Check sufficient crypto balance
        if ($crypto->balance < $request->amount_crypto) return response()->json([
            'message' => 'Insufficient ' . $crypto->name . ' balance'
        ], 400);

        // Create order
        $order = new Order;
        $order->crypto_id = $request->crypto_id;
        $order->user_id = $request->user_id;
        $order->amount_thbt = $request->amount_thbt;
        $order->amount_crypto = $request->amount_crypto;
        $order->price = $crypto->price;
        $order->slippage = $request->slippage;
        $order->save();

        // Update crypto balance
        $crypto->balance = $crypto->balance - $request->amount_crypto;
        $crypto->save();

        return response()->json([
            'message' => 'Order created',
        ], 201);
    }
}

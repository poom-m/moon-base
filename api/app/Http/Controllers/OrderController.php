<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Crypto;
use App\Models\Order;

class OrderController extends Controller
{
    public function create(OrderRequest $request) {
        $crypto = Crypto::find($request->crypto_id);
        if (!$crypto) return response()->json([
            'messsage' => 'Crypto not found'
        ], 404);

        $order = new Order;
        $order->crypto_id = $request->crypto_id;
        $order->user_id = $request->user_id;
        $order->amount_thbt = $request->amount_thbt;
        $order->amount_crypto = $request->amount_crypto;
        $order->price = $crypto->price;
        $order->slippage = $request->slippage;
        $order->save();

        return response()->json([
            'message' => 'Order created'
        ], 201);
    }
}

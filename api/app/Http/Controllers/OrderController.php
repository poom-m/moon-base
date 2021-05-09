<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Crypto;
use App\Models\Order;

class OrderController extends Controller
{
    public function index() {
        return OrderResource::collection(Order::orderBy('created_at', 'desc')->get());
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

        return response()->json([ 'amount_crypto' => $convertCrypto ]);
    }

    public function cryptoToThbt($crypto_id = null, $amount_crypto = null) {
        // Get parameter values
        $crypto_id = $crypto_id ?? request()->crypto_id;
        $crypto = Crypto::find($crypto_id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found.'
        ], 404);
        $amount_crypto = $amount_crypto ?? request()->amount_crypto;

        // Convert crypto to THBT
        $convertThbt = $crypto->cryptoToThbt($amount_crypto);

        return response()->json([ 'amount_thbt' => $convertThbt ]);
    }

    public function create(OrderRequest $request) {
        DB::beginTransaction();
        // Get crypto instance
        $crypto = Crypto::find($request->crypto_id);
        if (!$crypto) return response()->json([
            'message' => 'Crypto not found.'
        ], 404);

        // Check if user send correct exchange rate
        $buyWith = round($request->amount_thbt, 10);
        $convertedThbt = round($crypto->cryptoToThbt($request->amount_crypto));
        if ($convertedThbt != $buyWith) {
            // Allow for slippage
            $difference = $convertedThbt - $buyWith;
            $slippage = $difference / $buyWith;
            if ($slippage > 0 && $slippage > $request->slippage) {
                return response()->json([
                    'message' => 'Incorrect exchange rate or exceed slippage tolerance. Please try again.',
                ], 400);
            }
        }

        // Check sufficient thbt balance
        if ($request->balance_thbt < $convertedThbt) return response()->json([
            'message' => 'Insufficient THBT balance.'
        ], 400);

        // Check sufficient crypto balance
        if ($crypto->balance < $request->amount_crypto) return response()->json([
            'message' => 'Insufficient ' . $crypto->name . ' balance.'
        ], 400);

        // Create order
        $order = new Order;
        $order->crypto_id = $request->crypto_id;
        $order->user_id = $request->user_id;
        $order->amount_thbt = $convertedThbt;
        $order->amount_crypto = $request->amount_crypto;
        $order->price = $crypto->current_price;
        $order->slippage = $request->slippage;
        $order->save();

        // Update crypto balance
        $crypto->balance = $crypto->balance - $request->amount_crypto;
        $crypto->save();

        DB::commit();

        return response()->json([
            'message' => 'Order created',
            'amount_thbt' => $order->amount_thbt,
            'amount_crypto' => $order->amount_crypto,
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        // リクエストのバリデーション
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // モデルのメソッドを呼び出して購入処理を行う
            Product::handlePurchase($request->product_id, $request->quantity);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}

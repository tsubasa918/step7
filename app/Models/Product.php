<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock', 'company_id', 'image'];

    // 検索クエリのロジックをModelに集約
    public static function search($request)
    {
        $query = self::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('manufacturer')) {
            $query->where('company_id', $request->manufacturer);
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        if ($request->filled('stock_min') && $request->filled('stock_max')) {
            $query->whereBetween('stock', [$request->stock_min, $request->stock_max]);
        }

        return $query;
    }

    // 全商品の取得ロジックをModelに集約
    public static function getAllProducts($request)
    {
        return self::search($request)->get();
    }

    // IDから商品を取得し、削除
    public static function deleteProductById($id)
    {
        $product = self::findOrFail($id);
        $product->delete();
        return $product;
    }

    // 新規商品作成
    public static function createProduct($data)
    {
        return self::create($data);
    }

    // 購入処理を行うメソッドを追加
    public static function handlePurchase($productId, $quantity)
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $product = self::findOrFail($productId);

            if ($product->stock < $quantity) {
                throw new \Exception('Insufficient stock');
            }

            // 売上情報を作成
            Sale::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
            ]);

            // 在庫を減らす
            $product->reduceStock($quantity);

            return $product;
        });
    }

    // 在庫を減らす処理を独立したメソッドに切り出し
    public function reduceStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new \Exception('Insufficient stock to reduce');
        }

        $this->decrement('stock', $quantity);
    }
}

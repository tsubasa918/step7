<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock', 'company_id', 'image'];

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
}

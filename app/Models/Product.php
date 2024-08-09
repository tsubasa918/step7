<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id', 'price', 'stock', 'comment', 'image'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function searchProducts($name = null, $manufacturer = null)
    {
        $query = self::query();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($manufacturer) {
            $query->where('company_id', $manufacturer);
        }

        return $query->get();
    }
}

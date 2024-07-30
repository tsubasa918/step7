<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('manufacturer')) {
            $query->where('company_id', $request->manufacturer);
        }
        $products = $query->get();
        $companies = Company::all();
        return view('products.index', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->route('products.index');
    }

    public function create()
{
    $companies = Company::all();
    return view('products.create', compact('companies'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'company_id' => 'required|exists:companies,id',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'comment' => 'nullable|string',
        'image' => 'nullable|image'
    ]);

    $path = $request->file('image')->store('images', 'public');

    Product::create([
        'name' => $request->name,
        'company_id' => $request->company_id,
        'price' => $request->price,
        'stock' => $request->stock,
        'comment' => $request->comment,
        'image' => $path,
    ]);

    return redirect()->route('products.index');
}

}

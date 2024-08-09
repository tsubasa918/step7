<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::searchProducts($request->name, $request->manufacturer);
        $companies = Company::all();
        return view('products.index', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($id);
            $product->delete();

            DB::commit();
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }
}
class ProductController extends Controller
{
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

        try {
            DB::beginTransaction();

            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

            Product::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'image' => $path,
            ]);

            DB::commit();
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }
}

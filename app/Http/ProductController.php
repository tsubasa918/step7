<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config; // Configを追加

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::search($request)->get();
        $companies = Company::all();
        return view('products.index', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => Config::get('message.messages.delete_success')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => Config::get('message.errors.unexpected')], 500);
        }
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
            return redirect()->back()->withErrors(['error' => Config::get('message.errors.unexpected')]);
        }
    }

    public function search(Request $request)
    {
        $products = Product::search($request)->get();
        return response()->json($products);
    }
}

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
        // Modelからデータを取得
        $products = Product::getAllProducts($request);
        $companies = Company::all(); // Companyは別途Model化されていないため、ここで取得
        return view('products.index', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // 削除処理をModelに委譲
            Product::deleteProductById($id);

            DB::commit();
            return response()->json(['success' => true, 'message' => Config::get('message.messages.delete_success')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => Config::get('message.errors.unexpected')], 500);
        }
    }

    public function create()
    {
        $companies = Company::all(); // 全企業の取得
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

            // 画像のパスを処理
            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

            // 配列を一度変数に格納
            $productData = [
                'name' => $request->name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'image' => $path,
            ];

            // 新規商品作成をModelに委譲
            Product::createProduct($productData);

            DB::commit();
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => Config::get('message.errors.unexpected')]);
        }
    }

    public function search(Request $request)
    {
        // 検索処理をModelに委譲
        $products = Product::getAllProducts($request);
        return response()->json($products);
    }
}

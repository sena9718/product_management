<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 一覧表示
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $searchCompany = $request->input('search-company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');
        $sortColumn = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'desc');

        $model = new Product;
        $products = $model->searchList($keyword, $searchCompany, $min_price, $max_price, $min_stock, $max_stock, $sortColumn, $sortOrder);

        $companies = DB::table('companies')->get();

        return view('products.index', compact('products', 'companies', 'sortColumn', 'sortOrder'));
    }
    
    public function search(Request $request)
    {    
        // リクエストから検索パラメータを抽出
        $keyword = $request->input('keyword');
        $searchCompany = $request->input('search-company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');
        $sortColumn = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'desc');

        // 既存の searchList メソッドを使用して検索を実行
        $model = new Product;
        $products = $model->searchList($keyword, $searchCompany, $min_price, $max_price, $min_stock, $max_stock, $sortColumn, $sortOrder);

        // 検索結果を表示するビューを返す
        return response()->json(['products' => $products]);
    }

    // 新規登録画面表示
    public function create()
    {
        $companies = DB::table('companies')->get();

        return view('products.create', compact('companies'));
    }

    // 新規登録処理
    public function store(ProductRequest $request)
    {   
        $model = new Product;
        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            if($image) {
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;
                $model->storeProduct($request, $img_path);
            } else {
                $model->storeProductNoImg($request);
                // $img_path = null;
            }

            // $model->storeProduct($request, $img_path);

            DB::commit();
            return redirect()->route('products.create')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('products.create')->with('error', 'Error creating the product');
        }
    }

    // 詳細表示
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // 商品情報編集
    public function edit($id)
    {
        $companies = DB::table('companies')->get();
        $model = New Product;
        $product = $model->getProductById($id);

        return view('products.edit', compact('product', 'companies'));
    }

    // 商品情報更新処理
    public function update(ProductRequest $request, $id)
    {
        $model = New Product;
        DB::beginTransaction();
        try {
            $image = $request->file('img_path');
            if($image) {
                $filename = $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                $img_path = 'storage/images/'.$filename;
                $model->updateProduct($request, $img_path, $id);
            } else {
                $model->updateProductNoImg($request, $id);
            }

            DB::commit();
            return redirect(route('products.show', $id))->with('success', 'Product updated successfully');
        } catch(Exception $e) {
            DB::rollBack();
        }
    }

    // 削除処理
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $model = new Product;
            $model->deleteProduct($id);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return back();
        }

        return redirect('/products/index');
    }
}
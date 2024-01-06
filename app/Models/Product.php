<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class Product extends Model
{
    use HasFactory;

     protected $table = 'products';
     
     // 検索処理
     public function searchList($keyword, $searchCompany, $min_price, $max_price, $min_stock, $max_stock, $sortColumn = 'id', $sortOrder ='desc') 
     {
        // productsテーブルからデータ取得。companiesテーブルをjoin
         $query = DB::table('products')
                     ->join('companies', 'products.company_id', '=', 'companies.id')
                     ->select('products.*', 'companies.company_name');
         
         if($keyword) {
             $query->where('products.product_name', 'LIKE', "%".$keyword."%");
         }
         if($searchCompany) {
             $query->where('products.company_id', '=', $searchCompany);
         }
        //  価格下限〜上限
        if($min_price) {
            $query->where('products.price', '>=', $min_price);
        }
        if($max_price) {
            $query->where('products.price', '>=', $max_price);
        }
        // 在庫数下限〜上限
        if($min_stock) {
            $query->where('products.stock', '>=', $min_stock);
        }
        if($max_stock) {
            $query->where('products.stock', '>=', $max_stock);
        }
         $query->orderBy($sortColumn, $sortOrder);

         return $query->get();
     }
     public function getProductById($id)
     {
        return $this->findOrFail($id);
     }

    //バリデーション処理追加
     public function storeProduct(ProductRequest $request, $img_path) {
        // DB::table('products')->insert([
           $this->create([ 
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment, 
            'img_path' => $img_path,
        ]);
     }

    //  新規登録処理
     public function storeProductNoImg(ProductRequest $request) {
        // DB::table('products')->insert([
           $this->create([  
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
        ]);
     }
     
    //  商品情報編集処理
     public function updateProduct(ProductRequest $request, $img_path, $id) {
        $this->findOrFail($id)->update([
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment, 
            'img_path' => $request->img_path,
        ]);
     }

     public function updateProductNoImg(ProductRequest $request, $id) {
        $this->findOrFail($id)->update([
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment, 
        ]);
     }

     public function deleteProduct($id) {
        $product = $this->findOrFail($id);
        $product->delete();
     }
   
    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
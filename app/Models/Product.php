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
     public function searchList($keyword, $searchCompany) 
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

    // 新規情報更新処理
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
     
    //  商品情報更新処理
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
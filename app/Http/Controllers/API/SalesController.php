<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;

class SalesController extends Controller
{
    public function addSale(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the product
        $product = Product::findOrFail($request->input('product_id'));

        // Check if the product is in stock
        if ($product->stock < $request->input('quantity')) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        // Calculate the new stock after the sale
        $newStock = $product->stock - $request->input('quantity');

        // Start a database transaction
        \DB::beginTransaction();

        try {
            // Add a record to the sales table
            $sale = Sale::create([
                'product_id' => $product->id,
                'quantity' => $request->input('quantity'),
                'total_price' => $product->price * $request->input('quantity'),
            ]);

            // Update the product's stock in the products table
            $product->update(['stock' => $newStock]);

            // Commit the transaction
            \DB::commit();

            return response()->json(['message' => 'Sale completed successfully', 'sale' => $sale]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            \DB::rollback();

            return response()->json(['error' => 'Failed to complete the sale'], 500);
        }
    } 
}

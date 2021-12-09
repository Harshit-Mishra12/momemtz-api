<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Product;  
use App\Models\Customer_product; 
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }

       public function createProduct(Request $request) {
        $isExist = Product::select("*")
            ->where("ProductName", $request->ProductName)
            ->exists();

        if($isExist)
        {
            return response()->json(['statusCode'=>'400','message'=>"Product name already exists"], 400); 
        }
       

        $product = new Product();
        $product->user_id = $request->user_id;
        $product->ProductName = $request->ProductName;
        $product->location = $request->location;
        $product->pricePerQuantity = $request->pricePerQuantity;
        $product->description = $request->description;        
        $saved=$product->save();

        if($saved)
        {
            return response()->json(['statusCode'=>'200','data'=>$product], 200); 
        }
      
       }

       public function fetchProduct($vendorId) {

        $products_data = Product::select("*")
        ->where("user_id", $vendorId)
        ->get(); 
        return response()->json(['statusCode'=>'200','data'=>$products_data], 200); 
       }

       public function fetchOrder($vendorId) {
        $orders_data =DB::select("SELECT *
        FROM ((customer_products
        INNER JOIN orders ON orders.id = customer_products.order_id)
        INNER JOIN products ON products.id = customer_products.product_id)
        WHERE orders.vendor_id=$vendorId
        ORDER BY customer_products.order_id
        ");
        return response()->json(['statusCode'=>'200','data'=>$orders_data], 200); 
       }


    
}

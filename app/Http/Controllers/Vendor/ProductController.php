<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Product;  
use App\Models\Order_item; 

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }

       public function createProduct(Request $request) {
        $isExist = Product::select("*")
            ->where("product_name", $request->product_name)
            ->exists();

        if($isExist)
        {
            return response()->json(['statusCode'=>'400','message'=>"Product name already exists"], 400); 
        }
       

        $product = new Product();
        $product->user_id = $request->user_id;
        $product->product_name = $request->product_name;
        $product->location = $request->location;
        $product->price_per_quantity = $request->price_per_quantity;
        $product->description = $request->description;        
        $saved=$product->save();

        if($saved)
        {
            return response()->json(['statusCode'=>'200','data'=>$product], 200); 
        }
      
       }

       public function fetchProduct($vendorId) {

        $products = Product::select("*")
        ->where("user_id", $vendorId)
        ->get();

            if(count($products)==0)
            {
                return response()->json(['statusCode'=>'404','message'=>'No product found'], 404); 
            }
            else{
                return response()->json(['statusCode'=>'200','data'=>$products], 200); 
            }
       }

       public function fetchOrder($vendorId) {
        $orders_data =DB::select("SELECT *
        FROM ((Order_items
        INNER JOIN orders ON orders.id = Order_items.order_id)
        INNER JOIN products ON products.id = Order_items.product_id)
        WHERE orders.vendor_id=$vendorId
        ORDER BY Order_items.order_id
        ");
        return response()->json(['statusCode'=>'200','data'=>$orders_data], 200); 
       }


    
}

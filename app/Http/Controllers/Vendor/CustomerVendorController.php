<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Product;  
use App\Models\Vendor_profile;  
use Illuminate\Support\Facades\DB;

class CustomerVendorController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }

      

       public function fetchVendors() {
            $vendors_data =DB::select("SELECT *
            FROM (users
            INNER JOIN vendor_profiles ON vendor_profiles.user_id = users.id)
            WHERE users.is_Signup_Complete='1'
            ");

            return response()->json(['statusCode'=>'200','data'=>$vendors_data], 200); 
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
   public function fetchProductDetail($productId) {

    $products_detail = Product::select("*")
        ->where("id",$productId)
        ->get();
        
    if(count($products_detail)=='0')
    {
        return response()->json(['statusCode'=>'404','message'=>'No product found'], 404); 
    }
    else{
        return response()->json(['statusCode'=>'200','data'=>$products_detail], 200); 
    }
    
}
}

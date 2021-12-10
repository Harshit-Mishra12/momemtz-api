<?php

namespace App\Http\Controllers\Event\EventDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Wishlist;
use App\Models\Dresscode;
use App\Models\Event_dashboard_status;
use Validator;


class WishlistController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }
  
   
   
  
   public function createEventWishList(Request $request,$event_id)
   {

    $validation = Validator::make($request->all(), [
        'giftName' => '|required',
        'accountInfo' => '|required',
        'additionalInfo' => '|required',
        'price' => '|required|',
        
    ]);
    if(!$validation->passes())
    {
        return response()->json(['error'=>'Invalid request',
          'message' => 'Invalid request made from the client side'
          ]
          , 400); 
    }
            $isExist = Wishlist::select("id")
            ->where("giftName", $request->giftName)
            ->exists();

            if($isExist){  return response()->json(['error'=>'Already Exists','message'=>"Invalid request as data already exists"], 403);  }

            $wishlist = new Wishlist();
            $wishlist->giftName = $request->giftName;
            $wishlist->accountInfo = $request->accountInfo; 
            $wishlist->additionalInfo = $request->additionalInfo;
            $wishlist->event_id =$event_id;
            $wishlist->price = $request->price;
            $saved=$wishlist->save();

            $updated = Event_dashboard_status::where("event_id",$event_id)
            ->update([
                'wishlist' => '1',
            ]);

            if($saved && $updated)
            {
                return response()->json(['statusCode'=>'200','message'=>"data saved successfully"], 200);  
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>"data not saved"], 500);   
            }     

   }
   public function fetchEventWishlist($event_id)
   {

            $events_wishlist_data = Wishlist::select("*")
            ->where("event_id", $event_id)
            ->get();
        
            if(count($events_wishlist_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_wishlist_data], 200); 
            }
   }
   public function updateEventWishlist(Request $request,$wishlist_id)
   {

            
            $updated = Wishlist::where("id", $wishlist_id)
            ->update([
                'giftName' => $request->giftName,
                'accountInfo' => $request->accountInfo,
                'additionalInfo' => $request->additionalInfo,
                'price' => $request->price,

            ]);
            if(!$updated)
            { return response()->json(['statusCode'=>'400','message'=>"Updation failed"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','message'=>"Succesfully updated"], 200); 
            }
   }
   

}

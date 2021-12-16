<?php

namespace App\Http\Controllers\Event\EventDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Dresscode;
use App\Models\Event_dashboard_status;
use Validator;


class DressCodeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }
  
   
   
  
   
   public function createEventDresscode(Request $request,$event_id)
   {
    $validation = Validator::make($request->all(), [
        'description' => '|required|',
        'customer_id' =>'|required',
        'dress_code_category_id' => '|required',
        'color_one' => '|required',
        'color_two' => '|required|',
    ]);
    if(!$validation->passes())
    {
        return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
    }
            $dresscode = new Dresscode();
            $dresscode->description = $request->description;
            $dresscode->dress_code_category_id	 = $request->dress_code_category_id	;
            $dresscode->color_one = $request->color_one;
            $dresscode->color_two = $request->color_two;
            // $dresscode->customer_id = $request->customer_id;
            $dresscode->event_id = $event_id;
            $saved=$dresscode->save();

            $updated = Event_dashboard_status::where("event_id",$event_id)
            ->update([
                'dresscode' => '1',
            ]);
            
            if($saved && $updated)
            {
                return response()->json(['statusCode'=>'200','message'=>"data saved successfully"], 200);  
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>"data not saved"], 500);        }     
   }
   public function fetchEventDresscode($event_id)
   {        $events_dresscode_data = Dresscode::select("*")
            ->where("event_id", $event_id)
            ->get();
        
            if(count($events_dresscode_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_dresscode_data], 200);
                }
    }

}

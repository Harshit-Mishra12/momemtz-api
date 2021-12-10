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


class TicketController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }
  
   public function createEventTicket(Request $request,$event_id)
   {

    $validation = Validator::make($request->all(), [
        'name' => '|required',
        'description' => '|required',
        'customer_id' => '|required|',
        'stock' => '|required|',
        'price' => '|required|',
        
    ]);
    if(!$validation->passes())
    {
        return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
    }
            $isExist = Ticket::select("id")
            ->where("name", $request->name)
            ->exists();

            if($isExist){  return response()->json(['statusCode'=>'400','uniqueCode'=>'2','message'=>"Name already present"], 400);  }

            $ticket = new Ticket();
            $ticket->name = $request->name;
            $ticket->description = $request->description;
            $ticket->customer_id = $request->customer_id;
            $ticket->event_id =$event_id;
            $ticket->stock = $request->stock;
            $ticket->price = $request->price;
            $saved=$ticket->save();

            $updated = Event_dashboard_status::where("event_id",$event_id)
            ->update([
                'ticket' => '1',
            ]);

            if($saved && $updated)
            {
                return response()->json(['statusCode'=>'200','message'=>"data saved successfully"], 200);  
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>"data not saved"], 500);   
            }     

   }
   public function fetchEventTicket($event_id)
   {

            $events_ticket_data = Ticket::select("*")
            ->where("event_id", $event_id)
            ->get();
        
            if(count($events_ticket_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_ticket_data], 200); 
            }
   }
  
   public function updateEventTicket(Request $request,$ticket_id)
   {

            
            $updated = Ticket::where("id", $ticket_id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
                'stock' => $request->stock,
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

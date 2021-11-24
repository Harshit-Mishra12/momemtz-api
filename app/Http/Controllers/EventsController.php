<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Dresscode;
use Validator;


class EventsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }
   public function createEvent(Request $request)
   {

    $validation = Validator::make($request->all(), [
        'title' => '|required',
        'description' => '|required',
        'categoryId' => '|required|',
        'eventTypeId' => '|required|',
        'budget' => '|required|',
        'datetime' => '|required|',
        'locationTitle' => '|required|',
        'locationLon' => '|required|',
        'locationLat' => '|required|'
    ]);
    if(!$validation->passes())
    {
        return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
    }
            $isExist = Event::select("id")
            ->where("title", $request->title)
            ->exists();

            if($isExist){  return response()->json(['statusCode'=>'400','uniqueCode'=>'2','message'=>"title already present"], 400);  }

            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->customer_id = $request->customer_id;
            $event->categoryId = $request->categoryId;
            $event->eventTypeId = $request->eventTypeId;
            $event->budget = $request->budget;
            $event->datetime = $request->datetime;
            $event->locationTitle = $request->locationTitle;
            $event->locationLon = $request->locationLon;
            $event->locationLat = $request->locationLat;
            $saved=$event->save();
            if($saved)
            {
                return response()->json(['statusCode'=>'200','message'=>"data saved successfully"], 200);  
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>"data not saved"], 500);   
            }     

   }
   public function fetchEvents(Request $request)
   {

            $events_data = Event::select("*")
            ->where("customer_id", $request->customerId)
            ->get();
        
            if(count($events_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_data], 200); 
            }
   }
   public function updateEvents(Request $request)
   {

            $updated = Event::where("customer_id", $request->customerId)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'categoryId' => $request->categoryId,
                'eventTypeId' => $request->eventTypeId,
                'budget' => $request->budget,
                'datetime' => $request->datetime,
                'locationTitle' => $request->locationTitle,
                'locationLon' => $request->locationLon,
                'locationLat' => $request->locationLat,
            ]);
        
            if(!$updated)
            { return response()->json(['statusCode'=>'400','message'=>"Not updated"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>"sucessfully updated"], 200); 
            }
   }
   public function fetchAllEvents(Request $request)
   {
        $events_data = Event::select("*")
        ->get();
        if(count($events_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_data], 200); 
            }

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
            $ticket->event_id = $request->event_id;
            $ticket->stock = $request->stock;
            $ticket->price = $request->price;
            $saved=$ticket->save();
            if($saved)
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
   public function createEventDresscode(Request $request,$event_id)
   {

    $validation = Validator::make($request->all(), [
        'description' => '|required|',
        'customer_id' =>'|required',
        'dressCodeCategoryId' => '|required',
        'colorOne' => '|required',
        'colorTwo' => '|required|',
    ]);
    if(!$validation->passes())
    {
        return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
    }
            $dresscode = new Dresscode();
            $dresscode->description = $request->description;
            $dresscode->dressCodeCategoryId = $request->dressCodeCategoryId;
            $dresscode->colorOne = $request->colorOne;
            $dresscode->colorTwo = $request->colorTwo;
            $dresscode->customer_id = $request->customer_id;
            $dresscode->event_id = $request->event_id;
            $saved=$dresscode->save();
            if($saved)
            {
                return response()->json(['statusCode'=>'200','message'=>"data saved successfully"], 200);  
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>"data not saved"], 500);   
            }     
   }
   public function fetchEventDresscode($event_id)
   {

            $events_dresscode_data = Dresscode::select("*")
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

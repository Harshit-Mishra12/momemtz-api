<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Dresscode;
use App\Models\Event_dashboard_status;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon;

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
        'categoryInterestName' => '|required|',
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
            $event->categoryInterestName = $request->categoryInterestName;
            $event->eventTypeId = $request->eventTypeId;
            $event->budget = $request->budget;
            $event->datetime = $request->datetime;
            $event->locationTitle = $request->locationTitle;
            $event->locationLon = $request->locationLon;
            $event->locationLat = $request->locationLat;
            $event->save();

            $event_dashboard_status = new Event_dashboard_status();
            $event_dashboard_status->event_id = $event->id;
            $event_dashboard_status->ticket = '0';
            $event_dashboard_status->vendor = '0';
            $event_dashboard_status->dresscode = '0';
            $event_dashboard_status->wishlist = '0';
            $event_dashboard_status->to_dos = '0';
            $saved=$event_dashboard_status->save();

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
        // if isActive is 2 it means that event is deleted
            $events_data = Event::select("*")
            ->where("customer_id", $request->customerId)
            ->where("isActive", '!=' , 2)
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

            $updated = Event::where("id", $request->eventId)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'categoryInterestName' => $request->categoryInterestName,
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
   public function fetchAllEvents()
   {    // if isActive is 2 it means that event is deleted
            if (!Auth::check()) {
                return response()->json(['statusCode'=>'401','message'=>"not authoridsed"], 401);
            }
        $today_date = Carbon\Carbon::now();
        $events_data = Event::select("*")
        ->where("isActive", '!=' , 2)
        ->get();
        if(count($events_data) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$events_data], 200); 
            }

   }

   public function actionPublishEvent(Request $request,$event_id)
   {
        $isExist = Ticket::select("id")
        ->where("event_id", $event_id)
        ->exists();

        if(!$isExist)
        {
            return response()->json(['statusCode'=>'409','error'=>'no ticket exists','message'=>'no ticket exists to publish this event'], 409);   
        }
        else{

        $user = Event::where('id', $event_id)
            ->update([
                'isActive' => "1",
            ]);
            return response()->json(['statusCode'=>'200','message'=>'event is published'], 200); 
        }

   }
   public function fetchActiveEvent(Request $request)
   {    // ->whereDate('datetime', '>',$today_date) 
       // check it
            $today_date = Carbon\Carbon::now();
            $active_event = Event::select("*")
            ->where("isActive",1)
            ->whereDate('datetime', '>',$today_date)
            ->where("customer_id",$request->customerId)
            ->get();
        
            if(count($active_event) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$active_event], 200); 
            }
   }
   public function fetchAllActiveEvent()
   {
            $active_event = Event::select("*")
            ->where("isActive",1)
            ->whereDate('datetime', '>',$today_date)
            ->get();
          
            if(count($active_event) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$active_event], 200); 
            }
   }
   public function fetchUpcomingEvent()
   { 
       // https://laravel.io/forum/02-20-2015-using-carbon-to-get-next-weeks-dates
            $today_date = Carbon\Carbon::now();
            $date = Carbon\Carbon::now()->addDays(10);
            $active_event = Event::select("*")
            ->where("isActive",1)
            ->whereDate('datetime', '<',$date)
            ->whereDate('datetime', '>',$today_date)
            ->get();

        
            if(count($active_event) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$active_event], 200); 
            }
   }
   public function fetchCompletedEvent(Request $request)
   { 
      
            $today_date = Carbon\Carbon::now();
            $date = Carbon\Carbon::now()->addDays(10);
            $completed_event = Event::select("*")
            ->where("isActive",1)
            ->where("customer_id",$request->customerId)
            ->whereDate('datetime', '<',$today_date)
            ->get();

            if(count($completed_event) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$completed_event], 200); 
            }
   }
   public function fetchUnpublishEvent(Request $request)
   {
            // $unpublish_event =DB::select(" SELECT * FROM (events
            // INNER JOIN event_dashboard_statuses ON event_dashboard_statuses.event_id = events.id) 
            // WHERE event_dashboard_statuses.ticket='1' AND events.customer_id=$request->customerId 
            // AND events.isActive	='0'
            // AND( event_dashboard_statuses.vendor='0' 
            // OR event_dashboard_statuses.dresscode='0' OR event_dashboard_statuses.wishlist='0' 
            // OR event_dashboard_statuses.to_dos='0')
            // ");
            $unpublish_event = Event::select("*")
            ->where("customer_id",$request->customerId)
            ->where("isActive",0)
            ->get();

            if(count($unpublish_event) === 0)
            { return response()->json(['statusCode'=>'400','message'=>"Not found"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$unpublish_event], 200); 
            }
    }
    public function deleteAnEvent($event_id)
    {

        $updated = Event::where("id", $event_id)
        ->update([
            'isActive' => '2',
        ]);

        if(!$updated)
            { return response()->json(['statusCode'=>'400','message'=>"Not updated"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>"sucessfully updated"], 200); 
            }
    }

}

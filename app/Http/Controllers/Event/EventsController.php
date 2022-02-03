<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User_transaction;
use App\Models\Ticket;
use App\Models\Dresscode;
use App\Models\Event_dashboard_status;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon;
use File;

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
        'customer_id' => '|required', 
        'event_type_id' => '|required|',
        'event_interest_category_id'=> '|required|',
        'budget' => '|required|',
        'datetime' => '|required|',
        'location_title' => '|required|',
        'location_lon' => '|required|',
        'location_lat' => '|required|',
        'event_image_url' => 'required|mimes:jpeg,jpg,png|max:10000'
    ]);
    if(!$validation->passes())
    {
        return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
    }
    $path = public_path() . '/uploads/Event_Documents';
    if (!File::exists($path)) {
        File::makeDirectory($path, $mode = 0777, true, true);
    }
    $extension = $request->file('event_image_url')->getClientOriginalExtension();
    $filename = str_replace(" ","_", $request->title);
    // $fileNameToStore = $filename . '_' . '.' . $extension;
    $fileNameToStore = $filename . '_' . $request->customer_id . '.' . $extension;
    $request->file('event_image_url')->move(public_path('uploads/Event_Documents'),$fileNameToStore);
    

            $isExist = Event::select("id")
            ->where("title", $request->title)
            ->exists();

            if($isExist){  return response()->json(['statusCode'=>'400','uniqueCode'=>'2','message'=>"title already present"], 400);  }

            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->customer_id = $request->customer_id;
            $event->event_interest_category_id = $request->event_interest_category_id;
            $event->event_type_id = $request->event_type_id;
            $event->budget = $request->budget;
            $event->datetime = $request->datetime;
            $event->location_title = $request->location_title;
            $event->location_lon = $request->location_lon;
            $event->location_lat = $request->location_lat; 
            $event->event_image_url = $fileNameToStore;
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
                return response()->json(['statusCode'=>'200','event_id'=>$event->id,'message'=>"data saved successfully"], 200);  
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
            { return response()->json(['statusCode'=>'404','message'=>"Not found"], 404);
                
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
                'category_interest_name' => $request->category_interest_name,
                'budget' => $request->budget,
                'datetime' => $request->datetime,
                'location_title' => $request->location_title,
                'location_lon' => $request->location_lon,
                'location_lat' => $request->location_lat,
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

   public function actionPublishEvent($event_id)
   {
        $isExist = Ticket::select("id")
        ->where("event_id", $event_id)
        ->exists();

        if(!$isExist)
        {
            return response()->json(['status_code'=>'2','error'=>'no ticket exists','message'=>'no ticket exists to publish this event'], 409);   
        }
        else{

        $user = Event::where('id', $event_id)
            ->update([
                'isActive' => "1",
            ]);
            return response()->json(['status_code'=>'1','message'=>'event is published'], 200); 
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
            { return response()->json(['statusCode'=>'404','message'=>"Not found"], 404);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>$active_event], 200); 
            }
   }
   public function fetchAllActiveEvent()
   {        $today_date = Carbon\Carbon::now();
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
//    public function fetchUpcomingEvent()
//    { 
//        // https://laravel.io/forum/02-20-2015-using-carbon-to-get-next-weeks-dates
//             $today_date = Carbon\Carbon::now();
//             $date = Carbon\Carbon::now()->addDays(10);
//             $active_event = Event::select("*")
//             ->where("isActive",1)
//             ->whereDate('datetime', '<',$date)
//             ->whereDate('datetime', '>',$today_date)
//             ->get();

        
//             if(count($active_event) === 0)
//             { return response()->json(['statusCode'=>'404','message'=>"Not found"], 404);
                
//             }
//             else{  
//                 return response()->json(['statusCode'=>'200','data'=>$active_event], 200); 
//             }
//    }

    public function fetchUpcomingEvent(Request $request,$customer_id)
        {
          
      $data = Event::select("*")
         ->join('user_transactions', 'user_transactions.event_id', 'events.id')
         ->where("events.customer_id", $customer_id)
         ->where("events.event_type_id", '2')
         ->where("isActive",1)
         ->get();
                  if(count($data) === 0)
                     { return response()->json(['statusCode'=>'4','message'=>"No data exists!!"], 404);
                         
                     }
                     else{  
                         return response()->json(['statusCode'=>'200','message'=>"data exists!!",'data'=>$data], 200); 
                     }

      return response()->json(['statusCode'=>'200','data'=> $data], 200); 
            
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
    {  // isActive 2 means its deleted

        $updated = Event::where("id", $event_id)
        ->update([
            'isActive' => '2',
        ]);

        if(!$updated)
            { return response()->json(['statusCode'=>'400','message'=>"Not deleted"], 400);
                
            }
            else{  
                return response()->json(['statusCode'=>'200','data'=>"sucessfully deleted"], 200); 
            }
    }

}

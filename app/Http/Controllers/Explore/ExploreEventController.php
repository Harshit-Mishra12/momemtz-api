<?php

namespace App\Http\Controllers\Explore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User_interest;
use Illuminate\Support\Facades\DB;

class ExploreEventController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }

    public function fetchExploreCustomerEvents(Request $request)
    {
        
        $Music=$Sports=$Games=$Eating=$Dancing=$Tech='0';
        $arr = array();

         $interest = User_interest::select("*")
        ->where("user_id",$request->customerId)
        ->get();
        if($interest[0]->Music=='1'){  $Music='Music'; array_push($arr, $Music);}
        if($interest[0]->Sports=='1'){  $Sports="Sports";array_push($arr, $Sports);}
        if($interest[0]->Games=='1'){  $Games='Games';array_push($arr, $Games);}
        if($interest[0]->Eating=='1'){  $Eating='Eating';array_push($arr, $Eating);}
        if($interest[0]->Dancing=='1'){  $Dancing='Dancing';array_push($arr, $Dancing);}
        if($interest[0]->Tech=='1'){  $Tech='Tech';array_push($arr, $Tech);}

        $events_data = Event::select("*")
        ->where("isActive", '=' , 1)
        ->where("customer_id",$request->customerId) 
        ->whereIn('categoryInterestName',$arr)
        ->get();
        

        return response()->json(['statusCode'=>'200','data'=>$events_data], 200); 
    }
    public function fetchExploreNearbyEvents(Request $request)
    {
        // https://stackoverflow.com/questions/2234204/find-nearest-latitude-longitude-with-an-sql-query
            $my_lat=$request->locationLat; 
            $my_lng=$request->locationLon;
            $dist=5; #10 miles radius

            $events_data =DB::select("SELECT dest.id,dest.event_interest_category_id, dest.locationLat, dest.locationLon,  3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.locationLat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.locationLat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.locationLon)) *  pi()/180 / 2), 2))
            ) as distance
            FROM events as dest
            having distance < $dist
            ORDER BY distance limit 10;
            ");
        //     $arr = array();
        //     $events_data =DB::select("SELECT *  
        //     FROM tickets 
        //     WHERE price BETWEEN 100 AND 200
        //     ");
           
        //    for($i=0;$i< count($events_data);$i++)
        //    {
        //         if (!in_array($events_data[$i]->event_id, $arr))
        //         {  array_push($arr,$events_data[$i]->event_id);
        //         }
        //    }


        //     $my_lat=$request->locationLat; 
        //     $my_lng=$request->locationLon;
        //     $distance=5; #10 miles radius

        //     $events_data =DB::select("SELECT dest.id,dest.categoryInterestName,event_dashboard_statuses.dresscode, dest.locationLat, dest.locationLon,  3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.locationLat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.locationLat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.locationLon)) *  pi()/180 / 2), 2))
        //     ) as distance
        //     FROM (events as dest
        //     INNER JOIN event_dashboard_statuses ON event_dashboard_statuses.event_id = dest.id) 
        //     WHERE categoryInterestName = 'Sports' AND event_dashboard_statuses.dresscode = '1' AND dest.id IN ('1', '5') AND dest.isActive = '1'
        //     having distance >= 0 and distance < $distance
        //     ORDER BY datetime limit 10;
        //     ");

        return response()->json(['statusCode'=>'200','data'=> $events_data], 200); 
    }
    public function fetchExploreEventDetails(Request $request)
    {
            $event_data = Event::select("*")
            ->where("id",$request->eventId) 
            ->get();
        
        return response()->json(['statusCode'=>'200','data'=>$event_data], 200); 
    }
}

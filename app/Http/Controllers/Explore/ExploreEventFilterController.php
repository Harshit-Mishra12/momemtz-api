<?php

namespace App\Http\Controllers\Explore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User_interest;
use Illuminate\Support\Facades\DB;
use Carbon;


class ExploreEventFilterController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => [ ]]);
       }

    public function ExploreEventFilter(Request $request,$customer_id)
    {
            $arr = array();
            $category=$request->event_interest_category_id;
            $my_lat=$request->location_lat; 
            $my_lng=$request->location_lon;
            $sortby_option=$request->sortby_option;
            $dist=$request->distance; 
            
            $dresscodeStatus=$request->dresscodeStatus;
            $startPrice=$request->startPrice;
            $endPrice=$request->endPrice;


            $events_data =DB::select("SELECT *  
            FROM tickets 
            WHERE price BETWEEN $startPrice AND  $endPrice
            ");
           
           for($i=0;$i< count($events_data);$i++)
           {
                if (!in_array($events_data[$i]->event_id, $arr))
                {  $id=$events_data[$i]->event_id.'';
                    array_push($arr,$id);
                }
           }
            $today_date = Carbon\Carbon::now();
            $ids = implode(',', $arr);
            
            $events_data =DB::select("SELECT dest.customer_id, dest.id,dest.event_interest_category_id,event_dashboard_statuses.dresscode, dest.event_image_url,dest.location_lat, dest.location_lon,  3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.location_lat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.location_lat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.location_lon)) *  pi()/180 / 2), 2))
            ) as distance
            FROM (events as dest
            INNER JOIN event_dashboard_statuses ON event_dashboard_statuses.event_id = dest.id) 
            WHERE  event_dashboard_statuses.dresscode = $dresscodeStatus   AND dest.isActive = '1' AND dest.customer_id = '$customer_id' 
            AND  dest.event_interest_category_id= '$category'  AND  dest.id IN ($ids) AND dest.datetime >= '$today_date'
            having distance >= 0 and distance < $dist
            ORDER BY $sortby_option limit 10;
            ");
        return response()->json(['statusCode'=>'200','data'=> $events_data], 200); 
    }

    public function ExploreUpcomingEventFilter(Request $request,$customer_id)
    {
            $arr = array();
            $category=$request->event_interest_category_id;
            $my_lat=$request->location_lat; 
            $my_lng=$request->location_lon;
            $sortby_option=$request->sortby_option;
            $dist=$request->distance; 
            
            $dresscodeStatus=$request->dresscodeStatus;
            $startPrice=$request->startPrice;
            $endPrice=$request->endPrice;


            $events_data =DB::select("SELECT *  
            FROM tickets 
            WHERE price BETWEEN $startPrice AND  $endPrice
            ");
           
           for($i=0;$i< count($events_data);$i++)
           {
                if (!in_array($events_data[$i]->event_id, $arr))
                {  $id=$events_data[$i]->event_id.'';
                    array_push($arr,$id);
                }
           }
            $today_date = Carbon\Carbon::now();
            $ids = implode(',', $arr);
            
            $events_data =DB::select("SELECT dest.customer_id,dest.datetime,dest.location_title,dest.title, dest.id,dest.event_interest_category_id,event_dashboard_statuses.dresscode, dest.event_image_url,dest.location_lat, dest.location_lon,  3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.location_lat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.location_lat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.location_lon)) *  pi()/180 / 2), 2))
            ) as distance
            FROM ((events as dest
            INNER JOIN event_dashboard_statuses ON event_dashboard_statuses.event_id = dest.id) 
            INNER JOIN user_transactions ON user_transactions.event_id = dest.id) 
            WHERE  event_dashboard_statuses.dresscode = $dresscodeStatus   AND dest.isActive = '1' AND dest.customer_id = '$customer_id' 
            AND  dest.event_interest_category_id= '$category'  AND  dest.id IN ($ids) AND dest.datetime >= '$today_date'
            having distance >= 0 and distance < $dist
            ORDER BY $sortby_option limit 10;
            ");
        return response()->json(['statusCode'=>'200','data'=> $events_data], 200); 

    }

}

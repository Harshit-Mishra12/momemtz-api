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

    public function ExploreEventFilter(Request $request)
    {
            $arr = array();
            $category=$request->categoryInterestName;
            $my_lat=$request->locationLat; 
            $my_lng=$request->locationLon;
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
            $events_data =DB::select("SELECT dest.title, dest.id,dest.categoryInterestName,event_dashboard_statuses.dresscode, dest.locationLat, dest.locationLon,  3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.locationLat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.locationLat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.locationLon)) *  pi()/180 / 2), 2))
            ) as distance
            FROM (events as dest
            INNER JOIN event_dashboard_statuses ON event_dashboard_statuses.event_id = dest.id) 
            WHERE  event_dashboard_statuses.dresscode = $dresscodeStatus   AND dest.isActive = '1'
            AND  dest.categoryInterestName= '$category'  AND  dest.id IN ($ids) AND dest.datetime >= '$today_date'
            having distance >= 0 and distance < $dist
            ORDER BY $sortby_option limit 10;
            ");

        // $events_data = Event::select('events.id', 'categoryInterestName','event_dashboard_statuses.dresscode','events.locationLon','events.locationLon','3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.locationLat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.locationLat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.locationLon)) *  pi()/180 / 2), 2)))')
        // ->join('event_dashboard_statuses', 'event_dashboard_statuses.event_id', 'events.id')
        // ->where('event_dashboard_statuses.dresscode', '=', $dresscodeStatus)
        // ->where('categoryInterestName', '=', $category)
        // ->orderBy('datetime', 'ASC')
        // ->get();

        //  $events_data = DB::table('events as dest')
        //  ->select(DB::raw('dest.id,dest.categoryInterestName,3956 * 2 * ASIN(SQRT(POWER(SIN(($my_lat -abs(dest.locationLat)) * pi()/180 / 2),2) + COS($my_lat * pi()/180 ) * COS(abs(dest.locationLat) *  pi()/180) * POWER(SIN(($my_lng - abs(dest.locationLon)) *  pi()/180 / 2), 2))
        //   ) as distance'))
        //   ->join('event_dashboard_statuses', 'event_dashboard_statuses.event_id', 'dest.id')
        //  ->where('event_dashboard_statuses.dresscode', '=', $dresscodeStatus)
        //  ->where('categoryInterestName', '=', $category)
        //  ->orderBy('datetime', 'ASC')
        //  ->get();
        return response()->json(['statusCode'=>'200','data'=> $events_data], 200); 

    }
}

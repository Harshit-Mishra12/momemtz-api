<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_profile; 
use App\Models\Vendor_profile; 
use App\Models\User_signup_status;
use Validator;
use File;

class CustomersProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['actionProfile','actionVendorProfile']]);
       }

    public function actionProfile( Request $request)
    {
        $token = substr(str_shuffle("0123456789"), 0, 4);
        
        $fileNameToStore = '';
        $validation = Validator::make($request->all(), [
            'full_name' => '|required|',
            'location_city' => '|required|',
            'location_country' => '|required|',
            'profile_image_url' => 'required|mimes:jpeg,jpg,png|max:10000'
        ]);
        if(!$validation->passes())
        {
            // return 'Password reset token successfully sent to the user';
            return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
        }
                $path = public_path() . '/uploads/User_Documents';
                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $extension = $request->file('profile_image_url')->getClientOriginalExtension();
                $filename = str_replace(" ","_", $request->full_name);
                $fileNameToStore = $filename . '_' . $request->user_id . '.' . $extension;
                $request->file('profile_image_url')->move(public_path('uploads/User_Documents'), $fileNameToStore);

        $isExist = User::select("*")
            ->where("id", $request->user_id)
            ->exists();
        if(!$isExist){  return response()->json(['statusCode'=>'400','message' => 'This user does not exist'], 400);}

        $isExist = User_profile::select("*")
            ->where("user_id", $request->user_id)
            ->exists();
        $saved="";
        $updated="";
        if($isExist)
        {
            $updated = User_profile::where('user_id', $request->user_id)
            ->update([
                'full_name' => $request->full_name,
                'location_city'=>$request->location_city,
                'location_country'=>$request->location_country,
                'profile_image_url'=>$fileNameToStore
            ]);
            return response()->json(['statusCode'=>'200','message'=>'data is updated'], 200); 
        }
        else{
            $user = User_signup_status::where('user_id', $request->user_id)
            ->update([
                'is_profile_complete' => "Yes",
            ]);


            $user_profile = new User_profile();
            $user_profile->user_id = $request->user_id;
            $user_profile->full_name = $request->full_name;
            $user_profile->location_city = $request->location_city;
            $user_profile->location_country = $request->location_country;
            $user_profile->profile_image_url= $fileNameToStore;
            $saved=$user_profile->save();
                
        }
        if($updated)
               {
                return response()->json(['statusCode'=>'200','message '=> "User id". ' ' . $request->user_id . ' ' . "is updated"], 200);
               }
        elseif($saved)
                {
                    return response()->json(['statusCode'=>'200','data'=>$user_profile], 200); 
                }
         else{
                    return response()->json(['statusCode'=>'500','data is not saved' => 'no'], 500);   
                }

    }
    public function actionVendorProfile( Request $request)
    {
        $token = substr(str_shuffle("0123456789"), 0, 4);
        
        $fileNameToStore = '';
        $validation = Validator::make($request->all(), [
            'business_name' => '|required|',
            'location' => '|required|',
            'profile_image_url' => 'required|mimes:jpeg,jpg,png|max:10000'
        ]);
        if(!$validation->passes())
        {
            // return 'Password reset token successfully sent to the user';
            return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes','data'=>$request], 400);
        }
                $path = public_path() . '/uploads/User_Documents';
                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $extension = $request->file('profile_image_url')->getClientOriginalExtension();
                $filename = str_replace(" ","_", $request->full_name);
                $fileNameToStore = $filename . '_' . $request->user_id . '.' . $extension;
                $request->file('profile_image_url')->move(public_path('uploads/User_Documents'), $fileNameToStore);

        $isExist = User::select("*")
            ->where("id", $request->user_id)
            ->exists();
        if(!$isExist){  return response()->json(['statusCode'=>'400','message' => 'This user does not exist'], 400);}

        $isExist = Vendor_profile::select("*")
            ->where("user_id", $request->user_id)
            ->exists();
        $saved="";
        $updated="";
        if($isExist)
        {
            $updated = Vendor_profile::where('user_id', $request->user_id)
            ->update([
                'business_name' => $request->business_name,
                'location'=>$request->location,
                'profile_image_url'=>$fileNameToStore
            ]);
            return response()->json(['statusCode'=>'200','message'=>'data is updated'], 200); 
        }
        else{
            $user = User_signup_status::where('user_id', $request->user_id)
            ->update([
                'is_profile_complete' => "Yes",
            ]);

            $isExist = User::select("*")
            ->where("id", $request->user_id)
            ->where("user_type","vendor")
            ->exists();
            
            if($isExist)
            {
                $user = User::where('id', $request->user_id)
                ->update([
                    'is_Signup_Complete' => "1",
                ]);
            }

            

            $vendor_profile = new Vendor_profile();
            $vendor_profile->user_id = $request->user_id;
            $vendor_profile->business_name = $request->business_name;
            $vendor_profile->location = $request->location;
            $vendor_profile->profile_image_url= $fileNameToStore;
            $saved=$vendor_profile->save();
                
        }
        if($updated)
               {
                return response()->json(['statusCode'=>'200','message '=> "User id". ' ' . $request->user_id . ' ' . "is updated"], 200);
               }
        elseif($saved)
                {
                    return response()->json(['statusCode'=>'200','data'=>$vendor_profile], 200); 
                }
         else{
                    return response()->json(['statusCode'=>'500','data is not saved' => 'no'], 500);   
                }

    }
    public function fetchCustomerProfile(Request $request,$customerId)
    {
        $user_profile = User_profile::select("*")
        ->where("user_id", $customerId)
        ->get();
    

        return response()->json(['statusCode'=>'10','data' => $user_profile], 200);  
    }
}

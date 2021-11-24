<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_profile;
use App\Models\User_signup_status;
use Validator;
use File;

class CustomersProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['actionProfile']]);
       }

    public function actionProfile( Request $request)
    {
        $token = substr(str_shuffle("0123456789"), 0, 4);
        
        $fileNameToStore = '';
        $validation = Validator::make($request->all(), [
            'fullname' => '|required|',
            'locationCity' => '|required|',
            'locationCountry' => '|required|',
            'profile_image' => 'required|mimes:jpeg,jpg,png|max:5000'
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
        $extension = $request->file('profile_image')->getClientOriginalExtension();
        $filename = str_replace(" ","_", $request->fullname);
        $fileNameToStore = $filename . '_' . $request->user_id . '.' . $extension;
        $request->file('profile_image')->move(public_path('uploads/User_Documents'), $fileNameToStore);

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
                'fullname' => $request->fullname,
                'locationCity'=>$request->locationCity,
                'locationCountry'=>$request->locationCountry,
                'profile_image'=>$fileNameToStore
            ]);
            
        }
        else{
            $user = User_signup_status::where('user_id', $request->user_id)
            ->update([
                'isProfileComplete' => "Yes",
            ]);


            $user_profile = new User_profile();
            $user_profile->user_id = $request->user_id;
            $user_profile->fullname = $request->fullname;
            $user_profile->locationCity = $request->locationCity;
            $user_profile->locationCountry = $request->locationCountry;
            $user_profile->profile_image= $fileNameToStore;
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
}

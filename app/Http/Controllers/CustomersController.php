<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_interest;
use App\Models\User_signup_status;
use App\models\Verification;
use Illuminate\Support\Facades\Hash;
use Validator;
use GuzzleHttp\Client;
use Session;
use Illuminate\Support\Facades\DB;
use Carbon;
use DateTime;

class CustomersController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'actionRegister','actionVerifyOtp','actionCustomerInterest']]);
       }
    public function getRegisterPage()
    {
        return view('template.auth.signup');
    } 

    public function actionRegister(Request $request)
    {
            $validation = Validator::make($request->all(), [
                'email' => '|required|email',
                'password' => '|required|min:6',
                'pNumber' => '|required|',
                'countryCode' => '|required|',
                'date' => '|required|'
            ]);
            //$this->checkIfUserAlreadyExists($request->email);
        if(!$validation->passes())
        {
            
            return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
        }
       else{
            if($this->checkIfUserAlreadyExists($request->email))
            {
                // $user =DB::select(" SELECT users.id, date,users.email
                // FROM (users
                // INNER JOIN user_signup_statuses ON user_signup_statuses.user_id = users.id)
                // WHERE users.email='harshit@gmail.com'  AND user_signup_statuses.isOtpVerified = 'Yes'
                // AND user_signup_statuses.isProfileComplete	 = 'Yes' AND      user_signup_statuses.isInterestChoosen = 'Yes'
                // ");
                return response()->json(['statusCode'=>'400','User already Exists' => "yes"], 400); 
            }

            $user = new User();
            $user->email = $request->email;
            // $user->isOtpVerified = "No";
            // $user->isProfileComplete = "No";  
            // $user->isInterestChoosen = "No";
            $user->password = Hash::make($request->password);
            $user->user_type = "customer";
            $user->phone_number = $request->pNumber;
            $user->country_code = $request->countryCode;
            $user->date = $request->date;
            $request->session()->put('user', $user);
            $saved=$user->save();
            
            $user_signup_status =new User_signup_status();
            $user_signup_status->user_id=$user->id;
            $user_signup_status->isOtpVerified = "No";
            $user_signup_status->isProfileComplete = "No";  
            $user_signup_status->isInterestChoosen = "No";
            $user_signup_status->save();
            //create otp
            
            $otp=$this->createOtp($user);
           // $otp_get = $request->session()->get('verification_otp');
            if($otp)
            {
              $smsResponse= $this->sendOtpAsSms($request->pNumber,"OTP: $otp");
            }
       }
      
               if($saved)
               {
                return response()->json(['statusCode'=>'200','data'=>$user], 200);
               }
               else{
                return response()->json(['statusCode'=>'500','data is not saved' => 'no'], 500);
               }
      
    }
    public function actionVerifyOtp(Request $request)
    {
           // get user_id,otp in post parameter

           // vaildation check
           $validation = Validator::make($request->all(), [
            'user_id' => '|required|',
            'otp' => '|required|min:6',
            
        ]);
            if(!$validation->passes())
            {
                return response()->json(['statusCode'=>'400','Invalid request from the client side' => 'yes'], 400);
            }
            
           $isExist = Verification::select("created_at")
            ->where("user_id", $request->user_id)
            ->where("otp", $request->otp)
            ->exists();
        
            if($isExist)
            {    $data = Verification::select("created_at")
                ->where("user_id", $request->user_id)
                ->where("otp", $request->otp)
                ->get();
                
                $date2 = Carbon\Carbon::now();
                $date1    = new Carbon\Carbon($data[0]->created_at);
                $interval = $date1->diffInMinutes($date2).$date1->diff($date2)->format('');
                // $interval = $date1->diffInHours($date2) . ':' . $date1->diff($date2)->format('%I:%S');
                if($interval > 1)
                {
                    return response()->json(['statusCode'=>'400','message'=>'Otp is expired'], 400); 
                }
                else{
                    $user = User_signup_status::where('user_id', $request->user_id)
                    ->update([
                        'isOtpVerified' => "Yes",
                    ]);
                    return response()->json(['statusCode'=>'200','message'=>'Otp is Verified'], 200);
                }
               
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>'Otp is not Verified'], 500); 
            }



    }
    public function checkIfUserAlreadyExists($email)
    {   
        $data =DB::select(" SELECT users.id, date,users.email
        FROM (users
        INNER JOIN user_signup_statuses ON user_signup_statuses.user_id = users.id)
        WHERE users.email='harshit@gmail.com'  AND user_signup_statuses.isOtpVerified = 'Yes'
        AND user_signup_statuses.isProfileComplete	 = 'Yes' AND      user_signup_statuses.isInterestChoosen = 'Yes'
        ");
      

        // $isExist = User_signup_status::select("*")
        // ->where("user_id", $data[0]->id)
        // ->where("isOtpVerified", "Yes")
        // ->where("isProfileComplete", "Yes")
        // ->where("isInterestChoosen", "Yes")
        // ->exists();

        if (empty($data )) {return false;}
        else{return false;}
    }
    public function createOtp($user)
    {
        $otp = rand(111111, 999999);
        $verification = new Verification();
        $verification->otp = $otp;
        $verification->user_id = $user->id;
        //Session::put('verification_otp', $verification->otp);
        if (!$verification->save()) {
           return false;
        }
        return $otp;
    }
    public function sendOtpAsSms($pNumber,$otp)
    {
        // $data = array('apikey' =>'1zVb3P05JCBEo7sVATXRQAtAxYV', 'to' => $numbers, "sender" => $sender, "message" => $message);
 $data = [
            'api_key' =>'1zVb3P05JCBEo7sVATXRQAtAxYV',
            'api_secret' =>'QX41pNQS56vnVAmvppCsEIUIFMVzGcF76BKGFJNk',
            'text' => $otp,
            'to' =>'+919305364771',
            'message'=>'123432',
            'from' => 'Party App'
        ];
	// Send the POST request with cURL
        $ch = curl_init('https://api.movider.co/v1/sms');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     'Content-Type: application/x-www-form-urlencoded',
        //     "cache-control: no-cache"
        //   ));
        $response = curl_exec($ch);
        // $client = new \GuzzleHttp\Client();
        // $payload = [
        //     'api_key' =>'1zVb3P05JCBEo7sVATXRQAtAxYV',
        //     'api_secret' =>'QX41pNQS56vnVAmvppCsEIUIFMVzGcF76BKGFJNk',
        //     'text' => $otp,
        //     'to' =>'+919305364771',
        //     'from' => 'Party App'
        // ];
        // $response = $client->createRequest()
        // ->setMethod('POST')
        // ->setUrl('https://api.movider.co/v1/sms')
        // ->setHeaders([
        //     "Content-Type: application/x-www-form-urlencoded",
        //     "cache-control: no-cache"
        // ])
        // ->setData($payload)
        // ->send();

        if (1) {
            return $response; 
           
        }

         //return true;
    }

    public function actionCustomerInterest(Request $request)
    {
        $data=$request->categoryIds;
        $Music = $Sports = $Games = $Eating = $Dancing = $Tech='0';
        if (in_array("Music", $data)){ $Music = '1';}
        if (in_array("Sports", $data)){ $Sports = '1';}
        if (in_array("Games", $data)){ $Games = '1';}
        if (in_array("Eating", $data)){ $Eating = '1';}
        if (in_array("Dancing", $data)){ $Dancing = '1';}
        if (in_array("Tech", $data)){ $Tech = '1';}
        
        $isExist = User_interest::select("*")
            ->where("user_id", $request->user_id)
            ->exists();
            $updated='';
            $saved='';
        if($isExist)
        {
            $updated = User_interest::where('user_id', $request->user_id)
            ->update([
                'Music' =>$Music,
                'Sports' =>$Sports,
                'Games' =>$Games,
                'Eating' =>$Eating,
                'Dancing' =>$Dancing,
                'Tech' =>$Tech,
            ]);
        }
        else{

            $user_interest = new User_interest();
            $user_interest->user_id = $request->user_id;

            $user_interest->Music = $Music;
            $user_interest->Sports = $Sports;
            $user_interest->Games = $Games;
            $user_interest->Eating = $Eating;
            $user_interest->Dancing = $Dancing;
            $user_interest->Tech = $Tech;
            
            $saved=$user_interest->save();

            $user = User_signup_status::where('user_id', $request->user_id)
            ->update([
                'isInterestChoosen' => "Yes",
            ]);
            $user = User::where('id', $request->user_id)
            ->update([
                'isSignupComplete' => "1",
            ]);

        }
            if($saved)
            {
                return response()->json(['statusCode'=>'200','data'=>$user_interest], 200); 
            }
            elseif($updated){
                return response()->json(['statusCode'=>'200','message'=>"data is saved "], 200); 
            }
            else{
                return response()->json(['statusCode'=>'400','message'=>'data is not saved'], 400); 
            }
        
    }
}

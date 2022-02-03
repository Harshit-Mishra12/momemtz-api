<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_profile;
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
use Mail;

class CustomersController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'actionRegister',
        'actionVerifyOtp','actionCustomerInterest','actionForgetPassword',]]);
       
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
                'phone_number' => '|required|',
                'country_code' => '|required|',
                'dob' => '|required|',
                'user_type'=>'|required|'
            ]);
            //$this->checkIfUserAlreadyExists($request->email);
        if(!$validation->passes())
        {
            
            return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
        }
       else{
            $data=$this->checkIfUserAlreadyExists($request->email,$request->phone_number,$request->user_type);
            
                if(!empty($data))
                {
                    return response()->json(['statusCode'=>'4','message' => "data exists",'data'=>$data], 400); 
                }
                
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->user_type = $request->user_type;
            $user->phone_number = $request->phone_number;
            $user->country_code = $request->country_code;
            $user->dob = $request->dob;
            $request->session()->put('user', $user);
            $saved=$user->save();
            
            $user_signup_status =new User_signup_status();
            $user_signup_status->user_id=$user->id;
            $user_signup_status->is_otp_verified = "No";
            $user_signup_status->is_profile_complete = "No";  
            $user_signup_status->is_interest_choosen = "No";
            $user_signup_status->save();
            //create otp
            
            $otp=$this->createOtp($user);
           // $otp_get = $request->session()->get('verification_otp');
           $phone_number=$request->country_code.$request->phone_number;
            if($otp)
            { $smsResponse="1";  
              $smsResponse= $this->sendOtpAsSms($phone_number,"OTP: $otp");
             // return response()->json(['status_code'=>'12','data' => $smsResponse],400);
              if($smsResponse=="2")
              {
                return response()->json(['statusCode'=>'2','message' => 'otp is not sent'],400);   
              }
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
                        'is_otp_verified' => "Yes",
                    ]);
                    return response()->json(['statusCode'=>'200','message'=>'Otp is Verified'], 200);
                }
               
            }
            else{
                return response()->json(['statusCode'=>'500','message'=>'Otp is not Verified'], 500); 
            }
    }
    public function checkIfUserAlreadyExists($email,$phone_number,$user_type)
    {   
        // $data =DB::select(" SELECT users.id, date,users.email
        // FROM (users
        // INNER JOIN user_signup_statuses ON user_signup_statuses.user_id = users.id)
        // WHERE users.email='davidwarner@gmail.com'  AND user_signup_statuses.is_otp_verified = 'Yes'
        // AND user_signup_statuses.is_profile_complete = 'Yes' AND user_signup_statuses.is_interest_choosen = 'Yes'
        // ");
        
        $data =DB::select("Select * from users where is_Signup_Complete = '1'and user_type='$user_type' and  
        (email = '$email' or phone_number = '$phone_number')
        ");
        // $data = User::select("users.id","users.email")
        // ->join('user_signup_statuses', 'user_signup_statuses.user_id', 'users.id')
        //  ->where("users.email", $email)
        //  ->where("user_signup_statuses.is_otp_verified", 'Yes')
        //  ->where("user_signup_statuses.is_profile_complete", 'Yes')
        //  ->where("user_signup_statuses.is_interest_choosen", 'Yes')
        //  ->exists();
        //  $data = User::select("users.id","users.email")
        //  ->orWhere("users.email", $email)
        //  ->orWhere("users.phone_number", $phone_number)
        //  ->where("users.is_Signup_Complete", '1')
        //  ->exists();
        // $data = User::where('is_Signup_Complete','1')->where(function($query) {
		// 	$query->where('email',$email)
		// 				->orWhere('phone_number',$phone_number);
        //     })->get();
            return $data;
        // if ($data) {
        //     return true;}
        // else{return false;}
   
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
    public function sendOtpAsSms($phone_number,$otp)
    {
        // $data = array('apikey' =>'1zVb3P05JCBEo7sVATXRQAtAxYV', 'to' => $numbers, "sender" => $sender, "message" => $message);
        $data = [
                    'api_key' =>'1zVb3P05JCBEo7sVATXRQAtAxYV',
                    'api_secret' =>'QX41pNQS56vnVAmvppCsEIUIFMVzGcF76BKGFJNk',
                    'text' => $otp,
                    'to' =>$phone_number,
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
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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

        if ($httpcode=="200") {
            return $response;  
        }
        else{
            return "2";
        }

         //return true;
    }
    public function actionCustomerInterest(Request $request)
    {         
        $data=$request->category_Ids;
        $this->checkifexists($data,$request->user_id);
        $count=0;
        for($i=0;$i<count($data);$i++)
        {
            $user_interest = new User_interest();
            $user_interest->user_id = $request->user_id;
            $user_interest->is_active = "1";
            $user_interest->interest_category_id = $data[$i];
            $count++;
            $saved=$user_interest->save(); 
        }
        $fetch_categories = User_interest::select("interest_categories.category_id","interest_categories.name")
            ->join('interest_categories', 'interest_categories.category_id', 'user_interests.interest_category_id')
             ->where("user_id", $request->user_id)
             ->where("is_active", "1")
             ->get();

     $user = User_signup_status::where('user_id', $request->user_id)
            ->update([
                'is_interest_choosen' => "Yes",
            ]);
            $user = User::where('id', $request->user_id)
            ->update([
                'is_Signup_Complete' => "1",
            ]);
            
        if($count==count($data))
        {
            return response()->json(['statusCode'=>'200','data'=>$fetch_categories], 200);   
        }
        else{
            return response()->json(['statusCode'=>'400','message'=>'data is not saved'], 400); 
            }
                
    }
    public function checkifexists($data,$user_id)
    {
        $exists = User_interest::select("*")
         ->where("user_id", $user_id)
         ->exists();

         $updated = User_interest::where('user_id', $user_id)
           ->update([
                'is_active' =>"0",
             ]);
        if($updated)
        {
            return true;
        }
        else{
            return false;
            }
    }
    // public function actionCustomerInterest(Request $request)
    // {
    //     $data=$request->categoryIds;
    //     $Music = $Sports = $Games = $Eating = $Dancing = $Tech='0';
    //     if (in_array("Music", $data)){ $Music = '1';}
    //     if (in_array("Sports", $data)){ $Sports = '1';}
    //     if (in_array("Games", $data)){ $Games = '1';}
    //     if (in_array("Eating", $data)){ $Eating = '1';}
    //     if (in_array("Dancing", $data)){ $Dancing = '1';}
    //     if (in_array("Tech", $data)){ $Tech = '1';}
        
    //     $isExist = User_interest::select("*")
    //         ->where("user_id", $request->user_id)
    //         ->exists();
    //         $updated='';
    //         $saved='';
    //     if($isExist)
    //     {
    //         $updated = User_interest::where('user_id', $request->user_id)
    //         ->update([
    //             'Music' =>$Music,
    //             'Sports' =>$Sports,
    //             'Games' =>$Games,
    //             'Eating' =>$Eating,
    //             'Dancing' =>$Dancing,
    //             'Tech' =>$Tech,
    //         ]);
    //     }
    //     else{

    //         $user_interest = new User_interest();
    //         $user_interest->user_id = $request->user_id;

    //         $user_interest->Music = $Music;
    //         $user_interest->Sports = $Sports;
    //         $user_interest->Games = $Games;
    //         $user_interest->Eating = $Eating;
    //         $user_interest->Dancing = $Dancing;
    //         $user_interest->Tech = $Tech;
            
    //         $saved=$user_interest->save();

    //         $user = User_signup_status::where('user_id', $request->user_id)
    //         ->update([
    //             'is_interest_choosen' => "Yes",
    //         ]);
    //         $user = User::where('id', $request->user_id)
    //         ->update([
    //             'is_Signup_Complete' => "1",
    //         ]);

    //     }
    //         if($saved)
    //         {
    //             return response()->json(['statusCode'=>'200','data'=>$user_interest], 200); 
    //         }
    //         elseif($updated){
    //             return response()->json(['statusCode'=>'200','message'=>"data is saved "], 200); 
    //         }
    //         else{
    //             return response()->json(['statusCode'=>'400','message'=>'data is not saved'], 400); 
    //         }
    // }
    public function actionForgetPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => '|required|email',
            'phone_number' => '|required|',
            'phone_without_Code'=>'|required|',
        ]);
       
        if(!$validation->passes())
        {
            
            return response()->json(['status_code'=>'4','validation Error/Inavlid request from the client side' => 'yes'], 400);
        }

        $gen_Password = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 8);
        $auth_name='';
        $data = User::select("vendor_profiles.business_name")
            ->join('vendor_profiles', 'vendor_profiles.user_id', 'users.id')
            ->where("is_Signup_Complete","1")
            ->where("user_type",$request->user_type)
            ->where("email",$request->email)
            ->Where("phone_number",$request->phone_without_Code)
            ->get();
        // $data =DB::select("Select * from (users INNER JOIN vendor_profiles ON vendor_profiles.user_id = users.id) where is_Signup_Complete = '1' and
        // (email = '$email' or phone_number = $phone_without_Code)
        // ");
        
         // return response()->json(['data' => $data]);
        if (!empty($data[0])) {
           
            $auth_name=$data[0]['full_name'];
            //send new password to phone number
            $smsResponse= $this->sendOtpAsSms($request->phone_number,"New Password: $gen_Password");
          
              if($smsResponse=="2")
              {
                return response()->json(['status_code'=>'12','message' => 'password is not sent'],400);   
              }
            $email=$request->email;
            $user = User::where('email', $request->email)
                ->where('phone_number', $request->phone_number)
                ->where('is_Signup_Complete', '1')
                ->update([
                    'password' => Hash::make($gen_Password),
                ]);
       
            //$data = ['reset_password' => $random_no];

           // Mail::to($request->emailResetPwd)->send(new WelcomeMail($data));
          $data=['name'=>$auth_name,'password'=>$gen_Password,'data'=>'Please use this credentials as your new password'];
            
           $user_data['to']=$request->email;
           Mail::send('template.mail.sendPasswordTomail',$data,function($messages) use ($user_data,$auth_name){
            
              $messages->from('harshitmishra7921@gmail.com','Momentz');
              $messages->to($user_data['to']);
              $messages->subject('Support');
          });
          // return response()->json(['email_success' => 'yes']);
            if (empty(Mail::failures())) {
                return response()->json(['status_code'=>'1','email_success' => 'yes']);
            } 
            else {
                return response()->json(['status_code'=>'2','email_success' => 'no']);
            }
        } 
        else {
            // return response()->json(['error_message' => 'yes']);
            return response()->json(['status_code'=>'5','message'=>'user does not exist'], 400); 
        }
    }

    public function actionResetPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'auth_user_id' => '|required|',
            'old_password' => '|required|min:6',
            'new_password' => '|required|min:6',
        ]);
        if(!$validation->passes())
        {
            
            return response()->json(['statusCode'=>'400','validation Error/Inavlid request from the client side' => 'yes'], 400);
        }
        $data = User::find($request->auth_user_id);
     
        if(Hash::check($request->old_password, $data->password))
        {
            $user = User::where('id', $request->auth_user_id)
                    ->update([
                        'password' => Hash::make($request->new_password),
                    ]); 
            return response()->json(['statusCode'=>'200','data'=>$data], 200);
        }
        else
        {  
            return response()->json(['statusCode'=>'400','message'=>"old password do not match"], 400);
        }
        
    }
}

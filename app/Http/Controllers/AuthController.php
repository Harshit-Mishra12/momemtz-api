<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;


class AuthController extends Controller
{
  public function __construct() {
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
   }

   public function login(Request $request){

   
    $validator = Validator::make($request->all(), [
          // 'identifier' => 'required|email|numeric', 
          'password' => 'required|string|min:6',
      ]);
        $is_Signup_Complete='1';
      if($request->phone_number)
      {
        $credentials = $request->only('phone_number','password','user_type','is_Signup_Complete');
      
      }
       if($request->email){
        $credentials = $request->only('email','password','user_type','is_Signup_Complete');
        
      }
     
      if ($validator->fails()) {
          return response()->json(['error'=>'Invalid request','statusCode'=>'1',
          'message' => 'Invalid request made from the client side'
          ]
          , 400); 
      }
      if(auth()->attempt($credentials))
      {
        if (! $token = auth()->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized','statusCode'=>'2', "messages"=> "You are not authenticated to perform this action"], 401);
         }
      }
      else{
        return response()->json(['error' => 'Unauthorized','statusCode'=>'2', "messages"=> "You are not authenticated to perform this action"], 401);
      }
     return $this->createNewToken($token);
  }
  

    
    protected function createNewToken($token){

      $data = User::select("id  AS customerId","email","phone_number","dob")
      ->where("id",auth()->user()->id)
      ->get();
     
      // $myObj =new \stdClass();
      // $myObj->token =$token;
   

      // $myJSON = json_encode($myObj);

      return response()->json([
          'access_token' => $token,
          // 'token_type' => 'bearer',
          // 'expires_in' => auth()->factory()->getTTL() * 60,
          'profile' =>  $data[0]
      ]);
  }

}

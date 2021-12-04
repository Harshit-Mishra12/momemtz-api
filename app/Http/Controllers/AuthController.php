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
          'email' => 'required|email', 
          'phone_number' =>'required|numeric' ,
          'password' => 'required|string|min:6',
      ]);
    
      $credentials = $request->only('email', 'phone_number','password');
      if ($validator->fails()) {
          return response()->json(['error'=>'Invalid request',
          'message' => 'Invalid request made from the client side'
          ]
          , 400); 
      }
     
      if( auth()->attempt($credentials) && auth()->user()->isSignupComplete == '1')
      {
        if (! $token = auth()->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized', "messages"=> "You are not authenticated to perform this action"], 401);
         }
      }
      else{
        return response()->json(['error' => 'Unauthorized', "messages"=> "You are not authenticated to perform this action"], 401);
      }
     return $this->createNewToken($token);
  }
  

    
    protected function createNewToken($token){

      $data = User::select("id  AS customerId","email","phone_number","date")
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

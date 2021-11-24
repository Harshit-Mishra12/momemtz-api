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
          'phone_number' => 'required', 
          'password' => 'required|string|min:6',
      ]);
    
      $credentials = $request->only('email', 'phone_number','password');
      if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
      }
     
      if( auth()->attempt($credentials) && auth()->user()->isSignupComplete == '1')
      {
        if (! $token = auth()->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized'], 401);
         }
      }
      else{
        return response()->json(['error' => 'Unauthorized'], 401);
      }
     return $this->createNewToken($token);
  }
  public function me(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function getLoginPage()
    {

        return view('template.auth.login');
    }
    protected function createNewToken($token){
      return response()->json([
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => auth()->factory()->getTTL() * 60,
          'user' => auth()->user()
      ]);
  }

}

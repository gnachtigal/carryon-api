<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public $successStatus = 200;


   public function authenticate(Request $request)
   {
       // grab credentials from the request
       $credentials = $request->only('email', 'password');

       try {
           // attempt to verify the credentials and create a token for the user
           if (! $token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'invalid_credentials'], 401);
           }
       } catch (JWTException $e) {
           // something went wrong whilst attempting to encode the token
           return response()->json(['error' => 'could_not_create_token'], 500);
       }

       // all good so return the token
       return response()->json(compact('token'));
   }

    public function getUser($id)
    {
        try {
            $user = User::find($id);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }



    	return response()->json(compact('user', 'success'));
    }

}

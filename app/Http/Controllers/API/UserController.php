<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(){
       if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
           $user = Auth::user();
           return response()->json(['name' => $user->name, 'id' => $user->id, 'email' => $user->email, 'msg' => 'Você está conectado!'], $this->successStatus);
       }
       else{
           return response()->json(['msg'=>'Usuário ou senha inválidos!']);
       }
   }

   public function register(UserRequest $request)
   {

       if ($validator->fails()) {
           return response()->json(['error'=>$validator->errors()], 401);
       }

       $input = $request->all();
       $input['password'] = bcrypt($input['password']);
       $user = User::create($input);
       $success['name'] =  $user->name;

       return response()->json(['success'=>$success], $this->successStatus);
   }

}

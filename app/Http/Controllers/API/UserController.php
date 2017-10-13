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

    public function login(Request $request){
       if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
           $user = Auth::user();
           return response()->json(['success' => true, 'name' => $user->name, 'id' => $user->id, 'email' => $user->email, 'msg' => 'Você está conectado!'], $this->successStatus);
       }
       else{
           return response()->json(['msg'=>'Usuário ou senha inválidos!']);
       }
   }

   public function register(UserRequest $request)
   {
        if($request){
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            return response()->json(['success'=>true, 'name' => $user->name, 'id' => $user->id, 'email' => $user->email, 'msg' => 'Você está conectado!'], $this->successStatus);
        }else{
            if ($validator->fails()) {
              return response()->json(['success' => false, 'error'=>$validator->errors()], 401);
            }
        }

   }

}

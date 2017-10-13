<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index(){
        $user = Auth::user();

        // $chats = $user->chats()-with('members');

        return response()->json(['user' => $user]);
    }

    public function show($id){
        $chat = Chat::find($id);

        $messages = $chat->messages();

        return ['messages' => $messages, 'chat' => $chat];
    }

    public function delete(){

    }

    public function sendMessage(Request $request)
    {
        $sender = Auth::user();
        $receiver = $request->input('receiver_id');
        $body = $request->input('body');

        $message = $user->sentMessages()->create([
            'body' => $body,
            'receiver_id' => $receiver
        ]);

        broadcast(new MessageSent($sender, $receiver, $body))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}

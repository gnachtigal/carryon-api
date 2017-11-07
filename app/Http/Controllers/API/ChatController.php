<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Chat;
use App\UserChat;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Events\MessageSent;
use Cache;

class ChatController extends Controller
{
    public function index($id){
        $user = User::find($id);

        if($user->voluntary){
            $userChats = UserChat::where('voluntary_id', $id)->get();
        }else{
            $userChats = UserChat::where('user_id', $id)->get();
        }

        $chats = Chat::whereIn('id', $userChats->pluck('chat_id'))->get();

        return response()->json(compact('chats'));
    }

    public function show($id){
        $chat = Chat::find($id);

        $messages = $chat->messages;

        return response()->json(compact('messages'));
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

    public function setVoluntary($id){
        try {
            $voluntaries = [];
            $voluntary = User::find($id);
            array_push($voluntaries, $voluntary);

            // Cache::forever('voluntaries_active', );

        } catch (Exception $e) {
            return response()->json(compact('e'));
        }


    }

    public function searchVoluntary($id){
        $voluntaries = Cache::get($id);

        $voluntary = $voluntaries->first();

        return response()->json($this->startConversation($voluntary, $id));
    }

    private function startConversation($voluntary_id, $user_id){
        try {
            $voluntary = User::find($voluntary_id);
            $user = User::find($user_id);

            $chat = Chat::create([
                'title' => $voluntary->name . ' ajuda ' . $user->name
            ]);

            $user_chat = UserChats::create([
                'user_id' => $user->id,
                'voluntary_id' => $voluntary_id,
                'chat_id' => $chat->id
            ]);

            Cache::pull('voluntaries_active', $voluntary->id);
        } catch (Exception $e) {
            return response()->json(compact('e'));
        }



        return compact('chat', 'user_chat');
    }
}

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
use App\Message;
use LRedis;
use Pusher;

class ChatController extends Controller
{
    public function index($id){
        $user = User::find($id);
        $isVoluntary = $user->voluntary;

        if($isVoluntary){
            $userChats = UserChat::where('voluntary_id', $id)->get();
        }else{
            $userChats = UserChat::where('user_id', $id)->get();
        }

        $chats = Chat::whereIn('id', $userChats->pluck('chat_id'))->with('messages')->get();

        foreach ($chats as $chat) {
          if ($isVoluntary) {
            $uc = UserChat::where(['voluntary_id' => $user->id, 'chat_id' => $chat->id])->get()[0];
            $chat->contact = User::find($uc->user_id);
          }else {
            $uc = UserChat::where(['user_id' => $user->id, 'chat_id' => $chat->id])->get()[0];
            $chat->contact = User::find($uc->voluntary_id);
          }

        }

        return response()->json(compact('chats'));
    }

    public function show($id){
        $chat = Chat::find($id);
        $userChat = UserChat::where('chat_id', $chat->id)->get()[0];

        $user = User::find($userChat->user_id);
        $voluntary = User::find($userChat->voluntary_id);

        $messages = $chat->messages;

        return response()->json(compact('messages', 'user', 'voluntary'));
    }

    public function delete(){

    }

    public function sendMessage(Request $request)
    {
        $sender_id = $request->input('sender_id');
        $chat_id = $request->input('chat_id');
        $receiver_id = $request->input('receiver_id');
        $body = $request->input('body');
        $user = User::find($sender_id);

        $message = Message::create([
            'body' => $body,
            'receiver_id' => $receiver_id,
            'sender_id' => $sender_id,
            'chat_id' => $chat_id,
        ]);
        $sender = User::find($sender_id);
        $receiver = User::find($receiver_id);

        $options = array(
           'encrypted' => true
         );

         $pusher = new Pusher\Pusher(
           'f9ccff647813635fc3dc',
           'ad4cd74b4265d86a8267',
           '407923',
           $options
         );

        $data['message'] = $message;
        $data['sender'] = $sender;
        $data['receiver'] = $receiver;

        $pusher->trigger('chat.' . $chat_id, 'newMessage', $data);

        // broadcast(new MessageSent($sender, $receiver, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }

    public function setVoluntary(Request $request){
        $default = array();
        try {
            $voluntaries = Cache::get('voluntaries_active', $default);
            $voluntary = User::find($request['id']);

            if(!in_array($voluntary, $voluntaries)){
                array_push($voluntaries, $voluntary);
                Cache::forever('voluntaries_active', $voluntaries);

                $voluntaries = Cache::get('voluntaries_active', $default);
                $msg = "Você foi colocado como disponível! :)";
                $success = true;
                return response()->json(compact('success', 'voluntaries', 'msg'));
            }else{
                $voluntaries = Cache::get('voluntaries_active', $default);
                $msg = "Você já está disponível!";
                $success = true;
                return response()->json(compact('success', 'voluntaries','msg'));
            }


        } catch (Exception $e) {
            return response()->json(compact('e'));
        }


    }

    public function searchVoluntary($id){
        try {
            $voluntaries = Cache::get('voluntaries_active');
            $voluntary = array_shift($voluntaries);

            if (isset($voluntary)) {
                Cache::forever('voluntaries_active', $voluntaries);

                $result = $this->startConversation($voluntary->id, $id);
                return response()->json($result);
            }
            else{
                $success = false;
                return response()->json(compact('e', 'success'));
            }
        } catch (Exception $e) {
            $success = false;
            return response()->json(compact('e', 'success'));
        }

    }

    private function startConversation($voluntary_id, $user_id){
        try {
            $voluntary = User::find($voluntary_id);
            $user = User::find($user_id);

            $chat = Chat::create([
                'title' => $voluntary->name . ' ajuda ' . $user->name
            ]);

            $user_chat = UserChat::create([
                'user_id' => $user->id,
                'voluntary_id' => $voluntary_id,
                'chat_id' => $chat->id
            ]);

            $success = true;

        } catch (Exception $e) {
            $success = false;
            return response()->json(compact('e', 'success'));
        }



        return compact('chat', 'user_chat', 'voluntary', 'success');
    }
}

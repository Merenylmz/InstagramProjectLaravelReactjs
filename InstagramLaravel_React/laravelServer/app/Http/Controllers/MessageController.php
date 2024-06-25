<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function newChat(Request $request){
        $user1 = User::find($request->query("user1id"));
        $user2 = User::find($request->query("user2id"));

        $newChat = new Chat();
        $newChat->user1Id = $user1->id;
        $newChat->user2Id = $user2->id;
        $newChat->save();

        $user1Chats = json_decode($user1->chats);
        array_push($user1Chats, ["chatId"=>$newChat->id, "toUser"=>["id"=>$user2->id, "name"=>$user2->name, "profilePhoto"=>$user2->profilePhoto]]);
        $user1->chats = json_encode($user1Chats);
        

        $user2Chats = json_decode($user2->chats);
        array_push($user2Chats, ["chatId"=>$newChat->id, "toUser"=>["id"=>$user1->id, "name"=>$user1->name, "profilePhoto"=>$user1->profilePhoto]]);
        $user2->chats = json_encode($user2Chats);

        $user1->save();
        $user2->save();

        return response()->json($newChat);
    }

    public function send(Request $req){
        $chat = Chat::find($req->query("chatId"));
        if (!$chat) {return response()->json("");}

        $newMessage = new Message();
        $newMessage->chatId = $req->query("chatid");
        $newMessage->senderId = $req->query("senderid");
        $newMessage->receiverId = $req->query("receiverid");
        $newMessage->body = $req->input("body");
        $newMessage->save();


        $chatMessagesArray = json_decode($chat->messages);
        array_push($chatMessagesArray, ["senderId"=>$req->query("senderid"), "message"=>$req->input("message")]);
        $chat->messages = json_encode($chatMessagesArray);
        $chat->save();

        return response()->json(["dbMessage"=>$newMessage, "message"=>$req->input("body")]);
    }

    public function getMessages(Request $req){
        $chat = Chat::find($req->query("chatid"));
        if(!$chat){return response()->json("Chat is not found");}

        return response()->json($chat);
    }

    public function getChats(Request $req){
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("User is not found");}

        return response()->json($user->chats);
    }
}

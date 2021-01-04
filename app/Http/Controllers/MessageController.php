<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message ; 
use App\User ; 
use App\Events\messageSent;


use Auth;
class MessageController extends Controller
{
    public function index() { 
         
        $chats =Message::with('user')->get();

        
        return response()->json([
            'chatMessages' => $chats 
        ]);
        
    }

    public function sendMessage(Request $request) { 

        $message = new Message ; 
        $message->user_id = Auth::User()->id;
        $message->message = $request->message;
        $message->save();

        broadcast(new messageSent($message , Auth::User()))->toOthers();

        // event(new messageSent($message->load('user'))); shit is to slow 

        return response()->json([
            'msg' => 'success', 
        ]);
    }
}

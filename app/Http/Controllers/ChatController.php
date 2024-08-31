<?php

namespace App\Http\Controllers;

use App\Events\SentMessage;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    // public function index()
    // {
    //     $messages = User::select([
    //         'users.id',
    //         'users.name',
    //         'chat_messages.message',
    //     ])->join('chat_messages', 'users.id', '=', 'chat_messages.sender_id')->get();
    //     return Inertia::render('Messages/Index', [
    //         'messages' => $messages,
    //     ]);
    // }
    public function index()
    {
       
        return Inertia::render('Messages/Index', );
    }
    public function sendMessage(Request $request)
    {
    //    ChatMessage::create([
    //         'sender_id' => auth()->id(),
    //         'message' => $request->message,
    //    ]);
        event(new SentMessage($request->message));
       return response()->json($request->message);
    }
}

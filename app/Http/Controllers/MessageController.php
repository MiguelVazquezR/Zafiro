<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->get();
        return inertia('Message/Index', compact('messages'));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|min:10|max:10',
            'message' => 'required',
            'service' => 'nullable|string',
        ]); 

        Message::create($request->all());

        return back();

    }

    public function MarkAsdispatched(Request $request)
    {
        foreach ($request->messages as $message) {
            $message = Message::find($message['id']);
            $message->status = Message::DISPATCHED;
            $message->save();
        }

        return response()->json(['message' => 'mensaje(s) marcado(s) como despachado(s)']);
    }

    public function massiveDelete(Request $request)
    {
        foreach ($request->messages as $message) {
            $message = Message::find($message['id']);
            $message->delete();
        }

        return response()->json(['message' => 'mensaje(s) eliminado(s)']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    /**
     * Display the chat interface.
     */
    public function index()
    {
        $projects = \App\Models\Client::select('id', 'first_name', 'last_name', 'file_number')->get();
        $users = \App\Models\User::all();
        return view('chat.index', compact('projects', 'users'));
    }

    /**
     * Fetch the latest messages (API).
     */
    public function fetch(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $projectId = $request->input('project_id');

        $query = ChatMessage::with('user')
            ->where('id', '>', $lastId);

        if ($projectId) {
            $query->where('client_id', $projectId);
        }
        else {
            $query->whereNull('client_id');
        }

        $messages = $query->latest('created_at') // Get newest first for limiting
            ->limit(50) // Limit to avoid huge payloads
            ->get()
            ->reverse() // Reverse to show oldest first in chat
            ->values();

        return response()->json($messages);
    }

    /**
     * store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'project_id' => 'nullable|exists:clients,id'
        ]);

        $message = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'client_id' => $request->project_id
        ]);

        // Eager load user for instant response matching the fetch structure
        $message->load('user');

        return response()->json($message);
    }
}
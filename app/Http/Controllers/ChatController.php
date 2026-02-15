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
        $tasks = \App\Models\Task::with('client')->where('status', '!=', 'Completed')->get();
        return view('chat.index', compact('projects', 'users', 'tasks'));
    }

    /**
     * Fetch the latest messages (API).
     */
    public function fetch(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $projectId = $request->input('project_id');
        $parentId = $request->input('parent_id');

        $query = ChatMessage::with(['user', 'linkedTask', 'replies'])
            ->withCount('replies');

        if ($parentId) {
            $query->where('parent_id', $parentId);
        }
        else {
            $query->whereNull('parent_id');
            if ($projectId) {
                $query->where('client_id', $projectId);
            }
            else {
                $query->whereNull('client_id');
            }
        }

        $messages = $query->latest('created_at')
            ->limit(100)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|required_without:attachment|string|max:2000',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
            'project_id' => 'nullable|exists:clients,id',
            'parent_id' => 'nullable|exists:chat_messages,id'
        ]);

        $attachmentPath = null;
        $metadata = [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat_attachments', 'public');
            $metadata = [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ];
        }

        $message = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'client_id' => $request->project_id,
            'parent_id' => $request->parent_id,
            'attachment' => $attachmentPath,
            'metadata' => $metadata
        ]);

        $message->load(['user', 'linkedTask']);

        return response()->json($message);
    }

    public function togglePin(ChatMessage $message)
    {
        $message->update(['is_pinned' => !$message->is_pinned]);
        return response()->json(['status' => 'success', 'is_pinned' => $message->is_pinned]);
    }

    public function toggleDecision(ChatMessage $message)
    {
        $message->update(['is_decision' => !$message->is_decision]);
        return response()->json(['status' => 'success', 'is_decision' => $message->is_decision]);
    }

    public function linkTask(Request $request, ChatMessage $message)
    {
        $request->validate(['task_id' => 'required|exists:tasks,id']);
        $message->update(['linked_task_id' => $request->task_id]);
        return response()->json(['status' => 'success']);
    }

    public function addReaction(Request $request, ChatMessage $message)
    {
        $request->validate(['emoji' => 'required|string']);
        $reactions = $message->reactions ?? [];
        $user = Auth::user()->name;

        if (!isset($reactions[$request->emoji])) {
            $reactions[$request->emoji] = [];
        }

        if (in_array($user, $reactions[$request->emoji])) {
            $reactions[$request->emoji] = array_diff($reactions[$request->emoji], [$user]);
            if (empty($reactions[$request->emoji]))
                unset($reactions[$request->emoji]);
        }
        else {
            $reactions[$request->emoji][] = $user;
        }

        $message->update(['reactions' => $reactions]);
        return response()->json(['status' => 'success', 'reactions' => $reactions]);
    }
}
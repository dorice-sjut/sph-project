<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get conversations
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        $unreadCount = $user->receivedMessages()->unread()->count();

        return view('messages.index', compact('conversations', 'unreadCount'));
    }

    public function conversation(User $user)
    {
        $authUser = auth()->user();

        // Get messages between these two users
        $messages = Message::conversation($authUser->id, $user->id)
            ->with(['sender', 'receiver'])
            ->oldest()
            ->get();

        // Mark messages as read
        $messages->where('receiver_id', $authUser->id)->each->markAsRead();

        // Get recent contacts
        $contacts = User::where('id', '!=', $authUser->id)
            ->whereIn('role', ['farmer', 'buyer', 'supplier', 'expert', 'logistics'])
            ->take(20)
            ->get();

        return view('messages.conversation', compact('messages', 'user', 'contacts'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'attachments' => 'nullable|array',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $validated['content'],
            'attachments' => $request->file('attachments') ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return back()->with('success', 'Message sent.');
    }
}

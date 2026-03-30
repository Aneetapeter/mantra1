<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Message;
use App\Models\Block;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display the Chat Page.
     */
    public function index()
    {
        // View needs to be created
        return view('chat');
    }

    /**
     * Search available students to add as friends.
     * Excludes self and existing friends or pending requests.
     */
    public function searchStudents(Request $request)
    {
        $query = $request->input('q');
        $userId = Auth::id();

        // Find existing friends
        $friendIds = Friend::where('user_id', $userId)->pluck('friend_id')->toArray();
        $friendIds[] = $userId; // Exclude self

        // Find pending sent requests
        $pendingSentIds = FriendRequest::where('sender_id', $userId)
            ->where('status', 'pending')
            ->pluck('receiver_id')->toArray();

        // Find pending received requests
        $pendingReceivedIds = FriendRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->pluck('sender_id')->toArray();

        // Find blocks (both ways)
        $blockedIds = Block::where('user_id', $userId)->pluck('blocked_id')->toArray();
        $blockedByIds = Block::where('blocked_id', $userId)->pluck('user_id')->toArray();
        $allBlockedIds = array_unique(array_merge($blockedIds, $blockedByIds));

        $excludedIds = array_unique(array_merge($friendIds, $allBlockedIds));

        $studentsQuery = User::whereNotIn('id', $excludedIds);

        if ($query) {
            $studentsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            });
        }

        $students = $studentsQuery->select('id', 'name', 'email')
            ->limit(20)
            ->get();

        $students->transform(function ($student) use ($pendingSentIds, $pendingReceivedIds) {
            $status = 'none';
            if (in_array($student->id, $pendingSentIds)) {
                $status = 'sent';
            } elseif (in_array($student->id, $pendingReceivedIds)) {
                $status = 'received';
            }

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'status' => $status
            ];
        });

        return response()->json($students);
    }

    /**
     * Send a Friend Request
     */
    public function sendFriendRequest(Request $request)
    {
        $request->validate(['receiver_id' => 'required|exists:users,id']);

        $senderId = Auth::id();
        $receiverId = $request->receiver_id;

        if ($senderId == $receiverId) {
            return response()->json(['success' => false, 'message' => 'Cannot send request to yourself.']);
        }

        // Check if request already exists
        $exists = FriendRequest::where(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Request already exists.']);
        }

        FriendRequest::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending'
        ]);

        return response()->json(['success' => true, 'message' => 'Friend request sent!']);
    }

    /**
     * Get list of friend requests received
     */
    public function getFriendRequests()
    {
        $requests = FriendRequest::with('sender:id,name,email')
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'sender_name' => $req->sender->name,
                    'sender_email' => $req->sender->email,
                    'time_ago' => $this->formatCustomTime($req->created_at),
                ];
            });

        return response()->json($requests);
    }

    /**
     * Accept or Reject a friend request
     */
    public function respondRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:friend_requests,id',
            'action' => 'required|in:accept,reject'
        ]);

        $friendRequest = FriendRequest::find($request->request_id);

        if ($friendRequest->receiver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $friendRequest->status = $request->action === 'accept' ? 'accepted' : 'rejected';
        $friendRequest->save();

        if ($request->action === 'accept') {
            // Add to friends table for BOTH
            Friend::firstOrCreate(['user_id' => $friendRequest->sender_id, 'friend_id' => $friendRequest->receiver_id]);
            Friend::firstOrCreate(['user_id' => $friendRequest->receiver_id, 'friend_id' => $friendRequest->sender_id]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Fetch friends list
     */
    public function getFriends()
    {
        // Get friends
        $friends = Friend::with('friend:id,name,email')->where('user_id', Auth::id())->get()->map(function ($f) {

            // Count unread messages from this friend
            $unreadCount = Message::where('sender_id', $f->friend_id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            // Get last message info
            $lastMsg = Message::where(function ($q) use ($f) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $f->friend_id);
            })->orWhere(function ($q) use ($f) {
                $q->where('sender_id', $f->friend_id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'desc')->first();

            return [
                'id' => $f->friend->id,
                'name' => $f->friend->name,
                'email' => $f->friend->email,
                'unread' => $unreadCount,
                'last_msg' => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->message, 25) : null,
                'last_time' => $lastMsg ? $this->formatCustomTime($lastMsg->created_at) : null,
            ];
        });

        // Sort by unread or last message logic could go here
        return response()->json($friends);
    }


    /**
     * Fetch messages for a specific friend
     */
    public function getMessages($friendId, Request $request)
    {
        $userId = Auth::id();

        // Security check: Must not be blocked
        $isBlocked = Block::where(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $userId)->where('blocked_id', $friendId);
        })->orWhere(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $friendId)->where('blocked_id', $userId);
        })->exists();

        if ($isBlocked) {
            return response()->json(['success' => false, 'message' => 'You cannot reply to this conversation.'], 403);
        }

        // Security check: Must be friends and request accepted
        $isFriend = Friend::where('user_id', $userId)->where('friend_id', $friendId)->exists();
        if (!$isFriend) {
            return response()->json(['success' => false, 'message' => 'You can only chat after the friend request is accepted.'], 403);
        }

        // Mark incoming messages as read
        Message::where('sender_id', $friendId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Support fetching only new messages via polling (last_id)
        $lastId = $request->input('last_id', 0);

        $messages = Message::where(function ($query) use ($userId, $friendId) {
            $query->where(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $userId)->where('receiver_id', $friendId);
            })->orWhere(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $friendId)->where('receiver_id', $userId);
            });
        })
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($userId) {
                return [
                    'id' => $msg->id,
                    'is_sender' => $msg->sender_id == $userId,
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('H:i'),
                    'full_time' => $this->formatCustomTime($msg->created_at),
                ];
            });

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $userId = Auth::id();
        $receiverId = $request->receiver_id;

        // Security check: Must not be blocked
        $isBlocked = Block::where(function ($q) use ($userId, $receiverId) {
            $q->where('user_id', $userId)->where('blocked_id', $receiverId);
        })->orWhere(function ($q) use ($userId, $receiverId) {
            $q->where('user_id', $receiverId)->where('blocked_id', $userId);
        })->exists();

        if ($isBlocked) {
            return response()->json(['success' => false, 'message' => 'You cannot reply to this conversation.'], 403);
        }

        // Security check: Must be friends
        $isFriend = Friend::where('user_id', $userId)->where('friend_id', $receiverId)->exists();
        if (!$isFriend) {
            return response()->json(['success' => false, 'message' => 'You can only chat after the friend request is accepted.'], 403);
        }

        $msg = Message::create([
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $msg->id,
                'is_sender' => true,
                'message' => $msg->message,
                'time' => $msg->created_at->format('H:i')
            ]
        ]);
    }

    /**
     * Custom Carbon formatting per specifications
     */
    private function formatCustomTime($date)
    {
        $time = \Carbon\Carbon::parse($date);

        if ($time->diffInMinutes() < 60) {
            $mins = (int) $time->diffInMinutes();
            return $mins === 0 ? 'Just now' : $mins . 'm';
        } elseif ($time->diffInHours() < 24) {
            return (int) $time->diffInHours() . 'h';
        } elseif ($time->diffInDays() == 1) {
            return 'Yesterday';
        } else {
            return $time->format('d/m/Y');
        }
    }

    /**
     * Block a user
     */
    public function blockUser(Request $request)
    {
        $request->validate(['blocked_id' => 'required|exists:users,id']);

        $userId = Auth::id();
        $blockedId = $request->blocked_id;

        if ($userId == $blockedId) {
            return response()->json(['success' => false, 'message' => 'Cannot block yourself.']);
        }

        // Remove from friends and requests
        Friend::where(function ($q) use ($userId, $blockedId) {
            $q->where('user_id', $userId)->where('friend_id', $blockedId);
        })->orWhere(function ($q) use ($userId, $blockedId) {
            $q->where('user_id', $blockedId)->where('friend_id', $userId);
        })->delete();

        FriendRequest::where(function ($q) use ($userId, $blockedId) {
            $q->where('sender_id', $userId)->where('receiver_id', $blockedId);
        })->orWhere(function ($q) use ($userId, $blockedId) {
            $q->where('sender_id', $blockedId)->where('receiver_id', $userId);
        })->delete();

        Block::firstOrCreate(['user_id' => $userId, 'blocked_id' => $blockedId]);

        return response()->json(['success' => true]);
    }

    /**
     * Unblock a user
     */
    public function unblockUser(Request $request)
    {
        $request->validate(['blocked_id' => 'required|exists:users,id']);

        Block::where('user_id', Auth::id())->where('blocked_id', $request->blocked_id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get blocked users
     */
    public function getBlocked()
    {
        $blocked = Block::with('blocked:id,name,email')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->blocked->id,
                    'name' => $b->blocked->name,
                    'email' => $b->blocked->email
                ];
            });

        return response()->json($blocked);
    }

    /**
     * Fetch a user's profile data for the chat modal.
     */
    public function getUserProfile($id)
    {
        $userId = Auth::id();

        // 1. Security Check: Are they friends?
        $isFriend = Friend::where('user_id', $userId)->where('friend_id', $id)->exists();

        if (!$isFriend && $userId != $id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. You can only view profiles of your friends.'], 403);
        }

        $user = User::findOrFail($id);

        // 2. Format Study Time
        $totalSeconds = $user->total_study_seconds ?? 0;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $formattedStudyTime = ($hours > 0 ? $hours . "h " : "") . $minutes . "m";

        return response()->json([
            'success' => true,
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'current_streak' => $user->current_streak ?? 0,
                'xp' => $user->xp ?? 0,
                'level' => $user->level,
                'title' => $user->title,
                'study_time' => $formattedStudyTime,
                'badges' => $user->badges ?? [],
                'stickers' => $user->stickers ?? []
            ]
        ]);
    }

    /**
     * Save a shared note to the authenticated user's library.
     */
    public function saveSharedNote(Request $request)
    {
        $request->validate(['note_id' => 'required|exists:notes,id']);

        $userId = Auth::id();
        $targetNote = Note::findOrFail($request->note_id);

        // Security check: Either the note belongs to the user or it's from a friend
        // For simplicity in this context, we check if there's a friendship
        $isFriend = Friend::where('user_id', $userId)->where('friend_id', $targetNote->user_id)->exists();

        if (!$isFriend && $targetNote->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. You can only save notes shared by your friends.'], 403);
        }

        // Create a copy of the note for the current user
        $newNote = Note::create([
            'user_id' => $userId,
            'title' => $targetNote->title . ' (Shared)',
            'content' => $targetNote->content,
            'folder_id' => null, // Save to main library by default
            'is_pinned' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note saved to your library!',
            'note' => $newNote
        ]);
    }
}

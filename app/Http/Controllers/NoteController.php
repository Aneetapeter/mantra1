<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\StreakService;
use App\Services\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        return Note::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->input('content'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = $request->user();
        $streak = StreakService::record($user);
        $xp = XpService::award($user, 'create_note');

        return response()->json([
            'note' => $note,
            'xp' => $xp,
            'streak' => $streak,
            'message' => '+' . $xp['xp_gained'] . ' XP for creating a note!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->first();
        $note->update($request->all());

        $user = $request->user();
        $streak = StreakService::record($user);
        $xp = XpService::award($user, 'edit_note');

        return response()->json([
            'note' => $note,
            'xp' => $xp,
            'streak' => $streak,
        ]);
    }

    public function destroy($id)
    {
        Note::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
}

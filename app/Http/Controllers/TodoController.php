<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Services\StreakService;
use App\Services\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        return Todo::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $todo = Todo::create([
            'user_id' => Auth::id(),
            'text' => $request->text,
            'completed' => false,
        ]);

        $user = $request->user();
        $streak = StreakService::record($user);
        $xp = XpService::award($user, 'create_task');

        return response()->json([
            'todo' => $todo,
            'xp' => $xp,
            'streak' => $streak,
            'message' => '+' . $xp['xp_gained'] . ' XP for creating a task!',
        ]);
    }

    public function update($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::id())->first();
        $todo->completed = !$todo->completed;
        $todo->save();

        $user = Auth::user();
        $streak = null;
        $xp = null;

        if ($todo->completed) {
            $streak = StreakService::record($user);
            $xp = XpService::award($user, 'complete_task');
        }

        return response()->json([
            'todo' => $todo,
            'xp' => $xp,
            'streak' => $streak,
            'message' => $todo->completed ? ('+' . ($xp['xp_gained'] ?? 0) . ' XP for completing a task!') : null,
        ]);
    }

    public function destroy($id)
    {
        Todo::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
}

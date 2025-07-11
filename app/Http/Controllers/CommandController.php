<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'leader_id' => 'required|exists:users,id',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:users,id'
        ]);

//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        Command::where('topic_id', $request->topic_id)->delete();

        $command = Command::create([
            'topic_id' => $request->topic_id,
            'leader_id' => $request->leader_id,
            'member_ids' => json_encode($request->member_ids),
            'link' => '/images/standard_bars.png'
        ]);

        return response()->json($command, 201);
    }

    public function show($subject_id, $topic_id)
    {
        $command = Command::where('topic_id', $topic_id)->with('leader')->firstOrFail();
        $command->members = $command->members();
        return response()->json($command);
    }

    public function getStudents()
    {
//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        $students = User::where('role', 'user')->get(['id', 'username', 'firstname', 'lastname']);
        return response()->json($students);
    }

    public function addStudents(Request $request, $id)
    {
        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:users,id'
        ]);

//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        $command = Command::findOrFail($id);
        $currentMembers = is_string($command->member_ids) ? json_decode($command->member_ids, true) : $command->member_ids;
        $newMembers = array_unique(array_merge($currentMembers ?? [], $request->member_ids));
        $command->update(['member_ids' => json_encode($newMembers)]);

        return response()->json($command);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'leader_id' => 'required|exists:users,id',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:users,id'
        ]);

//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        $command = Command::findOrFail($id);
        $command->update([
            'leader_id' => $request->leader_id,
            'member_ids' => json_encode($request->member_ids),
        ]);

        return response()->json($command);
    }

    public function destroy($id)
    {
//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        $command = Command::findOrFail($id);
        $command->delete();

        return response()->json(['message' => 'Command deleted']);
    }

    public function upgradePhoto(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:standard_bars,golden_bars,silver_bars'
        ]);

//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }

        $command = Command::findOrFail($id);
        $command->update(['link' => "/images/{$request->type}.png"]);

        return response()->json($command);
    }
}

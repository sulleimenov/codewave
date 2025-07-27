<?php

namespace App\Http\Controllers;

use App\Models\Lection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectionController extends Controller
{
    public function show($subject_id)
    {
        $lection = Lection::where('subject_id', $subject_id)->first();

        if (!$lection) {
            return response()->json(['message' => 'Лекция не найдена'], 404);
        }

        return response()->json(['lection' => $lection]);
    }

    public function find($subject_id, $topic_id)
    {
        // Validate parameters
        if (!is_numeric($subject_id) || !is_numeric($topic_id)) {
            return response()->json(['error' => 'Invalid subject or topic ID'], 400);
        }

        // Fetch the lection for the given subject_id and topic_id
        $lection = Lection::where('subject_id', $subject_id)
            ->where('topic_id', $topic_id)
            // ->where('user_id', Auth::id() ?? 1) // Restrict to authenticated user
            ->firstOrFail();

        return response()->json([
            'markdown' => $lection->content,
            'title' => $lection->title ?? 'Lection', // Include title if available
        ]);
    }

    public function store(Request $request)
    {
//        if (!auth()->check()) {
//            return response()->json(['message' => 'Unauthorized'], 401);
//        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string',
            'topic_id' => 'required'
        ]);

        // Проверяем существование лекции для этого предмета
        $existingLection = Lection::where('subject_id', $validated['subject_id'])
            ->where('user_id', auth()->id() ?? 1)
            ->first();

        if ($existingLection) {
            return response()->json([
                'message' => 'Лекция для этого предмета уже существует',
                'existing_lection' => $existingLection,
                'action_required' => 'delete_first'
            ], 409); // 409 Conflict - подходящий статус для такой ситуации
        }

        // Создаем новую лекцию
        $lection = Lection::create([
            'subject_id' => $validated['subject_id'],
            'user_id' => auth()->id() ?? 1,
            'content' => $validated['content'],
            'topic_id' => $validated['topic_id']
        ]);

        return response()->json([
            'message' => 'Лекция успешно создана',
            'data' => $lection
        ], 201);
    }

    public function destroy($subject_id)
    {
//        if (!auth()->check()) {
//            return response()->json(['message' => 'Unauthorized'], 401);
//        }

        $lection = Lection::where('subject_id', $subject_id)
            ->where('user_id', auth()->id() ?? 1)
            ->first();

        if (!$lection) {
            return response()->json(['message' => 'Лекция не найдена'], 404);
        }

        $lection->delete();

        return response()->json([
            'message' => 'Лекция успешно удалена',
            'subject_id' => $subject_id
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $topics = Topic::where('subject_id', $id)->get()->map(function ($topic) {
            return [
                'id' => $topic->id,
                'subject_id' => $topic->subject_id,
                'name' => $topic->name,
                'date' => $topic->created_at->format('d.m.Y'),
            ];
        });

        return response()->json($topics, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|numeric',
            'name' => 'required|string',
            'objective' => 'required|string',
            'tasks' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|in:lecture,practical',
        ]);

        $topic = Topic::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'objective' => $request->objective,
            'tasks' => $request->tasks,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return response()->json($topic, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($subject_id, $topic_id)
    {
        $topic = Topic::where('subject_id', $subject_id)->where('id', $topic_id)->first();

        if (!$topic) {
            return response()->json(['message' => 'Тема не найдена'], 404);
        }

        return response()->json($topic, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Topic::find($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{

    public function index()
    {
        $subjects = Subject::with('topics')->get()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'description' => $subject->description,
                'image' => $subject->image,
                'lecture_count' => $subject->countLectureTopics(),
                'practice_count' => $subject->countPracticeTopics(),
            ];
        });

        return response()->json($subjects, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images', 'public');

        $subject = Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        //
    }

    public function edit($id)
    {
        $subject = Subject::with('topics')->find($id);

        return response()->json($subject, 200);
    }

    public function update(Request $request, Subject $subject)
    {

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($subject->image && Storage::disk('public')->exists($subject->image)) {
                Storage::disk('public')->delete($subject->image);
            }

            $validate['image'] = $request->file('image')->store('images', 'public');
        } else {
            unset($validate['image']);
        }

        $subject->update($validate);

        return response()->json($subject, 200);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if ($subject->image && Storage::disk('public')->exists($subject->image)) {
            Storage::disk('public')->delete($subject->image);
        }

        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully'], 204);
    }
}
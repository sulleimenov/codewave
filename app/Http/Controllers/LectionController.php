<?php

namespace App\Http\Controllers;

use App\Models\Lection;
use Illuminate\Http\Request;
namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string'
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Неавторизованный доступ'], 401);
        }

        $lection = Lection::updateOrCreate(
            ['subject_id' => $data['subject_id']],
            [
                'user_id' => $user->id,
                'content' => $data['content']
            ]
        );

        return response()->json(['lection' => $lection], 201);
    }
}

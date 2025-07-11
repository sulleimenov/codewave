<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function getTestByTopic($topic)
    {
        $test = Test::where('topic_id', $topic)
            ->with(['questions' => function ($query) {
                $query->with(['answers:id,question_id,title,is_correct'])->orderBy('id');
            }])
            ->first();

        if (!$test) {
            return response()->json(['error' => 'Test not found for this topic'], 404);
        }

        $previousResult = TestResult::where('test_id', $test->id)
            ->where('user_id', auth()->id() ?? 4)
            ->latest()
            ->first();

        $formattedPreviousResult = null;
        if ($previousResult) {
            $userAnswers = json_decode($previousResult->answers, true) ?? [];
            Log::debug('User Answers from test_results', ['answers' => $userAnswers]);

            $results = [];
            foreach ($test->questions as $index => $question) {
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                $userAnswerId = isset($userAnswers[$index]) ? (int)$userAnswers[$index] : null;
                $userAnswer = $userAnswerId ? $question->answers->where('id', $userAnswerId)->first() : null;

                Log::debug('Processing question', [
                    'question_id' => $question->id,
                    'index' => $index,
                    'user_answer_id' => $userAnswerId,
                    'user_answer' => $userAnswer ? $userAnswer->toArray() : null,
                    'correct_answer' => $correctAnswer ? $correctAnswer->toArray() : null,
                ]);

                $isCorrect = $userAnswer && $userAnswer->id === $correctAnswer->id;

                $results[] = [
                    'question_id' => $question->id,
                    'question' => $question->title,
                    'correct_answer' => $correctAnswer ? $correctAnswer->title : 'N/A',
                    'user_answer' => $userAnswer ? $userAnswer->title : 'Нет ответа',
                    'is_correct' => $isCorrect,
                ];
            }

            $formattedPreviousResult = [
                'score' => $previousResult->score,
                'correct' => $previousResult->correct,
                'total' => $previousResult->total,
                'details' => $results,
            ];
        }

        return response()->json([
            'test' => $test,
            'has_previous_attempt' => !is_null($previousResult),
            'previous_result' => $formattedPreviousResult,
        ]);
    }

    public function submitTest(Request $request, Test $test)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'exists:answers,id',
        ]);

        $questions = $test->questions()
            ->with(['answers' => function ($query) {
                $query->where('is_correct', true);
            }])
            ->orderBy('id')
            ->get();

        if ($questions->isEmpty()) {
            return response()->json(['error' => 'Test has no questions'], 400);
        }

        $userAnswers = $request->input('answers');
        $correctCount = 0;
        $results = [];

        foreach ($questions as $index => $question) {
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            $userAnswerId = isset($userAnswers[$index]) ? (int)$userAnswers[$index] : null;
            $userAnswer = $userAnswerId ? $question->answers()->where('id', $userAnswerId)->first() : null;

            $isCorrect = $userAnswer && $userAnswer->id === $correctAnswer->id;

            $results[] = [
                'question_id' => $question->id,
                'question' => $question->title,
                'correct_answer' => $correctAnswer ? $correctAnswer->title : 'N/A',
                'user_answer' => $userAnswer ? $userAnswer->title : 'Нет ответа',
                'is_correct' => $isCorrect,
            ];

            if ($isCorrect) {
                $correctCount++;
            }
        }

        $totalQuestions = $questions->count();
        $score = $totalQuestions > 0 ? ceil(($correctCount / $totalQuestions) * 100) : 0;

        TestResult::create([
            'test_id' => $test->id,
            'user_id' => auth()->id() ?? 4,
            'score' => $score,
            'correct' => $correctCount,
            'total' => $totalQuestions,
            'answers' => json_encode($userAnswers),
        ]);

        return response()->json([
            'score' => $score,
            'correct' => $correctCount,
            'total' => $totalQuestions,
            'details' => $results,
        ]);
    }
    public function createTest(Request $request)
    {
        // Логируем входные данные для отладки
        \Log::info('Request data:', $request->all());

        // Валидация запроса (убрали questions.*.type)
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'questions' => 'required|array|min:1',
            'questions.*.title' => 'required|string',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.title' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ]);

        // Проверяем, нет ли уже теста для данной темы
        $existingTest = Test::where('topic_id', $request->topic_id)->first();
        if ($existingTest) {
            return response()->json(['error' => 'Test already exists for this topic'], 400);
        }

        // Создаем тест
        $test = Test::create([
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'user_id' => $request->user_id,
        ]);

        foreach ($request->questions as $q) {
            $question = Question::create([
                'test_id' => $test->id,
                'title' => $q['title'],
                // Поле type удалено
            ]);

            foreach ($q['answers'] as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'title' => $answer['title'],
                    'is_correct' => $answer['is_correct'],
                ]);
            }
        }

        $test->load('questions.answers');
        return response()->json(['test' => $test], 201);
    }
    public function deleteTest($test_id)
    {
        $test = Test::find($test_id);
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }

        // Delete associated questions and answers
        foreach ($test->questions as $question) {
            $question->answers()->delete();
            $question->delete();
        }
        $test->results()->delete(); // Delete associated test results
        $test->delete();

        return response()->json(['message' => 'Test deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(\App\Models\Quiz $quiz): \Illuminate\View\View
    {
        abort_unless($quiz->course->published && auth()->user()->enrollments()->where('course_id', $quiz->course_id)->exists(), 403);
        return view('quizzes.show', compact('quiz'));
    }

    public function submit(Request $request, \App\Models\Quiz $quiz): \Illuminate\Http\RedirectResponse
    {
        abort_unless($quiz->course->published && auth()->user()->enrollments()->where('course_id', $quiz->course_id)->exists(), 403);
        $questions = $quiz->questions()->get();
        $validated = $request->validate(['answers' => ['required', 'array'], 'answers.*' => ['required', 'integer', 'min:0', 'max:3']]);
        $answers = $validated['answers'];
        abort_unless(count($answers) === $questions->count() && $questions->every(fn ($q) => array_key_exists($q->id, $answers)), 422);
        $correct = $questions->filter(fn ($q) => (int) $answers[$q->id] === (int) $q->correct_option)->count();
        $score = $questions->isEmpty() ? 0 : (int) round(100 * $correct / $questions->count());
        \App\Models\QuizAttempt::create(['quiz_id' => $quiz->id, 'user_id' => auth()->id(), 'score' => $score, 'answers' => $answers, 'submitted_at' => now()]);
        return redirect()->route('courses.show', $quiz->course)->with('success', "Quiz submitted. Score: {$score}%.");
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(\App\Models\Lesson $lesson): \Illuminate\View\View
    {
        $course = $lesson->course;
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->role === 'administrator' || $course->instructor_id === auth()->id() || ($course->published && auth()->user()->enrollments()->where('course_id', $course->id)->exists()), 403);
        return view('lessons.show', compact('lesson', 'course'));
    }

    public function complete(\App\Models\Lesson $lesson): \Illuminate\Http\RedirectResponse
    {
        $enrollment = auth()->user()->enrollments()->where('course_id', $lesson->course_id)->firstOrFail();
        \App\Models\LessonProgress::updateOrCreate(['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id], ['completed_at' => now()]);
        return back()->with('success', 'Lesson marked complete.');
    }
}

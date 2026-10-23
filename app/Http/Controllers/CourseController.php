<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $courses = \App\Models\Course::query()->where('published', true)->with('instructor')->withCount('lessons')->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->toString().'%'))->latest()->paginate(12)->withQueryString();
        return view('courses.index', compact('courses'));
    }

    public function show(\App\Models\Course $course): \Illuminate\View\View
    {
        abort_unless($course->published || auth()->id() === $course->instructor_id || auth()->user()?->role === 'administrator', 404);
        return view('courses.show', ['course' => $course->load('instructor', 'lessons')]);
    }
}

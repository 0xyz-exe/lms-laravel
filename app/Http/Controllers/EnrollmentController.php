<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(\App\Models\Course $course): \Illuminate\Http\RedirectResponse
    {
        abort_unless($course->published, 404);
        abort_unless(auth()->user()->role === 'student', 403);
        \App\Models\Enrollment::firstOrCreate(['course_id' => $course->id, 'user_id' => auth()->id()]);
        return redirect()->route('courses.show', $course)->with('success', 'Enrolled successfully.');
    }

    public function index(): \Illuminate\View\View
    {
        $courses = auth()->user()->enrollments()->with('course.instructor', 'progress')->latest()->paginate(10);
        return view('student.dashboard', compact('courses'));
    }
}

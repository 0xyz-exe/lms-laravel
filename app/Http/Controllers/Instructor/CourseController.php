<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(): \Illuminate\View\View { $courses = auth()->user()->courses()->latest()->paginate(10); return view('instructor.courses.index', compact('courses')); }
    public function create(): \Illuminate\View\View { return view('instructor.courses.form', ['course' => new \App\Models\Course]); }
    public function store(Request $request): \Illuminate\Http\RedirectResponse { $data = $request->validate(['title' => ['required','string','max:180'], 'slug' => ['required','alpha_dash','max:190','unique:courses'], 'description' => ['nullable','string'], 'category' => ['required','string','max:100'], 'published' => ['sometimes','boolean']]); $data['instructor_id'] = auth()->id(); $data['published'] = $request->boolean('published'); $course = \App\Models\Course::create($data); return redirect()->route('instructor.courses.index')->with('success','Course created.'); }
    public function edit(\App\Models\Course $course): \Illuminate\View\View { $this->authorizeOwner($course); return view('instructor.courses.form', compact('course')); }
    public function update(Request $request, \App\Models\Course $course): \Illuminate\Http\RedirectResponse { $this->authorizeOwner($course); $data = $request->validate(['title' => ['required','string','max:180'], 'slug' => ['required','alpha_dash','max:190',\Illuminate\Validation\Rule::unique('courses')->ignore($course)], 'description' => ['nullable','string'], 'category' => ['required','string','max:100'], 'published' => ['sometimes','boolean']]); $data['published'] = $request->boolean('published'); unset($data['instructor_id']); $course->update($data); return back()->with('success','Course updated.'); }
    public function destroy(\App\Models\Course $course): \Illuminate\Http\RedirectResponse { $this->authorizeOwner($course); $course->delete(); return redirect()->route('instructor.courses.index')->with('success','Course deleted.'); }
    private function authorizeOwner(\App\Models\Course $course): void { abort_unless(auth()->user()->role === 'administrator' || $course->instructor_id === auth()->id(), 403); }
}

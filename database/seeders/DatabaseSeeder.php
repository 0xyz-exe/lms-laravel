<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Demo Instructor', 'email' => 'instructor@example.test', 'role' => 'instructor']);
        User::factory()->create(['name' => 'Demo Student', 'email' => 'student@example.test', 'role' => 'student']);

        $instructor = User::where('email', 'instructor@example.test')->firstOrFail();
        $student = User::where('email', 'student@example.test')->firstOrFail();
        $course = \App\Models\Course::firstOrCreate(['slug' => 'web-development-foundations'], ['instructor_id' => $instructor->id, 'title' => 'Web Development Foundations', 'description' => 'A demo course showing enrollment, lesson progress, and quizzes.', 'category' => 'Development', 'published' => true]);
        $lesson = $course->lessons()->firstOrCreate(['position' => 1], ['title' => 'Welcome and course overview', 'content' => 'Start with the fundamentals. This is sample learning content.', 'media_url' => null]);
        $course->quizzes()->firstOrCreate(['title' => 'Foundations check'], ['passing_score' => 70])->questions()->firstOrCreate(['prompt' => 'Which language structures web page markup?'], ['options' => ['HTML', 'SQL', 'Bash', 'CSS'], 'correct_option' => 0]);
        $student->enrollments()->firstOrCreate(['course_id' => $course->id]);
        \App\Models\LessonProgress::firstOrCreate(['enrollment_id' => $student->enrollments()->where('course_id', $course->id)->value('id'), 'lesson_id' => $lesson->id], ['completed_at' => null]);
        if (app()->environment('local')) {
            $admin = User::firstOrCreate(['email' => 'admin@example.test'], ['name' => 'Local LMS Admin', 'password' => \Illuminate\Support\Facades\Hash::make('ChangeMe123!'), 'role' => 'administrator']);
        }
    }
}

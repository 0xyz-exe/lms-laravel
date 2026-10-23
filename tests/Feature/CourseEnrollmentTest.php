<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_enroll_and_view_lesson_but_non_enrolled_user_cannot(): void
    {
        $teacher = \App\Models\User::factory()->create(['role' => 'instructor']);
        $student = \App\Models\User::factory()->create(['role' => 'student']);
        $other = \App\Models\User::factory()->create(['role' => 'student']);
        $course = \App\Models\Course::create(['instructor_id' => $teacher->id, 'title' => 'Basics', 'slug' => 'basics', 'published' => true]);
        $lesson = $course->lessons()->create(['title' => 'Intro', 'position' => 1]);

        $this->actingAs($other)->get(route('lessons.show', $lesson))->assertForbidden();
        $this->actingAs($student)->post(route('courses.enroll', $course))->assertRedirect(route('courses.show', $course));
        $this->actingAs($student)->get(route('lessons.show', $lesson))->assertOk()->assertSee('Intro');
        $this->actingAs($student)->post(route('lessons.complete', $lesson))->assertRedirect();
        $this->assertDatabaseHas('lesson_progress', ['lesson_id' => $lesson->id, 'completed_at' => now()->toDateTimeString()]);
    }

    public function test_instructor_can_create_only_own_course(): void
    {
        $instructor = \App\Models\User::factory()->create(['role' => 'instructor']);
        $response = $this->actingAs($instructor)->post(route('instructor.courses.store'), ['title' => 'A course', 'slug' => 'a-course', 'category' => 'General', 'published' => '1']);
        $response->assertRedirect(route('instructor.courses.index'));
        $this->assertDatabaseHas('courses', ['slug' => 'a-course', 'instructor_id' => $instructor->id, 'published' => true]);
    }

    public function test_quiz_score_is_computed_from_answer_key(): void
    {
        $teacher = \App\Models\User::factory()->create(['role' => 'instructor']);
        $student = \App\Models\User::factory()->create(['role' => 'student']);
        $course = \App\Models\Course::create(['instructor_id' => $teacher->id, 'title' => 'Basics', 'slug' => 'basics', 'published' => true]);
        $course->enrollments()->create(['user_id' => $student->id]);
        $quiz = $course->quizzes()->create(['title' => 'Check', 'passing_score' => 60]);
        $question = $quiz->questions()->create(['prompt' => 'Correct?', 'options' => ['Yes', 'No'], 'correct_option' => 0]);
        $this->actingAs($student)->post(route('quizzes.submit', $quiz), ['answers' => [$question->id => 0]])->assertRedirect();
        $this->assertDatabaseHas('quiz_attempts', ['quiz_id' => $quiz->id, 'user_id' => $student->id, 'score' => 100]);
    }
}

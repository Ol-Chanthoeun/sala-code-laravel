<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseLearningTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_learning_redirects_to_first_published_lesson(): void
    {
        $course = Course::create([
            'title' => 'C Programming',
            'slug' => 'c-programming',
            'short_description' => 'Learn C.',
            'description' => 'Learn C.',
            'programming_language' => 'C',
            'difficulty_level' => 'Beginner',
            'status' => 'published',
            'price' => 'Free',
        ]);

        $section = CourseSection::create([
            'course_id' => $course->id,
            'title' => 'Introduction to C',
            'order_number' => 1,
        ]);

        $firstLesson = Lesson::create([
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => 'Hello World in C',
            'slug' => 'hello-world-c',
            'lesson_content' => '<p>Hello C</p>',
            'order_number' => 1,
            'status' => 'published',
        ]);

        Lesson::create([
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => 'Variables',
            'slug' => 'c-variables',
            'lesson_content' => '<p>Variables</p>',
            'order_number' => 2,
            'status' => 'published',
        ]);

        $this->get(route('courses.learn', $course->slug))
            ->assertRedirect(route('courses.lessons.show', [
                'course' => $course->slug,
                'lesson' => $firstLesson->slug,
            ]));
    }

    public function test_lesson_page_displays_curriculum_and_next_link(): void
    {
        $course = Course::create([
            'title' => 'C Programming',
            'slug' => 'c-programming',
            'short_description' => 'Learn C.',
            'description' => 'Learn C.',
            'programming_language' => 'C',
            'difficulty_level' => 'Beginner',
            'status' => 'published',
            'price' => 'Free',
        ]);

        $section = CourseSection::create([
            'course_id' => $course->id,
            'title' => 'Introduction to C',
            'order_number' => 1,
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => 'Hello World in C',
            'slug' => 'hello-world-c',
            'lesson_content' => '<p>Hello C</p>',
            'source_code' => '#include <stdio.h>',
            'expected_output' => 'Hello C',
            'order_number' => 1,
            'status' => 'published',
        ]);

        Lesson::create([
            'course_id' => $course->id,
            'section_id' => $section->id,
            'title' => 'Variables',
            'slug' => 'c-variables',
            'lesson_content' => '<p>Variables</p>',
            'order_number' => 2,
            'status' => 'published',
        ]);

        $this->get(route('courses.lessons.show', [
            'course' => $course->slug,
            'lesson' => $lesson->slug,
        ]))
            ->assertOk()
            ->assertSee('Introduction to C')
            ->assertSee('Hello World in C')
            ->assertSee('Next Lesson')
            ->assertSee('#include &lt;stdio.h&gt;', false);
    }

    public function test_empty_course_redirects_back_with_warning(): void
    {
        $course = Course::create([
            'title' => 'Empty Course',
            'slug' => 'empty-course',
            'short_description' => 'No lessons yet.',
            'description' => 'No lessons yet.',
            'programming_language' => 'C',
            'difficulty_level' => 'Beginner',
            'status' => 'published',
            'price' => 'Free',
        ]);

        $this->get(route('courses.learn', $course->slug))
            ->assertRedirect(route('courses.show', $course->slug))
            ->assertSessionHas('warning', 'This course does not have any published lessons yet.');
    }
}

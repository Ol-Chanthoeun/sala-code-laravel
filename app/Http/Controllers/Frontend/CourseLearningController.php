<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class CourseLearningController extends Controller
{
    public function showCourse(Course $course): View
    {
        abort_unless($course->status === 'published', 404);

        $course->load([
            'sections' => function ($query): void {
                $query->orderBy('order_number');
            },
            'sections.lessons' => function ($query): void {
                $query->where('status', 'published')->orderBy('order_number');
            },
        ]);

        $relatedCourses = Course::where('status', 'published')
            ->whereKeyNot($course->id)
            ->where('programming_language', $course->programming_language)
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.courses.show', compact('course', 'relatedCourses'));
    }

    public function start(Course $course): RedirectResponse
    {
        abort_unless($course->status === 'published', 404);

        $firstLesson = $this->publishedLessons($course)->first();

        if (! $firstLesson) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('warning', 'This course does not have any published lessons yet.');
        }

        return redirect()->route('courses.lessons.show', [
            'course' => $course->slug,
            'lesson' => $firstLesson->slug,
        ]);
    }

    public function showLesson(Course $course, Lesson $lesson): View
    {
        abort_unless($course->status === 'published', 404);
        abort_unless($lesson->course_id === $course->id, 404);
        abort_unless($lesson->status === 'published', 404);

        $sections = $course->sections()
            ->with(['lessons' => function ($query): void {
                $query->where('status', 'published')->orderBy('order_number');
            }])
            ->orderBy('order_number')
            ->get();

        $lessons = $sections->flatMap(fn ($section) => $section->lessons)->values();

        $currentIndex = $lessons->search(fn ($item) => $item->id === $lesson->id);

        abort_if($currentIndex === false, 404);

        $previousLesson = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null;

        $lesson->load(['section', 'examples']);

        return view('frontend.learning.lesson', [
            'course' => $course,
            'lesson' => $lesson,
            'sections' => $sections,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'videoEmbedUrl' => $this->youtubeEmbedUrl($lesson->video_url),
        ]);
    }

    private function publishedLessons(Course $course): Collection
    {
        $sections = $course->sections()
            ->with(['lessons' => function ($query): void {
                $query->where('status', 'published')->orderBy('order_number');
            }])
            ->orderBy('order_number')
            ->get();

        return $sections->flatMap(fn ($section) => $section->lessons)->values();
    }

    private function youtubeEmbedUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (str_contains($url, '/embed/')) {
            return $url;
        }

        $parts = parse_url($url);

        if (! $parts || empty($parts['host'])) {
            return $url;
        }

        if (str_contains($parts['host'], 'youtu.be')) {
            return 'https://www.youtube.com/embed/' . ltrim($parts['path'] ?? '', '/');
        }

        if (str_contains($parts['host'], 'youtube.com')) {
            parse_str($parts['query'] ?? '', $query);

            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/' . $query['v'];
            }
        }

        return $url;
    }
}

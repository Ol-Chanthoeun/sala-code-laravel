<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:80'],
        ]);

        $term = $validated['q'];

        $courses = Course::query()
            ->where('status', 'published')
            ->where('title', 'like', "%{$term}%")
            ->orderBy('title')
            ->limit(4)
            ->get(['title', 'slug'])
            ->map(fn (Course $course): array => [
                'title' => $course->title,
                'subtitle' => 'Course',
                'url' => route('courses.show', $course),
            ]);

        $lessons = Lesson::query()
            ->with('course:id,title,slug')
            ->where('status', 'published')
            ->whereHas('course', fn ($query) => $query->where('status', 'published'))
            ->where('title', 'like', "%{$term}%")
            ->orderBy('title')
            ->limit(4)
            ->get(['id', 'course_id', 'title', 'slug'])
            ->map(fn (Lesson $lesson): array => [
                'title' => $lesson->title,
                'subtitle' => $lesson->course->title,
                'url' => route('courses.lessons.show', [$lesson->course, $lesson]),
            ]);

        $videos = Video::query()
            ->with('playlist:id,name,slug,status')
            ->where('status', 'published')
            ->whereHas('playlist', fn ($query) => $query->where('status', 'published'))
            ->where('title', 'like', "%{$term}%")
            ->orderBy('title')
            ->limit(4)
            ->get(['title', 'slug', 'playlist_id'])
            ->map(fn (Video $video): array => [
                'title' => $video->title,
                'subtitle' => $video->playlist->name,
                'url' => route('videos.watch', [$video->playlist->slug, $video->slug]),
            ]);

        $tests = Quiz::query()
            ->with('programmingLanguage:id,name,slug')
            ->where('status', 'published')
            ->where('title', 'like', "%{$term}%")
            ->orderBy('title')
            ->limit(4)
            ->get(['id', 'programming_language_id', 'title'])
            ->filter(fn (Quiz $quiz): bool => $quiz->programmingLanguage !== null)
            ->map(fn (Quiz $quiz): array => [
                'title' => $quiz->title,
                'subtitle' => $quiz->programmingLanguage->name,
                'url' => route('quiz.course', $quiz->programmingLanguage),
            ])->values();

        return response()->json([
            'groups' => [
                ['label' => 'Courses', 'items' => $courses],
                ['label' => 'Lessons', 'items' => $lessons],
                ['label' => 'Videos', 'items' => $videos],
                ['label' => 'Tests', 'items' => $tests],
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LessonRequest;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::with(['course', 'section'])->orderBy('course_id')->orderBy('order_number')->paginate(15);

        return view('admin.lessons.index', compact('lessons'));
    }

    public function create(): View
    {
        return view('admin.lessons.create', $this->formData());
    }

    public function store(LessonRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->validateSectionBelongsToCourse($data);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['title']);
        $data['created_by'] = $request->user()->id;

        Lesson::create($data);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson created successfully.');
    }

    public function show(Lesson $lesson): View
    {
        $lesson->load(['course', 'section', 'examples']);

        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit(Lesson $lesson): View
    {
        return view('admin.lessons.edit', array_merge($this->formData(), compact('lesson')));
    }

    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $data = $request->validated();
        $this->validateSectionBelongsToCourse($data);
        $data['slug'] = $data['slug'] ? $this->uniqueSlug($data['slug'], $lesson->id) : $lesson->slug;

        $lesson->update($data);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'courses' => Course::orderBy('title')->get(),
            'sections' => CourseSection::with('course')->orderBy('course_id')->orderBy('order_number')->get(),
        ];
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Lesson::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateSectionBelongsToCourse(array $data): void
    {
        if (empty($data['section_id'])) {
            return;
        }

        $belongsToCourse = CourseSection::whereKey($data['section_id'])
            ->where('course_id', $data['course_id'])
            ->exists();

        if (! $belongsToCourse) {
            throw ValidationException::withMessages([
                'section_id' => 'The selected section does not belong to the selected course.',
            ]);
        }
    }
}

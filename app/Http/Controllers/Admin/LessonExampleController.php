<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LessonExampleRequest;
use App\Models\Lesson;
use App\Models\LessonExample;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LessonExampleController extends Controller
{
    public function index(): View
    {
        $examples = LessonExample::with('lesson.course')->latest()->paginate(15);

        return view('admin.examples.index', compact('examples'));
    }

    public function create(): View
    {
        return view('admin.examples.create', [
            'lessons' => Lesson::with('course')->orderBy('title')->get(),
        ]);
    }

    public function store(LessonExampleRequest $request): RedirectResponse
    {
        LessonExample::create($request->validated());

        return redirect()->route('admin.examples.index')->with('success', 'Code example created successfully.');
    }

    public function edit(LessonExample $example): View
    {
        return view('admin.examples.edit', [
            'example' => $example,
            'lessons' => Lesson::with('course')->orderBy('title')->get(),
        ]);
    }

    public function update(LessonExampleRequest $request, LessonExample $example): RedirectResponse
    {
        $example->update($request->validated());

        return redirect()->route('admin.examples.index')->with('success', 'Code example updated successfully.');
    }

    public function destroy(LessonExample $example): RedirectResponse
    {
        $example->delete();

        return back()->with('success', 'Code example deleted successfully.');
    }
}

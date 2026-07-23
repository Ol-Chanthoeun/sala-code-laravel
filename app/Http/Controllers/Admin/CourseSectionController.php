<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseSectionRequest;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseSectionController extends Controller
{
    public function index(): View
    {
        $sections = CourseSection::with('course')->orderBy('course_id')->orderBy('order_number')->paginate(15);

        return view('admin.sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('admin.sections.create', [
            'courses' => Course::orderBy('title')->get(),
        ]);
    }

    public function store(CourseSectionRequest $request): RedirectResponse
    {
        CourseSection::create($request->validated());

        return redirect()->route('admin.sections.index')->with('success', 'Course section created successfully.');
    }

    public function edit(CourseSection $section): View
    {
        return view('admin.sections.edit', [
            'section' => $section,
            'courses' => Course::orderBy('title')->get(),
        ]);
    }

    public function update(CourseSectionRequest $request, CourseSection $section): RedirectResponse
    {
        $section->update($request->validated());

        return redirect()->route('admin.sections.index')->with('success', 'Course section updated successfully.');
    }

    public function destroy(CourseSection $section): RedirectResponse
    {
        $section->delete();

        return back()->with('success', 'Course section deleted successfully.');
    }
}

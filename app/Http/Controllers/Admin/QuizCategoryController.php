<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuizCategoryRequest;
use App\Models\ProgrammingLanguage;
use App\Models\QuizCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class QuizCategoryController extends Controller
{
    public function index(): View
    {
        $categories = QuizCategory::with('programmingLanguage')
            ->withCount('quizzes')
            ->orderBy('programming_language_id')
            ->orderBy('order_number')
            ->paginate(15);

        return view('admin.quiz.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.quiz.categories.form', $this->formData(null, route('admin.quiz-categories.store'), 'POST'));
    }

    public function store(QuizCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        QuizCategory::create($data);

        return redirect()->route('admin.quiz-categories.index')->with('success', 'បានបង្កើតប្រភេទតេស្តដោយជោគជ័យ។');
    }

    public function edit(QuizCategory $quizCategory): View
    {
        return view('admin.quiz.categories.form', $this->formData($quizCategory, route('admin.quiz-categories.update', $quizCategory), 'PUT'));
    }

    public function update(QuizCategoryRequest $request, QuizCategory $quizCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: $quizCategory->slug;

        $quizCategory->update($data);

        return redirect()->route('admin.quiz-categories.index')->with('success', 'បានកែសម្រួលប្រភេទតេស្តដោយជោគជ័យ។');
    }

    public function destroy(QuizCategory $quizCategory): RedirectResponse
    {
        $quizCategory->delete();

        return back()->with('success', 'បានលុបប្រភេទតេស្តដោយជោគជ័យ។');
    }

    private function formData(?QuizCategory $category, string $action, string $method): array
    {
        return [
            'category' => $category,
            'languages' => ProgrammingLanguage::orderBy('order_number')->get(),
            'action' => $action,
            'method' => $method,
        ];
    }
}

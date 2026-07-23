<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LmsQuizRequest;
use App\Models\ProgrammingLanguage;
use App\Models\Quiz;
use App\Models\QuizCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LmsQuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::with(['programmingLanguage', 'category'])
            ->withCount('questions')
            ->orderBy('programming_language_id')
            ->orderBy('order_number')
            ->paginate(15);

        return view('admin.quiz.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        return view('admin.quiz.quizzes.form', $this->formData(null, route('admin.quizzes.store'), 'POST'));
    }

    public function store(LmsQuizRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->validateCategoryBelongsToLanguage($data);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        Quiz::create($data);

        return redirect()->route('admin.quizzes.index')->with('success', 'បានបង្កើតតេស្តដោយជោគជ័យ។');
    }

    public function edit(Quiz $quiz): View
    {
        return view('admin.quiz.quizzes.form', $this->formData($quiz, route('admin.quizzes.update', $quiz), 'PUT'));
    }

    public function update(LmsQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $data = $request->validated();
        $this->validateCategoryBelongsToLanguage($data);
        $data['slug'] = $data['slug'] ?: $quiz->slug;

        $quiz->update($data);

        return redirect()->route('admin.quizzes.index')->with('success', 'បានកែសម្រួលតេស្តដោយជោគជ័យ។');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return back()->with('success', 'បានលុបតេស្តដោយជោគជ័យ។');
    }

    private function formData(?Quiz $quiz, string $action, string $method): array
    {
        return [
            'quiz' => $quiz,
            'languages' => ProgrammingLanguage::orderBy('order_number')->get(),
            'categories' => QuizCategory::with('programmingLanguage')->orderBy('programming_language_id')->orderBy('order_number')->get(),
            'action' => $action,
            'method' => $method,
        ];
    }

    private function validateCategoryBelongsToLanguage(array $data): void
    {
        $belongs = QuizCategory::whereKey($data['quiz_category_id'])
            ->where('programming_language_id', $data['programming_language_id'])
            ->exists();

        if (! $belongs) {
            throw ValidationException::withMessages([
                'quiz_category_id' => 'ប្រភេទដែលបានជ្រើសរើសមិនស្ថិតក្នុងភាសាដែលបានជ្រើសរើសទេ។',
            ]);
        }
    }
}

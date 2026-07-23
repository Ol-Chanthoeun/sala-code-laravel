<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuizQuestionRequest;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuizQuestionController extends Controller
{
    public function index(): View
    {
        $questions = QuizQuestion::with('quiz.programmingLanguage', 'choices')
            ->orderBy('quiz_id')
            ->orderBy('order_number')
            ->paginate(15);

        return view('admin.quiz.questions.index', compact('questions'));
    }

    public function create(): View
    {
        return view('admin.quiz.questions.form', $this->formData(null, route('admin.quiz-questions.store'), 'POST'));
    }

    public function store(QuizQuestionRequest $request): RedirectResponse
    {
        $this->saveQuestion(new QuizQuestion(), $request->validated());

        return redirect()->route('admin.quiz-questions.index')->with('success', 'បានបង្កើតសំណួរដោយជោគជ័យ។');
    }

    public function edit(QuizQuestion $quizQuestion): View
    {
        $quizQuestion->load('choices');

        return view('admin.quiz.questions.form', $this->formData($quizQuestion, route('admin.quiz-questions.update', $quizQuestion), 'PUT'));
    }

    public function update(QuizQuestionRequest $request, QuizQuestion $quizQuestion): RedirectResponse
    {
        $this->saveQuestion($quizQuestion, $request->validated());

        return redirect()->route('admin.quiz-questions.index')->with('success', 'បានកែសម្រួលសំណួរដោយជោគជ័យ។');
    }

    public function destroy(QuizQuestion $quizQuestion): RedirectResponse
    {
        $quizQuestion->delete();

        return back()->with('success', 'បានលុបសំណួរដោយជោគជ័យ។');
    }

    private function saveQuestion(QuizQuestion $question, array $data): void
    {
        $correctIndex = (int) $data['correct_choice'];
        $choices = array_values($data['choices']);

        if (! array_key_exists($correctIndex, $choices)) {
            throw ValidationException::withMessages(['correct_choice' => 'សូមជ្រើសរើសចម្លើយត្រឹមត្រូវដែលមានសុពលភាព។']);
        }

        DB::transaction(function () use ($question, $data, $choices, $correctIndex): void {
            $question->fill([
                'quiz_id' => $data['quiz_id'],
                'question' => $data['question'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'points' => $data['points'],
                'order_number' => $data['order_number'],
            ]);
            $question->save();

            $question->choices()->delete();
            $correctChoice = null;

            foreach ($choices as $index => $choiceData) {
                $choice = QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['choice_text'],
                    'is_correct' => $index === $correctIndex,
                    'order_number' => $index + 1,
                ]);

                if ($index === $correctIndex) {
                    $correctChoice = $choice;
                }
            }

            $question->update(['correct_choice_id' => $correctChoice?->id]);
        });
    }

    private function formData(?QuizQuestion $question, string $action, string $method): array
    {
        return [
            'question' => $question,
            'quizzes' => Quiz::with('programmingLanguage', 'category')->orderBy('programming_language_id')->orderBy('order_number')->get(),
            'action' => $action,
            'method' => $method,
        ];
    }
}

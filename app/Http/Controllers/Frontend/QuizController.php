<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProgrammingLanguage;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $languages = ProgrammingLanguage::query()
            ->where('status', 'published')
            ->with(['quizzes' => function ($query): void {
                $query->where('status', 'published')->withCount('questions');
            }])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('name', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('difficulty'), function ($query) use ($request): void {
                $query->where('difficulty', $request->string('difficulty'));
            })
            ->orderBy('order_number')
            ->get();

        $attemptQuery = QuizAttempt::with('quiz.programmingLanguage')
            ->where('status', 'completed')
            ->latest('completed_at');

        $recentAttempts = (clone $attemptQuery)
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
            ->when(! auth()->check(), fn ($query) => $query->where('session_id', $request->session()->getId()))
            ->take(5)
            ->get();

        $continueAttempts = QuizAttempt::with('quiz.programmingLanguage')
            ->where('status', 'in_progress')
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
            ->when(! auth()->check(), fn ($query) => $query->where('session_id', $request->session()->getId()))
            ->latest()
            ->take(3)
            ->get();

        $popularQuizzes = Quiz::with('programmingLanguage')
            ->where('status', 'published')
            ->withCount('attempts')
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        return view('frontend.quiz.index', compact('languages', 'recentAttempts', 'continueAttempts', 'popularQuizzes'));
    }

    public function language(Request $request, ProgrammingLanguage $language): View
    {
        abort_unless($language->status === 'published', 404);

        $language->load(['categories' => function ($query) use ($request): void {
            $query->where('status', 'published')
                ->when($request->filled('category'), fn ($q) => $q->where('slug', $request->string('category')))
                ->when($request->filled('difficulty'), fn ($q) => $q->where('difficulty', $request->string('difficulty')))
                ->with(['quizzes' => function ($quizQuery) use ($request): void {
                    $quizQuery->where('status', 'published')
                        ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->string('search') . '%'))
                        ->when($request->filled('difficulty'), fn ($q) => $q->where('difficulty', $request->string('difficulty')))
                        ->withCount('questions')
                        ->orderBy('order_number');
                }])
                ->orderBy('order_number');
        }]);

        return view('frontend.quiz.language', compact('language'));
    }

    public function start(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_unless($quiz->status === 'published', 404);
        abort_unless($quiz->questions()->exists(), 404);

        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'session_id' => $request->session()->getId(),
            'status' => 'in_progress',
            'total_points' => $quiz->questions()->sum('points'),
            'started_at' => now(),
        ]);

        return redirect()->route('quiz.take', $attempt);
    }

    public function take(QuizAttempt $attempt): View
    {
        $this->authorizeAttempt($attempt);

        $attempt->load([
            'quiz.programmingLanguage',
            'quiz.category',
            'quiz.questions.choices',
        ]);

        abort_if($attempt->status === 'completed', 404);

        return view('frontend.quiz.take', compact('attempt'));
    }

    public function submit(Request $request, QuizAttempt $attempt): RedirectResponse
    {
        $this->authorizeAttempt($attempt);

        $attempt->load('quiz.questions.choices');
        $answers = $request->input('answers', []);
        $timeUsed = max(0, (int) $request->integer('time_used', 0));

        DB::transaction(function () use ($attempt, $answers, $timeUsed): void {
            $attempt->answers()->delete();

            $correctCount = 0;
            $incorrectCount = 0;
            $score = 0;
            $totalPoints = $attempt->quiz->questions->sum('points');

            foreach ($attempt->quiz->questions as $question) {
                $choiceId = $answers[$question->id] ?? null;
                $choice = $choiceId ? $question->choices->firstWhere('id', (int) $choiceId) : null;
                $isCorrect = $choice && $question->correct_choice_id && (int) $choice->id === (int) $question->correct_choice_id;

                if ($isCorrect) {
                    $correctCount++;
                    $score += $question->points;
                } else {
                    $incorrectCount++;
                }

                QuizAttemptAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                    'quiz_choice_id' => $choice?->id,
                    'is_correct' => (bool) $isCorrect,
                    'points_awarded' => $isCorrect ? $question->points : 0,
                ]);
            }

            $attempt->update([
                'status' => 'completed',
                'score' => $score,
                'total_points' => $totalPoints,
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'time_used' => $timeUsed,
                'completed_at' => now(),
            ]);
        });

        return redirect()->route('quiz.result', $attempt);
    }

    public function result(QuizAttempt $attempt): View
    {
        $this->authorizeAttempt($attempt);
        $attempt->load('quiz.programmingLanguage', 'quiz.category');

        return view('frontend.quiz.result', compact('attempt'));
    }

    public function review(QuizAttempt $attempt): View
    {
        $this->authorizeAttempt($attempt);
        $attempt->load('quiz.programmingLanguage', 'quiz.category', 'quiz.questions.choices', 'answers.choice');

        return view('frontend.quiz.review', compact('attempt'));
    }

    public function history(Request $request): View
    {
        $attempts = QuizAttempt::with('quiz.programmingLanguage', 'quiz.category')
            ->where('status', 'completed')
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
            ->when(! auth()->check(), fn ($query) => $query->where('session_id', $request->session()->getId()))
            ->latest('completed_at')
            ->paginate(12);

        return view('frontend.quiz.history', compact('attempts'));
    }

    public function certificate(QuizAttempt $attempt): View
    {
        $this->authorizeAttempt($attempt);
        $attempt->load('quiz.programmingLanguage', 'quiz.category', 'user');

        $percentage = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
        abort_if($percentage < $attempt->quiz->passing_score, 403);

        return view('frontend.quiz.certificate', compact('attempt', 'percentage'));
    }

    public function leaderboard(Quiz $quiz): View
    {
        $quiz->load('programmingLanguage', 'category');
        $attempts = $quiz->attempts()
            ->where('status', 'completed')
            ->with('user')
            ->orderByDesc('score')
            ->orderBy('time_used')
            ->take(20)
            ->get();

        return view('frontend.quiz.leaderboard', compact('quiz', 'attempts'));
    }

    private function authorizeAttempt(QuizAttempt $attempt): void
    {
        if ($attempt->user_id && auth()->id() === $attempt->user_id) {
            return;
        }

        if (! $attempt->user_id && $attempt->session_id === request()->session()->getId()) {
            return;
        }

        abort(403);
    }
}

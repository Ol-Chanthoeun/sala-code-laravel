@extends('layouts.admin')

@section('title', 'Quiz Questions')
@section('page-title', 'Quiz Questions')
@section('breadcrumb', 'Quiz Questions')

@section('content')
    <div class="admin-sticky-toolbar">
        <a href="{{ route('admin.quiz-questions.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Quiz Question" style="width:220px;margin-bottom:20px;"><i class="fas fa-plus-circle"></i> Add Question</a>
    </div>
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    <div class="data-table">
        <div class="table-header"><h3>Questions and Choices</h3></div>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Quiz</th><th>Order</th><th>Question</th><th>Choices</th><th>Difficulty</th><th>Points</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($questions as $question)
                        <tr>
                            <td>{{ $question->quiz?->programmingLanguage?->name }}<br><small>{{ $question->quiz?->title }}</small></td>
                            <td>{{ $question->order_number }}</td>
                            <td>{{ Str::limit($question->question, 100) }}</td>
                            <td>{{ $question->choices->count() }}</td>
                            <td>{{ $question->difficulty }}</td>
                            <td>{{ $question->points }}</td>
                            <td><div class="admin-table-actions"><a class="admin-icon-btn admin-icon-btn--edit" href="{{ route('admin.quiz-questions.edit', $question) }}" title="Edit Question" aria-label="Edit Question"><i class="fas fa-pen" aria-hidden="true"></i></a><form class="admin-destructive-form" action="{{ route('admin.quiz-questions.destroy', $question) }}" method="POST" data-confirm-title="Confirm Delete Question" data-confirm-message="Are you sure you want to delete this quiz question?" data-confirm-item="{{ Str::limit($question->question, 80) }}" data-confirm-label="Delete Question">@csrf @method('DELETE')<button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Question" aria-label="Delete Question"><i class="fas fa-trash" aria-hidden="true"></i></button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;">No questions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $questions->links('admin.partials.pagination') }}
@endsection

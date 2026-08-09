@extends('layouts.admin')

@section('title', 'Quizzes')
@section('page-title', 'Quizzes')
@section('breadcrumb', 'Quizzes')

@section('content')
    <div class="admin-sticky-toolbar" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
        <a href="{{ route('admin.quizzes.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Quiz" style="width:180px;"><i class="fas fa-plus-circle"></i> Add Quiz</a>
        <a href="{{ route('admin.programming-languages.index') }}" class="action-btn" style="width:220px;">Languages</a>
        <a href="{{ route('admin.quiz-categories.index') }}" class="action-btn" style="width:220px;">Categories</a>
        <a href="{{ route('admin.quiz-questions.index') }}" class="action-btn" style="width:220px;">Questions</a>
        @include('admin.reports._quick-export', ['reportType' => 'quizzes'])
    </div>
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    <div class="data-table">
        <div class="table-header"><h3>LMS Quizzes</h3></div>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Language</th><th>Category</th><th>Order</th><th>Quiz</th><th>Questions</th><th>Difficulty</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->programmingLanguage?->name }}</td>
                            <td>{{ $quiz->category?->title }}</td>
                            <td>{{ $quiz->order_number }}</td>
                            <td><strong>{{ $quiz->title }}</strong><br><small>{{ $quiz->slug }}</small></td>
                            <td>{{ $quiz->questions_count }}</td>
                            <td>{{ $quiz->difficulty }}</td>
                            <td>{{ ucfirst($quiz->status) }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" href="{{ route('admin.quizzes.edit', ['quiz' => $quiz, 'modal' => 1]) }}" data-modal-title="Edit Quiz" title="Edit Quiz" aria-label="Edit Quiz"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    <form class="admin-destructive-form" action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" data-confirm-title="Confirm Delete Quiz" data-confirm-message="Are you sure you want to delete this quiz?" data-confirm-item="{{ $quiz->title }}" data-confirm-label="Delete Quiz">@csrf @method('DELETE')<button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Quiz" aria-label="Delete Quiz"><i class="fas fa-trash" aria-hidden="true"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;">No quizzes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $quizzes->links('admin.partials.pagination') }}
@endsection

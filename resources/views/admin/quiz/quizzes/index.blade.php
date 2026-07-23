@extends('layouts.admin')

@section('title', 'Quizzes')
@section('page-title', 'Quizzes')
@section('breadcrumb', 'Quizzes')

@section('content')
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
        <a href="{{ route('admin.quizzes.create') }}" class="action-btn" style="width:180px;"><i class="fas fa-plus-circle"></i> Add Quiz</a>
        <a href="{{ route('admin.programming-languages.index') }}" class="action-btn" style="width:220px;">Languages</a>
        <a href="{{ route('admin.quiz-categories.index') }}" class="action-btn" style="width:220px;">Categories</a>
        <a href="{{ route('admin.quiz-questions.index') }}" class="action-btn" style="width:220px;">Questions</a>
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
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}">Edit</a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" style="display:inline;margin-left:8px;">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete this quiz?')">Delete</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;">No quizzes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top:20px;">{{ $quizzes->links() }}</div>
@endsection

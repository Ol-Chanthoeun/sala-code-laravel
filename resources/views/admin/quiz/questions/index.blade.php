@extends('layouts.admin')

@section('title', 'Quiz Questions')
@section('page-title', 'Quiz Questions')
@section('breadcrumb', 'Quiz Questions')

@section('content')
    <a href="{{ route('admin.quiz-questions.create') }}" class="action-btn" style="width:220px;margin-bottom:20px;"><i class="fas fa-plus-circle"></i> Add Question</a>
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
                            <td><a href="{{ route('admin.quiz-questions.edit', $question) }}">Edit</a><form action="{{ route('admin.quiz-questions.destroy', $question) }}" method="POST" style="display:inline;margin-left:8px;">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete this question?')">Delete</button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;">No questions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top:20px;">{{ $questions->links() }}</div>
@endsection

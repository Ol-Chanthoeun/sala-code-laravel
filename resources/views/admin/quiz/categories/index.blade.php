@extends('layouts.admin')

@section('title', 'Quiz Categories')
@section('page-title', 'Quiz Categories')
@section('breadcrumb', 'Quiz Categories')

@section('content')
    <a href="{{ route('admin.quiz-categories.create') }}" class="action-btn" style="width:220px;margin-bottom:20px;"><i class="fas fa-plus-circle"></i> Add Category</a>
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    <div class="data-table">
        <div class="table-header"><h3>Quiz Categories</h3></div>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Language</th><th>Order</th><th>Title</th><th>Difficulty</th><th>Status</th><th>Quizzes</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->programmingLanguage?->name }}</td>
                            <td>{{ $category->order_number }}</td>
                            <td><strong>{{ $category->title }}</strong><br><small>{{ $category->slug }}</small></td>
                            <td>{{ $category->difficulty }}</td>
                            <td>{{ ucfirst($category->status) }}</td>
                            <td>{{ $category->quizzes_count }}</td>
                            <td><a href="{{ route('admin.quiz-categories.edit', $category) }}">Edit</a><form action="{{ route('admin.quiz-categories.destroy', $category) }}" method="POST" style="display:inline;margin-left:8px;">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete this category?')">Delete</button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top:20px;">{{ $categories->links() }}</div>
@endsection

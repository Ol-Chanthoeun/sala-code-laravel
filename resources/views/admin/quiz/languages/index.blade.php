@extends('layouts.admin')

@section('title', 'Quiz Languages')
@section('page-title', 'Quiz Languages')
@section('breadcrumb', 'Quiz Languages')

@section('content')
    <a href="{{ route('admin.programming-languages.create') }}" class="action-btn" style="width:220px;margin-bottom:20px;">
        <i class="fas fa-plus-circle"></i> Add Language
    </a>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header"><h3>Programming Languages</h3></div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name</th>
                        <th>Difficulty</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Categories</th>
                        <th>Quizzes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($languages as $language)
                        <tr>
                            <td>{{ $language->order_number }}</td>
                            <td><strong>{{ $language->name }}</strong><br><small>{{ $language->slug }}</small></td>
                            <td>{{ $language->difficulty }}</td>
                            <td>{{ $language->estimated_time }} min</td>
                            <td>{{ ucfirst($language->status) }}</td>
                            <td>{{ $language->categories_count }}</td>
                            <td>{{ $language->quizzes_count }}</td>
                            <td>
                                <a href="{{ route('admin.programming-languages.edit', $language) }}">Edit</a>
                                <form action="{{ route('admin.programming-languages.destroy', $language) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this language and all quizzes?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;">No languages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">{{ $languages->links() }}</div>
@endsection

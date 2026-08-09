@extends('layouts.admin')

@section('title', 'Quiz Languages')
@section('page-title', 'Quiz Languages')
@section('breadcrumb', 'Quiz Languages')

@section('content')
    <div class="admin-sticky-toolbar">
        <a href="{{ route('admin.programming-languages.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Quiz Language" style="width:220px;margin-bottom:20px;">
            <i class="fas fa-plus-circle"></i> Add Language
        </a>
    </div>

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
                                <div class="admin-table-actions">
                                    <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" href="{{ route('admin.programming-languages.edit', ['programming_language' => $language, 'modal' => 1]) }}" data-modal-title="Edit Quiz Language" title="Edit Language" aria-label="Edit Language"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    <form class="admin-destructive-form" action="{{ route('admin.programming-languages.destroy', $language) }}" method="POST" data-confirm-title="Confirm Delete Quiz Language" data-confirm-message="Are you sure you want to delete this language and its quizzes?" data-confirm-item="{{ $language->name }}" data-confirm-label="Delete Language">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Language" aria-label="Delete Language"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;">No languages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $languages->links('admin.partials.pagination') }}
@endsection

@extends('layouts.admin')

@section('title', 'Lessons')
@section('page-title', 'Lessons')
@section('breadcrumb', 'Lessons')

@section('content')
    <div class="admin-sticky-toolbar" style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('admin.lessons.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Lesson" style="width:180px;margin-bottom:20px;">
            <i class="fas fa-plus-circle"></i> Add Lesson
        </a>
        @include('admin.reports._quick-export', ['reportType' => 'lessons'])
    </div>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Course Lessons</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Section</th>
                        <th>Order</th>
                        <th>Lesson</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                        <tr>
                            <td>{{ $lesson->course?->title }}</td>
                            <td>{{ $lesson->section?->title ?? 'No section' }}</td>
                            <td>{{ $lesson->order_number }}</td>
                            <td>
                                <strong>{{ $lesson->title }}</strong><br>
                                <small>{{ $lesson->slug }}</small>
                            </td>
                            <td>{{ ucfirst($lesson->status) }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="admin-icon-btn admin-icon-btn--primary admin-modal-form-link" href="{{ route('admin.lessons.show', ['lesson' => $lesson, 'modal' => 1]) }}" data-modal-title="Lesson Details" title="View Lesson" aria-label="View Lesson"><i class="fas fa-eye" aria-hidden="true"></i></a>
                                    <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" href="{{ route('admin.lessons.edit', ['lesson' => $lesson, 'modal' => 1]) }}" data-modal-title="Edit Lesson" title="Edit Lesson" aria-label="Edit Lesson"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    <form class="admin-destructive-form" action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" data-confirm-title="Confirm Delete Lesson" data-confirm-message="Are you sure you want to delete this lesson and its examples?" data-confirm-item="{{ $lesson->title }}" data-confirm-label="Delete Lesson">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Lesson" aria-label="Delete Lesson"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">No lessons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $lessons->links('admin.partials.pagination') }}
@endsection

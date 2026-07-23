@extends('layouts.admin')

@section('title', 'Lessons')
@section('page-title', 'Lessons')
@section('breadcrumb', 'Lessons')

@section('content')
    <a href="{{ route('admin.lessons.create') }}" class="action-btn" style="width:180px;margin-bottom:20px;">
        <i class="fas fa-plus-circle"></i> Add Lesson
    </a>

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
                                <a href="{{ route('admin.lessons.show', $lesson) }}">View</a>
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" style="margin-left:8px;">Edit</a>
                                <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this lesson and its examples?')">Delete</button>
                                </form>
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

    <div style="margin-top:20px;">{{ $lessons->links() }}</div>
@endsection

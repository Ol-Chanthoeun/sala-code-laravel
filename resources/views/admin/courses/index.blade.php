@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses')
@section('breadcrumb', 'Courses')

@section('content')
    <a href="{{ route('admin.courses.create') }}" class="action-btn" style="width:180px;margin-bottom:20px;">
        <i class="fas fa-plus-circle"></i> Add Course
    </a>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Programming Courses</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th>Sections</th>
                        <th>Lessons</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                @if($course->thumbnail || $course->image)
                                    <img src="{{ asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) }}" width="70" alt="{{ $course->title }}">
                                @else
                                    No image
                                @endif
                            </td>
                            <td>
                                <strong>{{ $course->title }}</strong><br>
                                <small>{{ $course->slug }}</small>
                            </td>
                            <td>{{ $course->programming_language }}</td>
                            <td>{{ $course->difficulty_level }}</td>
                            <td>{{ ucfirst($course->status) }}</td>
                            <td>{{ $course->sections_count }}</td>
                            <td>{{ $course->lessons_count }}</td>
                            <td>
                                <a href="{{ route('admin.courses.show', $course) }}">View</a>
                                <a href="{{ route('admin.courses.edit', $course) }}" style="margin-left:8px;">Edit</a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this course and all related sections, lessons, and examples?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">
        {{ $courses->links() }}
    </div>
@endsection

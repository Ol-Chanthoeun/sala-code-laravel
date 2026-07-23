@extends('layouts.admin')

@section('title', 'Course Sections')
@section('page-title', 'Course Sections')
@section('breadcrumb', 'Course Sections')

@section('content')
    <a href="{{ route('admin.sections.create') }}" class="action-btn" style="width:210px;margin-bottom:20px;">
        <i class="fas fa-plus-circle"></i> Add Section
    </a>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Course Sections</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Order</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>{{ $section->course?->title }}</td>
                            <td>{{ $section->order_number }}</td>
                            <td>{{ $section->title }}</td>
                            <td>{{ Str::limit($section->description, 80) }}</td>
                            <td>
                                <a href="{{ route('admin.sections.edit', $section) }}">Edit</a>
                                <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this section? Lessons will keep the course but lose this section.')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">No sections found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">{{ $sections->links() }}</div>
@endsection

@extends('layouts.admin')

@section('title', 'Code Examples')
@section('page-title', 'Code Examples')
@section('breadcrumb', 'Code Examples')

@section('content')
    <a href="{{ route('admin.examples.create') }}" class="action-btn" style="width:220px;margin-bottom:20px;">
        <i class="fas fa-plus-circle"></i> Add Code Example
    </a>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Lesson Code Examples</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Lesson</th>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Expected Output</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examples as $example)
                        <tr>
                            <td>{{ $example->lesson?->title }}</td>
                            <td>{{ $example->lesson?->course?->title }}</td>
                            <td>{{ $example->title }}</td>
                            <td>{{ Str::limit($example->expected_output, 80) }}</td>
                            <td>
                                <a href="{{ route('admin.examples.edit', $example) }}">Edit</a>
                                <form action="{{ route('admin.examples.destroy', $example) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this code example?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">No code examples found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">{{ $examples->links() }}</div>
@endsection

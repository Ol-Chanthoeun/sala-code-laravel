@extends('layouts.admin')

@section('title', 'Code Examples')
@section('page-title', 'Code Examples')
@section('breadcrumb', 'Code Examples')

@section('content')
    <div class="admin-sticky-toolbar">
        <a href="{{ route('admin.examples.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Code Example" style="width:220px;margin-bottom:20px;">
            <i class="fas fa-plus-circle"></i> Add Code Example
        </a>
    </div>

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
                                <div class="admin-table-actions">
                                    <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" href="{{ route('admin.examples.edit', ['example' => $example, 'modal' => 1]) }}" data-modal-title="Edit Code Example" title="Edit Code Example" aria-label="Edit Code Example"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    <form class="admin-destructive-form" action="{{ route('admin.examples.destroy', $example) }}" method="POST" data-confirm-title="Confirm Delete Code Example" data-confirm-message="Are you sure you want to delete this code example?" data-confirm-item="{{ $example->title }}" data-confirm-label="Delete Example">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Code Example" aria-label="Delete Code Example"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
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

    {{ $examples->links('admin.partials.pagination') }}
@endsection

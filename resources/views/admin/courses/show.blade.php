@extends('layouts.admin')

@section('title', 'Course Details')
@section('page-title', $course->title)
@section('breadcrumb', 'Course Details')

@section('content')
    <div class="system-info">
        <div class="section-title">{{ $course->title }}</div>
        <p><strong>Language:</strong> {{ $course->programming_language }}</p>
        <p><strong>Difficulty:</strong> {{ $course->difficulty_level }}</p>
        <p><strong>Status:</strong> {{ ucfirst($course->status) }}</p>
        <p style="margin-top:15px;">{!! nl2br(e($course->full_description ?? $course->description)) !!}</p>
    </div>

    <div class="data-table" style="margin-top:20px;">
        <div class="table-header">
            <h3>Curriculum</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Lessons</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->sections as $section)
                        <tr>
                            <td>{{ $section->order_number }}. {{ $section->title }}</td>
                            <td>
                                @forelse($section->lessons as $lesson)
                                    <div>{{ $lesson->order_number }}. {{ $lesson->title }} ({{ $lesson->status }})</div>
                                @empty
                                    No lessons
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;">No sections yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

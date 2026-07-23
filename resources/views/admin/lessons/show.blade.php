@extends('layouts.admin')

@section('title', 'Lesson Details')
@section('page-title', $lesson->title)
@section('breadcrumb', 'Lesson Details')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
@endpush

@section('content')
    <div class="system-info">
        <div class="section-title">{{ $lesson->title }}</div>
        <p><strong>Course:</strong> {{ $lesson->course?->title }}</p>
        <p><strong>Section:</strong> {{ $lesson->section?->title ?? 'No section' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($lesson->status) }}</p>
        <p><strong>Difficulty:</strong> {{ $lesson->difficulty_level ?? 'Beginner' }}</p>
        <p><strong>Estimated time:</strong> {{ $lesson->estimated_learning_time ?? 20 }} minutes</p>

        @if($lesson->short_description)
            <p style="margin-top:15px;">{{ $lesson->short_description }}</p>
        @endif

        <div style="margin-top:20px;">{!! $lesson->lesson_content !!}</div>

        @if($lesson->source_code)
            <h3 style="margin-top:20px;">Source Code</h3>
            <pre><code class="language-c">{{ $lesson->source_code }}</code></pre>
        @endif

        @if($lesson->expected_output)
            <h3 style="margin-top:20px;">Expected Output</h3>
            <pre>{{ $lesson->expected_output }}</pre>
        @endif

        @if($lesson->explanation)
            <h3 style="margin-top:20px;">Explanation</h3>
            <p>{{ $lesson->explanation }}</p>
        @endif

        @foreach([
            'code_explanation' => 'Code Explanation',
            'common_mistakes' => 'Common Mistakes',
            'tips' => 'Tips and Best Practices',
            'summary' => 'Summary',
            'exercise' => 'Exercise',
        ] as $field => $heading)
            @if($lesson->{$field})
                <h3 style="margin-top:20px;">{{ $heading }}</h3>
                <div style="line-height:1.7;">{!! nl2br(e($lesson->{$field})) !!}</div>
            @endif
        @endforeach

        @if(! empty($lesson->quiz))
            <h3 style="margin-top:20px;">Quiz</h3>
            @foreach($lesson->quiz as $item)
                <div style="border:1px solid #eef2f6;border-radius:8px;padding:12px;margin-top:10px;">
                    <p><strong>{{ $item['question'] ?? '' }}</strong></p>
                    <ol style="margin:8px 0 0 20px;">
                        @foreach($item['options'] ?? [] as $option)
                            <li>{{ $option }}</li>
                        @endforeach
                    </ol>
                    <p style="margin-top:8px;"><strong>Answer:</strong> {{ $item['answer'] ?? '' }}</p>
                </div>
            @endforeach
        @endif

        @if($lesson->video_url)
            <h3 style="margin-top:20px;">Video</h3>
            <a href="{{ $lesson->video_url }}" target="_blank" rel="noopener">Open video</a>
        @endif
    </div>

    <div class="data-table" style="margin-top:20px;">
        <div class="table-header">
            <h3>Code Examples</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Expected Output</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lesson->examples as $example)
                        <tr>
                            <td>{{ $example->title }}</td>
                            <td>{{ Str::limit($example->expected_output, 120) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;">No examples yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
@endpush

@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}">
    <style>
        .course-detail-page {
            padding: 120px 24px 70px;
            background: #f8fbff;
            min-height: 100vh;
        }

        .course-detail-shell {
            max-width: 1120px;
            margin: 0 auto;
        }

        .course-detail-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.7fr);
            gap: 28px;
            align-items: start;
        }

        .course-detail-panel,
        .course-curriculum-panel {
            background: #ffffff;
            border: 1px solid #dbe4f0;
            border-radius: 8px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
            padding: 24px;
        }

        .course-detail-panel h1 {
            color: #0f172a;
            font-size: 34px;
            margin-bottom: 12px;
        }

        .course-meta {
            color: #475569;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .course-meta span {
            background: #e8f1ff;
            border-radius: 6px;
            color: #1e40af;
            font-weight: 700;
            padding: 8px 10px;
        }

        .start-learning-btn {
            background: #1f6fe5;
            border-radius: 6px;
            color: #ffffff;
            display: inline-flex;
            font-weight: 800;
            margin-top: 22px;
            padding: 12px 18px;
            text-decoration: none;
        }

        .course-thumbnail {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 16px;
        }

        .course-curriculum-panel h2 {
            color: #0f172a;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .curriculum-section {
            border-top: 1px solid #e2e8f0;
            padding: 14px 0;
        }

        .curriculum-section h3 {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .curriculum-section a,
        .curriculum-section span {
            color: #334155;
            display: block;
            padding: 7px 0;
            text-decoration: none;
        }

        .course-warning {
            background: #fef3c7;
            border-radius: 6px;
            color: #92400e;
            margin-bottom: 18px;
            padding: 12px 14px;
        }

        @media (max-width: 900px) {
            .course-detail-hero {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <main class="course-detail-page">
        <div class="course-detail-shell">
            @if(session('warning'))
                <div class="course-warning">{{ session('warning') }}</div>
            @endif

            <div class="course-detail-hero">
                <section class="course-detail-panel">
                    <h1>{{ $course->title }}</h1>

                    <div class="course-meta">
                        <span>{{ $course->programming_language }}</span>
                        <span>{{ $course->difficulty_level }}</span>
                        <span>{{ $course->lessons()->where('status', 'published')->count() }} lessons</span>
                    </div>

                    <p>{{ $course->short_description ?? $course->description }}</p>

                    @if($course->full_description)
                        <div style="margin-top:18px;line-height:1.7;">
                            {!! nl2br(e($course->full_description)) !!}
                        </div>
                    @endif

                    <a href="{{ route('courses.learn', $course->slug) }}" class="start-learning-btn">
                        Start Learning
                    </a>
                </section>

                <aside class="course-curriculum-panel">
                    @if($course->thumbnail || $course->image)
                        <img class="course-thumbnail" src="{{ asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) }}" alt="{{ $course->title }}">
                    @endif

                    <h2>Course Curriculum</h2>

                    @forelse($course->sections as $section)
                        <div class="curriculum-section">
                            <h3>{{ $section->order_number }}. {{ $section->title }}</h3>

                            @forelse($section->lessons as $lesson)
                                <a href="{{ route('courses.lessons.show', ['course' => $course->slug, 'lesson' => $lesson->slug]) }}">
                                    {{ $lesson->title }}
                                </a>
                            @empty
                                <span>No published lessons yet.</span>
                            @endforelse
                        </div>
                    @empty
                        <p>No course sections yet.</p>
                    @endforelse
                </aside>
            </div>

            @if($relatedCourses->isNotEmpty())
                <section class="course-detail-panel" style="margin-top:28px;">
                    <h2>Related Courses</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:16px;">
                        @foreach($relatedCourses as $relatedCourse)
                            <a href="{{ route('courses.show', $relatedCourse->slug) }}" style="color:#1f6fe5;text-decoration:none;font-weight:800;">
                                {{ $relatedCourse->title }}
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection

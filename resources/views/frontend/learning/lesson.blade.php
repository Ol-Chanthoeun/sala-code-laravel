@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
    <style>
        .learning-page {
            background: #f8fbff;
            min-height: 100vh;
            overflow-x: hidden;
            padding: 96px 0 0;
        }

        .learning-layout {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            min-height: calc(100vh - 96px);
        }

        .learning-sidebar {
            background: #ffffff;
            border-right: 1px solid #dbe4f0;
            padding: 24px;
            position: sticky;
            top: 72px;
            height: calc(100vh - 72px);
            overflow-y: auto;
        }

        .learning-sidebar h2 {
            color: #0f172a;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .lesson-sidebar-toggle,
        .lesson-sidebar-close {
            display: none;
        }

        .course-section {
            border-top: 1px solid #e2e8f0;
            padding: 14px 0;
        }

        .course-section h5 {
            color: #334155;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .course-section a {
            border-radius: 6px;
            color: #475569;
            display: block;
            padding: 9px 10px;
            text-decoration: none;
        }

        .course-section a.active,
        .course-section a:hover {
            background: #1f6fe5;
            color: #ffffff;
        }

        .lesson-main {
            padding: 34px;
        }

        .lesson-card {
            background: #ffffff;
            border: 1px solid #dbe4f0;
            border-radius: 8px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
            max-width: 980px;
            min-width: 0;
            padding: 30px;
            width: 100%;
        }

        .lesson-card h1 {
            color: #0f172a;
            font-size: 34px;
            margin-bottom: 10px;
        }

        .lesson-meta {
            color: #64748b;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }

        .lesson-meta span {
            background: #e8f1ff;
            border-radius: 6px;
            color: #1e40af;
            font-weight: 700;
            padding: 6px 9px;
        }

        .lesson-content {
            color: #1f2937;
            line-height: 1.75;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: normal;
        }

        .lesson-content h2,
        .lesson-content h3 {
            margin: 18px 0 10px;
        }

        pre {
            background: #0f172a;
            border-radius: 8px;
            color: #e2e8f0;
            overflow-x: auto;
            max-width: 100%;
            padding: 18px;
            white-space: pre;
            -webkit-overflow-scrolling: touch;
        }

        pre code { white-space: inherit; }

        .lesson-content img,
        .lesson-card img,
        .lesson-content video,
        .lesson-card video,
        .lesson-content iframe {
            height: auto;
            max-width: 100%;
        }

        .lesson-content table,
        .lesson-card table {
            display: block;
            max-width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .expected-output {
            margin-top: 24px;
        }

        .lesson-video {
            aspect-ratio: 16 / 9;
            margin-top: 24px;
            width: 100%;
        }

        .lesson-video iframe {
            border: 0;
            border-radius: 8px;
            height: 100%;
            width: 100%;
        }

        .lesson-section {
            border-top: 1px solid #e2e8f0;
            margin-top: 24px;
            padding-top: 20px;
        }

        .lesson-section h3 {
            color: #0f172a;
            margin-bottom: 10px;
        }

        .lesson-quiz-item {
            border: 1px solid #dbe4f0;
            border-radius: 8px;
            margin-top: 12px;
            padding: 14px;
        }

        .lesson-quiz-item ol {
            margin: 10px 0 0 22px;
        }

        .lesson-navigation {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: space-between;
            margin-top: 28px;
        }

        .lesson-navigation a {
            background: #1f6fe5;
            border-radius: 6px;
            color: #ffffff;
            font-weight: 800;
            padding: 11px 14px;
            text-decoration: none;
        }

        .lesson-navigation .back-link {
            background: #475569;
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .learning-layout {
                grid-template-columns: 260px minmax(0, 1fr);
            }

            .learning-sidebar { padding: 20px 16px; }
            .lesson-main { padding: 24px; }
            .lesson-card { padding: 24px; }
            .lesson-card h1 { font-size: 30px; }
        }

        @media (max-width: 767px) {
            body.lesson-sidebar-open { overflow: hidden; }

            .learning-layout {
                grid-template-columns: 1fr;
            }

            .learning-sidebar {
                box-shadow: 8px 0 28px rgba(15, 23, 42, 0.2);
                height: 100dvh;
                left: 0;
                max-width: 86vw;
                padding: 22px 18px;
                position: fixed;
                top: 0;
                transform: translateX(-105%);
                transition: transform .25s ease;
                width: 320px;
                z-index: 1200;
            }

            .learning-sidebar.is-open { transform: translateX(0); }

            .lesson-sidebar-backdrop {
                background: rgba(15, 23, 42, .45);
                inset: 0;
                opacity: 0;
                pointer-events: none;
                position: fixed;
                transition: opacity .2s ease;
                z-index: 1190;
            }

            .lesson-sidebar-backdrop.is-open {
                opacity: 1;
                pointer-events: auto;
            }

            .lesson-sidebar-toggle {
                align-items: center;
                background: #1f6fe5;
                border: 0;
                border-radius: 6px;
                color: #fff;
                display: inline-flex;
                font-weight: 800;
                gap: 8px;
                margin: 16px 16px 0;
                padding: 10px 14px;
            }

            .lesson-sidebar-close {
                align-items: center;
                background: transparent;
                border: 0;
                color: #334155;
                display: inline-flex;
                float: right;
                font-size: 28px;
                height: 34px;
                justify-content: center;
                margin: -8px -4px 8px 8px;
                width: 34px;
            }

            .lesson-main {
                min-width: 0;
                padding: 16px;
            }

            .lesson-card { padding: 20px 16px; }
            .lesson-card h1 { font-size: 26px; line-height: 1.35; }
            .lesson-card h2 { font-size: 22px; line-height: 1.4; }
            .lesson-card h3 { font-size: 19px; line-height: 1.45; }
            .lesson-content, .lesson-card p, .lesson-card li { line-height: 1.8; }
            .lesson-meta { align-items: flex-start; margin-bottom: 18px; }
            .lesson-meta span { line-height: 1.35; }
            .lesson-navigation { flex-direction: column; }
            .lesson-navigation > div,
            .lesson-navigation a { width: 100%; }
            .lesson-navigation a { display: block; text-align: center; }
        }
    </style>
@endpush

@section('content')
    <main class="learning-page">
        <button class="lesson-sidebar-toggle" id="lessonSidebarToggle" type="button" aria-controls="lessonSidebar" aria-expanded="false">
            <i class="bx bx-menu"></i> Lessons
        </button>
        <div class="lesson-sidebar-backdrop" id="lessonSidebarBackdrop"></div>
        <div class="learning-layout">
            <aside class="learning-sidebar" id="lessonSidebar">
                <button class="lesson-sidebar-close" id="lessonSidebarClose" type="button" aria-label="Close lessons">&times;</button>
                <h2>{{ $course->title }}</h2>

                @foreach ($sections as $section)
                    <div class="course-section">
                        <h5>{{ $section->title }}</h5>

                        @foreach ($section->lessons as $item)
                            <a href="{{ route('courses.lessons.show', [
                                'course' => $course->slug,
                                'lesson' => $item->slug,
                            ]) }}"
                               class="{{ $item->id === $lesson->id ? 'active' : '' }}">
                                {{ $item->title }}
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </aside>

            <section class="lesson-main">
                <article class="lesson-card">
                    <div class="lesson-meta">
                        <span>{{ $course->title }}</span>
                        @if($lesson->section)
                            <span>{{ $lesson->section->title }}</span>
                        @endif
                        <span>{{ $lesson->difficulty_level ?? 'Beginner' }}</span>
                        <span>{{ $lesson->estimated_learning_time ?? 20 }} minutes</span>
                    </div>

                    <h1>{{ $lesson->title }}</h1>

                    @if($lesson->short_description)
                        <p style="color:#475569;line-height:1.7;margin-bottom:20px;">{{ $lesson->short_description }}</p>
                    @endif

                    <div class="lesson-content">
                        {!! $lesson->lesson_content !!}
                    </div>

                    @if ($lesson->source_code)
                        <h3 style="margin-top:24px;">Source Code</h3>
                        <pre><code class="language-c">{{ $lesson->source_code }}</code></pre>
                    @endif

                    @if ($lesson->expected_output)
                        <div class="expected-output">
                            <h3>Expected Output</h3>
                            <pre>{{ $lesson->expected_output }}</pre>
                        </div>
                    @endif

                    @if ($lesson->explanation)
                        <div class="lesson-section lesson-content">
                            <h3>Explanation</h3>
                            <p>{{ $lesson->explanation }}</p>
                        </div>
                    @endif

                    @foreach([
                        'code_explanation' => 'ការពន្យល់កូដ',
                        'common_mistakes' => 'កំហុសដែលជួបញឹកញាប់',
                        'tips' => 'គន្លឹះ និងអនុវត្តល្អ',
                        'summary' => 'សេចក្ដីសង្ខេប',
                        'exercise' => 'លំហាត់អនុវត្ត',
                    ] as $field => $heading)
                        @if($lesson->{$field})
                            <div class="lesson-section lesson-content">
                                <h3>{{ $heading }}</h3>
                                <div>{!! nl2br(e($lesson->{$field})) !!}</div>
                            </div>
                        @endif
                    @endforeach

                    @if(! empty($lesson->quiz))
                        <div class="lesson-section lesson-content">
                            <h3>សំណួរសាកល្បង</h3>
                            @foreach($lesson->quiz as $quizItem)
                                <div class="lesson-quiz-item">
                                    <p><strong>{{ $quizItem['question'] ?? '' }}</strong></p>
                                    <ol>
                                        @foreach($quizItem['options'] ?? [] as $option)
                                            <li>{{ $option }}</li>
                                        @endforeach
                                    </ol>
                                    <p style="margin-top:10px;"><strong>ចម្លើយត្រឹមត្រូវ:</strong> {{ $quizItem['answer'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($videoEmbedUrl)
                        <div class="lesson-video">
                            <iframe src="{{ $videoEmbedUrl }}" allowfullscreen></iframe>
                        </div>
                    @endif

                    <div class="lesson-navigation">
                        <div>
                            @if ($previousLesson)
                                <a href="{{ route('courses.lessons.show', [
                                    'course' => $course->slug,
                                    'lesson' => $previousLesson->slug,
                                ]) }}">
                                    Previous Lesson
                                </a>
                            @endif
                        </div>

                        <a class="back-link" href="{{ route('courses.show', $course->slug) }}">
                            Back to Course
                        </a>

                        <div>
                            @if ($nextLesson)
                                <a href="{{ route('courses.lessons.show', [
                                    'course' => $course->slug,
                                    'lesson' => $nextLesson->slug,
                                ]) }}">
                                    Next Lesson
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script>
        (() => {
            const toggle = document.getElementById('lessonSidebarToggle');
            const close = document.getElementById('lessonSidebarClose');
            const sidebar = document.getElementById('lessonSidebar');
            const backdrop = document.getElementById('lessonSidebarBackdrop');
            if (!toggle || !close || !sidebar || !backdrop) return;

            const setOpen = (open) => {
                sidebar.classList.toggle('is-open', open);
                backdrop.classList.toggle('is-open', open);
                document.body.classList.toggle('lesson-sidebar-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            toggle.addEventListener('click', () => setOpen(true));
            close.addEventListener('click', () => setOpen(false));
            backdrop.addEventListener('click', () => setOpen(false));
            sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) setOpen(false);
            });
        })();
    </script>
@endpush

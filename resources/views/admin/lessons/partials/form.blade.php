@if($errors->any())
    <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
@endif

<div class="system-info">
    <div class="section-title">{{ $lesson ? 'Edit Lesson' : 'Create Lesson' }}</div>

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <p>
                <label>Course</label><br>
                <select id="course_id" name="course_id" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id', $lesson->course_id ?? '') == $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
            </p>

            <p>
                <label>Section</label><br>
                <select id="section_id" name="section_id" style="width:100%;padding:12px;margin-top:8px;">
                    <option value="">No section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" data-course-id="{{ $section->course_id }}" @selected(old('section_id', $lesson->section_id ?? '') == $section->id)>
                            {{ $section->course?->title }} - {{ $section->title }}
                        </option>
                    @endforeach
                </select>
            </p>
        </div>

        <p style="margin-top:15px;">
            <label>Lesson title</label><br>
            <input type="text" name="title" value="{{ old('title', $lesson->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug', $lesson->slug ?? '') }}" placeholder="Auto-generated if blank" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Short description</label><br>
            <textarea name="short_description" rows="3" style="width:100%;padding:12px;margin-top:8px;">{{ old('short_description', $lesson->short_description ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Full explanation</label><br>
            <textarea id="lesson_content" name="lesson_content" rows="8" style="width:100%;padding:12px;margin-top:8px;">{{ old('lesson_content', $lesson->lesson_content ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Source code</label><br>
            <textarea name="source_code" rows="8" style="width:100%;padding:12px;margin-top:8px;font-family:Consolas,monospace;">{{ old('source_code', $lesson->source_code ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Expected output</label><br>
            <textarea name="expected_output" rows="4" style="width:100%;padding:12px;margin-top:8px;font-family:Consolas,monospace;">{{ old('expected_output', $lesson->expected_output ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>General explanation</label><br>
            <textarea name="explanation" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('explanation', $lesson->explanation ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Code explanation</label><br>
            <textarea name="code_explanation" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('code_explanation', $lesson->code_explanation ?? '') }}</textarea>
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px;">
            <p>
                <label>Common mistakes</label><br>
                <textarea name="common_mistakes" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('common_mistakes', $lesson->common_mistakes ?? '') }}</textarea>
            </p>

            <p>
                <label>Tips and best practices</label><br>
                <textarea name="tips" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('tips', $lesson->tips ?? '') }}</textarea>
            </p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px;">
            <p>
                <label>Summary</label><br>
                <textarea name="summary" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('summary', $lesson->summary ?? '') }}</textarea>
            </p>

            <p>
                <label>Exercise</label><br>
                <textarea name="exercise" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('exercise', $lesson->exercise ?? '') }}</textarea>
            </p>
        </div>

        @php
            $quizItems = old('quiz', $lesson->quiz ?? []);
            $quizItems = array_pad(is_array($quizItems) ? $quizItems : [], 4, [
                'question' => '',
                'options' => ['', '', '', ''],
                'answer' => '',
            ]);
        @endphp

        <div class="section-title" style="margin-top:25px;">Quiz Questions</div>

        @foreach(array_slice($quizItems, 0, 4) as $quizIndex => $quizItem)
            @php
                $options = array_pad($quizItem['options'] ?? [], 4, '');
            @endphp
            <div style="border:1px solid #eef2f6;border-radius:8px;padding:15px;margin-top:12px;">
                <p>
                    <label>Question {{ $quizIndex + 1 }}</label><br>
                    <input type="text" name="quiz[{{ $quizIndex }}][question]" value="{{ $quizItem['question'] ?? '' }}" style="width:100%;padding:12px;margin-top:8px;">
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px;">
                    @foreach($options as $optionIndex => $option)
                        <p>
                            <label>Option {{ $optionIndex + 1 }}</label><br>
                            <input type="text" name="quiz[{{ $quizIndex }}][options][{{ $optionIndex }}]" value="{{ $option }}" style="width:100%;padding:12px;margin-top:8px;">
                        </p>
                    @endforeach
                </div>

                <p style="margin-top:12px;">
                    <label>Correct answer</label><br>
                    <input type="text" name="quiz[{{ $quizIndex }}][answer]" value="{{ $quizItem['answer'] ?? '' }}" style="width:100%;padding:12px;margin-top:8px;">
                </p>
            </div>
        @endforeach

        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr;gap:15px;margin-top:15px;">
            <p>
                <label>Video URL</label><br>
                <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..." style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Difficulty</label><br>
                <select name="difficulty_level" style="width:100%;padding:12px;margin-top:8px;">
                    @foreach(['Beginner', 'Intermediate', 'Advanced'] as $difficulty)
                        <option value="{{ $difficulty }}" @selected(old('difficulty_level', $lesson->difficulty_level ?? 'Beginner') === $difficulty)>{{ $difficulty }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Minutes</label><br>
                <input type="number" name="estimated_learning_time" value="{{ old('estimated_learning_time', $lesson->estimated_learning_time ?? 20) }}" min="1" max="600" style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Lesson order</label><br>
                <input type="number" name="order_number" value="{{ old('order_number', $lesson->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Status</label><br>
                <select name="status" required style="width:100%;padding:12px;margin-top:8px;">
                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'unpublished' => 'Unpublished'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $lesson->status ?? 'draft') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </p>
        </div>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            {{ $buttonText }}
        </button>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        const courseSelect = document.querySelector('#course_id');
        const sectionSelect = document.querySelector('#section_id');

        function filterSectionsByCourse() {
            const selectedCourseId = courseSelect.value;

            sectionSelect.querySelectorAll('option[data-course-id]').forEach(function (option) {
                const visible = !selectedCourseId || option.dataset.courseId === selectedCourseId;
                option.hidden = !visible;

                if (!visible && option.selected) {
                    sectionSelect.value = '';
                }
            });
        }

        courseSelect?.addEventListener('change', filterSectionsByCourse);
        filterSectionsByCourse();

        if (window.ClassicEditor) {
            ClassicEditor.create(document.querySelector('#lesson_content')).catch(function (error) {
                console.error(error);
            });
        }
    </script>
@endpush

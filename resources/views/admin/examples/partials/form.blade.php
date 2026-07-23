@if($errors->any())
    <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
@endif

<div class="system-info">
    <div class="section-title">{{ $example ? 'Edit Code Example' : 'Create Code Example' }}</div>

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <p>
            <label>Lesson</label><br>
            <select name="lesson_id" required style="width:100%;padding:12px;margin-top:8px;">
                <option value="">-- Select Lesson --</option>
                @foreach($lessons as $lesson)
                    <option value="{{ $lesson->id }}" @selected(old('lesson_id', $example->lesson_id ?? '') == $lesson->id)>
                        {{ $lesson->course?->title }} - {{ $lesson->title }}
                    </option>
                @endforeach
            </select>
        </p>

        <p style="margin-top:15px;">
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title', $example->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Source code</label><br>
            <textarea name="source_code" rows="9" style="width:100%;padding:12px;margin-top:8px;font-family:Consolas,monospace;">{{ old('source_code', $example->source_code ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Expected output</label><br>
            <textarea name="expected_output" rows="4" style="width:100%;padding:12px;margin-top:8px;font-family:Consolas,monospace;">{{ old('expected_output', $example->expected_output ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Explanation</label><br>
            <textarea name="explanation" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('explanation', $example->explanation ?? '') }}</textarea>
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            {{ $buttonText }}
        </button>
    </form>
</div>

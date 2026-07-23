@if($errors->any())
    <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
@endif

<div class="system-info">
    <div class="section-title">{{ $course ? 'Edit Course' : 'Create New Course' }}</div>

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <p>
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title', $course->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug', $course->slug ?? '') }}" placeholder="Auto-generated if blank" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Short description</label><br>
            <textarea name="short_description" rows="3" style="width:100%;padding:12px;margin-top:8px;">{{ old('short_description', $course->short_description ?? $course->description ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Full description</label><br>
            <textarea name="full_description" rows="7" style="width:100%;padding:12px;margin-top:8px;">{{ old('full_description', $course->full_description ?? '') }}</textarea>
        </p>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-top:15px;">
            <p>
                <label>Programming language</label><br>
                <input type="text" name="programming_language" value="{{ old('programming_language', $course->programming_language ?? '') }}" placeholder="C, C++, Java, Python" required style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Difficulty level</label><br>
                <select name="difficulty_level" required style="width:100%;padding:12px;margin-top:8px;">
                    @foreach(['Beginner','Intermediate','Advanced'] as $level)
                        <option value="{{ $level }}" @selected(old('difficulty_level', $course->difficulty_level ?? 'Beginner') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Status</label><br>
                <select name="status" required style="width:100%;padding:12px;margin-top:8px;">
                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'unpublished' => 'Unpublished'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $course->status ?? 'draft') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </p>
        </div>

        <p style="margin-top:15px;">
            <label>Thumbnail</label><br>
            <input type="file" name="thumbnail" accept="image/png,image/jpeg,image/webp" style="margin-top:8px;">
            @if($course && ($course->thumbnail || $course->image))
                <br><img src="{{ asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) }}" width="120" style="margin-top:10px;" alt="{{ $course->title }}">
            @endif
        </p>

        <p style="margin-top:15px;">
            <label>Price</label><br>
            <input type="text" name="price" value="{{ old('price', $course->price ?? 'Free') }}" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            {{ $buttonText }}
        </button>
    </form>
</div>

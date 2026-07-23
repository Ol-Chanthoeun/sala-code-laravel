@if($errors->any())
    <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
@endif

<div class="system-info">
    <div class="section-title">{{ $section ? 'Edit Section' : 'Create Course Section' }}</div>

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <p>
            <label>Course</label><br>
            <select name="course_id" required style="width:100%;padding:12px;margin-top:8px;">
                <option value="">-- Select Course --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $section->course_id ?? '') == $course->id)>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
        </p>

        <p style="margin-top:15px;">
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title', $section->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Description</label><br>
            <textarea name="description" rows="4" style="width:100%;padding:12px;margin-top:8px;">{{ old('description', $section->description ?? '') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>Order number</label><br>
            <input type="number" name="order_number" value="{{ old('order_number', $section->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            {{ $buttonText }}
        </button>
    </form>
</div>

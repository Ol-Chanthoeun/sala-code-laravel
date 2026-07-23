@extends('layouts.admin')

@section('title', $quiz ? 'Edit Quiz' : 'Add Quiz')
@section('page-title', $quiz ? 'Edit Quiz' : 'Add Quiz')
@section('breadcrumb', 'Quizzes')

@section('content')
    @if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif
    <div class="system-info">
        <div class="section-title">{{ $quiz ? 'Edit Quiz' : 'Create Quiz' }}</div>
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method !== 'POST') @method($method) @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <p><label>Language</label><br><select id="quiz_language" name="programming_language_id" required style="width:100%;padding:12px;margin-top:8px;">@foreach($languages as $language)<option value="{{ $language->id }}" @selected(old('programming_language_id', $quiz->programming_language_id ?? '') == $language->id)>{{ $language->name }}</option>@endforeach</select></p>
                <p><label>Category</label><br><select id="quiz_category" name="quiz_category_id" required style="width:100%;padding:12px;margin-top:8px;">@foreach($categories as $category)<option value="{{ $category->id }}" data-language-id="{{ $category->programming_language_id }}" @selected(old('quiz_category_id', $quiz->quiz_category_id ?? '') == $category->id)>{{ $category->programmingLanguage?->name }} - {{ $category->title }}</option>@endforeach</select></p>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px;">
                <p><label>Title</label><br><input name="title" value="{{ old('title', $quiz->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Slug</label><br><input name="slug" value="{{ old('slug', $quiz->slug ?? '') }}" placeholder="auto if blank" style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>
            <p style="margin-top:15px;"><label>Description</label><br><textarea name="description" rows="4" style="width:100%;padding:12px;margin-top:8px;">{{ old('description', $quiz->description ?? '') }}</textarea></p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:15px;margin-top:15px;">
                <p><label>Difficulty</label><br><select name="difficulty" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['Easy','Medium','Hard'] as $value)<option value="{{ $value }}" @selected(old('difficulty', $quiz->difficulty ?? 'Easy') === $value)>{{ $value }}</option>@endforeach</select></p>
                <p><label>Minutes</label><br><input type="number" name="estimated_time" value="{{ old('estimated_time', $quiz->estimated_time ?? 15) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Passing %</label><br><input type="number" name="passing_score" value="{{ old('passing_score', $quiz->passing_score ?? 60) }}" min="1" max="100" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Status</label><br><select name="status" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['draft','published','archived'] as $value)<option value="{{ $value }}" @selected(old('status', $quiz->status ?? 'published') === $value)>{{ ucfirst($value) }}</option>@endforeach</select></p>
                <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', $quiz->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>
            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">Save Quiz</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const languageSelect = document.querySelector('#quiz_language');
        const categorySelect = document.querySelector('#quiz_category');
        function filterQuizCategories() {
            const languageId = languageSelect.value;
            categorySelect.querySelectorAll('option[data-language-id]').forEach((option) => {
                const visible = option.dataset.languageId === languageId;
                option.hidden = !visible;
                if (!visible && option.selected) categorySelect.value = '';
            });
        }
        languageSelect?.addEventListener('change', filterQuizCategories);
        filterQuizCategories();
    </script>
@endpush

@extends('layouts.admin')

@section('title', $question ? 'Edit Question' : 'Add Question')
@section('page-title', $question ? 'Edit Question' : 'Add Question')
@section('breadcrumb', 'Quiz Questions')

@section('content')
    @if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif
    @php
        $choices = old('choices', $question?->choices?->map(fn($choice) => ['choice_text' => $choice->choice_text])->toArray() ?? []);
        $choices = array_pad($choices, 4, ['choice_text' => '']);
        $correctIndex = old('correct_choice');
        if ($correctIndex === null && $question) {
            $correctIndex = $question->choices->search(fn($choice) => $choice->id === $question->correct_choice_id);
            $correctIndex = $correctIndex === false ? 0 : $correctIndex;
        }
        $correctIndex = $correctIndex ?? 0;
    @endphp
    <div class="system-info">
        <div class="section-title">{{ $question ? 'Edit Question' : 'Create Question' }}</div>
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method !== 'POST') @method($method) @endif
            <p><label>Quiz</label><br><select name="quiz_id" required style="width:100%;padding:12px;margin-top:8px;">@foreach($quizzes as $quiz)<option value="{{ $quiz->id }}" @selected(old('quiz_id', $question->quiz_id ?? '') == $quiz->id)>{{ $quiz->programmingLanguage?->name }} / {{ $quiz->category?->title }} / {{ $quiz->title }}</option>@endforeach</select></p>
            <p style="margin-top:15px;"><label>Question</label><br><textarea name="question" rows="4" required style="width:100%;padding:12px;margin-top:8px;">{{ old('question', $question->question ?? '') }}</textarea></p>
            <p style="margin-top:15px;"><label>Explanation</label><br><textarea name="explanation" rows="4" style="width:100%;padding:12px;margin-top:8px;">{{ old('explanation', $question->explanation ?? '') }}</textarea></p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-top:15px;">
                <p><label>Difficulty</label><br><select name="difficulty" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['Easy','Medium','Hard'] as $value)<option value="{{ $value }}" @selected(old('difficulty', $question->difficulty ?? 'Easy') === $value)>{{ $value }}</option>@endforeach</select></p>
                <p><label>Points</label><br><input type="number" name="points" value="{{ old('points', $question->points ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', $question->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>
            <div class="section-title" style="margin-top:22px;">Choices</div>
            @foreach(array_slice($choices, 0, 6) as $index => $choice)
                <div style="display:grid;grid-template-columns:auto 1fr;gap:10px;align-items:end;margin-top:12px;">
                    <label style="padding-bottom:12px;"><input type="radio" name="correct_choice" value="{{ $index }}" @checked((string)$correctIndex === (string)$index)> Correct</label>
                    <p><label>Choice {{ $index + 1 }}</label><br><input name="choices[{{ $index }}][choice_text]" value="{{ $choice['choice_text'] ?? '' }}" required style="width:100%;padding:12px;margin-top:8px;"></p>
                </div>
            @endforeach
            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">Save Question</button>
        </form>
    </div>
@endsection

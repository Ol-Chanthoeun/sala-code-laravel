@extends('layouts.admin')

@section('title', $category ? 'Edit Category' : 'Add Category')
@section('page-title', $category ? 'Edit Category' : 'Add Category')
@section('breadcrumb', 'Quiz Categories')

@section('content')
    @if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif
    <div class="system-info">
        <div class="section-title">{{ $category ? 'Edit Quiz Category' : 'Create Quiz Category' }}</div>
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method !== 'POST') @method($method) @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <p><label>Language</label><br><select name="programming_language_id" required style="width:100%;padding:12px;margin-top:8px;">@foreach($languages as $language)<option value="{{ $language->id }}" @selected(old('programming_language_id', $category->programming_language_id ?? '') == $language->id)>{{ $language->name }}</option>@endforeach</select></p>
                <p><label>Title</label><br><input name="title" value="{{ old('title', $category->title ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>
            <p style="margin-top:15px;"><label>Slug</label><br><input name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="auto if blank" style="width:100%;padding:12px;margin-top:8px;"></p>
            <p style="margin-top:15px;"><label>Description</label><br><textarea name="description" rows="4" style="width:100%;padding:12px;margin-top:8px;">{{ old('description', $category->description ?? '') }}</textarea></p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-top:15px;">
                <p><label>Difficulty</label><br><select name="difficulty" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['Easy','Medium','Hard'] as $value)<option value="{{ $value }}" @selected(old('difficulty', $category->difficulty ?? 'Easy') === $value)>{{ $value }}</option>@endforeach</select></p>
                <p><label>Status</label><br><select name="status" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['draft','published','archived'] as $value)<option value="{{ $value }}" @selected(old('status', $category->status ?? 'published') === $value)>{{ ucfirst($value) }}</option>@endforeach</select></p>
                <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', $category->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>
            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">Save Category</button>
        </form>
    </div>
@endsection

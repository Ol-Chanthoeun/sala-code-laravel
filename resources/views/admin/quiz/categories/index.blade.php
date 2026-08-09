@extends('layouts.admin')

@section('title', 'Quiz Categories')
@section('page-title', 'Quiz Categories')
@section('breadcrumb', 'Quiz Categories')

@section('content')
    <div class="admin-sticky-toolbar">
        <button type="button" class="action-btn admin-primary-action category-form-trigger" data-category-id="" style="width:220px;margin-bottom:20px;border:0;cursor:pointer;"><i class="fas fa-plus-circle"></i> Add Category</button>
    </div>
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    <div class="data-table">
        <div class="table-header"><h3>Quiz Categories</h3></div>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Language</th><th>Order</th><th>Title</th><th>Difficulty</th><th>Status</th><th>Quizzes</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->programmingLanguage?->name }}</td>
                            <td>{{ $category->order_number }}</td>
                            <td><strong>{{ $category->title }}</strong><br><small>{{ $category->slug }}</small></td>
                            <td>{{ $category->difficulty }}</td>
                            <td>{{ ucfirst($category->status) }}</td>
                            <td>{{ $category->quizzes_count }}</td>
                            <td><div class="admin-table-actions"><button class="admin-icon-btn admin-icon-btn--edit category-form-trigger" type="button" data-category-id="{{ $category->id }}" title="Edit Category" aria-label="Edit Category"><i class="fas fa-pen" aria-hidden="true"></i></button><form class="admin-destructive-form" action="{{ route('admin.quiz-categories.destroy', $category) }}" method="POST" data-confirm-title="Confirm Delete Quiz Category" data-confirm-message="Are you sure you want to delete this quiz category?" data-confirm-item="{{ $category->title }}" data-confirm-label="Delete Category">@csrf @method('DELETE')<button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Category" aria-label="Delete Category"><i class="fas fa-trash" aria-hidden="true"></i></button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $categories->links('admin.partials.pagination') }}
    <dialog class="admin-confirm-dialog" id="categoryFormDialog"><div class="admin-confirm-dialog__panel edit-user-dialog__panel"><div class="admin-confirm-dialog__header"><div class="admin-confirm-dialog__title"><i class="fas fa-tags"></i><h3 id="categoryFormTitle">Add Quiz Category</h3></div><button class="admin-confirm-dialog__close" id="closeCategoryForm" type="button">&times;</button></div><div class="edit-user-dialog__errors" id="categoryFormErrors" hidden></div><form class="edit-user-form" id="categoryForm" method="POST">@csrf<input id="categoryMethod" type="hidden" name="_method" value="PUT" disabled><input id="categoryMode" type="hidden" name="category_modal_mode"><input id="categoryId" type="hidden" name="category_modal_id"><label>Language<select id="categoryLanguage" name="programming_language_id" required>@foreach($languages as $language)<option value="{{ $language->id }}">{{ $language->name }}</option>@endforeach</select></label><label>Title<input id="categoryTitle" name="title" required maxlength="255"></label><label>Slug<input id="categorySlug" name="slug" maxlength="255"></label><label>Description<textarea id="categoryDescription" name="description" rows="4"></textarea></label><label>Difficulty<select id="categoryDifficulty" name="difficulty" required><option>Easy</option><option>Medium</option><option>Hard</option></select></label><label>Status<select id="categoryStatus" name="status" required><option value="draft">Draft</option><option value="published">Published</option><option value="archived">Archived</option></select></label><label>Order<input id="categoryOrder" type="number" name="order_number" min="1" required></label><div class="admin-confirm-dialog__actions"><button class="admin-confirm-dialog__cancel" id="cancelCategoryForm" type="button">Cancel</button><button class="admin-confirm-dialog__confirm" id="submitCategoryForm">Create Category</button></div></form></div></dialog>
@endsection
@push('scripts')<script>(()=>{@php $categoryData=$categories->getCollection()->mapWithKeys(fn($c)=>[(string)$c->id=>['id'=>$c->id,'programming_language_id'=>$c->programming_language_id,'title'=>$c->title,'slug'=>$c->slug,'description'=>$c->description,'difficulty'=>$c->difficulty,'status'=>$c->status,'order_number'=>$c->order_number,'update_url'=>route('admin.quiz-categories.update',$c)]]); $failedCategory=old('category_modal_mode')?['mode'=>old('category_modal_mode'),'id'=>(string)old('category_modal_id'),'values'=>['programming_language_id'=>old('programming_language_id'),'title'=>old('title'),'slug'=>old('slug'),'description'=>old('description'),'difficulty'=>old('difficulty'),'status'=>old('status'),'order_number'=>old('order_number')],'errors'=>$errors->all()]:null; @endphp const items=@json($categoryData),failed=@json($failedCategory),dialog=document.getElementById('categoryFormDialog'),form=document.getElementById('categoryForm'),method=document.getElementById('categoryMethod'),fields={programming_language_id:document.getElementById('categoryLanguage'),title:document.getElementById('categoryTitle'),slug:document.getElementById('categorySlug'),description:document.getElementById('categoryDescription'),difficulty:document.getElementById('categoryDifficulty'),status:document.getElementById('categoryStatus'),order_number:document.getElementById('categoryOrder')};function open(mode,item={},values={},errors=[]){let edit=mode==='edit';form.action=edit?item.update_url:@json(route('admin.quiz-categories.store'));method.disabled=!edit;categoryMode.value=mode;categoryId.value=edit?item.id:'';categoryFormTitle.textContent=edit?'Edit Quiz Category':'Add Quiz Category';submitCategoryForm.textContent=edit?'Save Changes':'Create Category';let defaults=edit?item:{difficulty:'Easy',status:'published',order_number:1};Object.entries(fields).forEach(([n,f])=>f.value=values[n]??defaults[n]??'');categoryFormErrors.replaceChildren(...errors.map(e=>{let p=document.createElement('p');p.textContent=e;return p}));categoryFormErrors.hidden=!errors.length;dialog.showModal();document.body.classList.add('admin-modal-open')}document.querySelectorAll('.category-form-trigger').forEach(t=>t.onclick=()=>{let item=items[t.dataset.categoryId];open(item?'edit':'create',item)});cancelCategoryForm.onclick=closeCategoryForm.onclick=()=>dialog.close();dialog.onclick=e=>{if(e.target===dialog)dialog.close()};dialog.onclose=()=>document.body.classList.remove('admin-modal-open');if(failed)open(failed.mode,items[failed.id]||{},failed.values,failed.errors)})();</script>@endpush

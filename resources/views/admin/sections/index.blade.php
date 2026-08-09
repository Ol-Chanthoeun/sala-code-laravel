@extends('layouts.admin')

@section('title', 'Course Sections')
@section('page-title', 'Course Sections')
@section('breadcrumb', 'Course Sections')

@section('content')
    <div class="admin-sticky-toolbar">
        <button type="button" class="action-btn admin-primary-action section-form-trigger" data-section-id="" style="width:210px;margin-bottom:20px;border:0;cursor:pointer;">
            <i class="fas fa-plus-circle"></i> Add Section
        </button>
    </div>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Course Sections</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Order</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>{{ $section->course?->title }}</td>
                            <td>{{ $section->order_number }}</td>
                            <td>{{ $section->title }}</td>
                            <td>{{ Str::limit($section->description, 80) }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <button class="admin-icon-btn admin-icon-btn--edit section-form-trigger" type="button" data-section-id="{{ $section->id }}" title="Edit Section" aria-label="Edit Section"><i class="fas fa-pen" aria-hidden="true"></i></button>
                                    <form class="admin-destructive-form" action="{{ route('admin.sections.destroy', $section) }}" method="POST" data-confirm-title="Confirm Delete Section" data-confirm-message="Are you sure you want to delete this section? Lessons will keep the course but lose this section." data-confirm-item="{{ $section->title }} — {{ $section->course?->title }}" data-confirm-label="Delete Section">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Section" aria-label="Delete Section"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">No sections found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $sections->links('admin.partials.pagination') }}

    <dialog class="admin-confirm-dialog" id="sectionFormDialog" aria-labelledby="sectionFormDialogTitle">
        <div class="admin-confirm-dialog__panel edit-user-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-layer-group" aria-hidden="true"></i><h3 id="sectionFormDialogTitle">Add Section</h3></div>
                <button class="admin-confirm-dialog__close" id="closeSectionForm" type="button" aria-label="Close">&times;</button>
            </div>
            <div class="edit-user-dialog__errors" id="sectionFormErrors" hidden></div>
            <form class="edit-user-form" id="sectionForm" method="POST">
                @csrf
                <input id="sectionFormMethod" type="hidden" name="_method" value="PUT" disabled>
                <input id="sectionModalMode" type="hidden" name="section_modal_mode" value="create">
                <input id="sectionModalId" type="hidden" name="section_modal_id">
                <label>Course<select id="sectionFormCourse" name="course_id" required><option value="">-- Select Course --</option>@foreach($courses as $course)<option value="{{ $course->id }}">{{ $course->title }}</option>@endforeach</select></label>
                <label>Order Number<input id="sectionFormOrder" type="number" name="order_number" min="1" required></label>
                <label>Title<input id="sectionFormTitle" type="text" name="title" maxlength="255" required></label>
                <label>Description<textarea id="sectionFormDescription" name="description" rows="4"></textarea></label>
                <div class="admin-confirm-dialog__actions">
                    <button class="admin-confirm-dialog__cancel" id="cancelSectionForm" type="button">Cancel</button>
                    <button class="admin-confirm-dialog__confirm" id="submitSectionForm" type="submit">Create Section</button>
                </div>
            </form>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script>
        (() => {
            @php
                $sectionModalData = $sections->getCollection()->mapWithKeys(fn ($section) => [(string) $section->id => [
                    'id' => $section->id, 'course_id' => $section->course_id, 'order_number' => $section->order_number,
                    'title' => $section->title, 'description' => $section->description,
                    'update_url' => route('admin.sections.update', $section),
                ]]);
                $failedSectionForm = old('section_modal_mode') ? [
                    'mode' => old('section_modal_mode'), 'id' => (string) old('section_modal_id'),
                    'values' => ['course_id' => old('course_id'), 'order_number' => old('order_number'), 'title' => old('title'), 'description' => old('description')],
                    'errors' => $errors->all(),
                ] : null;
            @endphp
            const sections = @json($sectionModalData);
            const failedSectionForm = @json($failedSectionForm);
            const dialog = document.getElementById('sectionFormDialog');
            const form = document.getElementById('sectionForm');
            const method = document.getElementById('sectionFormMethod');
            const fields = {
                course_id: document.getElementById('sectionFormCourse'), order_number: document.getElementById('sectionFormOrder'),
                title: document.getElementById('sectionFormTitle'), description: document.getElementById('sectionFormDescription'),
            };

            const openSectionForm = (mode, section = {}, values = {}, errors = []) => {
                const editing = mode === 'edit';
                form.action = editing ? section.update_url : @json(route('admin.sections.store'));
                method.disabled = !editing;
                document.getElementById('sectionModalMode').value = mode;
                document.getElementById('sectionModalId').value = editing ? section.id : '';
                document.getElementById('sectionFormDialogTitle').textContent = editing ? 'Edit Section' : 'Add Section';
                document.getElementById('submitSectionForm').textContent = editing ? 'Save Changes' : 'Create Section';
                const defaults = editing ? section : { order_number: 1 };
                Object.entries(fields).forEach(([name, field]) => { field.value = values[name] ?? defaults[name] ?? ''; });
                const errorBox = document.getElementById('sectionFormErrors');
                errorBox.replaceChildren(...errors.map((error) => {
                    const item = document.createElement('p'); item.textContent = error; return item;
                }));
                errorBox.hidden = errors.length === 0;
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            };

            document.querySelectorAll('.section-form-trigger').forEach((trigger) => trigger.addEventListener('click', () => {
                const section = sections[trigger.dataset.sectionId];
                openSectionForm(section ? 'edit' : 'create', section);
            }));
            document.getElementById('cancelSectionForm').addEventListener('click', () => dialog.close());
            document.getElementById('closeSectionForm').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
            dialog.addEventListener('close', () => document.body.classList.remove('admin-modal-open'));
            if (failedSectionForm) openSectionForm(failedSectionForm.mode, sections[failedSectionForm.id] || {}, failedSectionForm.values, failedSectionForm.errors);
        })();
    </script>
@endpush

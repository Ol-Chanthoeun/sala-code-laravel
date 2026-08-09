@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses')
@section('breadcrumb', 'Courses')

@section('content')
    <div class="admin-sticky-toolbar" style="display:flex;align-items:center;gap:10px;">
        <button type="button" class="action-btn admin-primary-action course-form-trigger" data-course-id="" style="width:180px;margin-bottom:20px;border:0;cursor:pointer;">
            <i class="fas fa-plus-circle"></i> Add Course
        </button>
        @include('admin.reports._quick-export', ['reportType' => 'courses'])
    </div>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Programming Courses</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th>Sections</th>
                        <th>Lessons</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                @if($course->thumbnail || $course->image)
                                    <img src="{{ asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) }}" width="70" alt="{{ $course->title }}">
                                @else
                                    No image
                                @endif
                            </td>
                            <td>
                                <strong>{{ $course->title }}</strong><br>
                                <small>{{ $course->slug }}</small>
                            </td>
                            <td>{{ $course->programming_language }}</td>
                            <td>{{ $course->difficulty_level }}</td>
                            <td>{{ ucfirst($course->status) }}</td>
                            <td>{{ $course->sections_count }}</td>
                            <td>{{ $course->lessons_count }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <button class="admin-icon-btn admin-icon-btn--primary view-course-trigger" type="button" title="View Course" aria-label="View Course" data-course-title="{{ $course->title }}" data-course-thumbnail="{{ $course->thumbnail || $course->image ? asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) : '' }}" data-course-language="{{ $course->programming_language }}" data-course-difficulty="{{ $course->difficulty_level }}" data-course-status="{{ ucfirst($course->status) }}" data-course-sections="{{ $course->sections_count }}" data-course-lessons="{{ $course->lessons_count }}" data-course-slug="{{ $course->slug }}" data-course-price="{{ $course->price ?? 'Free' }}"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                    <button class="admin-icon-btn admin-icon-btn--edit course-form-trigger" type="button" data-course-id="{{ $course->id }}" title="Edit Course" aria-label="Edit Course"><i class="fas fa-pen" aria-hidden="true"></i></button>
                                    <form class="admin-destructive-form" action="{{ route('admin.courses.destroy', $course) }}" method="POST" data-confirm-title="Confirm Delete Course" data-confirm-message="Are you sure you want to delete this course and all related content?" data-confirm-item="{{ $course->title }}" data-confirm-label="Delete Course">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Course" aria-label="Delete Course"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $courses->links('admin.partials.pagination') }}

    <dialog class="admin-confirm-dialog course-form-dialog" id="courseFormDialog" aria-labelledby="courseFormDialogTitle">
        <div class="admin-confirm-dialog__panel course-form-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-book" aria-hidden="true"></i><h3 id="courseFormDialogTitle">Add Course</h3></div>
                <button class="admin-confirm-dialog__close" id="closeCourseForm" type="button" aria-label="Close">&times;</button>
            </div>

            <div class="edit-user-dialog__errors" id="courseFormErrors" hidden></div>

            <form class="course-modal-form" id="courseForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input id="courseFormMethod" type="hidden" name="_method" value="PUT" disabled>
                <input id="courseModalMode" type="hidden" name="course_modal_mode" value="create">
                <input id="courseModalId" type="hidden" name="course_modal_id">

                <label>Title<input id="courseFormTitle" type="text" name="title" required maxlength="255"></label>
                <label>Slug<input id="courseFormSlug" type="text" name="slug" maxlength="255" placeholder="Auto-generated if blank"></label>
                <label class="course-modal-form__wide">Short Description<textarea id="courseFormShortDescription" name="short_description" rows="3" maxlength="1000"></textarea></label>
                <label class="course-modal-form__wide">Full Description<textarea id="courseFormFullDescription" name="full_description" rows="6"></textarea></label>
                <label>Programming Language<input id="courseFormLanguage" type="text" name="programming_language" required maxlength="100" placeholder="C, C++, Java, Python"></label>
                <label>Difficulty Level
                    <select id="courseFormDifficulty" name="difficulty_level" required><option value="Beginner">Beginner</option><option value="Intermediate">Intermediate</option><option value="Advanced">Advanced</option></select>
                </label>
                <label>Status
                    <select id="courseFormStatus" name="status" required><option value="draft">Draft</option><option value="published">Published</option><option value="unpublished">Unpublished</option></select>
                </label>
                <label>Price<input id="courseFormPrice" type="text" name="price" maxlength="50"></label>
                <label class="course-modal-form__wide">Thumbnail
                    <input id="courseFormThumbnail" type="file" name="thumbnail" accept="image/png,image/jpeg,image/webp">
                    <img class="course-modal-form__preview" id="courseFormPreview" alt="Current course thumbnail" hidden>
                </label>

                <div class="admin-confirm-dialog__actions course-modal-form__wide">
                    <button class="admin-confirm-dialog__cancel" id="cancelCourseForm" type="button">Cancel</button>
                    <button class="admin-confirm-dialog__confirm" id="submitCourseForm" type="submit">Create Course</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog class="admin-confirm-dialog course-details-dialog" id="courseDetailsDialog" aria-labelledby="courseDetailsDialogTitle">
        <div class="admin-confirm-dialog__panel admin-details-modal__panel course-details-dialog__panel">
            <div class="admin-confirm-dialog__header admin-details-modal__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-book-open" aria-hidden="true"></i><h3 id="courseDetailsDialogTitle">Course Details</h3></div>
                <button class="admin-confirm-dialog__close" id="closeCourseDetails" type="button" aria-label="Close">&times;</button>
            </div>

            <div class="course-details-dialog__content">
                <h4 class="admin-details-modal__heading" id="courseDetailsTitle"></h4>
                <div class="course-details-dialog__media">
                    <img class="course-details-dialog__thumbnail" id="courseDetailsThumbnail" alt="">
                    <div class="course-details-dialog__placeholder" id="courseDetailsPlaceholder" hidden>
                        <i class="fas fa-image" aria-hidden="true"></i>
                        <span>No thumbnail available</span>
                    </div>
                </div>
                <dl class="admin-details-modal__grid">
                    <div><dt>Language</dt><dd id="courseDetailsLanguage"></dd></div>
                    <div><dt>Difficulty</dt><dd><span class="admin-details-modal__badge admin-details-modal__badge--difficulty" id="courseDetailsDifficulty"></span></dd></div>
                    <div><dt>Status</dt><dd><span class="admin-details-modal__badge" id="courseDetailsStatus"></span></dd></div>
                    <div><dt>Price</dt><dd id="courseDetailsPrice"></dd></div>
                    <div><dt>Sections</dt><dd id="courseDetailsSections"></dd></div>
                    <div><dt>Lessons</dt><dd id="courseDetailsLessons"></dd></div>
                    <div class="admin-details-modal__wide"><dt>Slug</dt><dd id="courseDetailsSlug"></dd></div>
                </dl>
            </div>

            <div class="admin-confirm-dialog__actions admin-details-modal__footer">
                <button class="admin-confirm-dialog__cancel" id="cancelCourseDetails" type="button">Close</button>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script>
        (() => {
            @php
                $courseModalData = $courses->getCollection()->mapWithKeys(fn ($course) => [(string) $course->id => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description ?? $course->description,
                    'full_description' => $course->full_description,
                    'programming_language' => $course->programming_language,
                    'difficulty_level' => $course->difficulty_level,
                    'status' => $course->status,
                    'price' => $course->price ?? 'Free',
                    'thumbnail' => $course->thumbnail || $course->image ? asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) : '',
                    'update_url' => route('admin.courses.update', $course),
                ]]);
                $failedCourseForm = old('course_modal_mode') ? [
                    'mode' => old('course_modal_mode'),
                    'id' => (string) old('course_modal_id'),
                    'values' => [
                        'title' => old('title'), 'slug' => old('slug'), 'short_description' => old('short_description'),
                        'full_description' => old('full_description'), 'programming_language' => old('programming_language'),
                        'difficulty_level' => old('difficulty_level'), 'status' => old('status'), 'price' => old('price'),
                    ],
                    'errors' => $errors->all(),
                ] : null;
            @endphp
            const courses = @json($courseModalData);
            const failedCourseForm = @json($failedCourseForm);
            const dialog = document.getElementById('courseFormDialog');
            const form = document.getElementById('courseForm');
            const method = document.getElementById('courseFormMethod');
            const preview = document.getElementById('courseFormPreview');
            const thumbnail = document.getElementById('courseFormThumbnail');
            const fields = {
                title: document.getElementById('courseFormTitle'), slug: document.getElementById('courseFormSlug'),
                short_description: document.getElementById('courseFormShortDescription'), full_description: document.getElementById('courseFormFullDescription'),
                programming_language: document.getElementById('courseFormLanguage'), difficulty_level: document.getElementById('courseFormDifficulty'),
                status: document.getElementById('courseFormStatus'), price: document.getElementById('courseFormPrice'),
            };

            const openCourseForm = (mode, course = {}, values = {}, errors = []) => {
                const editing = mode === 'edit';
                form.action = editing ? course.update_url : @json(route('admin.courses.store'));
                method.disabled = !editing;
                document.getElementById('courseModalMode').value = mode;
                document.getElementById('courseModalId').value = editing ? course.id : '';
                document.getElementById('courseFormDialogTitle').textContent = editing ? 'Edit Course' : 'Add Course';
                document.getElementById('submitCourseForm').textContent = editing ? 'Save Changes' : 'Create Course';

                const defaults = editing ? course : { difficulty_level: 'Beginner', status: 'draft', price: 'Free' };
                Object.entries(fields).forEach(([name, field]) => { field.value = values[name] ?? defaults[name] ?? ''; });
                thumbnail.value = '';
                preview.src = editing ? course.thumbnail : '';
                preview.hidden = !preview.src;

                const errorBox = document.getElementById('courseFormErrors');
                errorBox.replaceChildren(...errors.map((error) => {
                    const item = document.createElement('p');
                    item.textContent = error;
                    return item;
                }));
                errorBox.hidden = errors.length === 0;
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            };

            document.querySelectorAll('.course-form-trigger').forEach((trigger) => trigger.addEventListener('click', () => {
                const course = courses[trigger.dataset.courseId];
                openCourseForm(course ? 'edit' : 'create', course);
            }));
            thumbnail.addEventListener('change', () => {
                const file = thumbnail.files[0];
                if (!file) return;
                preview.src = URL.createObjectURL(file);
                preview.hidden = false;
            });
            document.getElementById('cancelCourseForm').addEventListener('click', () => dialog.close());
            document.getElementById('closeCourseForm').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
            dialog.addEventListener('close', () => document.body.classList.remove('admin-modal-open'));

            if (failedCourseForm) {
                const course = courses[failedCourseForm.id] || {};
                openCourseForm(failedCourseForm.mode, course, failedCourseForm.values, failedCourseForm.errors);
            }
        })();

        (() => {
            const dialog = document.getElementById('courseDetailsDialog');
            const thumbnail = document.getElementById('courseDetailsThumbnail');
            const displayValue = (value) => value && value.trim() ? value : '—';

            document.querySelectorAll('.view-course-trigger').forEach((trigger) => trigger.addEventListener('click', () => {
                document.getElementById('courseDetailsTitle').textContent = displayValue(trigger.dataset.courseTitle);
                document.getElementById('courseDetailsLanguage').textContent = displayValue(trigger.dataset.courseLanguage);
                const difficulty = document.getElementById('courseDetailsDifficulty');
                const status = document.getElementById('courseDetailsStatus');
                difficulty.textContent = displayValue(trigger.dataset.courseDifficulty);
                status.textContent = displayValue(trigger.dataset.courseStatus);
                status.className = `admin-details-modal__badge admin-details-modal__badge--${trigger.dataset.courseStatus.toLowerCase() === 'published' ? 'published' : 'draft'}`;
                document.getElementById('courseDetailsSections').textContent = displayValue(trigger.dataset.courseSections);
                document.getElementById('courseDetailsLessons').textContent = displayValue(trigger.dataset.courseLessons);
                document.getElementById('courseDetailsSlug').textContent = displayValue(trigger.dataset.courseSlug);
                document.getElementById('courseDetailsPrice').textContent = displayValue(trigger.dataset.coursePrice);
                thumbnail.src = trigger.dataset.courseThumbnail;
                thumbnail.alt = trigger.dataset.courseThumbnail ? trigger.dataset.courseTitle : '';
                thumbnail.hidden = !trigger.dataset.courseThumbnail;
                document.getElementById('courseDetailsPlaceholder').hidden = Boolean(trigger.dataset.courseThumbnail);
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            }));

            document.getElementById('cancelCourseDetails').addEventListener('click', () => dialog.close());
            document.getElementById('closeCourseDetails').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
            dialog.addEventListener('close', () => document.body.classList.remove('admin-modal-open'));
        })();
    </script>
@endpush

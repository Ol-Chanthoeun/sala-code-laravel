@extends('layouts.admin')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')
@section('breadcrumb', 'Admin Management')

@section('content')
    <div class="admin-sticky-toolbar">
        <a href="{{ route('admin.admins.create') }}" class="action-btn admin-primary-action" style="width:210px;margin-bottom:20px;">
            <i class="fas fa-user-plus"></i> Create Admin
        </a>
    </div>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    @if($errors->any() && ! old('edit_admin_id'))
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Admins and Super Admins</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</td>
                            <td>{{ ucfirst($admin->status) }}</td>
                            <td>{{ $admin->created_at?->format('Y-m-d') }}</td>
                            <td>
                                @if($admin->isAdmin())
                                    <div class="admin-table-actions">
                                        <button class="admin-icon-btn admin-icon-btn--edit edit-admin-trigger" type="button" title="Edit Admin" aria-label="Edit Admin" data-admin-id="{{ $admin->id }}" data-update-url="{{ route('admin.admins.update', $admin) }}" data-admin-name="{{ $admin->name }}" data-admin-email="{{ $admin->email }}" data-admin-status="{{ $admin->status }}" data-admin-role="{{ ucfirst(str_replace('_', ' ', $admin->role)) }}"><i class="fas fa-pen" aria-hidden="true"></i></button>
                                        <form class="delete-admin-form" action="{{ route('admin.admins.destroy', $admin) }}" method="POST" data-admin-name="{{ $admin->name }}" data-admin-email="{{ $admin->email }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Admin" aria-label="Delete Admin"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    </div>
                                @else
                                    Protected
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">No admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $admins->links('admin.partials.pagination') }}

    <dialog class="admin-confirm-dialog edit-user-dialog" id="editAdminDialog" aria-labelledby="editAdminDialogTitle">
        <div class="admin-confirm-dialog__panel edit-user-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title">
                    <i class="fas fa-user-pen" aria-hidden="true"></i>
                    <div><h3 id="editAdminDialogTitle">Edit Admin</h3><small id="editAdminSubtitle"></small></div>
                </div>
                <button class="admin-confirm-dialog__close" id="closeEditAdmin" type="button" aria-label="Close">&times;</button>
            </div>

            <div class="edit-user-dialog__errors" id="editAdminErrors" hidden></div>

            <form class="edit-user-form" id="editAdminForm" method="POST">
                @csrf
                @method('PUT')
                <input id="editAdminId" type="hidden" name="edit_admin_id">
                <label>Full Name<input id="editAdminName" type="text" name="name" required maxlength="255"></label>
                <label>Email<input id="editAdminEmail" type="email" name="email" required maxlength="255"></label>
                <label>Account Status
                    <select id="editAdminStatus" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option></select>
                </label>
                <label>Role<input id="editAdminRole" type="text" disabled></label>
                <div class="admin-confirm-dialog__actions">
                    <button class="admin-confirm-dialog__cancel" id="cancelEditAdmin" type="button">Cancel</button>
                    <button class="admin-confirm-dialog__confirm" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog class="admin-confirm-dialog" id="deleteAdminDialog" data-tone="danger" aria-labelledby="deleteAdminDialogTitle">
        <div class="admin-confirm-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-user-minus" aria-hidden="true"></i><h3 id="deleteAdminDialogTitle">Confirm Delete Admin</h3></div>
                <button class="admin-confirm-dialog__close" id="closeDeleteAdmin" type="button" aria-label="Close">&times;</button>
            </div>
            <p>Are you sure you want to permanently delete this admin account?</p>
            <p class="admin-confirm-dialog__note">This action is permanent and cannot be undone.</p>
            <dl><div><dt>Admin</dt><dd id="deleteAdminName"></dd></div><div><dt>Email</dt><dd id="deleteAdminEmail"></dd></div></dl>
            <div class="admin-confirm-dialog__actions">
                <button class="admin-confirm-dialog__cancel" id="cancelDeleteAdmin" type="button">Cancel</button>
                <button class="admin-confirm-dialog__confirm" id="confirmDeleteAdmin" type="button">Delete Admin</button>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script>
        (() => {
            const editDialog = document.getElementById('editAdminDialog');
            const editForm = document.getElementById('editAdminForm');

            const openEditAdmin = (trigger, values = {}, errors = []) => {
                editForm.action = trigger.dataset.updateUrl;
                document.getElementById('editAdminId').value = trigger.dataset.adminId;
                document.getElementById('editAdminName').value = values.name ?? trigger.dataset.adminName;
                document.getElementById('editAdminEmail').value = values.email ?? trigger.dataset.adminEmail;
                document.getElementById('editAdminStatus').value = values.status ?? trigger.dataset.adminStatus;
                document.getElementById('editAdminRole').value = trigger.dataset.adminRole;
                document.getElementById('editAdminSubtitle').textContent = trigger.dataset.adminName;
                const errorBox = document.getElementById('editAdminErrors');
                errorBox.replaceChildren(...errors.map((error) => {
                    const item = document.createElement('p');
                    item.textContent = error;
                    return item;
                }));
                errorBox.hidden = errors.length === 0;
                editDialog.showModal();
                document.body.classList.add('admin-modal-open');
            };

            document.querySelectorAll('.edit-admin-trigger').forEach((trigger) => trigger.addEventListener('click', () => openEditAdmin(trigger)));
            document.getElementById('cancelEditAdmin').addEventListener('click', () => editDialog.close());
            document.getElementById('closeEditAdmin').addEventListener('click', () => editDialog.close());
            editDialog.addEventListener('click', (event) => { if (event.target === editDialog) editDialog.close(); });
            editDialog.addEventListener('close', () => document.body.classList.remove('admin-modal-open'));

            @php
                $failedAdminEdit = old('edit_admin_id') ? [
                    'id' => (string) old('edit_admin_id'),
                    'values' => ['name' => old('name'), 'email' => old('email'), 'status' => old('status')],
                    'errors' => $errors->all(),
                ] : null;
            @endphp
            const failedAdminEdit = @json($failedAdminEdit);
            if (failedAdminEdit) {
                const trigger = document.querySelector(`.edit-admin-trigger[data-admin-id="${failedAdminEdit.id}"]`);
                if (trigger) openEditAdmin(trigger, failedAdminEdit.values, failedAdminEdit.errors);
            }

            const deleteDialog = document.getElementById('deleteAdminDialog');
            let pendingDeleteForm = null;
            document.querySelectorAll('.delete-admin-form').forEach((form) => form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingDeleteForm = form;
                document.getElementById('deleteAdminName').textContent = form.dataset.adminName;
                document.getElementById('deleteAdminEmail').textContent = form.dataset.adminEmail;
                deleteDialog.showModal();
                document.body.classList.add('admin-modal-open');
            }));
            document.getElementById('cancelDeleteAdmin').addEventListener('click', () => deleteDialog.close());
            document.getElementById('closeDeleteAdmin').addEventListener('click', () => deleteDialog.close());
            deleteDialog.addEventListener('click', (event) => { if (event.target === deleteDialog) deleteDialog.close(); });
            deleteDialog.addEventListener('close', () => {
                pendingDeleteForm = null;
                document.body.classList.remove('admin-modal-open');
            });
            document.getElementById('confirmDeleteAdmin').addEventListener('click', () => {
                const form = pendingDeleteForm;
                deleteDialog.close();
                if (form) HTMLFormElement.prototype.submit.call(form);
            });
        })();
    </script>
@endpush

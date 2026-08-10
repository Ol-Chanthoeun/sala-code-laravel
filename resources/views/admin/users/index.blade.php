@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb', 'Users')

@push('styles')
    <style>
        .users-back-to-top {
            align-items: center;
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            border: 0;
            border-radius: 50%;
            bottom: 28px;
            box-shadow: 0 8px 22px rgba(49, 46, 129, .24);
            color: #fff;
            cursor: pointer;
            display: flex;
            height: 40px;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            position: fixed;
            right: 28px;
            transform: translateY(8px);
            transition: bottom .2s ease, opacity .2s ease, transform .2s ease, visibility .2s ease;
            visibility: hidden;
            width: 40px;
            z-index: 120;
        }

        .users-back-to-top.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
            visibility: visible;
        }

        .users-back-to-top.is-near-pagination { bottom: 88px; }
        .users-back-to-top:hover,
        .users-back-to-top:focus-visible { background: linear-gradient(135deg, #4338ca, #1d4ed8); }

        @media (max-width: 768px) {
            .users-back-to-top { bottom: 18px; height: 36px; right: 16px; width: 36px; }
            .users-back-to-top.is-near-pagination { bottom: 76px; }
        }
    </style>
@endpush

@section('content')
    @if(auth()->user()->isSuperAdmin())
        <div class="users-create-admin-action">
            <a href="{{ route('admin.admins.create') }}"
               class="action-btn admin-primary-action admin-modal-form-link"
               data-modal-title="Create Admin"
               style="width:190px;">
                <i class="fas fa-user-shield"></i> Create Admin
            </a>
        </div>
    @endif
    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    @if($errors->any() && ! old('edit_user_id'))
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info users-filter-toolbar" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end;">
            <p>
                <label>Search users</label><br>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email" style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Filter role</label><br>
                <select name="role" style="width:100%;padding:12px;margin-top:8px;">
                    <option value="">All roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </p>
            <button class="action-btn" style="border:0;cursor:pointer;">Filter</button>
        </form>
    </div>

    <div class="data-table users-data-table">
        <div class="table-header">
            <h3>Registered Users</h3>
            @include('admin.reports._quick-export', ['reportType' => 'users'])
        </div>

        <div class="table-responsive users-table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Auth provider</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th class="users-actions-heading">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $managedUser)
                        <tr>
                            <td class="users-identity-cell">
                                <strong>{{ $managedUser->name }}</strong>
                                <span title="{{ $managedUser->email }}">{{ $managedUser->email }}</span>
                            </td>
                            <td><span class="auth-provider-badge auth-provider-badge--{{ strtolower($managedUser->authProviderLabel()) }}">{{ $managedUser->authProviderLabel() }}</span></td>
                            <td><span class="user-meta-badge user-meta-badge--role">{{ $roles[$managedUser->role] ?? $managedUser->role }}</span></td>
                            <td><span class="user-meta-badge user-meta-badge--{{ $managedUser->isActive() ? 'active' : 'inactive' }}">{{ ucfirst($managedUser->status) }}</span></td>
                            <td>{{ $managedUser->created_at?->format('Y-m-d') }}</td>
                            <td class="users-actions-cell">
                                @php
                                    $currentUser = auth()->user();
                                    $canEditUser = $currentUser->can('update', $managedUser);
                                    $canChangeStatus = $currentUser->can('changeStatus', $managedUser);
                                    $canDeleteUser = $currentUser->can('delete', $managedUser);
                                    $canChangeRoles = $currentUser->can('changeRole', $managedUser);
                                    $canSendPasswordReset = $currentUser->can('sendPasswordReset', $managedUser);
                                @endphp

                                @if($managedUser->isProtectedPrimarySuperAdmin())
                                    <span class="user-actions-empty">Protected</span>
                                @elseif($managedUser->isSuperAdmin())
                                    <span class="user-actions-empty">Restricted</span>
                                @elseif($canEditUser || $canChangeStatus || $canDeleteUser || $canChangeRoles || $canSendPasswordReset)
                                    <div class="user-actions-compact">
                                            @if($canEditUser)
                                                <button class="admin-icon-btn admin-icon-btn--edit edit-user-trigger" type="button" title="Edit User" aria-label="Edit User" data-user-id="{{ $managedUser->id }}" data-update-url="{{ route('admin.users.update', $managedUser) }}" data-role-update-url="{{ route('admin.users.update-role', $managedUser) }}" data-user-name="{{ $managedUser->name }}" data-user-email="{{ $managedUser->email }}" data-user-status="{{ $managedUser->status }}" data-user-role="{{ $managedUser->role }}" data-can-edit-role="{{ $canChangeRoles ? '1' : '0' }}" data-requires-role-confirm="{{ auth()->id() === $managedUser->id && $managedUser->isSuperAdmin() ? '1' : '0' }}">
                                                    <i class="fas fa-pen" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                            @if($canChangeStatus || $canSendPasswordReset || $canDeleteUser)
                                                <details class="user-more-actions">
                                                    <summary class="admin-icon-btn user-more-actions__toggle" title="More Actions" aria-label="More Actions"><i class="fas fa-ellipsis-vertical" aria-hidden="true"></i></summary>
                                                    <div class="user-more-actions__menu">
                                                        @if($canChangeStatus)
                                                            <form class="user-confirm-form" action="{{ route('admin.users.toggle-status', $managedUser) }}" method="POST" data-confirm-action="{{ $managedUser->status === 'active' ? 'deactivate' : 'activate' }}" data-user-name="{{ $managedUser->name }}" data-user-email="{{ $managedUser->email }}" data-current-role="{{ $roles[$managedUser->role] ?? $managedUser->role }}">
                                                                @csrf @method('PATCH')
                                                                <button class="user-more-actions__item user-more-actions__item--{{ $managedUser->status === 'active' ? 'status' : 'activate' }}" type="submit"><i class="fas {{ $managedUser->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>{{ $managedUser->status === 'active' ? 'Deactivate User' : 'Activate User' }}</button>
                                                            </form>
                                                        @endif
                                                        @if($canSendPasswordReset)
                                                            <form class="user-confirm-form" action="{{ route('admin.users.password-reset', $managedUser) }}" method="POST" data-confirm-action="reset-password" data-user-name="{{ $managedUser->name }}" data-user-email="{{ $managedUser->email }}" data-current-role="{{ $roles[$managedUser->role] ?? $managedUser->role }}">
                                                                @csrf
                                                                <button class="user-more-actions__item user-more-actions__item--reset" type="submit"><i class="fas fa-key"></i>Send Reset Link</button>
                                                            </form>
                                                        @endif
                                                        @if($canDeleteUser)
                                                            <form class="user-confirm-form" action="{{ route('admin.users.destroy', $managedUser) }}" method="POST" data-confirm-action="delete" data-user-name="{{ $managedUser->name }}" data-user-email="{{ $managedUser->email }}" data-current-role="{{ $roles[$managedUser->role] ?? $managedUser->role }}">
                                                                @csrf @method('DELETE')
                                                                <button class="user-more-actions__item user-more-actions__item--delete" type="submit"><i class="fas fa-trash"></i>Delete User</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </details>
                                            @endif
                                    </div>
                                @else
                                    <span class="user-actions-empty">Restricted</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="users-pagination" style="margin-top:20px;">
        {{ $users->links('admin.partials.pagination') }}
    </div>

    <button class="users-back-to-top" id="usersBackToTop" type="button" title="Back to Top" aria-label="Back to Top">
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>

    <dialog class="admin-confirm-dialog edit-user-dialog" id="editUserDialog" aria-labelledby="editUserDialogTitle">
        <div class="admin-confirm-dialog__panel edit-user-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title">
                    <i class="fas fa-user-pen" aria-hidden="true"></i>
                    <div>
                        <h3 id="editUserDialogTitle">Edit User</h3>
                        <small id="editUserSubtitle"></small>
                    </div>
                </div>
                <button class="admin-confirm-dialog__close" id="closeEditUser" type="button" aria-label="Close">&times;</button>
            </div>

            <div class="edit-user-dialog__errors" id="editUserErrors" hidden></div>

            <form class="edit-user-form" id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input id="editUserId" type="hidden" name="edit_user_id">

                <label>Full Name<input id="editUserName" type="text" name="name" required maxlength="255"></label>
                <label>Email<input id="editUserEmail" type="email" name="email" required maxlength="255"></label>
                <label>Account Status
                    <select id="editUserStatus" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </label>
                <label>Role
                    <span class="edit-user-role-controls">
                        <select id="editUserRole" name="role" form="editUserRoleForm">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <button class="admin-icon-btn admin-icon-btn--primary" id="editUserRoleSubmit" form="editUserRoleForm" type="submit" title="Update Role" aria-label="Update Role"><i class="fas fa-check"></i></button>
                    </span>
                </label>

                <div class="edit-user-role-confirm" id="editUserRoleConfirm" hidden>
                    <label class="user-role-confirm">
                        <input id="editUserRoleConfirmation" type="checkbox" name="confirm_self_role_change" value="1">
                        <span>Confirm changing your own Super Admin role</span>
                    </label>
                </div>

                <div class="admin-confirm-dialog__actions">
                    <button class="admin-confirm-dialog__cancel" id="cancelEditUser" type="button">Cancel</button>
                    <button class="admin-confirm-dialog__confirm" type="submit">Save Changes</button>
                </div>
            </form>
            <form class="user-role-form" id="editUserRoleForm" method="POST">
                @csrf
                @method('PATCH')
            </form>
        </div>
    </dialog>

    <dialog class="admin-confirm-dialog" id="adminConfirmDialog" aria-labelledby="adminConfirmDialogTitle">
        <div class="admin-confirm-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title">
                    <i class="fas fa-user-shield" aria-hidden="true"></i>
                    <h3 id="adminConfirmDialogTitle">Confirm Action</h3>
                </div>
                <button class="admin-confirm-dialog__close" id="closeRoleChange" type="button" aria-label="Close">&times;</button>
            </div>
            <p id="adminConfirmDialogMessage"></p>
            <p class="admin-confirm-dialog__note" id="adminConfirmDialogNote" hidden></p>
            <dl>
                <div><dt>User</dt><dd id="adminConfirmUser"></dd></div>
                <div id="adminConfirmEmailRow"><dt>Email</dt><dd id="adminConfirmEmail"></dd></div>
                <div id="adminConfirmRoleRow"><dt>Current Role</dt><dd id="adminConfirmRole"></dd></div>
                <div class="admin-confirm-dialog__role-change" id="adminConfirmRoleChange" hidden>
                    <div><dt>Current Role</dt><dd id="adminConfirmCurrentRole"></dd></div>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    <div><dt>New Role</dt><dd id="adminConfirmNewRole"></dd></div>
                </div>
            </dl>
            <div class="admin-confirm-dialog__actions">
                <button class="admin-confirm-dialog__cancel" id="cancelRoleChange" type="button">Cancel</button>
                <button class="admin-confirm-dialog__confirm" id="confirmAdminAction" type="button">Confirm</button>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script>
        (() => {
            const button = document.getElementById('usersBackToTop');
            const pagination = document.querySelector('.users-pagination');
            const updateVisibility = () => button.classList.toggle('is-visible', window.scrollY >= 450);

            window.addEventListener('scroll', updateVisibility, { passive: true });
            button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
            updateVisibility();

            if (pagination && 'IntersectionObserver' in window) {
                new IntersectionObserver(([entry]) => {
                    button.classList.toggle('is-near-pagination', entry.isIntersecting);
                }, { threshold: 0 }).observe(pagination);
            }
        })();

        (() => {
            const menus = document.querySelectorAll('.user-more-actions');

            menus.forEach((menu) => {
                menu.addEventListener('toggle', () => {
                    if (!menu.open) return;
                    menus.forEach((other) => {
                        if (other !== menu) other.removeAttribute('open');
                    });
                });
            });

            document.addEventListener('click', (event) => {
                menus.forEach((menu) => {
                    if (!menu.contains(event.target)) menu.removeAttribute('open');
                });
            });
        })();

        (() => {
            const dialog = document.getElementById('editUserDialog');
            const form = document.getElementById('editUserForm');
            const role = document.getElementById('editUserRole');
            const roleForm = document.getElementById('editUserRoleForm');
            const roleSubmit = document.getElementById('editUserRoleSubmit');
            const roleConfirm = document.getElementById('editUserRoleConfirm');
            let activeTrigger = null;

            const updateRoleConfirmation = () => {
                roleConfirm.hidden = !activeTrigger
                    || activeTrigger.dataset.requiresRoleConfirm !== '1'
                    || role.value === 'super_admin';
            };

            const openEditModal = (trigger, values = {}, errors = []) => {
                activeTrigger = trigger;
                form.action = trigger.dataset.updateUrl;
                document.getElementById('editUserId').value = trigger.dataset.userId;
                document.getElementById('editUserName').value = values.name ?? trigger.dataset.userName;
                document.getElementById('editUserEmail').value = values.email ?? trigger.dataset.userEmail;
                document.getElementById('editUserStatus').value = values.status ?? trigger.dataset.userStatus;
                role.value = values.role ?? trigger.dataset.userRole;
                role.disabled = trigger.dataset.canEditRole !== '1';
                roleSubmit.hidden = trigger.dataset.canEditRole !== '1';
                roleForm.action = trigger.dataset.roleUpdateUrl;
                roleForm.dataset.userName = trigger.dataset.userName;
                roleForm.dataset.userEmail = trigger.dataset.userEmail;
                roleForm.dataset.currentRole = trigger.dataset.userRole === 'admin' ? 'Admin' : 'User';
                document.getElementById('editUserRoleConfirmation').checked = values.confirmSelfRoleChange === '1';
                document.getElementById('editUserSubtitle').textContent = trigger.dataset.userName;

                const errorBox = document.getElementById('editUserErrors');
                errorBox.replaceChildren(...errors.map((error) => {
                    const item = document.createElement('p');
                    item.textContent = error;
                    return item;
                }));
                errorBox.hidden = errors.length === 0;
                updateRoleConfirmation();
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            };

            document.querySelectorAll('.edit-user-trigger').forEach((trigger) => {
                trigger.addEventListener('click', () => openEditModal(trigger));
            });
            role.addEventListener('change', updateRoleConfirmation);
            document.getElementById('cancelEditUser').addEventListener('click', () => dialog.close());
            document.getElementById('closeEditUser').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });
            dialog.addEventListener('close', () => {
                activeTrigger = null;
                document.body.classList.remove('admin-modal-open');
            });

            @php
                $failedEdit = old('edit_user_id') ? [
                    'id' => (string) old('edit_user_id'),
                    'values' => [
                        'name' => old('name'),
                        'email' => old('email'),
                        'status' => old('status'),
                        'role' => old('role'),
                        'confirmSelfRoleChange' => old('confirm_self_role_change'),
                    ],
                    'errors' => $errors->all(),
                ] : null;
            @endphp
            const failedEdit = @json($failedEdit);

            if (failedEdit) {
                const trigger = document.querySelector(`.edit-user-trigger[data-user-id="${failedEdit.id}"]`);
                if (trigger) openEditModal(trigger, failedEdit.values, failedEdit.errors);
            }
        })();

        (() => {
            const dialog = document.getElementById('adminConfirmDialog');
            const cancel = document.getElementById('cancelRoleChange');
            const close = document.getElementById('closeRoleChange');
            const confirm = document.getElementById('confirmAdminAction');
            let pendingForm = null;

            const actions = {
                delete: {
                    title: 'Delete User Permanently?',
                    message: 'Permanently delete this user account?',
                    note: 'This action cannot be undone.',
                    confirmLabel: 'Delete User',
                    tone: 'danger',
                },
                deactivate: {
                    title: 'Deactivate User?',
                    message: 'Deactivate this user account?',
                    note: 'The user will no longer be able to sign in.',
                    confirmLabel: 'Deactivate',
                    tone: 'warning',
                },
                activate: {
                    title: 'Confirm Activate User',
                    message: 'Are you sure you want to activate this user?',
                    note: '',
                    confirmLabel: 'Activate',
                    tone: 'success',
                },
                'reset-password': {
                    title: 'Send Password Reset Link?',
                    message: 'A secure password reset link will be sent to this email address.',
                    note: '',
                    confirmLabel: 'Send Reset Link',
                    tone: 'primary',
                },
            };

            const openDialog = (form, config, newRole = null) => {
                pendingForm = form;
                dialog.dataset.tone = config.tone;
                document.getElementById('adminConfirmDialogTitle').textContent = config.title;
                document.getElementById('adminConfirmDialogMessage').textContent = config.message;
                const note = document.getElementById('adminConfirmDialogNote');
                note.textContent = config.note;
                note.hidden = !config.note;
                document.getElementById('adminConfirmUser').textContent = form.dataset.userName;
                document.getElementById('adminConfirmEmail').textContent = form.dataset.userEmail || '';
                document.getElementById('adminConfirmRole').textContent = form.dataset.currentRole;
                document.getElementById('adminConfirmEmailRow').hidden = !form.dataset.userEmail;
                document.getElementById('adminConfirmRoleRow').hidden = newRole !== null;
                document.getElementById('adminConfirmRoleChange').hidden = newRole === null;
                document.getElementById('adminConfirmCurrentRole').textContent = form.dataset.currentRole;
                document.getElementById('adminConfirmNewRole').textContent = newRole || '';
                confirm.textContent = config.confirmLabel;
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            };

            document.querySelectorAll('.user-role-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const roleSelect = form.elements.namedItem('role');
                    openDialog(form, {
                        title: 'Change User Role?',
                        message: "Confirm the role change below.",
                        note: '',
                        confirmLabel: 'Confirm Change',
                        tone: 'primary',
                    }, roleSelect.selectedOptions[0].textContent);
                });
            });

            document.querySelectorAll('.user-confirm-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    openDialog(form, actions[form.dataset.confirmAction]);
                });
            });

            cancel.addEventListener('click', () => dialog.close());
            close.addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });
            confirm.addEventListener('click', () => {
                const form = pendingForm;
                dialog.close();
                if (form) HTMLFormElement.prototype.submit.call(form);
            });
            dialog.addEventListener('close', () => {
                pendingForm = null;
                document.body.classList.remove('admin-modal-open');
            });
        })();
    </script>
@endpush

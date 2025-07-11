@extends('layouts.main')

@section('title', 'Roles & Permissions')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        /* Modal Styling */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            border-radius: 12px 12px 0 0;
        }
        
        .form-control {
            border: 2px solid #e3e6ea;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .action li a {
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .action li.view a:hover {
            background-color: #17a2b8;
            color: white;
        }
        
        .action li.edit a:hover {
            background-color: #28a745;
            color: white;
        }
        
        .action li.delete a:hover {
            background-color: #dc3545;
            color: white;
        }

        .permission-grid {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 5px 0;
        }

        .permission-item input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Roles & Permissions</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Roles & Permissions</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row widget-grid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Roles Management</h5>
                                <p class="f-m-light mt-1">Create and manage roles with permissions</p>
                            </div>
                            <div class="header-actions">
                                <button class="btn btn-success btn-sm" onclick="createRole()">
                                    <i class="fa fa-plus me-1"></i>Create Role
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable" id="rolesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Role Name</th>
                                        <th>Permissions Count</th>
                                        <th>Users Count</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>
                                                <span class="badge bg-{{ $role->name === 'Super Admin' ? 'danger' : ($role->name === 'Admin' ? 'success' : ($role->name === 'Agent' ? 'warning' : 'info')) }}">
                                                    {{ $role->name }}
                                                </span>
                                            </td>
                                            <td>{{ $role->permissions->count() }}</td>
                                            <td>{{ $role->users->count() }}</td>
                                            <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <ul class="action">
                                                    @if($role->name !== 'Super Admin')
                                                        <li class="edit">
                                                            <a href="#!" onclick="editRole({{ $role->id }})">
                                                                <i class="fa-regular fa-pen-to-square"></i>
                                                            </a>
                                                        </li>
                                                        <li class="delete">
                                                            <a href="#!" onclick="deleteRole({{ $role->id }})">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Role Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="roleModalLabel">
                        <i class="fa fa-shield me-2"></i>Create Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roleForm">
                        <input type="hidden" id="roleId" name="id">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fa fa-shield text-primary me-1"></i>Role Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter role name" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-key text-primary me-1"></i>Permissions
                                </label>
                                <div class="permission-grid">
                                    <div class="row">
                                        @foreach($permissions->groupBy(function($permission) { return explode('.', $permission->name)[0]; }) as $group => $groupPermissions)
                                            <div class="col-md-6 mb-3">
                                                <h6 class="text-capitalize text-primary">{{ str_replace('-', ' ', $group) }}</h6>
                                                @foreach($groupPermissions as $permission)
                                                    <div class="permission-item">
                                                        <input type="checkbox" 
                                                               id="permission_{{ $permission->id }}" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}">
                                                        <label for="permission_{{ $permission->id }}" class="form-label mb-0">
                                                            {{ ucfirst(str_replace(['-', '.'], [' ', ' '], $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="saveRoleBtn" onclick="saveRole()">
                        <i class="fa fa-save me-1"></i>Save Role
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Are you sure?</h5>
                        <p>Do you really want to delete this role? This action cannot be undone.</p>
                        <input type="hidden" id="deleteRoleId">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fa fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        let dataTable;
        let isEditMode = false;
        
        $(document).ready(function() {
            dataTable = $('#rolesTable').DataTable({
                "responsive": true,
                "pageLength": 10,
                "order": [],
            });
        });

        // Create new role
        function createRole() {
            isEditMode = false;
            document.getElementById('roleModalLabel').innerHTML = '<i class="fa fa-shield me-2"></i>Create Role';
            document.getElementById('saveRoleBtn').innerHTML = '<i class="fa fa-save me-1"></i>Save Role';
            document.getElementById('roleForm').reset();
            document.getElementById('roleId').value = '';
            
            // Uncheck all permissions
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            clearValidationErrors();
            new bootstrap.Modal(document.getElementById('roleModal')).show();
        }

        // Edit role
        function editRole(id) {
            isEditMode = true;
            document.getElementById('roleModalLabel').innerHTML = '<i class="fa fa-edit me-2"></i>Edit Role';
            document.getElementById('saveRoleBtn').innerHTML = '<i class="fa fa-save me-1"></i>Update Role';
            
            // Fetch role data
            fetch(`/roles/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('roleId').value = data.data.id;
                        document.getElementById('name').value = data.data.name;
                        
                        // Uncheck all permissions first
                        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        
                        // Check role permissions
                        data.data.permissions.forEach(permission => {
                            const checkbox = document.getElementById(`permission_${permission.id}`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                        
                        clearValidationErrors();
                        new bootstrap.Modal(document.getElementById('roleModal')).show();
                    }
                })
                .catch(error => {
                    showNotification('Error fetching role data', 'error');
                    console.error('Error:', error);
                });
        }

        function saveRole() {
            const form = document.getElementById('roleForm');
            const formData = new FormData(form);
            const id = document.getElementById('roleId').value;
            
            const url = isEditMode ? `/roles/${id}` : '/roles';
            const method = isEditMode ? 'PUT' : 'POST';
            
            // Clear previous validation errors
            clearValidationErrors();
            
            // Show loading state
            const saveBtn = document.getElementById('saveRoleBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving...';
            saveBtn.disabled = true;
            
            // Get selected permissions
            const selectedPermissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                .map(checkbox => checkbox.value);
            
            const requestData = {
                name: formData.get('name'),
                permissions: selectedPermissions
            };
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (data.errors) {
                        displayValidationErrors(data.errors);
                    } else {
                        showNotification(data.message || 'An error occurred', 'error');
                    }
                }
            })
            .catch(error => {
                showNotification('An error occurred while saving', 'error');
                console.error('Error:', error);
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Delete role
        function deleteRole(id) {
            document.getElementById('deleteRoleId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Confirm delete
        function confirmDelete() {
            const id = document.getElementById('deleteRoleId').value;
            
            fetch(`/roles/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred while deleting', 'error');
                console.error('Error:', error);
            });
        }

        // Clear validation errors
        function clearValidationErrors() {
            document.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(element => {
                element.textContent = '';
            });
        }

        // Display validation errors
        function displayValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field);
                const errorDiv = document.getElementById(field + 'Error');
                
                if (input && errorDiv) {
                    input.classList.add('is-invalid');
                    errorDiv.textContent = errors[field][0];
                }
            });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const types = {
                'success': '✅',
                'error': '❌', 
                'warning': '⚠️',
                'info': 'ℹ️'
            };
            
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${types[type]} ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 4000);
        }
    </script>
@endsection

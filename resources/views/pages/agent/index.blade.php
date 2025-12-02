@extends('layouts.main')

@section('title', 'Agents')

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
            border-color: #3D9FD8;
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
        
        .action li.review a:hover {
            background-color: #ffc107;
            color: white;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Agents</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Agents Management</li>
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
                                <h5>Agents Management</h5>
                                <p class="f-m-light mt-1">Create and manage agents</p>
                            </div>
                            <div class="header-actions">
                                <button class="btn btn-success btn-sm" onclick="createUser()">
                                    <i class="fa fa-plus me-1"></i>Create Agent
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable" id="usersTable">
                                <thead>
                                    <tr>
                                        <th width="2%">No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Commission (%)</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td class="text-center">{{ ++$key }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->approval_status === 'approved')
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Approved</span>
                                                @elseif($user->approval_status === 'pending')
                                                    <span class="badge bg-warning"><i class="fa fa-clock"></i> Pending</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->commission_percentage)
                                                    <span class="badge bg-success">{{ number_format($user->commission_percentage, 2) }}%</span>
                                                @else
                                                    <span class="text-muted">0%</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->roles->isNotEmpty())
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-{{ $role->name === 'Super Admin' ? 'danger' : ($role->name === 'Admin' ? 'success' : ($role->name === 'Agent' ? 'warning' : 'info')) }}">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <ul class="action">
                                                    @if($user->approval_status === 'pending')
                                                        <li class="review" title="Review Agent">
                                                            <a href="#!" onclick="reviewAgent({{ $user->id }})">
                                                                <i class="fa-solid fa-clipboard-check text-warning"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="view">
                                                        <a href="#!" onclick="viewUser({{ $user->id }})">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li class="edit">
                                                        <a href="#!" onclick="editUser({{ $user->id }})">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <a href="#!" onclick="deleteUser({{ $user->id }})">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </li>
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

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="userModalLabel">
                        <i class="fa fa-user me-2"></i>Create Agent
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="id">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fa fa-user text-primary me-1"></i>Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fa fa-envelope text-primary me-1"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="commission_percentage" class="form-label fw-bold">
                                    <i class="fa fa-percent text-primary me-1"></i>Commission Percentage
                                </label>
                                <input type="number" class="form-control" id="commission_percentage" name="commission_percentage" placeholder="Enter commission percentage (0-100)" min="0" max="100" step="0.01" value="0">
                                <div class="invalid-feedback" id="commissionPercentageError"></div>
                                <small class="text-muted">Enter commission percentage between 0 and 100</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label fw-bold">
                                    <i class="fa fa-birthday-cake text-primary me-1"></i>Date of Birth
                                </label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                <div class="invalid-feedback" id="dateOfBirthError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label fw-bold">
                                    <i class="fa fa-map-marker text-primary me-1"></i>Location
                                </label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="City, State">
                                <div class="invalid-feedback" id="locationError"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="bank_account_number" class="form-label fw-bold">
                                    <i class="fa fa-university text-primary me-1"></i>Bank Account Number
                                </label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="Enter bank account number">
                                <div class="invalid-feedback" id="bankAccountNumberError"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="subscribe_newsletter" name="subscribe_newsletter" value="1">
                                    <label class="form-check-label" for="subscribe_newsletter">
                                        Subscribe to newsletter and receive offers
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fa fa-lock text-primary me-1"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                                <div class="invalid-feedback" id="passwordError"></div>
                                <p id="passwordHelp">Leave blank to keep current password (for edit)</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">
                                    <i class="fa fa-lock text-primary me-1"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                                <div class="invalid-feedback" id="password_confirmationError"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="saveUserBtn" onclick="saveUser()">
                        <i class="fa fa-save me-1"></i>Save User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewUserModalLabel">
                        <i class="fa fa-eye me-2"></i>View Agent
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-user me-1"></i>Name</h6>
                                            <p class="mb-3" id="viewName"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-envelope me-1"></i>Email</h6>
                                            <p class="mb-3" id="viewEmail"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-check-circle me-1"></i>Status</h6>
                                            <p class="mb-3" id="viewStatus"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-percent me-1"></i>Commission</h6>
                                            <p class="mb-3" id="viewCommission"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-birthday-cake me-1"></i>Date of Birth</h6>
                                            <p class="mb-3" id="viewDateOfBirth"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-map-marker me-1"></i>Location</h6>
                                            <p class="mb-3" id="viewLocation"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <h6 class="text-primary"><i class="fa fa-university me-1"></i>Bank Account Number</h6>
                                            <p class="mb-3" id="viewBankAccount"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-envelope me-1"></i>Newsletter Subscription</h6>
                                            <p class="mb-3" id="viewNewsletter"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-calendar me-1"></i>Created At</h6>
                                            <p class="mb-3" id="viewCreatedAt"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fa fa-edit me-1"></i>Updated At</h6>
                                            <p class="mb-3" id="viewUpdatedAt"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Agent Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title text-dark" id="reviewModalLabel">
                        <i class="fa fa-clipboard-check me-2"></i>Review Agent Application
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Review the agent details below and set commission if approving.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-user me-1"></i>Name</h6>
                            <p class="mb-0" id="reviewName"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-envelope me-1"></i>Email</h6>
                            <p class="mb-0" id="reviewEmail"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-phone me-1"></i>Contact Number</h6>
                            <p class="mb-0" id="reviewContact"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-birthday-cake me-1"></i>Date of Birth</h6>
                            <p class="mb-0" id="reviewDateOfBirth"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-map-marker me-1"></i>Location</h6>
                            <p class="mb-0" id="reviewLocation"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-university me-1"></i>Bank Account Number</h6>
                            <p class="mb-0" id="reviewBankAccount"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-envelope me-1"></i>Newsletter Subscription</h6>
                            <p class="mb-0" id="reviewNewsletter"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="fa fa-calendar me-1"></i>Applied At</h6>
                            <p class="mb-0" id="reviewCreatedAt"></p>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <h6 class="text-primary mb-3"><i class="fa fa-percent me-1"></i>Commission Percentage (Required for Approval)</h6>
                            <input type="number" class="form-control" id="reviewCommission" name="commission_percentage" 
                                   placeholder="Enter commission percentage (0-100)" min="0" max="100" step="0.01" value="0">
                            <small class="text-muted">Set commission percentage for this agent</small>
                        </div>
                    </div>
                    <input type="hidden" id="reviewUserId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="processRejection()">
                        <i class="fa fa-times me-1"></i>Reject
                    </button>
                    <button type="button" class="btn btn-success" onclick="processApproval()">
                        <i class="fa fa-check me-1"></i>Approve with Commission
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
                        <p>Do you really want to delete this user? This action cannot be undone.</p>
                        <input type="hidden" id="deleteUserId">
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
            dataTable = $('#usersTable').DataTable({
                "responsive": true,
                "pageLength": 10,
                "order": [],
            });
        });

        function createUser() {
            isEditMode = false;
            document.getElementById('userModalLabel').innerHTML = '<i class="fa fa-user me-2"></i>Create User';
            document.getElementById('saveUserBtn').innerHTML = '<i class="fa fa-save me-1"></i>Save User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
            document.getElementById('passwordHelp').style.display = 'none';
            clearValidationErrors();
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        function editUser(id) {
            isEditMode = true;
            document.getElementById('userModalLabel').innerHTML = '<i class="fa fa-edit me-2"></i>Edit User';
            document.getElementById('saveUserBtn').innerHTML = '<i class="fa fa-save me-1"></i>Update User';
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('passwordHelp').style.display = 'block';
            
            fetch(`/agents/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('userId').value = data.data.id;
                        document.getElementById('name').value = data.data.name;
                        document.getElementById('email').value = data.data.email;
                        document.getElementById('commission_percentage').value = data.data.commission_percentage || 0;
                        document.getElementById('date_of_birth').value = data.data.date_of_birth || '';
                        document.getElementById('location').value = data.data.location || '';
                        document.getElementById('bank_account_number').value = data.data.bank_account_number || '';
                        document.getElementById('subscribe_newsletter').checked = data.data.subscribe_newsletter || false;
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';
                        clearValidationErrors();
                        new bootstrap.Modal(document.getElementById('userModal')).show();
                    }
                })
                .catch(error => {
                    showNotification('Error fetching user data', 'error');
                    console.error('Error:', error);
                });
        }

        function viewUser(id) {
            fetch(`/agents/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('viewName').textContent = data.data.name;
                        document.getElementById('viewEmail').textContent = data.data.email;
                        
                        // Status badge
                        let statusHtml = '';
                        if (data.data.approval_status === 'approved') {
                            statusHtml = '<span class="badge bg-success"><i class="fa fa-check"></i> Approved</span>';
                        } else if (data.data.approval_status === 'pending') {
                            statusHtml = '<span class="badge bg-warning"><i class="fa fa-clock"></i> Pending</span>';
                        } else {
                            statusHtml = '<span class="badge bg-danger"><i class="fa fa-times"></i> Rejected</span>';
                        }
                        document.getElementById('viewStatus').innerHTML = statusHtml;
                        
                        document.getElementById('viewCommission').textContent = (data.data.commission_percentage || 0) + '%';
                        document.getElementById('viewDateOfBirth').textContent = data.data.date_of_birth || 'N/A';
                        document.getElementById('viewLocation').textContent = data.data.location || 'N/A';
                        document.getElementById('viewBankAccount').textContent = data.data.bank_account_number || 'N/A';
                        document.getElementById('viewNewsletter').textContent = data.data.subscribe_newsletter ? 'Subscribed' : 'Not Subscribed';
                        document.getElementById('viewCreatedAt').textContent = new Date(data.data.created_at).toLocaleString();
                        document.getElementById('viewUpdatedAt').textContent = new Date(data.data.updated_at).toLocaleString();
                        new bootstrap.Modal(document.getElementById('viewUserModal')).show();
                    }
                })
                .catch(error => {
                    showNotification('Error fetching user data', 'error');
                    console.error('Error:', error);
                });
        }

        // Save user (create or update)
        function saveUser() {
            const form = document.getElementById('userForm');
            const formData = new FormData(form);
            const id = document.getElementById('userId').value;
            
            const url = isEditMode ? `/agents/${id}` : '/agents';
            const method = isEditMode ? 'PUT' : 'POST';
            
            // Clear previous validation errors
            clearValidationErrors();
            
            // Show loading state
            const saveBtn = document.getElementById('saveUserBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving...';
            saveBtn.disabled = true;
            
            const requestData = {
                name: formData.get('name'),
                email: formData.get('email'),
                commission_percentage: formData.get('commission_percentage') || 0,
                date_of_birth: formData.get('date_of_birth') || null,
                location: formData.get('location') || null,
                bank_account_number: formData.get('bank_account_number') || null,
                subscribe_newsletter: document.getElementById('subscribe_newsletter').checked ? 1 : 0,
            };

            // Only include password if it's provided
            if (formData.get('password')) {
                requestData.password = formData.get('password');
                requestData.password_confirmation = formData.get('password_confirmation');
            }
            
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
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
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

        // Delete user
        function deleteUser(id) {
            document.getElementById('deleteUserId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Confirm delete
        function confirmDelete() {
            const id = document.getElementById('deleteUserId').value;
            
            fetch(`/agents/${id}`, {
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

        // Review agent
        function reviewAgent(id) {
            fetch(`/agents/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('reviewUserId').value = data.data.id;
                        document.getElementById('reviewName').textContent = data.data.name;
                        document.getElementById('reviewEmail').textContent = data.data.email;
                        document.getElementById('reviewContact').textContent = data.data.contact_no || 'N/A';
                        document.getElementById('reviewDateOfBirth').textContent = data.data.date_of_birth || 'N/A';
                        document.getElementById('reviewLocation').textContent = data.data.location || 'N/A';
                        document.getElementById('reviewBankAccount').textContent = data.data.bank_account_number || 'N/A';
                        document.getElementById('reviewNewsletter').textContent = data.data.subscribe_newsletter ? 'Subscribed' : 'Not Subscribed';
                        document.getElementById('reviewCreatedAt').textContent = new Date(data.data.created_at).toLocaleString();
                        document.getElementById('reviewCommission').value = data.data.commission_percentage || 0;
                        new bootstrap.Modal(document.getElementById('reviewModal')).show();
                    }
                })
                .catch(error => {
                    showNotification('Error fetching agent data', 'error');
                    console.error('Error:', error);
                });
        }

        // Process approval with commission
        function processApproval() {
            const id = document.getElementById('reviewUserId').value;
            const commission = document.getElementById('reviewCommission').value;

            if (!commission || commission < 0 || commission > 100) {
                showNotification('Please enter a valid commission percentage (0-100)', 'error');
                return;
            }

            if (!confirm('Are you sure you want to approve this agent with ' + commission + '% commission?')) {
                return;
            }

            fetch(`/agents/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ commission_percentage: commission })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Failed to approve agent', 'error');
                }
            })
            .catch(error => {
                showNotification('Error approving agent', 'error');
                console.error('Error:', error);
            });
        }

        // Process rejection
        function processRejection() {
            const id = document.getElementById('reviewUserId').value;

            if (!confirm('Are you sure you want to reject this agent application?')) {
                return;
            }

            fetch(`/agents/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Failed to reject agent', 'error');
                }
            })
            .catch(error => {
                showNotification('Error rejecting agent', 'error');
                console.error('Error:', error);
            });
        }
    </script>
@endsection
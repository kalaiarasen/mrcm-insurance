@extends('layouts.main')

@section('title', 'Announcements')

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
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Announcements</h3>
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
                        <li class="breadcrumb-item active">Announcements</li>
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
                                <h5>Announcements Management</h5>
                                <p class="f-m-light mt-1">Create and manage announcements</p>
                            </div>
                            <div class="header-actions">
                                <button class="btn btn-success btn-sm" onclick="createAnnouncement()">
                                    <i class="fa fa-plus me-1"></i>Create Announcement
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable" id="announcementsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        <tr>
                                            <td>{{ $announcement->id }}</td>
                                            <td>{{ $announcement->title }}</td>
                                            <td>{{ Str::limit($announcement->description, 100) }}</td>
                                            <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <ul class="action">
                                                    <li class="edit">
                                                        <a href="#!" onclick="editAnnouncement({{ $announcement->id }})">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <a href="#!" onclick="deleteAnnouncement({{ $announcement->id }})">
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

    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="announcementModalLabel">
                        <i class="fa fa-bullhorn me-2"></i>Create Announcement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="announcementForm">
                        <input type="hidden" id="announcementId" name="id">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">
                                Title
                            </label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter announcement title" required>
                            <div class="invalid-feedback" id="titleError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">
                                Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="6" placeholder="Enter announcement description" required></textarea>
                            <div class="invalid-feedback" id="descriptionError"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="saveAnnouncementBtn" onclick="saveAnnouncement()">
                        <i class="fa fa-save me-1"></i>Save Announcement
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                        <p>Do you really want to delete this announcement? This action cannot be undone.</p>
                        <input type="hidden" id="deleteAnnouncementId">
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
            // Initialize DataTable
            dataTable = $('#announcementsTable').DataTable({
                "responsive": true,
                "pageLength": 10,
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    {
                        "targets": -1,
                        "orderable": false
                    }
                ]
            });
        });

        // Create new announcement
        function createAnnouncement() {
            isEditMode = false;
            document.getElementById('announcementModalLabel').innerHTML = '<i class="fa fa-bullhorn me-2"></i>Create Announcement';
            document.getElementById('saveAnnouncementBtn').innerHTML = '<i class="fa fa-save me-1"></i>Save Announcement';
            document.getElementById('announcementForm').reset();
            document.getElementById('announcementId').value = '';
            clearValidationErrors();
            new bootstrap.Modal(document.getElementById('announcementModal')).show();
        }

        // Edit announcement
        function editAnnouncement(id) {
            isEditMode = true;
            document.getElementById('announcementModalLabel').innerHTML = '<i class="fa fa-edit me-2"></i>Edit Announcement';
            document.getElementById('saveAnnouncementBtn').innerHTML = '<i class="fa fa-save me-1"></i>Update Announcement';
            
            // Fetch announcement data
            fetch(`/announcements/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('announcementId').value = data.data.id;
                        document.getElementById('title').value = data.data.title;
                        document.getElementById('description').value = data.data.description;
                        clearValidationErrors();
                        new bootstrap.Modal(document.getElementById('announcementModal')).show();
                    }
                })
                .catch(error => {
                    showNotification('Error fetching announcement data', 'error');
                    console.error('Error:', error);
                });
        }

        // Save announcement (create or update)
        function saveAnnouncement() {
            const form = document.getElementById('announcementForm');
            const formData = new FormData(form);
            const id = document.getElementById('announcementId').value;
            
            const url = isEditMode ? `/announcements/${id}` : '/announcements';
            const method = isEditMode ? 'PUT' : 'POST';
            
            // Clear previous validation errors
            clearValidationErrors();
            
            // Show loading state
            const saveBtn = document.getElementById('saveAnnouncementBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving...';
            saveBtn.disabled = true;
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: formData.get('title'),
                    description: formData.get('description')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('announcementModal')).hide();
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

        // Delete announcement
        function deleteAnnouncement(id) {
            document.getElementById('deleteAnnouncementId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Confirm delete
        function confirmDelete() {
            const id = document.getElementById('deleteAnnouncementId').value;
            
            fetch(`/announcements/${id}`, {
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

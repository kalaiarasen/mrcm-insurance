@extends('layouts.main')

@section('title', 'Policy Holders')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        #dateFilterCollapse .card-body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        #dateFilterCollapse .form-control, 
        #dateFilterCollapse .form-select {
            border: 2px solid #e3e6ea;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        #dateFilterCollapse .form-control:focus, 
        #dateFilterCollapse .form-select:focus {
            border-color: #3D9FD8;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            transform: translateY(-1px);
        }
        
        #dateFilterCollapse .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(25, 135, 84, 0.4);
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border: 1px solid #b6d4da;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Animation for filter collapse */
        #dateFilterCollapse {
            transition: all 0.4s ease;
        }
        
        /* Quick select styling */
        #quickDateSelect option {
            padding: 8px;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Policy Holders</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-task') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Policy Holders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Policies</h5>
                                <p class="f-m-light mt-1">Policy status update</p>
                            </div>
                            <div class="header-actions">
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#dateFilterCollapse" aria-expanded="false" aria-controls="dateFilterCollapse">
                                    <i class="fa fa-filter me-1"></i>Date Filter
                                </button>
                            </div>
                        </div>
                        
                        <!-- Date Filter Section -->
                        <div class="collapse mt-3" id="dateFilterCollapse">
                            <div class="card card-body border-light bg-light">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label for="startDate" class="form-label fw-bold text-dark">
                                            <i class="fa fa-calendar-alt text-primary me-1"></i>Start Date
                                        </label>
                                        <input type="date" class="form-control" id="startDate" name="start_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="endDate" class="form-label fw-bold text-dark">
                                            <i class="fa fa-calendar-alt text-primary me-1"></i>End Date
                                        </label>
                                        <input type="date" class="form-control" id="endDate" name="end_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-dark">Quick Select</label>
                                        <select class="form-select" id="quickDateSelect">
                                            <option value="">Select Range</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last7days">Last 7 Days</option>
                                            <option value="last30days">Last 30 Days</option>
                                            <option value="thismonth">This Month</option>
                                            <option value="lastmonth">Last Month</option>
                                            <option value="thisyear">This Year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success flex-fill" onclick="applyDateFilter()">
                                                <i class="fa fa-search me-1"></i>Apply Filter
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="clearDateFilter()" title="Clear Filter">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Filter Display -->
                                <div id="activeFilterDisplay" class="mt-3" style="display: none;">
                                    <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                        <i class="fa fa-info-circle me-1"></i>
                                        <strong>Active Filter:</strong> <span id="filterText"></span>
                                        <button type="button" class="btn-close" aria-label="Close" onclick="clearDateFilter()"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Nation Status</th>
                                        <th>NRIC No</th>
                                        <th>Email</th>
                                        <th>Contact No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policyHolders as $holder)
                                        <tr>
                                            <td>
                                                <small>{{ $holder->created_at->format('d-M-Y') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $holder->applicantProfile?->title ?? '' }}. {{ $holder->name }}</strong>
                                            </td>
                                            <td>
                                                {{ ucwords($holder->applicantProfile?->gender ?? '-') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucwords($holder->applicantProfile?->nationality_status ?? '-') }}</span>
                                            </td>
                                            <td>
                                                {{ $holder->applicantProfile?->nric_number ?? $holder->applicantProfile?->passport_number ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $holder->email }}
                                            </td>
                                            <td>
                                                {{ $holder->contact_no }}
                                            </td>
                                            <td>
                                                <ul class="action">
                                                    <li class="edit">
                                                        <a href="#" onclick="editPolicyHolder({{ $holder->id }}); return false;" title="Edit">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    </li>
                                                    <li class="view">
                                                        <a href="#" onclick="viewPolicyHolder({{ $holder->id }}); return false;" title="View Details">
                                                            <i class="fa-regular fa-eye"></i>
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
    </div><!-- Container-fluid Ends-->

    <!-- View Details Modal -->
    <div class="modal fade" id="viewPolicyHolderModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Policy Holder Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="viewDetailsContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPolicyHolderModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Policy Holder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Basic Information Section -->
                        <div class="mb-3">
                            <label for="editName" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                            <div class="invalid-feedback d-block" id="nameError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editEmail" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                            <div class="invalid-feedback d-block" id="emailError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editContactNo" class="form-label fw-bold">Contact No</label>
                            <input type="text" class="form-control" id="editContactNo" name="contact_no" required>
                            <div class="invalid-feedback d-block" id="contactNoError"></div>
                        </div>

                        <!-- Password Section -->
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">
                            <i class="fa fa-lock me-2"></i>Change Password (Optional)
                        </h6>

                        <div class="mb-3">
                            <label for="editCurrentPassword" class="form-label fw-bold">Current Password</label>
                            <input type="password" class="form-control" id="editCurrentPassword" name="current_password">
                            <div class="invalid-feedback d-block" id="currentPasswordError"></div>
                            <small class="text-muted">Leave blank if you don't want to change password</small>
                        </div>

                        <div class="mb-3">
                            <label for="editNewPassword" class="form-label fw-bold">New Password</label>
                            <input type="password" class="form-control" id="editNewPassword" name="new_password">
                            <div class="invalid-feedback d-block" id="newPasswordError"></div>
                            <small class="text-muted d-block">
                                Password must contain:
                                <ul class="mb-2 mt-2">
                                    <li>At least 8 characters</li>
                                    <li>Uppercase & lowercase letters</li>
                                    <li>Numbers & symbols</li>
                                </ul>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="editConfirmPassword" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" class="form-control" id="editConfirmPassword" name="confirm_password">
                            <div class="invalid-feedback d-block" id="confirmPasswordError"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        let dataTable;
        
        $(document).ready(function () {
            $(".datatable").DataTable();
        });

        // Show Notification Function
        function showNotification(message, type = 'info') {
            const alertTypes = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            };
            
            const alertClass = alertTypes[type] || 'alert-info';
            const iconMap = {
                'success': 'fa-check-circle',
                'error': 'fa-exclamation-circle',
                'warning': 'fa-exclamation-triangle',
                'info': 'fa-info-circle'
            };
            
            const icon = iconMap[type] || 'fa-info-circle';
            
            const alertHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fa ${icon} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Create container if it doesn't exist
            let alertContainer = document.getElementById('notificationContainer');
            if (!alertContainer) {
                alertContainer = document.createElement('div');
                alertContainer.id = 'notificationContainer';
                alertContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
                document.body.appendChild(alertContainer);
            }
            
            // Add the alert
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = alertHTML;
            const alertElement = tempDiv.firstElementChild;
            alertContainer.appendChild(alertElement);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                alertElement.classList.remove('show');
                alertElement.addEventListener('transitionend', () => {
                    alertElement.remove();
                }, { once: true });
            }, 5000);
        }

        document.getElementById('quickDateSelect').addEventListener('change', function() {
            const selection = this.value;
            const today = new Date();
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            
            let startDate, endDate;
            
            switch(selection) {
                case 'today':
                    startDate = endDate = today;
                    break;
                case 'yesterday':
                    startDate = endDate = new Date(today.getTime() - 24 * 60 * 60 * 1000);
                    break;
                case 'last7days':
                    startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                case 'last30days':
                    startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                case 'thismonth':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = today;
                    break;
                case 'lastmonth':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case 'thisyear':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = today;
                    break;
                default:
                    startDateInput.value = '';
                    endDateInput.value = '';
                    return;
            }
            
            if (startDate) {
                startDateInput.value = startDate.toISOString().split('T')[0];
            }
            if (endDate) {
                endDateInput.value = endDate.toISOString().split('T')[0];
            }
        });

        // Apply Date Filter Function
        function applyDateFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const quickSelect = document.getElementById('quickDateSelect').value;
            
            // Validation
            if (!startDate && !endDate && !quickSelect) {
                showNotification('Please select a date range or use quick select.', 'warning');
                return;
            }
            
            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                showNotification('Start date cannot be later than end date.', 'error');
                return;
            }
            
            // Show active filter
            displayActiveFilter(startDate, endDate, quickSelect);
            
            // Apply filter to DataTable (example - you'll need to implement actual filtering based on your data)
            filterDataTable(startDate, endDate);
            
            // document.querySelector('[data-bs-target="#dateFilterCollapse"]').click();
        }

        // Clear Date Filter Function
        function clearDateFilter() {
            // Clear inputs
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('quickDateSelect').value = '';
            
            // Hide active filter display
            document.getElementById('activeFilterDisplay').style.display = 'none';
            
            // Clear DataTable filter
            if (dataTable) {
                dataTable.search('').draw();
            }
            
            showNotification('Date filter cleared.', 'info');
        }

        // Display Active Filter
        function displayActiveFilter(startDate, endDate, quickSelect) {
            const filterDisplay = document.getElementById('activeFilterDisplay');
            const filterText = document.getElementById('filterText');
            
            let text = '';
            
            if (quickSelect) {
                const options = {
                    'today': 'Today',
                    'yesterday': 'Yesterday', 
                    'last7days': 'Last 7 Days',
                    'last30days': 'Last 30 Days',
                    'thismonth': 'This Month',
                    'lastmonth': 'Last Month',
                    'thisyear': 'This Year'
                };
                text = options[quickSelect] || quickSelect;
            } else {
                if (startDate && endDate) {
                    text = `${formatDate(startDate)} to ${formatDate(endDate)}`;
                } else if (startDate) {
                    text = `From ${formatDate(startDate)}`;
                } else if (endDate) {
                    text = `Until ${formatDate(endDate)}`;
                }
            }
            
            filterText.textContent = text;
            filterDisplay.style.display = 'block';
        }

        // Format Date for Display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Filter DataTable (implement based on your data structure)
        function filterDataTable(startDate, endDate) {
            // Example: Custom search function for DataTable
            // You'll need to adapt this based on your actual date column format
            
            if (!startDate && !endDate) {
                dataTable.search('').draw();
                return;
            }
            
            // Example implementation - you may need to customize this
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const dateColumn = 4; // Assuming date is in column 4 (Start date)
                    const rowDate = new Date(data[dateColumn]);
                    
                    if (startDate && endDate) {
                        return rowDate >= new Date(startDate) && rowDate <= new Date(endDate);
                    } else if (startDate) {
                        return rowDate >= new Date(startDate);
                    } else if (endDate) {
                        return rowDate <= new Date(endDate);
                    }
                    
                    return true;
                }
            );
            
            dataTable.draw();
        }

        // Clear custom search when clearing filter
        function clearCustomSearch() {
            $.fn.dataTable.ext.search = [];
        }

        // View Policy Holder Details
        function viewPolicyHolder(holderId) {
            $.ajax({
                url: `/policy-holders/${holderId}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        
                        // Helper function to convert to title case (ucwords equivalent)
                        const ucwords = (str) => {
                            if (!str) return str;
                            return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
                        };
                        
                        const detailsHTML = `
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Full Name:</strong></p>
                                    <p>${data.name}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Email:</strong></p>
                                    <p>${data.email}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Contact No:</strong></p>
                                    <p>${data.contact_no}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Joined Date:</strong></p>
                                    <p>${data.created_at}</p>
                                </div>
                            </div>
                        `;
                        
                        document.getElementById('viewDetailsContent').innerHTML = detailsHTML;
                        const modal = new bootstrap.Modal(document.getElementById('viewPolicyHolderModal'));
                        modal.show();
                    }
                },
                error: function(xhr) {
                    showNotification('Error loading policy holder details', 'error');
                    console.error(xhr);
                }
            });
        }

        // Edit Policy Holder
        let currentPolicyHolderId = null;

        function editPolicyHolder(holderId) {
            currentPolicyHolderId = holderId;
            
            // Fetch policy holder data
            $.ajax({
                url: `/policy-holders/${holderId}/edit`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        
                        // Populate form
                        document.getElementById('editName').value = data.name;
                        document.getElementById('editEmail').value = data.email;
                        document.getElementById('editContactNo').value = data.contact_no;
                        
                        // Clear password fields
                        document.getElementById('editCurrentPassword').value = '';
                        document.getElementById('editNewPassword').value = '';
                        document.getElementById('editConfirmPassword').value = '';
                        
                        // Clear error messages
                        clearFormErrors();
                        
                        // Update form action
                        const form = document.getElementById('editForm');
                        form.action = `/policy-holders/${holderId}`;
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('editPolicyHolderModal'));
                        modal.show();
                    }
                },
                error: function(xhr) {
                    showNotification('Error loading policy holder data', 'error');
                    console.error(xhr);
                }
            });
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            $.ajax({
                url: this.action,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editPolicyHolderModal'));
                        modal.hide();
                        
                        // Reload the page after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    clearFormErrors();
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        displayFormErrors(errors);
                        showNotification('Please fix the errors below', 'warning');
                    } else if (xhr.status === 403) {
                        showNotification('You are not authorized to perform this action', 'error');
                    } else {
                        showNotification('An error occurred while updating the policy holder', 'error');
                        console.error(xhr);
                    }
                }
            });
        });

        // Display form validation errors
        function displayFormErrors(errors) {
            const errorMap = {
                'name': 'nameError',
                'email': 'emailError',
                'contact_no': 'contactNoError',
                'current_password': 'currentPasswordError',
                'new_password': 'newPasswordError',
                'confirm_password': 'confirmPasswordError'
            };
            
            for (const field in errors) {
                if (errorMap[field]) {
                    const errorElement = document.getElementById(errorMap[field]);
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                        errorElement.style.color = '#dc3545';
                    }
                }
            }
        }

        // Clear form errors
        function clearFormErrors() {
            const errorFields = [
                'nameError',
                'emailError',
                'contactNoError',
                'currentPasswordError',
                'newPasswordError',
                'confirmPasswordError'
            ];
            
            errorFields.forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.textContent = '';
                }
            });
        }
    </script>
@endsection

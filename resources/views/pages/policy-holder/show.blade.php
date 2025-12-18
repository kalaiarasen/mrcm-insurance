@extends('layouts.main')

@section('title', 'Policy Holder Details')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        .info-card {
            border-left: 3px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: var(--body-font-color);
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--body-font-color);
            margin-bottom: 5px;
        }

        .info-value {
            color: var(--body-font-color);
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Policy Holder Details</h3>
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('policy-holder') }}">Policy Holders</a>
                        </li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- User Information Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fa fa-user me-2"></i>Personal Information
                            </h5>
                            @hasanyrole('Super Admin|Admin')
                                <button class="btn btn-primary btn-sm" onclick="editPolicyHolder({{ $user->id }})">
                                    <i class="fa fa-edit me-1"></i>Edit
                                </button>
                            @endhasanyrole
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">
                                    {{ $user->applicantProfile?->title ? $user->applicantProfile->title . '. ' : '' }}{{ $user->name }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">{{ $user->contact_no }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-label">Gender</div>
                                <div class="info-value">{{ ucfirst($user->applicantProfile->gender ?? '-') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Nationality Status</div>
                                <div class="info-value">
                                    <span
                                        class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $user->applicantProfile->nationality_status ?? '-')) }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-label">NRIC Number</div>
                                <div class="info-value">{{ $user->applicantProfile->nric_number ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Passport Number</div>
                                <div class="info-value">{{ $user->applicantProfile->passport_number ?? '-' }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-label">Loading (%)</div>
                                <div class="info-value">
                                    @if ($user->loading)
                                        <span class="badge bg-warning">{{ number_format($user->loading, 2) }}%</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="info-label">Registered Date</div>
                                <div class="info-value">{{ $user->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editPolicyHolderModal" tabindex="-1" aria-labelledby="editModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" id="editContactNo" name="contact_no"
                                    required>
                                <div class="invalid-feedback d-block" id="contactNoError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="editLoading" class="form-label fw-bold">Loading (%)</label>
                                <input type="number" class="form-control" id="editLoading" name="loading"
                                    step="0.01" min="0" max="100" placeholder="Enter loading percentage">
                                <div class="invalid-feedback d-block" id="loadingError"></div>
                                <small class="text-muted">Optional: Enter loading percentage (0-100)</small>
                            </div>

                            <!-- Password Section -->
                            <hr class="my-4">
                            <h6 class="text-muted mb-3">
                                <i class="fa fa-lock me-2"></i>Change Password (Optional)
                            </h6>

                            <div class="mb-3">
                                <label for="editCurrentPassword" class="form-label fw-bold">Current Password</label>
                                <input type="password" class="form-control" id="editCurrentPassword"
                                    name="current_password">
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
                                <input type="password" class="form-control" id="editConfirmPassword"
                                    name="confirm_password">
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

        <!-- Policy Applications History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Policy Applications History</h5>
                                <p class="f-m-light mt-1">Total applications: {{ $policyApplications->count() }}</p>
                            </div>
                            <div>
                                <a href="{{ route('policy-holder') }}" class="btn btn-light">
                                    <i class="fa fa-arrow-left me-1"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($policyApplications->count() > 0)
                            <div class="table-responsive">
                                <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th>Reference No</th>
                                            <th>Submission Date</th>
                                            <th>Policy Expiry Date</th>
                                            <th>Professional Type</th>
                                            <th>Liability Limit</th>
                                            <th>Total Payable</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($policyApplications as $application)
                                            <tr>
                                                <td>
                                                    <strong>{{ $application->reference_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $application->created_at->format('d M Y') }}<br>
                                                    <small
                                                        class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    @if ($application->policyPricing && $application->policyPricing->policy_expiry_date)
                                                        <strong>{{ \Carbon\Carbon::parse($application->policyPricing->policy_expiry_date)->format('d M Y') }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($application->healthcareService)
                                                        {{ ucfirst(str_replace('_', ' ', $application->healthcareService->professional_indemnity_type ?? '-')) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($application->policyPricing && $application->policyPricing->liability_limit)
                                                        RM
                                                        {{ number_format($application->policyPricing->liability_limit, 0) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($application->policyPricing)
                                                        <strong>RM
                                                            {{ number_format($application->policyPricing->total_payable ?? 0, 2) }}</strong>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $statusMap = [
                                                            'pending' => ['badge' => 'bg-warning', 'text' => 'Pending'],
                                                            'pay_now' => ['badge' => 'bg-info', 'text' => 'Pay Now'],
                                                            'paid' => ['badge' => 'bg-success', 'text' => 'Paid'],
                                                            'sent_uw' => [
                                                                'badge' => 'bg-primary',
                                                                'text' => 'Sent to UW',
                                                            ],
                                                            'active' => ['badge' => 'bg-success', 'text' => 'Active'],
                                                            'rejected' => [
                                                                'badge' => 'bg-danger',
                                                                'text' => 'Rejected',
                                                            ],
                                                        ];
                                                        $adminStatus = $application->admin_status ?? 'pending';
                                                        $statusInfo = $statusMap[$adminStatus] ?? [
                                                            'badge' => 'bg-secondary',
                                                            'text' => ucfirst($adminStatus),
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusInfo['badge'] }}">{{ $statusInfo['text'] }}</span>
                                                </td>
                                                <td>
                                                    @hasanyrole('Super Admin|Admin')
                                                        <a href="{{ route('policy-holders.application.show', ['user' => $user->id, 'application' => $application->id]) }}"
                                                            class="btn btn-sm btn-primary" title="View Details">
                                                            <i class="fa fa-eye me-1"></i>View
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">View only</span>
                                                    @endhasanyrole
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                No policy applications found for this user.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".datatable").DataTable({
                order: [
                    [1, 'desc']
                ] // Sort by submission date descending
            });
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
                alertContainer.style.cssText =
                    'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
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
                }, {
                    once: true
                });
            }, 5000);
        }

        // Edit Policy Holder
        function editPolicyHolder(holderId) {
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
                        document.getElementById('editLoading').value = data.loading || '';

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
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'editPolicyHolderModal'));
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
                'loading': 'loadingError',
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
                'loadingError',
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

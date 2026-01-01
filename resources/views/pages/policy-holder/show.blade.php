@extends('layouts.main')

@php
    use App\Helpers\HealthcareHelper;
@endphp

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
                                <input type="text" class="form-control" id="editCurrentPassword" disabled>
                                <small class="text-muted">For information only - you can set a new password without knowing this</small>
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
                                            <th style="min-width: 250px">Policy Info</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Submitted Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($policyApplications as $application)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="mb-1"><strong>Ref:</strong>
                                                            {{ $application->reference_number ?? '-' }}</span>

                                                        <span class="text-muted small mb-1">
                                                            <i class="fa fa-briefcase me-1"></i>
                                                            Class:
                                                            @php
                                                                $healthcareService = $application->healthcareService;
                                                            @endphp
                                                            {{ HealthcareHelper::getClassValue($healthcareService) }}
                                                        </span>

                                                        @if ($application->policyPricing)
                                                            <span class="text-muted small mb-1">
                                                                <i class="fa fa-calendar me-1"></i>
                                                                {{ \Carbon\Carbon::parse($application->policyPricing->policy_start_date)->format('d M Y') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($application->policyPricing->policy_expiry_date)->format('d M Y') }}
                                                            </span>
                                                            <span class="text-muted small">
                                                                <i class="fa fa-shield-alt me-1"></i>Limit: RM
                                                                {{ number_format($application->policyPricing->liability_limit ?? 0) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">Pricing Pending</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="align-middle">RM
                                                    {{ number_format($application->policyPricing->total_payable ?? 0, 2) }}
                                                </td>
                                                <td class="align-middle">
                                                    @php
                                                        $statusMap = [
                                                            'pending' => ['badge' => 'bg-secondary', 'icon' => 'fa-clock', 'text' => 'Pending'],
                                                            'pay_now' => ['badge' => 'bg-danger', 'icon' => 'fa-clock', 'text' => 'Payment Required'],
                                                            'not_paid' => ['badge' => 'bg-danger', 'icon' => 'fa-exclamation-circle', 'text' => 'Not Paid'],
                                                            'paid' => ['badge' => 'bg-success', 'icon' => 'fa-check', 'text' => 'Paid'],
                                                            'sent_uw' => ['badge' => 'bg-warning', 'icon' => 'fa-paper-plane', 'text' => 'Sent to UW'],
                                                            'approved' => ['badge' => 'bg-success', 'icon' => 'fa-check-circle', 'text' => 'Approved'],
                                                            'active' => ['badge' => 'bg-success', 'icon' => 'fa-check-circle', 'text' => 'Active'],
                                                            'processing' => ['badge' => 'bg-warning', 'icon' => 'fa-spinner', 'text' => 'Processing'],
                                                            'rejected' => ['badge' => 'bg-danger', 'icon' => 'fa-times-circle', 'text' => 'Rejected'],
                                                            'new_case' => ['badge' => 'bg-secondary', 'icon' => 'fa-file', 'text' => 'New Case'],
                                                        ];
                                                        $adminStatus = $application->admin_status ?? 'pending';
                                                        $statusInfo = $statusMap[$adminStatus] ?? [
                                                            'badge' => 'bg-secondary',
                                                            'icon' => 'fa-info-circle',
                                                            'text' => ucfirst(str_replace('_', ' ', $adminStatus)),
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ $statusInfo['badge'] }}">
                                                        <i class="fa {{ $statusInfo['icon'] }} me-1"></i>{{ $statusInfo['text'] }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">{{ $application->created_at->format('d M Y') }}</td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        @hasanyrole('Super Admin|Admin')
                                                            <a href="{{ route('policy-holders.application.show', ['user' => $user->id, 'application' => $application->id]) }}"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="fa fa-eye me-1"></i>View
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">View only</span>
                                                        @endhasanyrole

                                                        {{-- Download CI Document --}}
                                                        @if ($application->certificate_document)
                                                            <a href="{{ Storage::url($application->certificate_document) }}"
                                                                class="btn btn-sm btn-outline-success" target="_blank"
                                                                title="Download CI">
                                                                <i class="fa fa-download"></i>
                                                                CI
                                                            </a>
                                                        @endif

                                                        {{-- Download Tax Receipt --}}
                                                        @if ($application->tax_receipt_path)
                                                            <a href="{{ Storage::url($application->tax_receipt_path) }}"
                                                                class="btn btn-sm btn-outline-info" target="_blank"
                                                                title="Download Tax Receipt">
                                                                <i class="fa fa-download"></i>
                                                                Tax
                                                            </a>
                                                        @endif

                                                        {{-- Download Policy Schedule --}}
                                                        @if ($application->policy_schedule_path)
                                                            <a href="{{ Storage::url($application->policy_schedule_path) }}"
                                                                class="btn btn-sm btn-outline-primary" target="_blank"
                                                                title="Download Policy Schedule">
                                                                <i class="fa fa-download"></i>
                                                                Certificate
                                                            </a>
                                                        @endif
                                                    </div>
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
                order: [[3, 'desc']], // Sort by submission date descending
                columnDefs: [
                    { orderable: false, targets: [4] } // Disable sorting on Action column
                ]
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
                        document.getElementById('editCurrentPassword').value = data.password;
                        document.getElementById('currentPasswordHidden').value = data.password;
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

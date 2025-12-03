@extends('layouts.main')

@section('title', 'My Profile')

@section('css')
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>My Profile</h3>
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
                        <li class="breadcrumb-item active">My Profile</li>
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
                                <h5>Agent Information</h5>
                                <p class="f-m-light mt-1">View and update your profile details</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Profile Image Upload -->
                                <div class="col-md-12 mb-4 text-center">
                                    <div class="mb-3">
                                        <img id="profileImagePreview" 
                                            src="{{ $agent->profile_image ? asset('storage/' . $agent->profile_image) : asset('assets/images/dashboard/profile.png') }}" 
                                            alt="Profile Image" 
                                            class="rounded-circle" 
                                            style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f0f0f0;">
                                    </div>
                                    <div>
                                        <label for="profile_image" class="btn btn-primary btn-sm">
                                            <i class="fa fa-camera me-1"></i>Upload Photo
                                        </label>
                                        <input type="file" class="d-none" id="profile_image" name="profile_image" accept="image/*">
                                        <div class="invalid-feedback d-block" id="profileImageError"></div>
                                        <div class="text-muted small mt-2">Accepted formats: JPG, PNG, GIF (Max: 2MB)</div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                            value="{{ $agent->name }}" required>
                                        <div class="invalid-feedback d-block" id="nameError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                            value="{{ $agent->email }}" required>
                                        <div class="invalid-feedback d-block" id="emailError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_no" class="form-label fw-bold">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_no" name="contact_no" 
                                            value="{{ $agent->contact_no }}">
                                        <div class="invalid-feedback d-block" id="contactNoError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label fw-bold">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                            value="{{ $agent->date_of_birth }}">
                                        <div class="invalid-feedback d-block" id="dateOfBirthError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location" class="form-label fw-bold">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" 
                                            value="{{ $agent->location }}" placeholder="City, State">
                                        <div class="invalid-feedback d-block" id="locationError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_account_number" class="form-label fw-bold">Bank Account Number</label>
                                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" 
                                            value="{{ $agent->bank_account_number }}" placeholder="For commission payments">
                                        <div class="invalid-feedback d-block" id="bankAccountNumberError"></div>
                                    </div>
                                </div>

                                <!-- Read-only Commission Rate -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission_percentage" class="form-label fw-bold">Commission Rate</label>
                                        <input type="text" class="form-control" id="commission_percentage" 
                                            value="{{ $agent->commission_percentage ?? 0 }}%" readonly disabled>
                                        <small class="text-muted">Your commission rate is set by the administrator</small>
                                    </div>
                                </div>

                                <!-- Newsletter Subscription -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="subscribe_newsletter" 
                                                name="subscribe_newsletter" value="1" 
                                                {{ $agent->subscribe_newsletter ? 'checked' : '' }}>
                                            <label class="form-check-label" for="subscribe_newsletter">
                                                Subscribe to newsletter and updates
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-1"></i>Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Handle profile form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                clearFormErrors();

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '{{ route("agent.profile.update") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                            
                            // Reload page after short delay
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            displayFormErrors(errors);
                            showNotification('Please fix the errors below', 'warning');
                        } else {
                            showNotification('An error occurred while updating your profile', 'error');
                            console.error(xhr);
                        }
                    }
                });
            });
        });

        // Display form validation errors
        function displayFormErrors(errors) {
            const errorMap = {
                'name': 'nameError',
                'email': 'emailError',
                'contact_no': 'contactNoError',
                'date_of_birth': 'dateOfBirthError',
                'location': 'locationError',
                'bank_account_number': 'bankAccountNumberError',
                'profile_image': 'profileImageError'
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
                'dateOfBirthError',
                'locationError',
                'bankAccountNumberError',
                'profileImageError'
            ];

            errorFields.forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.textContent = '';
                }
            });
        }

        // Profile image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
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
    </script>
@endsection

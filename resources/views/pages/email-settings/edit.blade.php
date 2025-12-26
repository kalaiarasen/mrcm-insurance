@extends('layouts.main')

@section('title', 'Email Settings')

@section('css')
    <style>
        .email-preview {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Email Settings</h3>
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
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active">Email Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="fa fa-envelope me-2 text-primary"></i>Underwriting Email Configuration
                        </h5>
                        <p class="text-muted mt-2">Configure email addresses for sending policy applications to underwriting</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('email-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Primary Recipient -->
                                <div class="col-md-6 mb-4">
                                    <label for="mail_new_policy" class="form-label fw-bold">
                                        <i class="fa fa-user text-primary me-1"></i>Primary Recipient Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('mail_new_policy') is-invalid @enderror" 
                                           id="mail_new_policy" 
                                           name="mail_new_policy"
                                           value="{{ old('mail_new_policy', $setting->mail_new_policy) }}" 
                                           placeholder="underwriting@example.com"
                                           required>
                                    @error('mail_new_policy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Main recipient for new policy applications</small>
                                </div>

                                <!-- Sender Email -->
                                <div class="col-md-6 mb-4">
                                    <label for="mail_from_uw" class="form-label fw-bold">
                                        <i class="fa fa-paper-plane text-primary me-1"></i>Sender Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('mail_from_uw') is-invalid @enderror" 
                                           id="mail_from_uw" 
                                           name="mail_from_uw"
                                           value="{{ old('mail_from_uw', $setting->mail_from_uw) }}" 
                                           placeholder="noreply@example.com"
                                           required>
                                    @error('mail_from_uw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Email address that appears in "From" field</small>
                                </div>

                                <!-- Sender Name -->
                                <div class="col-md-6 mb-4">
                                    <label for="mail_from_name" class="form-label fw-bold">
                                        <i class="fa fa-signature text-primary me-1"></i>Sender Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('mail_from_name') is-invalid @enderror" 
                                           id="mail_from_name" 
                                           name="mail_from_name"
                                           value="{{ old('mail_from_name', $setting->mail_from_name) }}" 
                                           placeholder="MRCM Insurance"
                                           required>
                                    @error('mail_from_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Name that appears in "From" field</small>
                                </div>

                                <!-- CC Recipients -->
                                <div class="col-md-6 mb-4">
                                    <label for="mail_cc_uw" class="form-label fw-bold">
                                        <i class="fa fa-users text-primary me-1"></i>CC Recipients
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('mail_cc_uw') is-invalid @enderror" 
                                              id="mail_cc_uw" 
                                              name="mail_cc_uw"
                                              rows="3"
                                              placeholder="email1@example.com,email2@example.com,email3@example.com"
                                              required>{{ old('mail_cc_uw', $setting->mail_cc_uw) }}</textarea>
                                    @error('mail_cc_uw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Enter multiple email addresses separated by commas (,)
                                    </small>
                                </div>
                            </div>

                            <!-- Email Preview -->
                            <div class="email-preview mb-4">
                                <h6 class="mb-3">
                                    <i class="fa fa-eye me-2"></i>Email Preview
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>From:</strong> 
                                            <span id="preview_from_name">{{ $setting->mail_from_name }}</span>
                                            &lt;<span id="preview_from_email">{{ $setting->mail_from_uw }}</span>&gt;
                                        </p>
                                        <p class="mb-2">
                                            <strong>To:</strong> 
                                            <span id="preview_to">{{ $setting->mail_new_policy }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>CC:</strong> 
                                            <span id="preview_cc">{{ $setting->mail_cc_uw }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Subject:</strong> 
                                            <span class="text-muted">[MRCM#123] [New Application] – GEGM Professional Indemnity – [Specialist] JOHN DOE</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>Save Settings
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fa fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6><i class="fa fa-info-circle me-2 text-info"></i>Information</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li><strong>Primary Recipient:</strong> This email will receive all new policy applications sent to underwriting</li>
                            <li><strong>CC Recipients:</strong> Additional people who should receive copies of the email</li>
                            <li><strong>Sender Email:</strong> The email address that appears as the sender</li>
                            <li><strong>Sender Name:</strong> The name that appears as the sender</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Live preview updates
        document.getElementById('mail_from_name').addEventListener('input', function(e) {
            document.getElementById('preview_from_name').textContent = e.target.value || 'MRCM Insurance';
        });

        document.getElementById('mail_from_uw').addEventListener('input', function(e) {
            document.getElementById('preview_from_email').textContent = e.target.value || 'insurance@mrcm.com.my';
        });

        document.getElementById('mail_new_policy').addEventListener('input', function(e) {
            document.getElementById('preview_to').textContent = e.target.value || 'underwriting@example.com';
        });

        document.getElementById('mail_cc_uw').addEventListener('input', function(e) {
            document.getElementById('preview_cc').textContent = e.target.value || 'No CC recipients';
        });
    </script>
@endsection

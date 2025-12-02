@extends('layouts.main')

@section('title', 'Dashboard Settings')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard Settings</h3>
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
                        <li class="breadcrumb-item active">Dashboard Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-cog"></i> Client Dashboard Welcome Banner</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="welcome_title" class="form-label">Welcome Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('welcome_title') is-invalid @enderror"
                                    id="welcome_title" name="welcome_title"
                                    value="{{ old('welcome_title', $setting->welcome_title) }}" required>
                                @error('welcome_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This will be displayed as the main heading on the client
                                    dashboard</small>
                            </div>

                            <div class="mb-3">
                                <label for="welcome_description" class="form-label">Welcome Description</label>
                                <textarea class="form-control @error('welcome_description') is-invalid @enderror" id="welcome_description"
                                    name="welcome_description" rows="6" placeholder="Enter welcome message for clients...">{{ old('welcome_description', $setting->welcome_description) }}</textarea>
                                @error('welcome_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This text will appear below the welcome title</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fa fa-eye"></i> Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="card" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);">
                            <div class="card-body p-4">
                                <h3 class="text-primary mb-3">{{ $setting->welcome_title }}</h3>
                                <p class="text-muted" style="white-space: pre-line;">{{ $setting->welcome_description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection

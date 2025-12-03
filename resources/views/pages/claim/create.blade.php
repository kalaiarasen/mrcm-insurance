@extends('layouts.main')

@section('title', 'File a Claim')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <style>
        .form-section {
            background: var(--card-color);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 3px solid #0d6efd;
        }

        .policy-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .policy-card {
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .policy-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        }

        .policy-card.active {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .policy-info {
            font-size: 0.9rem;
            color: var(--light-font);
            margin-top: 8px;
        }

        .form-label {
            font-weight: 600;
            color: var(--body-font-color);
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: var(--light-background);
        }

        .file-upload-area:hover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.02);
        }

        .file-upload-area.dragover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .file-list {
            margin-top: 15px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: var(--card-color);
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .file-name {
            flex-grow: 1;
            margin-left: 10px;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>File a Claim</h3>
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
                            <a href="{{ route('claims.index') }}">Claims</a>
                        </li>
                        <li class="breadcrumb-item active">File Claim</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('claims.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1: Select Policy -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fa fa-file-contract me-2"></i>Step 1: Select Your Policy</h5>
                            <p class="text-muted mb-0">Choose the policy related to this claim</p>
                        </div>
                        <div class="card-body">
                            @if ($policies->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i>
                                    You have no active paid policies. Please complete payment for a policy first.
                                </div>
                            @else
                                <div class="policy-selector">
                                    @foreach ($policies as $policy)
                                        <div class="policy-card" onclick="selectPolicy({{ $policy->id }})">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-2">
                                                        <strong>{{ $policy->reference_number ?? 'N/A' }}</strong>
                                                    </h6>
                                                </div>
                                                <input type="radio" name="policy_application_id"
                                                    value="{{ $policy->id }}" id="policy_{{ $policy->id }}"
                                                    class="form-check-input">
                                            </div>
                                            <div class="policy-info">
                                                <p class="mb-1">
                                                    <i class="fa fa-user me-1"></i>
                                                    {{ $policy->user->healthcareService->professional_indemnity_type ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fa fa-shield-alt me-1"></i>
                                                    RM
                                                    {{ number_format(($policy->policyPricing->liability_limit ?? 0) / 1000000, 1) }}M
                                                    Coverage
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fa fa-calendar me-1"></i>
                                                    {{ $policy->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('policy_application_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <!-- Step 2: Claim Internation Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fa fa-info-circle me-2"></i>Step 2: Claim Internation Details</h5>
                            <p class="text-muted mb-0">Provide information about the incident</p>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="incident_date" class="form-label">Incident Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('incident_date') is-invalid @enderror"
                                        id="incident_date" name="incident_date" value="{{ old('incident_date') }}"
                                        required>
                                    @error('incident_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="notification_date" class="form-label">Notification Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('notification_date') is-invalid @enderror"
                                        id="notification_date" name="notification_date"
                                        value="{{ old('notification_date') }}" required>
                                    @error('notification_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="claim_title" class="form-label">Claim Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('claim_title') is-invalid @enderror"
                                    id="claim_title" name="claim_title"
                                    placeholder="e.g., Lip paraesthesia after extraction" value="{{ old('claim_title') }}"
                                    required>
                                @error('claim_title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="claim_description" class="form-label">Claim Detail Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('claim_description') is-invalid @enderror" id="claim_description"
                                    name="claim_description" rows="5" placeholder="Describe the incident in detail..." required>{{ old('claim_description') }}</textarea>
                                @error('claim_description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Upload Documents -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fa fa-upload me-2"></i>Step 3: Upload Claim Internation Documents</h5>
                            <p class="text-muted mb-0">Upload supporting documents (PDF, JPG, PNG, DOC, DOCX - Max 5MB
                                each)</p>
                        </div>
                        <div class="card-body">
                            <div class="file-upload-area" id="fileUploadArea">
                                <i class="fa fa-cloud-upload-alt fa-3x mb-3 text-muted"></i>
                                <p>Drag and drop your documents here or click to select</p>
                                <small class="text-muted">Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX (Max 5MB
                                    each)</small>
                                <input type="file" name="documents[]" id="fileInput" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="d-none">
                            </div>

                            <div id="fileList" class="file-list"></div>

                            @error('documents')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('claims.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-arrow-left me-2"></i>Back to Claims
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="fa fa-check me-2"></i>Submit Claim
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');
        const submitBtn = document.getElementById('submitBtn');
        let selectedFiles = [];

        // File upload area click
        fileUploadArea.addEventListener('click', () => fileInput.click());

        // Drag and drop
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            selectedFiles = Array.from(files);
            updateFileList();
            updateSubmitButton();

            // Update the actual form input
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        function updateFileList() {
            fileList.innerHTML = '';
            if (selectedFiles.length > 0) {
                const heading = document.createElement('h6');
                heading.className = 'mt-4 mb-3';
                heading.innerHTML = '<i class="fa fa-file me-2"></i>Selected Files (' + selectedFiles.length + ')';
                fileList.appendChild(heading);

                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    fileItem.innerHTML = `
                        <i class="fa fa-file-pdf text-danger"></i>
                        <span class="file-name">${file.name} (${fileSize}MB)</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
            }
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
            updateSubmitButton();
        }

        function selectPolicy(policyId) {
            document.getElementById('policy_' + policyId).checked = true;
            updateSubmitButton();
        }

        function updateSubmitButton() {
            const policySelected = document.querySelector('input[name="policy_application_id"]:checked');
            submitBtn.disabled = !policySelected;
        }

        // Initialize
        updateSubmitButton();
    </script>
@endsection

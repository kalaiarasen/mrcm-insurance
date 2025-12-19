@extends('layouts.main')

@section('title', 'Quotation Request Details')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Quotation Request #{{ $quotationRequest->id }}</h3>
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
                        <li class="breadcrumb-item"><a href="{{ route('quotation-requests.index') }}">Quotation Requests</a>
                        </li>
                        <li class="breadcrumb-item active">Request #{{ $quotationRequest->id }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Request Details -->
            <div class="col-lg-8">
                <!-- Customer Information -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-user"></i> Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $quotationRequest->user->name }}</p>
                                <p><strong>Email:</strong> {{ $quotationRequest->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Submitted:</strong> {{ $quotationRequest->created_at->format('M d, Y H:i A') }}
                                </p>
                                <p><strong>Customer Status:</strong> <span
                                        class="badge {{ $quotationRequest->status_badge }}">{{ $quotationRequest->status_name }}</span>
                                </p>
                                <p><strong>Admin Status:</strong>
                                    @php
                                        $adminBadges = [
                                            'new' => 'bg-secondary',
                                            'quote' => 'bg-warning text-dark',
                                            'active' => 'bg-success',
                                        ];
                                        $badge = $adminBadges[$quotationRequest->admin_status] ?? 'bg-secondary';
                                    @endphp
                                    <span
                                        class="badge {{ $badge }}">{{ ucfirst($quotationRequest->admin_status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fa fa-box"></i> Product Information</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $quotationRequest->product->title }}</h6>
                        <span class="badge bg-info">{{ $quotationRequest->product->type_name }}</span>
                        @if ($quotationRequest->product->brochure_path)
                            <div class="mt-3">
                                <img src="{{ $quotationRequest->product->brochure_url }}"
                                    alt="{{ $quotationRequest->product->title }}" class="img-fluid rounded"
                                    style="max-height: 200px;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Data -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fa fa-file-alt"></i> Submitted Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotationRequest->form_data as $key => $value)
                                        <tr>
                                            <td><strong>{{ ucwords(str_replace('_', ' ', $key)) }}</strong></td>
                                            <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quotation Options -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fa fa-list"></i> Quotation Options</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addOptionModal">
                            <i class="fa fa-plus"></i> Add Option
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($quotationRequest->options->count() > 0)
                            <div class="row">
                                @foreach ($quotationRequest->options as $option)
                                    <div class="col-md-6 mb-3">
                                        <div
                                            class="card border {{ $quotationRequest->selected_option_id == $option->id ? 'border-success' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="card-title">
                                                        {{ $option->option_name }}
                                                        @if ($quotationRequest->selected_option_id == $option->id)
                                                            <span class="badge bg-success ms-2">Selected by Customer</span>
                                                        @endif
                                                    </h6>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="deleteOption({{ $option->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <p class="mb-2"><strong>Price:</strong> RM
                                                    {{ number_format($option->price, 2) }}</p>
                                                <p class="mb-2"><strong>Details:</strong></p>
                                                <p class="text-muted small">{{ $option->details }}</p>
                                                @if ($option->pdf_document)
                                                    <a href="{{ $option->pdf_document_url }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary mt-2">
                                                        <i class="fa fa-file-pdf"></i> View PDF
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No quotation options added yet. Click "Add Option" to
                                create quotation options for the customer.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Document (if uploaded) -->
                @if ($quotationRequest->payment_document)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5><i class="fa fa-file-invoice"></i> Payment Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($quotationRequest->quoted_price)
                                <div class="row mb-3">
                                    <div class="col-6"><strong>Quoted Price:</strong></div>
                                    <div class="col-6 text-end">RM {{ number_format($quotationRequest->quoted_price, 2) }}
                                    </div>
                                </div>
                            @endif

                            @if ($quotationRequest->wallet_amount_applied > 0)
                                <div class="row mb-3 text-success">
                                    <div class="col-6"><strong><i class="fa fa-wallet"></i> Wallet Applied:</strong></div>
                                    <div class="col-6 text-end">- RM
                                        {{ number_format($quotationRequest->wallet_amount_applied, 2) }}</div>
                                </div>
                            @endif

                            @if ($quotationRequest->final_price !== null)
                                <div class="row mb-3">
                                    <div class="col-6"><strong>Final Amount Paid:</strong></div>
                                    <div class="col-6 text-end">
                                        <h5 class="mb-0 text-primary">RM
                                            {{ number_format($quotationRequest->final_price, 2) }}</h5>
                                    </div>
                                </div>
                                <hr>
                            @endif

                            <p><strong>Payment Uploaded:</strong>
                                {{ $quotationRequest->payment_uploaded_at?->format('M d, Y H:i A') ?? 'N/A' }}</p>
                            <a href="{{ $quotationRequest->payment_document_url }}" target="_blank"
                                class="btn btn-primary w-100">
                                <i class="fa fa-download"></i> View Payment Proof
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Policy Upload (when status is active) -->
                @if ($quotationRequest->admin_status === 'active' || $quotationRequest->policy_document)
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa fa-file-contract"></i> Policy Document</h5>
                        </div>
                        <div class="card-body">
                            @if ($quotationRequest->policy_document)
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> Policy document has been uploaded
                                </div>
                                <a href="{{ asset('storage/' . $quotationRequest->policy_document) }}" target="_blank"
                                    class="btn btn-outline-success w-100 mb-3">
                                    <i class="fa fa-download"></i> View Policy Document
                                </a>
                                <hr>
                                <p class="text-muted mb-2"><strong>Upload New Policy (Replace existing):</strong></p>
                            @else
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Please upload the policy document for the customer
                                </div>
                            @endif

                            <form action="{{ route('quotation-requests.upload-policy', $quotationRequest->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="policy_document" class="form-label">Policy PDF <span
                                            class="text-danger">*</span></label>
                                    <input type="file"
                                        class="form-control @error('policy_document') is-invalid @enderror"
                                        id="policy_document" name="policy_document" accept=".pdf" required>
                                    @error('policy_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload the final policy document (PDF, max 10MB)</small>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fa fa-upload"></i>
                                    {{ $quotationRequest->policy_document ? 'Replace' : 'Upload' }} Policy Document
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status Update & Quotation -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-edit"></i> Admin Action</h5>
                    </div>
                    <div class="card-body">
                        <form id="statusForm" action="{{ route('quotation-requests.update', $quotationRequest->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="admin_status">Admin Status</label>
                                <select class="form-select @error('admin_status') is-invalid @enderror" id="admin_status"
                                    name="admin_status" required>
                                    <option value="new"
                                        {{ $quotationRequest->admin_status == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="quote"
                                        {{ $quotationRequest->admin_status == 'quote' ? 'selected' : '' }}>Quote
                                    </option>
                                    <option value="active"
                                        {{ $quotationRequest->admin_status == 'active' ? 'selected' : '' }}>Active</option>
                                </select>
                                @error('admin_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Customer Status: <span
                                        class="badge {{ $quotationRequest->status_badge }}">{{ $quotationRequest->status_name }}</span></small>
                            </div>

                            <!-- Hidden fields for quotation data (will be populated from modal) -->
                            <input type="hidden" name="quoted_price" id="hidden_quoted_price"
                                value="{{ old('quoted_price', $quotationRequest->quoted_price) }}">
                            <input type="hidden" name="quotation_details" id="hidden_quotation_details"
                                value="{{ old('quotation_details', $quotationRequest->quotation_details) }}">
                            <input type="hidden" name="admin_notes" id="hidden_admin_notes"
                                value="{{ old('admin_notes', $quotationRequest->admin_notes) }}">

                            <button type="button" id="updateBtn" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> Update Request
                            </button>
                        </form>

                        <hr class="my-3">

                        <div class="d-grid gap-2">
                            <a href="{{ route('quotation-requests.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <form action="{{ route('quotation-requests.destroy', $quotationRequest->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this quotation request?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-trash"></i> Delete Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Provide Quotation Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approvalModalLabel">
                        <i class="fa fa-check-circle"></i> Provide Quotation Options
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Add one or more quotation options for the customer to choose from:</p>

                    <!-- Quotation Options Container -->
                    <div id="quotationOptionsContainer">
                        <!-- Option 1 (default) -->
                        <div class="quotation-option-item card mb-3" data-option-index="1">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Option 1</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"
                                    style="display: none;">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Option Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control option-name"
                                            placeholder="e.g., Basic, Standard, Premium" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0"
                                            class="form-control option-price" placeholder="Enter price" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control option-details" rows="3" placeholder="Describe what's included in this option..."
                                        required></textarea>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">PDF Document (Optional)</label>
                                    <input type="file" class="form-control option-pdf" accept=".pdf">
                                    <small class="text-muted">Upload a PDF with detailed information (Max 10MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Another Option Button -->
                    <button type="button" class="btn btn-outline-primary mb-3" id="addAnotherOptionBtn">
                        <i class="fa fa-plus"></i> Add Another Option
                    </button>

                    <hr>

                    <!-- Admin Notes -->
                    <div class="mb-3">
                        <label for="modal_admin_notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="modal_admin_notes" rows="3"
                            placeholder="Add internal notes about this request..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmApprovalBtn">
                        <i class="fa fa-check"></i> Save Quotation Options
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Quotation Option Modal -->
    <div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="addOptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('quotation-requests.options.store', $quotationRequest->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addOptionModalLabel">
                            <i class="fa fa-plus-circle"></i> Add Quotation Option
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="option_name" class="form-label">Option Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="option_name" name="option_name"
                                placeholder="e.g., Basic, Standard, Premium" required>
                            <small class="text-muted">Give this option a descriptive name</small>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price (RM) <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price"
                                name="price" placeholder="Enter price" required>
                        </div>

                        <div class="mb-3">
                            <label for="details" class="form-label">Details <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="details" name="details" rows="5"
                                placeholder="Describe what's included in this option..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="pdf_document" class="form-label">PDF Document (Optional)</label>
                            <input type="file" class="form-control" id="pdf_document" name="pdf_document"
                                accept=".pdf">
                            <small class="text-muted">Upload a PDF with detailed information (Max 10MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Add Option
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Delete option function
        function deleteOption(optionId) {
            if (confirm('Are you sure you want to delete this quotation option?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/quotation-options/${optionId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Handle status change and form submission
            const statusSelect = document.getElementById('admin_status');
            const updateBtn = document.getElementById('updateBtn');
            const statusForm = document.getElementById('statusForm');
            const approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));

            // Update button click handler
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedStatus = statusSelect.value;

                // If status is "quote", show modal
                if (selectedStatus === 'quote') {
                    approvalModal.show();
                } else {
                    // For other statuses, submit directly
                    statusForm.submit();
                }
            });

            // Confirm approval button in modal
            let optionCounter = 1;

            // Add another option
            document.getElementById('addAnotherOptionBtn').addEventListener('click', function() {
                optionCounter++;
                const container = document.getElementById('quotationOptionsContainer');

                const newOption = document.createElement('div');
                newOption.className = 'quotation-option-item card mb-3';
                newOption.setAttribute('data-option-index', optionCounter);
                newOption.innerHTML = `
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Option ${optionCounter}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Option Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control option-name" 
                                    placeholder="e.g., Basic, Standard, Premium" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control option-price" 
                                    placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Details <span class="text-danger">*</span></label>
                            <textarea class="form-control option-details" rows="3" 
                                placeholder="Describe what's included in this option..." required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">PDF Document (Optional)</label>
                            <input type="file" class="form-control option-pdf" accept=".pdf">
                            <small class="text-muted">Upload a PDF with detailed information (Max 10MB)</small>
                        </div>
                    </div>
                `;

                container.appendChild(newOption);
                updateRemoveButtons();
            });

            // Remove option handler (event delegation)
            document.getElementById('quotationOptionsContainer').addEventListener('click', function(e) {
                if (e.target.closest('.remove-option-btn')) {
                    e.target.closest('.quotation-option-item').remove();
                    updateRemoveButtons();
                }
            });

            // Update remove button visibility
            function updateRemoveButtons() {
                const options = document.querySelectorAll('.quotation-option-item');
                options.forEach((option, index) => {
                    const removeBtn = option.querySelector('.remove-option-btn');
                    if (options.length > 1) {
                        removeBtn.style.display = 'inline-block';
                    } else {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            // Confirm and save quotation options
            document.getElementById('confirmApprovalBtn').addEventListener('click', async function() {
                const options = document.querySelectorAll('.quotation-option-item');
                const adminNotes = document.getElementById('modal_admin_notes').value;

                // Validate all options
                let isValid = true;
                const optionsData = [];

                options.forEach((optionEl, index) => {
                    const name = optionEl.querySelector('.option-name').value.trim();
                    const price = optionEl.querySelector('.option-price').value;
                    const details = optionEl.querySelector('.option-details').value.trim();
                    const pdfFile = optionEl.querySelector('.option-pdf').files[0];

                    if (!name || !price || !details) {
                        isValid = false;
                        alert(`Please fill in all required fields for Option ${index + 1}`);
                        return;
                    }

                    optionsData.push({
                        name: name,
                        price: price,
                        details: details,
                        pdfFile: pdfFile
                    });
                });

                if (!isValid) return;

                // Disable button to prevent double submission
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

                try {
                    // First, update status to 'quote'
                    statusSelect.value = 'quote';
                    document.getElementById('hidden_admin_notes').value = adminNotes;

                    // Submit status update
                    await new Promise((resolve, reject) => {
                        const formData = new FormData(statusForm);
                        fetch(statusForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(response => {
                            if (response.ok) resolve();
                            else reject();
                        }).catch(reject);
                    });

                    // Then, create each quotation option
                    for (const option of optionsData) {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('option_name', option.name);
                        formData.append('price', option.price);
                        formData.append('details', option.details);
                        if (option.pdfFile) {
                            formData.append('pdf_document', option.pdfFile);
                        }

                        await fetch(
                            '{{ route('quotation-requests.options.store', $quotationRequest->id) }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                    }

                    // Success - reload page
                    window.location.reload();
                } catch (error) {
                    alert('An error occurred while saving. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-check"></i> Save Quotation Options';
                }
            });
        });
    </script>
@endsection

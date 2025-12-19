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
                    <div class="card-header pb-0">
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
                    <div class="card-header pb-0">
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
                    <div class="card-header pb-0">
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

                <!-- Payment Document (if uploaded) -->
                @if ($quotationRequest->payment_document)
                    <div class="card mt-3">
                        <div class="card-header pb-0">
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

    <!-- Approval Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approvalModalLabel">
                        <i class="fa fa-check-circle"></i> Provide Quotation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Please provide the quotation details for the customer:</p>

                    <div class="mb-3">
                        <label for="modal_quoted_price" class="form-label">Quoted Price (RM) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control"
                            id="modal_quoted_price" placeholder="Enter quoted price" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_quotation_details" class="form-label">Quotation Details <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="modal_quotation_details" rows="6"
                            placeholder="Enter quotation details, coverage, terms, etc..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="modal_admin_notes" class="form-label">Admin Notes</label>
                        <textarea class="form-control" id="modal_admin_notes" rows="4"
                            placeholder="Add internal notes about this request..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmApprovalBtn">
                        <i class="fa fa-check"></i> Approve & Send Quotation
                    </button>
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
                    // Pre-fill modal with existing values if any
                    document.getElementById('modal_quoted_price').value = document.getElementById(
                        'hidden_quoted_price').value || '';
                    document.getElementById('modal_quotation_details').value = document.getElementById(
                        'hidden_quotation_details').value || '';
                    document.getElementById('modal_admin_notes').value = document.getElementById(
                        'hidden_admin_notes').value || '';

                    approvalModal.show();
                } else {
                    // For other statuses, submit directly
                    statusForm.submit();
                }
            });

            // Confirm approval button in modal
            document.getElementById('confirmApprovalBtn').addEventListener('click', function() {
                const quotedPrice = document.getElementById('modal_quoted_price').value;
                const quotationDetails = document.getElementById('modal_quotation_details').value;
                const adminNotes = document.getElementById('modal_admin_notes').value;

                // Validate required fields
                if (!quotedPrice || !quotationDetails) {
                    alert('Please fill in the Quoted Price and Quotation Details.');
                    return;
                }

                // Update hidden fields
                document.getElementById('hidden_quoted_price').value = quotedPrice;
                document.getElementById('hidden_quotation_details').value = quotationDetails;
                document.getElementById('hidden_admin_notes').value = adminNotes;

                // Close modal and submit form
                approvalModal.hide();
                statusForm.submit();
            });
        });
    </script>
@endsection

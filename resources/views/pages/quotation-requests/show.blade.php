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
                                <p><strong>Status:</strong> <span
                                        class="badge {{ $quotationRequest->status_badge }}">{{ $quotationRequest->status_name }}</span>
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
            </div>

            <!-- Status Update & Notes -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-edit"></i> Update Status</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quotation-requests.update', $quotationRequest->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="pending" {{ $quotationRequest->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="reviewed"
                                        {{ $quotationRequest->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <option value="quoted" {{ $quotationRequest->status == 'quoted' ? 'selected' : '' }}>
                                        Quoted</option>
                                    <option value="rejected"
                                        {{ $quotationRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="admin_notes">Admin Notes</label>
                                <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes"
                                    rows="6" placeholder="Add internal notes about this request...">{{ old('admin_notes', $quotationRequest->admin_notes) }}</textarea>
                                @error('admin_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> Update Request
                            </button>
                        </form>

                        <hr class="my-3">

                        <div class="d-grid gap-2">
                            <a href="{{ route('quotation-requests.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <form action="{{ route('quotation-requests.destroy', $quotationRequest->id) }}" method="POST"
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

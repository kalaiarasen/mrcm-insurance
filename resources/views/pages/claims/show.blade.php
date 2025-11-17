@extends('layouts.main')

@section('title', 'Claim')

@section('main_content')
<div class="page-wrapper">
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="m-0">{{ $claim->claim_title }}</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'closed' => 'secondary',
                                ];
                                $statusColor = $statusColors[$claim->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} p-2">
                                {{ ucfirst($claim->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Claim Action</label>
                                <p class="text-muted">{{ ucfirst($claim->action) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Claim Amount</label>
                                <p class="text-muted">
                                    @if ($claim->claim_amount)
                                        RM {{ number_format($claim->claim_amount, 2) }}
                                    @else
                                        <span class="text-secondary">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Dates Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Incident Date</label>
                                <p class="text-muted">{{ $claim->incident_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Notification Date</label>
                                <p class="text-muted">{{ $claim->notification_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Claim Description -->
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Claim Description</label>
                        <div class="alert alert-light border" style="min-height: 120px;">
                            <p class="m-0">{{ $claim->claim_description }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Policy Information -->
                    <div class="form-group">
                        <label class="form-label fw-bold">Associated Policy</label>
                        @if ($claim->policyApplication)
                            <div class="alert alert-info border">
                                <strong>Reference Number:</strong> {{ $claim->policyApplication->reference_number ?? 'N/A' }}
                                <br>
                                <strong>Status:</strong>
                                <span class="badge bg-success">{{ ucfirst($claim->policyApplication->status) }}</span>
                            </div>
                        @else
                            <p class="text-muted">No policy associated with this claim</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Uploaded Claim Documents Section -->
            <div class="card mt-4">
                <div class="card-header border-bottom">
                    <h5 class="m-0">Uploaded Claim Documents</h5>
                </div>

                <div class="card-body">
                    @if ($claim->claimDocuments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($claim->claimDocuments as $document)
                                        <tr>
                                            <td>
                                                <i class="fa fa-file"></i>
                                                {{ $document->document_name }}
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ strtoupper(pathinfo($document->document_name, PATHINFO_EXTENSION)) }}</span>
                                            </td>
                                            <td>
                                                {{ formatBytes($document->file_size) }}
                                            </td>
                                            <td>
                                                {{ $document->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('claims.documents.download', [$claim->id, $document->id]) }}"
                                                    class="btn btn-sm btn-primary" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Upload Additional Documents (if pending) -->
                        @if ($claim->status === 'pending')
                            <div class="border-top pt-3 mt-3">
                                <h6 class="mb-3">Add More Documents</h6>
                                <form action="{{ route('claims.update', $claim->id) }}" method="POST"
                                    enctype="multipart/form-data" id="addDocumentsForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="form-label">Upload Additional Documents</label>
                                        <input type="file" name="claim_documents[]" class="form-control"
                                            multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" id="additionalDocuments">
                                        <small class="text-muted d-block mt-2">
                                            Allowed formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB per file)
                                        </small>
                                    </div>

                                    <div id="additionalFilesPreview" class="mt-3"></div>

                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="fa fa-upload"></i> Upload Documents
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>
                            No documents uploaded for this claim yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="m-0">Claim Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Filed -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="m-0">Claim Filed</h6>
                                <small class="text-muted">{{ $claim->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        <!-- Incident Occurred -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="m-0">Incident Date</h6>
                                <small class="text-muted">{{ $claim->incident_date->format('d M Y') }}</small>
                            </div>
                        </div>

                        <!-- Notification Date -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="m-0">Notified</h6>
                                <small class="text-muted">{{ $claim->notification_date->format('d M Y') }}</small>
                            </div>
                        </div>

                        <!-- Status -->
                        @php
                            $statusMessage = match ($claim->status) {
                                'approved' => 'Claim Approved',
                                'rejected' => 'Claim Rejected',
                                'closed' => 'Claim Closed',
                                default => 'Awaiting Review',
                            };
                            $statusMarker = match ($claim->status) {
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'closed' => 'secondary',
                                default => 'warning',
                            };
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $statusMarker }}"></div>
                            <div class="timeline-content">
                                <h6 class="m-0">{{ $statusMessage }}</h6>
                                <small class="text-muted">{{ $claim->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Controls -->
            @hasanyrole('Super Admin|Admin|Agent')
            <div class="card mt-3">
                <div class="card-header border-bottom bg-primary text-white">
                    <h5 class="m-0">Admin Controls</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('claims.updateStatus', $claim->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Update Status</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $claim->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $claim->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $claim->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="closed" {{ $claim->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Claim Amount (RM)</label>
                            <input type="number" name="claim_amount" class="form-control" 
                                step="0.01" min="0" 
                                value="{{ $claim->claim_amount }}" 
                                placeholder="Enter approved amount">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="4" 
                                placeholder="Enter notes for this claim">{{ $claim->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-save"></i> Update Claim
                        </button>
                    </form>
                </div>
            </div>
            @endhasanyrole

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header border-bottom">
                    <h5 class="m-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('claims.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Claims
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Document Summary -->
            <div class="card mt-3">
                <div class="card-header border-bottom">
                    <h5 class="m-0">Documents Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="m-0">{{ $claim->claimDocuments->count() }}</h4>
                            <small class="text-muted">Files</small>
                        </div>
                        <div class="col-4">
                            <h4 class="m-0">
                                {{ formatBytes($claim->claimDocuments->sum('file_size')) }}
                            </h4>
                            <small class="text-muted">Total Size</small>
                        </div>
                        <div class="col-4">
                            <h4 class="m-0">
                                {{ $claim->claimDocuments->count() > 0 ? $claim->claimDocuments->map(fn($d) => pathinfo($d->document_name, PATHINFO_EXTENSION))->unique()->count() : 0 }}
                            </h4>
                            <small class="text-muted">Types</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 40px;
        height: calc(100% + 20px);
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-marker {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        flex-shrink: 0;
        z-index: 1;
        margin-top: 2px;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e0e0e0;
    }

    .timeline-content {
        margin-left: 20px;
        flex: 1;
    }
</style>

<script>
    $(document).ready(function() {
        // File preview for additional documents
        $('#additionalDocuments').on('change', function() {
            var files = this.files;
            var preview = $('#additionalFilesPreview');
            preview.html('');

            if (files.length > 0) {
                preview.append('<h6 class="mb-2">Selected Files:</h6>');
                var fileList = $('<ul class="list-unstyled"></ul>');

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var size = (file.size / 1024).toFixed(2);
                    fileList.append(
                        '<li class="mb-2"><i class="fa fa-file"></i> ' + file.name +
                        ' <span class="badge bg-light text-dark">' + size + ' KB</span></li>'
                    );
                }

                preview.append(fileList);
            }
        });

        // Handle form submission for additional documents
        $('#addDocumentsForm').on('submit', function(e) {
            // Could add additional validation here if needed
            // For now, let it submit normally
        });
    });

    // Helper function to format bytes
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
</script>
@endsection

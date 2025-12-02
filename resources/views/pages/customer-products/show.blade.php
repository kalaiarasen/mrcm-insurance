@extends('layouts.main')

@section('title', $product->title)

@section('css')
    <!-- Lightbox for image viewing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{ $product->title }}</h3>
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
                        <li class="breadcrumb-item"><a href="{{ route('customer.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">{{ $product->title }}</li>
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
            <!-- Product Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-info">{{ $product->type_name }}</span>
                        </div>

                        @if ($product->brochure_path)
                            <div class="mb-4">
                                <a href="{{ $product->brochure_url }}" data-lightbox="product-brochure"
                                    data-title="{{ $product->title }}">
                                    <img src="{{ $product->brochure_url }}" class="img-fluid rounded"
                                        alt="{{ $product->title }}"
                                        style="max-height: 400px; width: 100%; object-fit: cover; cursor: pointer;">
                                </a>
                                <small class="text-muted d-block mt-2"><i class="fa fa-info-circle"></i> Click image to view
                                    full size</small>
                            </div>
                        @endif

                        <h4 class="mb-3">Coverage & Benefits</h4>
                        <div class="coverage-content">
                            {!! $product->coverage_benefits !!}
                        </div>

                        @if ($product->pdf_path)
                            <div class="mt-4">
                                <a href="{{ $product->pdf_url }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fa fa-file-pdf"></i> {{ $product->pdf_title }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quotation Request Form -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-file-alt"></i> Request Quotation</h5>
                    </div>
                    <div class="card-body">
                        @if ($product->form_fields && isset($product->form_fields['fields']) && count($product->form_fields['fields']) > 0)
                            <form action="{{ route('customer.products.quotation', $product->id) }}" method="POST">
                                @csrf

                                @foreach ($product->form_fields['fields'] as $field)
                                    <div class="mb-3">
                                        <label class="form-label" for="{{ $field['name'] }}">
                                            {{ $field['label'] }}
                                            @if ($field['required'])
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        @if ($field['type'] === 'textarea')
                                            <textarea class="form-control @error($field['name']) is-invalid @enderror" id="{{ $field['name'] }}"
                                                name="{{ $field['name'] }}" rows="4" {{ $field['required'] ? 'required' : '' }}>{{ old($field['name']) }}</textarea>
                                        @elseif($field['type'] === 'select')
                                            <select class="form-select @error($field['name']) is-invalid @enderror"
                                                id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                                {{ $field['required'] ? 'required' : '' }}>
                                                <option value="">Select {{ $field['label'] }}</option>
                                                @foreach ($field['options'] ?? [] as $option)
                                                    <option value="{{ $option }}"
                                                        {{ old($field['name']) == $option ? 'selected' : '' }}>
                                                        {{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="{{ $field['type'] }}"
                                                class="form-control @error($field['name']) is-invalid @enderror"
                                                id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                                value="{{ old($field['name']) }}"
                                                {{ $field['required'] ? 'required' : '' }}>
                                        @endif

                                        @error($field['name'])
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-paper-plane"></i> Submit Request
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No quotation form available for this product. Please
                                contact us directly.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submission History -->
                @if ($previousRequests->isNotEmpty())
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa fa-history"></i> Your Submission History</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($previousRequests as $request)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">Request #{{ $request->id }}</h6>
                                                <small class="text-muted">
                                                    <i class="fa fa-calendar"></i>
                                                    {{ $request->created_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge {{ $request->status_badge }}">
                                                {{ $request->status_name }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('customer.quotations.show', $request->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i> View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

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

        // Lightbox configuration
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Image %1 of %2'
        });
    </script>

    <style>
        .coverage-content {
            line-height: 1.8;
        }

        .coverage-content h1,
        .coverage-content h2,
        .coverage-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .coverage-content ul,
        .coverage-content ol {
            margin-bottom: 1rem;
        }

        .coverage-content table {
            width: 100%;
            margin-bottom: 1rem;
        }
    </style>
@endsection

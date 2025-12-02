@extends('layouts.main')

@section('title', 'Products')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Our Products</h3>
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
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if ($products->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fa fa-box fa-3x text-muted mb-3"></i>
                            <h4>No Products Available</h4>
                            <p class="text-muted">There are currently no products available. Please check back later.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @foreach ($products as $product)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card product-card-horizontal">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <!-- Brochure Image Section -->
                                    <div class="col-lg-4 col-md-4">
                                        @if ($product->brochure_path)
                                            <img src="{{ $product->brochure_url }}" class="img-fluid product-brochure-img"
                                                alt="{{ $product->title }}">
                                        @else
                                            <div class="product-brochure-placeholder">
                                                <i class="fa fa-image fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>


                                    <!-- Content Section -->
                                    <div class="col-lg-8 col-md-8">
                                        <div class="row g-0 h-100">
                                            <!-- Left: Content -->
                                            <div class="col-lg-9">
                                                <div class="product-content-wrapper d-flex flex-column h-100">
                                                    <!-- Header -->
                                                    <div class="p-4 pb-3">
                                                        <h4 class="product-title mb-0">{{ $product->title }}</h4>
                                                    </div>

                                                    <!-- Coverage & Benefits -->
                                                    <div class="px-4 pb-3 flex-grow-1">
                                                        <h6 class="text-secondary mb-2">Coverage & Benefits</h6>
                                                        <div class="product-description">
                                                            {!! Str::limit($product->coverage_benefits, 1000) !!}
                                                        </div>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="px-4 pb-4 mt-auto">
                                                        <div class="d-flex flex-column gap-3">
                                                            @if ($product->brochure_path)
                                                                <a href="{{ $product->brochure_url }}" target="_blank"
                                                                    class="btn btn-dark w-100">
                                                                    <i class="fa fa-download me-2"></i>Download Brochure
                                                                </a>
                                                            @endif

                                                            @if ($product->pdf_path)
                                                                <a href="{{ $product->pdf_url }}" target="_blank"
                                                                    class="btn btn-dark w-100">
                                                                    <i class="fa fa-file-pdf me-2"></i>Policy Wording
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right: Badge and Quote Button -->
                                            <div class="col-lg-3 d-flex justify-content-end">
                                                <div class="d-flex flex-column h-100 py-4 pe-4">
                                                    <!-- TYPE OF POLICY badge at top -->
                                                    <div class="ms-auto mb-3">
                                                        <span class="badge bg-info">{{ $product->type_name }}</span>
                                                    </div>

                                                    <!-- Request Quote button at bottom -->
                                                    <div class="mt-auto text-end">
                                                        <a href="{{ route('customer.products.show', $product->id) }}"
                                                            class="btn btn-primary">
                                                            <i class="fa fa-pen-to-square me-2"></i>Request Quote
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@section('scripts')
    <style>
        .product-card-horizontal {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .product-card-horizontal:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .product-brochure-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 400px;
        }

        .product-brochure-placeholder {
            width: 100%;
            height: 100%;
            min-height: 400px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-content {
            background-color: #ffffff;
        }

        .product-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .product-description {
            color: #5a6c7d;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .product-actions {
            background-color: #f8f9fa;
            border-left: 1px solid #e9ecef;
        }

        .product-actions .btn {
            font-weight: 500;
            border-radius: 6px;
            padding: 12px 20px;
        }

        .product-actions .btn-dark {
            background-color: #3d4e5c;
            border-color: #3d4e5c;
        }

        .product-actions .btn-dark:hover {
            background-color: #2c3a45;
            border-color: #2c3a45;
        }

        .product-actions .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {

            .product-brochure-img,
            .product-brochure-placeholder {
                min-height: 300px;
            }

            .product-actions {
                border-left: none;
                border-top: 1px solid #e9ecef;
            }
        }

        @media (max-width: 767px) {

            .product-brochure-img,
            .product-brochure-placeholder {
                min-height: 250px;
            }

            .product-content,
            .product-actions {
                padding: 1.5rem !important;
            }
        }
    </style>
@endsection

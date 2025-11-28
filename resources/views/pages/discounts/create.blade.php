@extends('layouts.main')

@section('title', 'Add New Discount')

@section('css')
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Add New Discount</h3>
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
                            <a href="{{ route('discounts.index') }}">Discount Setup</a>
                        </li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>Discount Information</h5>
                        <p class="f-m-light mt-1">Create a new discount with percentage and date range</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('discounts.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Type -->
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type"
                                        name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="discount" {{ old('type') == 'discount' ? 'selected' : '' }}>Discount
                                        </option>
                                        <option value="voucher" {{ old('type') == 'voucher' ? 'selected' : '' }}>Voucher
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Product (conditional) -->
                                <div class="col-md-6" id="productField" style="display: none;">
                                    <label for="product" class="form-label">Product <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('product') is-invalid @enderror" id="product"
                                        name="product">
                                        <option value="">Select Product</option>
                                        <option value="pharmacist" {{ old('product') == 'pharmacist' ? 'selected' : '' }}>
                                            Pharmacist</option>
                                        <option value="medical_practice"
                                            {{ old('product') == 'medical_practice' ? 'selected' : '' }}>Medical Practice
                                        </option>
                                        <option value="dental_practice"
                                            {{ old('product') == 'dental_practice' ? 'selected' : '' }}>Dental Practice
                                        </option>
                                    </select>
                                    @error('product')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Voucher Code Display (conditional) -->
                                <div class="col-md-6" id="voucherCodeInfo" style="display: none;">
                                    <label class="form-label">Voucher Code</label>
                                    <div class="alert alert-info mb-0">
                                        <i class="fa fa-info-circle me-2"></i>
                                        Voucher code will be auto-generated upon creation
                                    </div>
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Percentage -->
                                <div class="col-md-6">
                                    <label for="percentage" class="form-label">Discount Percentage (%) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('percentage') is-invalid @enderror"
                                        id="percentage" name="percentage" placeholder="Enter percentage (0-100)"
                                        value="{{ old('percentage') }}" min="0" max="100" step="0.01"
                                        required>
                                    @error('percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter a value between 0 and 100</small>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" placeholder="Enter optional description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12">
                                    <hr class="mt-4 mb-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('discounts.index') }}" class="btn btn-light">
                                            <i class="fa fa-times me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-1"></i>Save Discount
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const productField = document.getElementById('productField');
            const productSelect = document.getElementById('product');
            const voucherCodeInfo = document.getElementById('voucherCodeInfo');
            const start = document.getElementById('start_date');
            const end = document.getElementById('end_date');
            const today = new Date().toISOString().split('T')[0];

            // Handle type change
            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    if (this.value === 'discount') {
                        productField.style.display = 'block';
                        productSelect.required = true;
                        voucherCodeInfo.style.display = 'none';
                    } else if (this.value === 'voucher') {
                        productField.style.display = 'none';
                        productSelect.required = false;
                        productSelect.value = '';
                        voucherCodeInfo.style.display = 'block';
                    } else {
                        productField.style.display = 'none';
                        productSelect.required = false;
                        voucherCodeInfo.style.display = 'none';
                    }
                });

                // Trigger on page load if there's an old value
                if (typeSelect.value) {
                    typeSelect.dispatchEvent(new Event('change'));
                }
            }

            // Date validation
            if (start) {
                // Prevent selecting past dates
                start.setAttribute('min', today);
                start.addEventListener('change', function() {
                    if (start.value) {
                        end.setAttribute('min', start.value);
                        if (end.value && end.value < start.value) {
                            end.value = start.value;
                        }
                    } else {
                        end.setAttribute('min', today);
                    }
                });
            }

            if (end) {
                end.setAttribute('min', today);
            }
        });
    </script>
@endsection

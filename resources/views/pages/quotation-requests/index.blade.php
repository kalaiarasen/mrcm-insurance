@extends('layouts.main')

@section('title', 'Quotation Requests')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Quotation Requests</h3>
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
                        <li class="breadcrumb-item active">Quotation Requests</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>
                                    @if ($selectedProduct)
                                        Quotation Requests for: {{ $selectedProduct->title }}
                                    @else
                                        All Quotation Requests
                                    @endif
                                </h5>
                            </div>
                            <div class="header-actions">
                                <form action="{{ route('quotation-requests.index') }}" method="GET"
                                    class="d-flex align-items-center gap-2">
                                    <select name="product" class="form-select form-select-sm" style="width: 250px;"
                                        onchange="this.form.submit()">
                                        <option value="">All Products</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $selectedProduct && $selectedProduct->id == $product->id ? 'selected' : '' }}>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        <tr>
                                            <td><strong>#{{ $quotation->id }}</strong></td>
                                            <td>
                                                <div>{{ $quotation->user->name }}</div>
                                                <small class="text-muted">{{ $quotation->user->email }}</small>
                                            </td>
                                            <td>
                                                <div>{{ $quotation->product->title }}</div>
                                                <small class="badge bg-info">{{ $quotation->product->type_name }}</small>
                                            </td>
                                            <td>{{ $quotation->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $quotation->status_badge }}">{{ $quotation->status_name }}</span>
                                            </td>
                                            <td>
                                                <ul class="action">
                                                    <li class="edit me-2">
                                                        <a href="{{ route('quotation-requests.show', $quotation->id) }}"
                                                            title="View Details">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <form
                                                            action="{{ route('quotation-requests.destroy', $quotation->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Are you sure you want to delete this quotation request?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                style="background: none; border: none; color: inherit; cursor: pointer; padding: 0;"
                                                                title="Delete">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".datatable").DataTable({
                order: [
                    [3, 'desc'] // Sort by submitted date descending
                ]
            });
        });

        // Auto-hide success/error messages after 5 seconds
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

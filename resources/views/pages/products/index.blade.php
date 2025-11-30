@extends('layouts.main')

@section('title', 'Product Management')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Product Management</h3>
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
                        <li class="breadcrumb-item active">Product Management</li>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Product List</h5>
                                <p class="f-m-light mt-1">Manage insurance products displayed to customers</p>
                            </div>
                            <div class="header-actions">
                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i>Add New Product
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Brochure</th>
                                        <th>PDF</th>
                                        <th>Email</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><strong>{{ $product->title }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ $product->type_name }}</span>
                                            </td>
                                            <td>
                                                @if ($product->brochure_path)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->pdf_path)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $product->notification_email }}</small></td>
                                            <td><span class="badge bg-primary">{{ $product->display_order }}</span></td>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="action">
                                                    <li class="view me-2">
                                                        <a href="{{ route('quotation-requests.index', ['product' => $product->id]) }}"
                                                            title="View Quotation Requests">
                                                            <i class="fa-regular fa-file-lines"></i>
                                                        </a>
                                                    </li>
                                                    <li class="edit me-2">
                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                            title="Edit">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <form action="{{ route('products.destroy', $product->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                    [5, 'asc'] // Sort by display_order
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

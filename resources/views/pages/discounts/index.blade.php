@extends('layouts.main')

@section('title', 'Discount Setup')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Discount Setup</h3>
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
                        <li class="breadcrumb-item active">Discount Setup</li>
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
                                <h5>Discount List</h5>
                                <p class="f-m-light mt-1">Manage discount percentages based on date ranges</p>
                            </div>
                            <div class="header-actions">
                                <a href="{{ route('discounts.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i>Add New Discount
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Product</th>
                                        <th>Voucher Code</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Percentage</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discounts as $discount)
                                        <tr>
                                            <td>
                                                @if ($discount->type === 'voucher')
                                                    <span class="badge bg-info">Voucher</span>
                                                @else
                                                    <span class="badge bg-primary">Discount</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($discount->product)
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $discount->product)) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($discount->voucher_code)
                                                    <code class="text-primary">{{ $discount->voucher_code }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $discount->start_date->format('d M Y') }}</td>
                                            <td>{{ $discount->end_date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $discount->percentage }}%</span>
                                            </td>
                                            <td>{{ $discount->description ?? '-' }}</td>
                                            <td>
                                                @if ($discount->isActive())
                                                    <span class="badge bg-success">Active</span>
                                                @elseif(now()->lt($discount->start_date))
                                                    <span class="badge bg-info">Upcoming</span>
                                                @else
                                                    <span class="badge bg-secondary">Expired</span>
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="action">
                                                    <li class="edit">
                                                        <a href="{{ route('discounts.edit', $discount->id) }}"
                                                            title="Edit">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <form action="{{ route('discounts.destroy', $discount->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Are you sure you want to delete this discount?');">
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
                    [0, 'desc']
                ] // Sort by start date descending
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
